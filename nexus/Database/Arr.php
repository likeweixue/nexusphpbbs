<?php

namespace Nexus\Database;

/**
 * 数组工具（替代 Nexus\Database\Arr 的常用方法）
 */
class Arr
{
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (!is_array($array)) {
            return $default;
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }

    public static function has($array, $key): bool
    {
        if (is_null($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }
        return true;
    }

    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    public static function forget(&$array, $keys): void
    {
        foreach ((array)$keys as $key) {
            $parts = explode('.', $key);
            $target = &$array;
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (!isset($target[$part]) || !is_array($target[$part])) {
                    continue 2;
                }
                $target = &$target[$part];
            }
            unset($target[array_shift($parts)]);
        }
    }

    public static function first($array, ?callable $callback = null, $default = null)
    {
        foreach ($array as $key => $value) {
            if (is_null($callback) || $callback($value, $key)) {
                return $value;
            }
        }
        return $default;
    }

    public static function only($array, $keys): array
    {
        $result = [];
        foreach ((array)$keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }
        return $result;
    }

    public static function wrap($value): array
    {
        if (is_null($value)) {
            return [];
        }
        return is_array($value) ? $value : [$value];
    }

    public static function pluck($array, $value, $key = null): array
    {
        $result = [];
        foreach ($array as $item) {
            $itemValue = self::get($item, $value);
            if (is_null($key)) {
                $result[] = $itemValue;
            } else {
                $result[self::get($item, $key)] = $itemValue;
            }
        }
        return $result;
    }
}
