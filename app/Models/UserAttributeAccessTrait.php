<?php

namespace App\Models;

/**
 * 用户模型的数组 / 属性访问与查询入口（纯 PHP 实现，替代 Eloquent 的模型基类行为）。
 */
trait UserAttributeAccessTrait
{
    public static function query(): \Nexus\Database\QueryBuilder
    {
        return new \Nexus\Database\QueryBuilder(self::$table);
    }

    public static function find($id, array $columns = ['*'])
    {
        if (empty($id)) {
            return null;
        }
        $row = \Nexus\Database\NexusDB::getOne(self::$table, 'id = ' . sqlesc($id), $columns === ['*'] ? '*' : implode(', ', $columns));
        return $row ? new static($row) : null;
    }

    public static function findOrFail($id, array $columns = ['*'])
    {
        $model = static::find($id, $columns);
        if (!$model) {
            throw new \RuntimeException(sprintf('%s not found: %s', self::$table, $id));
        }
        return $model;
    }

    public static function where($column, $operator = null, $value = null): \Nexus\Database\QueryBuilder
    {
        $builder = new \Nexus\Database\QueryBuilder(self::$table);
        if (func_num_args() === 2) {
            return $builder->where($column, $operator);
        }
        return $builder->where($column, $operator, $value);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->attributes, $options);
    }

    public function update(array $attributes): bool
    {
        unset($attributes['id']);
        if (empty($attributes) || empty($this->attributes['id'])) {
            return false;
        }
        \Nexus\Database\NexusDB::update(self::$table, $attributes, 'id = ' . sqlesc($this->attributes['id']));
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        return true;
    }

    /**
     * 保存模型（有 id 且记录已存在则更新，否则插入）
     */
    public function save(): bool
    {
        $attributes = $this->attributes;
        if (empty($attributes)) {
            return false;
        }
        if (!empty($attributes['id']) && \Nexus\Database\NexusDB::getOne(self::$table, 'id = ' . sqlesc($attributes['id']), 'id')) {
            $id = $attributes['id'];
            unset($attributes['id']);
            \Nexus\Database\NexusDB::update(self::$table, $attributes, 'id = ' . sqlesc($id));
            return true;
        }
        $id = \Nexus\Database\NexusDB::insert(self::$table, $attributes);
        $this->attributes['id'] = $id;
        return (bool)$id;
    }

    public function delete(): bool
    {
        $id = $this->attributes['id'] ?? null;
        if (empty($id)) {
            return false;
        }
        return \Nexus\Database\NexusDB::delete(self::$table, 'id = ' . sqlesc($id)) > 0;
    }

    public static function create(array $attributes): static
    {
        $id = \Nexus\Database\NexusDB::insert(self::$table, $attributes);
        $attributes['id'] = $id;
        return new static($attributes);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($key)
    {
        return $this->getAttributeValue($key);
    }

    public function setAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function setRawAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function __get($key)
    {
        return $this->getAttributeValue($key);
    }

    public function __set($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function getKey()
    {
        return $this->attributes['id'] ?? null;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->getAttributeValue($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    /**
     * 属性取值：优先匹配 get{Studly}Attribute 方法与内置派生属性。
     */
    protected function getAttributeValue($key)
    {
        $studly = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', (string)$key)));
        $accessor = 'get' . $studly . 'Attribute';
        if (method_exists($this, $accessor)) {
            return $this->{$accessor}();
        }
        $derived = [
            'class_text' => 'getClassTextAttribute',
            'donate_status' => 'getDonateStatusAttribute',
            'locale' => 'getLocaleAttribute',
            'site_lang_folder' => 'getSiteLangFolderAttribute',
            'uploaded_text' => 'getUploadedTextAttribute',
            'downloaded_text' => 'getDownloadedTextAttribute',
        ];
        if (isset($derived[$key]) && method_exists($this, $derived[$key])) {
            return $this->{$derived[$key]}();
        }
        if ($key === 'avatar') {
            return $this->accessAvatar($this->attributes['avatar'] ?? '');
        }
        if ($key === 'language') {
            return null;
        }
        return $this->attributes[$key] ?? null;
    }
}
