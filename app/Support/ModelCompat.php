<?php

namespace App\Support;

use Nexus\Database\NexusDB;
use Nexus\Database\QueryBuilder;

/**
 * 为尚未改造的模型提供 query()/find()/findOrFail() 等 Eloquent 风格入口，
 * 内部全部走 NexusDB 原生 SQL（无 vendor 版本）。
 */
trait ModelCompat
{
    public static function query(): QueryBuilder
    {
        return NexusDB::table(static::$table);
    }

    public static function find($id, array $columns = ['*'])
    {
        $row = NexusDB::getOne(static::$table, 'id = ' . sqlesc($id), $columns === ['*'] ? '*' : implode(', ', $columns));
        return $row ? new static($row) : null;
    }

    public static function findOrFail($id, array $columns = ['*'])
    {
        $model = static::find($id, $columns);
        if (!$model) {
            throw new \RuntimeException(sprintf('%s not found: %s', static::$table, $id));
        }
        return $model;
    }

    public static function where($column, $operator = null, $value = null): QueryBuilder
    {
        $builder = NexusDB::table(static::$table);
        if (func_num_args() === 2) {
            return $builder->where($column, $operator);
        }
        return $builder->where($column, $operator, $value);
    }

    public static function create(array $attributes)
    {
        $id = NexusDB::insert(static::$table, $attributes);
        $attributes['id'] = $id;
        return new static($attributes);
    }
}
