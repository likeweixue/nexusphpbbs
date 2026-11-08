<?php

namespace Nexus\Database;

/**
 * 纯 PHP 实现的 MySQL 查询构建器。
 *
 * 只实现传统页面实际用到的子集：where / whereIn / whereNull / whereRaw /
 * orderBy / groupBy / limit / offset / join / select / first / get / count /
 * sum / max / min / avg / pluck / exists / value / insert / update / delete / chunk。
 */
class QueryBuilder
{
    private string $table;

    private array $wheres = [];

    private array $bindings = [];

    private array $orders = [];

    private array $groups = [];

    private array $joins = [];

    private array $columns = ['*'];

    private array $havings = [];

    private bool $distinct = false;

    private ?int $limit = null;

    private ?int $offset = null;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    private function quoteTable(string $table): string
    {
        if (str_contains($table, ' ') || str_contains($table, ' as ')) {
            return $table;
        }
        return '`' . str_replace('`', '', $table) . '`';
    }

    private function quoteIdentifier(string $identifier): string
    {
        if ($identifier === '*' || str_contains($identifier, '(') || str_ends_with($identifier, '.*')) {
            return $identifier;
        }
        if (str_contains($identifier, ' as ')) {
            [$left, $right] = preg_split('/\s+as\s+/i', $identifier);
            return $this->quoteIdentifier(trim($left)) . ' as ' . $this->quoteIdentifier(trim($right));
        }
        if (str_contains($identifier, '.')) {
            return implode('.', array_map(fn($part) => $this->quoteIdentifier($part), explode('.', $identifier)));
        }
        return '`' . str_replace('`', '', $identifier) . '`';
    }

    private function formatValue($value): string
    {
        if ($value instanceof RawExpression) {
            return $value->getValue();
        }
        if (is_null($value)) {
            return 'null';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }
        if (is_array($value)) {
            return implode(', ', array_map(fn($item) => $this->formatValue($item), $value));
        }
        if (function_exists('sqlesc')) {
            return sqlesc((string)$value);
        }
        return "'" . str_replace(['\\', "'"], ['\\\\', "\\'"], (string)$value) . "'";
    }

    private function addWhere(string $sql, string $boolean = 'and', array $bindings = []): self
    {
        $this->wheres[] = [$boolean, $sql];
        $this->bindings = array_merge($this->bindings, $bindings);
        return $this;
    }

    public function where($column, $operator = null, $value = null, string $boolean = 'and'): self
    {
        if ($column instanceof \Closure) {
            $nested = new self($this->table);
            $column($nested);
            return $this->addWhere('(' . $nested->compileWheres() . ')', $boolean, $nested->bindings);
        }
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        if (is_null($value) && $operator === '=') {
            return $this->whereNull($column, $boolean);
        }
        if (is_null($value) && in_array(strtolower($operator), ['!=', '<>'], true)) {
            return $this->whereNotNull($column, $boolean);
        }
        $sql = sprintf('%s %s %s', $this->quoteIdentifier($column), strtoupper($operator), $this->formatValue($value));
        return $this->addWhere($sql, $boolean);
    }

    public function orWhere($column, $operator = null, $value = null): self
    {
        $args = func_get_args();
        $args[] = 'or';
        return $this->where(...$args);
    }

    public function whereIn($column, array $values, string $boolean = 'and', bool $not = false): self
    {
        if (empty($values)) {
            return $this->addWhere($not ? '1 = 1' : '1 = 0', $boolean);
        }
        $values = array_values($values);
        $sql = sprintf('%s %s (%s)', $this->quoteIdentifier($column), $not ? 'not in' : 'in', $this->formatValue($values));
        return $this->addWhere($sql, $boolean);
    }

    public function whereNotIn($column, array $values, string $boolean = 'and'): self
    {
        return $this->whereIn($column, $values, $boolean, true);
    }

    public function orWhereIn($column, array $values): self
    {
        return $this->whereIn($column, $values, 'or');
    }

    public function whereNull($column, string $boolean = 'and'): self
    {
        return $this->addWhere($this->quoteIdentifier($column) . ' is null', $boolean);
    }

    public function whereNotNull($column, string $boolean = 'and'): self
    {
        return $this->addWhere($this->quoteIdentifier($column) . ' is not null', $boolean);
    }

    public function whereRaw($sql, array $bindings = [], string $boolean = 'and'): self
    {
        return $this->addWhere($sql, $boolean, $bindings);
    }

    public function orWhereRaw($sql, array $bindings = []): self
    {
        return $this->addWhere($sql, 'or', $bindings);
    }

    public function whereBetween($column, array $values, string $boolean = 'and'): self
    {
        $sql = sprintf('%s between %s and %s', $this->quoteIdentifier($column), $this->formatValue($values[0] ?? null), $this->formatValue($values[1] ?? null));
        return $this->addWhere($sql, $boolean);
    }

    public function join($table, $first, $operator = null, $second = null, string $type = 'inner'): self
    {
        $this->joins[] = sprintf(' %s join %s on %s %s %s', $type, $this->quoteTable($table), $first, $operator, $second);
        return $this;
    }

    public function leftJoin($table, $first, $operator = null, $second = null): self
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    public function orderBy($column, string $direction = 'asc'): self
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        $this->orders[] = $this->quoteIdentifier($column) . ' ' . $direction;
        return $this;
    }

    public function orderByDesc($column): self
    {
        return $this->orderBy($column, 'desc');
    }

    public function groupBy($column): self
    {
        $this->groups[] = $this->quoteIdentifier($column);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function select($columns): self
    {
        $this->columns = is_array($columns) ? array_values($columns) : func_get_args();
        return $this;
    }

    public function selectRaw($expression): self
    {
        return $this->select($expression);
    }

    private function compileWheres(): string
    {
        if (empty($this->wheres)) {
            return '1 = 1';
        }
        $sql = '';
        foreach ($this->wheres as $index => [$boolean, $clause]) {
            $sql .= $index === 0 ? $clause : " $boolean $clause";
        }
        return $sql;
    }

    private function compileSelect(): string
    {
        $columns = implode(', ', array_map(fn($column) => $column === '*' ? '*' : $this->quoteIdentifier($column), $this->columns));
        $sql = sprintf('select %s%s from %s', $this->distinct ? 'distinct ' : '', $columns, $this->quoteTable($this->table));
        if (!empty($this->joins)) {
            $sql .= implode('', $this->joins);
        }
        $sql .= ' where ' . $this->compileWheres();
        if (!empty($this->groups)) {
            $sql .= ' group by ' . implode(', ', $this->groups);
        }
        if (!empty($this->havings)) {
            $sql .= ' having ' . implode(' and ', $this->havings);
        }
        if (!empty($this->orders)) {
            $sql .= ' order by ' . implode(', ', $this->orders);
        }
        if (!is_null($this->limit)) {
            $sql .= ' limit ' . $this->limit;
        }
        if (!is_null($this->offset)) {
            $sql .= ' offset ' . $this->offset;
        }
        return $sql;
    }

    public function toSql(): string
    {
        return $this->compileSelect();
    }

    public function get(): QueryCollection
    {
        return new QueryCollection(NexusDB::select($this->compileSelect()));
    }

    public function first()
    {
        $clone = clone $this;
        $clone->limit = 1;
        $rows = NexusDB::select($clone->compileSelect());
        return $rows ? (object)$rows[0] : null;
    }

    public function find($id, $columns = ['*'])
    {
        if (is_array($id)) {
            return $this->whereIn('id', $id)->get();
        }
        return $this->select($columns)->where('id', $id)->first();
    }

    public function findOrFail($id, $columns = ['*'])
    {
        $row = $this->find($id, $columns);
        if (!$row) {
            throw new \RuntimeException(sprintf('%s not found: %s', $this->table, is_array($id) ? json_encode($id) : $id));
        }
        return $row;
    }

    public function value($column)
    {
        $row = $this->select($column)->first();
        return $row ? $row->{$column} : null;
    }

    public function pluck($column, $key = null): array
    {
        $rows = $this->select($key ? [$key, $column] : [$column])->get();
        $result = [];
        foreach ($rows as $row) {
            if ($key) {
                $result[$row->{$key}] = $row->{$column};
            } else {
                $result[] = $row->{$column};
            }
        }
        return $result;
    }

    public function count(): int
    {
        $clone = clone $this;
        $clone->orders = [];
        $clone->limit = null;
        $clone->offset = null;
        $clone->columns = ['count(*) as aggregate'];
        $rows = NexusDB::select($clone->compileSelect());
        return (int)($rows[0]['aggregate'] ?? 0);
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    private function aggregate(string $function, string $column)
    {
        $clone = clone $this;
        $clone->orders = [];
        $clone->limit = null;
        $clone->offset = null;
        $clone->columns = [sprintf('%s(%s) as aggregate', $function, $column === '*' ? '*' : $this->quoteIdentifier($column))];
        $rows = NexusDB::select($clone->compileSelect());
        $value = $rows[0]['aggregate'] ?? null;
        if (in_array($function, ['sum', 'avg'], true) && is_null($value)) {
            return $function === 'sum' ? 0 : null;
        }
        return $value;
    }

    public function sum($column)
    {
        return $this->aggregate('sum', $column);
    }

    public function max($column)
    {
        return $this->aggregate('max', $column);
    }

    public function min($column)
    {
        return $this->aggregate('min', $column);
    }

    public function avg($column)
    {
        return $this->aggregate('avg', $column);
    }

    public function insert(array $data): bool
    {
        if (empty($data)) {
            return true;
        }
        if (!array_is_list($data)) {
            $data = [$data];
        }
        foreach ($data as $row) {
            NexusDB::insert($this->table, $row);
        }
        return true;
    }

    public function insertGetId(array $data): int
    {
        return NexusDB::insert($this->table, $data);
    }

    public function update(array $data): int
    {
        $sets = [];
        foreach ($data as $column => $value) {
            $sets[] = $this->quoteIdentifier($column) . ' = ' . $this->formatValue($value);
        }
        $sql = sprintf('update %s set %s where %s', $this->quoteTable($this->table), implode(', ', $sets), $this->compileWheres());
        NexusDB::statement($sql);
        return NexusDB::getInstance()->affectedRows();
    }

    public function increment($column, $amount = 1, array $extra = []): int
    {
        $sets = [$this->quoteIdentifier($column) . ' = ' . $this->quoteIdentifier($column) . ' + ' . (int)$amount];
        foreach ($extra as $key => $value) {
            $sets[] = $this->quoteIdentifier($key) . ' = ' . $this->formatValue($value);
        }
        $sql = sprintf('update %s set %s where %s', $this->quoteTable($this->table), implode(', ', $sets), $this->compileWheres());
        NexusDB::statement($sql);
        return NexusDB::getInstance()->affectedRows();
    }

    public function decrement($column, $amount = 1, array $extra = []): int
    {
        return $this->increment($column, -$amount, $extra);
    }

    public function delete(): int
    {
        $sql = sprintf('delete from %s where %s', $this->quoteTable($this->table), $this->compileWheres());
        NexusDB::statement($sql);
        return NexusDB::getInstance()->affectedRows();
    }

    /**
     * 分块处理，回调参数为 QueryCollection
     */

    public function whereNot($column, $value = null, string $boolean = 'and'): self
    {
        if (func_num_args() === 2) {
            $value = $column;
            $column = $this->table;
        }
        return $this->addWhere($this->quoteIdentifier($column) . ' != ' . $this->formatValue($value), $boolean);
    }

    public function whereDate($column, $value, string $operator = '=', string $boolean = 'and'): self
    {
        $date = $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : (is_string($value) ? substr($value, 0, 10) : (string)$value);
        return $this->addWhere(sprintf('date(%s) %s %s', $this->quoteIdentifier($column), strtoupper($operator), $this->formatValue($date)), $boolean);
    }

    public function whereColumn($first, string $operator, $second = null, string $boolean = 'and'): self
    {
        if (func_num_args() === 3) {
            $second = $operator;
            $operator = '=';
        }
        return $this->addWhere(sprintf('%s %s %s', $this->quoteIdentifier($first), strtoupper($operator), $this->quoteIdentifier($second)), $boolean);
    }

    public function orWhereNull($column): self
    {
        return $this->whereNull($column, 'or');
    }

    public function orWhereNotNull($column): self
    {
        return $this->whereNotNull($column, 'or');
    }

    public function latest($column = 'created_at'): self
    {
        return $this->orderBy($column, 'desc');
    }

    public function oldest($column = 'created_at'): self
    {
        return $this->orderBy($column, 'asc');
    }

    public function forPage(int $page, int $perpage = 15): self
    {
        return $this->offset(max(0, ($page - 1) * $perpage))->limit($perpage);
    }

    public function having($column, $operator = null, $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        $this->havings[] = sprintf('%s %s %s', $this->quoteIdentifier($column), strtoupper($operator), $this->formatValue($value));
        return $this;
    }

    public function havingRaw($sql): self
    {
        $this->havings[] = $sql;
        return $this;
    }

    public function distinct($enable = true): self
    {
        $this->distinct = (bool)$enable;
        return $this;
    }

    public function when($value, \Closure $callback, ?\Closure $default = null): self
    {
        if ($value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * 无 vendor 版本不支持关联预加载，调用时忽略（页面按需自行查询关联数据）。
     */
    public function with($relations): self
    {
        do_log('[NO_VENDOR] ignore eager load: ' . (is_array($relations) ? implode(',', $relations) : $relations), 'warning');
        return $this;
    }

    public function withCount($relations): self
    {
        return $this;
    }

    public function whereHas($relation, ?\Closure $callback = null): self
    {
        throw new \RuntimeException('whereHas is not supported in the vendor-less query builder: ' . $this->table . '.' . $relation);
    }

    public function chunk(int $size, \Closure $callback): bool
    {
        $page = 1;
        do {
            $clone = clone $this;
            $clone->limit = $size;
            $clone->offset = ($page - 1) * $size;
            $results = $clone->get();
            if ($results->isEmpty()) {
                break;
            }
            if ($callback($results) === false) {
                return false;
            }
            $page++;
        } while ($results->count() === $size);
        return true;
    }
}
