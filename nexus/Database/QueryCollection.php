<?php

namespace Nexus\Database;

/**
 * 查询结果集合（替代 Illuminate\Support\Collection）
 */
class QueryCollection implements \ArrayAccess, \IteratorAggregate, \Countable, \JsonSerializable
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    public static function make($items): self
    {
        if ($items instanceof self) {
            return $items;
        }
        if (!is_array($items)) {
            $items = is_iterable($items) ? iterator_to_array($items) : [$items];
        }
        return new self($items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                return $item->toArray();
            }
            if ($item instanceof self) {
                return $item->toArray();
            }
            return $item;
        }, $this->items);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->items);
    }

    public function first()
    {
        return $this->items[0] ?? null;
    }

    public function last()
    {
        return empty($this->items) ? null : $this->items[count($this->items) - 1];
    }

    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    public function has($key): bool
    {
        return isset($this->items[$key]);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function values(): self
    {
        return new self(array_values($this->items));
    }

    public function push($value): self
    {
        $this->items[] = $value;
        return $this;
    }

    public function add($value): self
    {
        return $this->push($value);
    }

    public function merge($items): self
    {
        return new self(array_merge($this->items, $items instanceof self ? $items->all() : (array)$items));
    }

    public function map(\Closure $callback): self
    {
        return new self(array_map($callback, $this->items));
    }

    public function filter(?\Closure $callback = null): self
    {
        return new self(array_values(array_filter($this->items, $callback)));
    }

    public function each(\Closure $callback): self
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function pluck($key, $valueKey = null): self
    {
        $result = [];
        foreach ($this->items as $item) {
            $itemKey = is_array($item) ? ($item[$key] ?? null) : ($item->{$key} ?? null);
            if ($valueKey === null) {
                $result[] = $itemKey;
            } else {
                $result[$itemKey] = is_array($item) ? ($item[$valueKey] ?? null) : ($item->{$valueKey} ?? null);
            }
        }
        return new self($result);
    }

    public function keyBy($key): self
    {
        $result = [];
        foreach ($this->items as $item) {
            $itemKey = is_array($item) ? ($item[$key] ?? null) : ($item->{$key} ?? null);
            $result[$itemKey] = $item;
        }
        return new self($result);
    }

    public function groupBy($key): self
    {
        $result = [];
        foreach ($this->items as $item) {
            $itemKey = is_array($item) ? ($item[$key] ?? null) : ($item->{$key} ?? null);
            $result[$itemKey][] = $item;
        }
        return new self(array_map(fn($group) => new self($group), $result));
    }

    public function sortBy($key, bool $descending = false): self
    {
        $items = $this->items;
        usort($items, function ($a, $b) use ($key, $descending) {
            $left = is_array($a) ? ($a[$key] ?? null) : ($a->{$key} ?? null);
            $right = is_array($b) ? ($b[$key] ?? null) : ($b->{$key} ?? null);
            $result = $left <=> $right;
            return $descending ? -$result : $result;
        });
        return new self($items);
    }

    public function sortByDesc($key): self
    {
        return $this->sortBy($key, true);
    }

    public function unique($key = null): self
    {
        $seen = [];
        $result = [];
        foreach ($this->items as $item) {
            $value = is_null($key) ? $item : (is_array($item) ? ($item[$key] ?? null) : ($item->{$key} ?? null));
            $hash = is_scalar($value) ? (string)$value : md5(serialize($value));
            if (!isset($seen[$hash])) {
                $seen[$hash] = true;
                $result[] = $item;
            }
        }
        return new self($result);
    }

    public function chunk(int $size): self
    {
        return new self(array_map(fn($chunk) => new self($chunk), array_chunk($this->items, $size)));
    }

    public function take(int $limit): self
    {
        return new self(array_slice($this->items, 0, $limit));
    }

    public function skip(int $count): self
    {
        return new self(array_slice($this->items, $count));
    }

    public function sum($key = null)
    {
        return array_sum(array_map(function ($item) use ($key) {
            if (is_null($key)) {
                return $item;
            }
            return is_array($item) ? ($item[$key] ?? 0) : ($item->{$key} ?? 0);
        }, $this->items));
    }

    public function implode($value, $glue = ','): string
    {
        return implode($glue, $this->pluck($value)->all());
    }

    public function contains($callback): bool
    {
        foreach ($this->items as $item) {
            if (is_callable($callback) ? $callback($item) : ($item == $callback)) {
                return true;
            }
        }
        return false;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
