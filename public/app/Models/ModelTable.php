<?php

namespace App\Models;

/**
 * 无 vendor 版本的模型基类：只有表名常量与最小工具方法，数据访问统一走 NexusDB。
 */
abstract class ModelTable
{
    use \App\Support\ModelCompat;

    public static string $table = '';

    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public static function table(): string
    {
        return static::$table;
    }
}
