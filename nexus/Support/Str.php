<?php

namespace Nexus\Support;

/**
 * 字符串工具（替代 Nexus\Support\Str 的常用方法）
 */
class Str
{
    public static function ascii($value): string
    {
        $value = (string)$value;
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($converted === false) {
            $converted = preg_replace('/[^\x20-\x7E]/', '', $value);
        }
        return (string)$converted;
    }

    public static function slug($title, $separator = '-'): string
    {
        $title = static::ascii($title);
        $title = preg_replace('/[^A-Za-z0-9]+/u', $separator, $title);
        $title = trim($title, $separator);
        return strtolower($title);
    }

    public static function limit($value, $limit = 100, $end = '...'): string
    {
        $value = (string)$value;
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return rtrim(mb_substr($value, 0, $limit)) . $end;
    }

    public static function random($length = 16): string
    {
        return bin2hex(random_bytes((int)ceil($length / 2)));
    }

    public static function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function contains($haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && str_contains((string)$haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    public static function startsWith($haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && str_starts_with((string)$haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    public static function endsWith($haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && str_ends_with((string)$haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    public static function finish($value, $cap): string
    {
        if (str_ends_with((string)$value, $cap)) {
            return $value;
        }
        return $value . $cap;
    }

    public static function replaceFirst($search, $replace, $subject): string
    {
        $pos = strpos((string)$subject, $search);
        if ($pos === false) {
            return $subject;
        }
        return substr_replace($subject, $replace, $pos, strlen($search));
    }

    public static function snake($value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', (string)$value));
    }

    public static function studly($value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', (string)$value)));
    }

    public static function camel($value): string
    {
        return lcfirst(static::studly($value));
    }

    public static function after($subject, $search): string
    {
        if ($search === '') {
            return (string)$subject;
        }
        $pos = strpos((string)$subject, $search);
        return $pos === false ? (string)$subject : substr((string)$subject, $pos + strlen($search));
    }
}
