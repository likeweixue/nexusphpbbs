<?php

namespace App\Support;

/**
 * 数据库行对象：支持属性访问（$row->id）与数组访问（$row['id']）。
 */
class RowObject implements \ArrayAccess, \JsonSerializable
{
    private array $attributes;

    public function __construct(array $attributes)
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

    /**
     * 分类标签（替代 SearchBox::getTaxonomyLabel()，供搜索框分类表格使用）
     */
    public function getTaxonomyLabel($torrentField)
    {
        $lang = function_exists('get_langfolder_cookie') ? get_langfolder_cookie() : 'en';
        $extra = $this->attributes['extra'] ?? [];
        if (is_string($extra)) {
            $extra = json_decode($extra, true) ?: [];
        }
        foreach ($extra['taxonomy_labels'] ?? [] as $item) {
            if (($item['torrent_field'] ?? '') == $torrentField && !empty($item['display_text'][$lang])) {
                return $item['display_text'][$lang];
            }
        }
        return nexus_trans("searchbox.sub_category_{$torrentField}_label") ?: ucfirst($torrentField);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->attributes, $options);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset] ?? null;
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
}
