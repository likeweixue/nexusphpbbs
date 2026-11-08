<?php
/**
 * 通用辅助函数（替代 Laravel 全局函数，纯 PHP 实现）。
 */
use Nexus\Database\NexusDB;

if (!function_exists('now')) {
    /**
     * 当前时间：传参时返回对应格式的字符串，不传参返回 DateTime 对象
     */
    function now($format = 'Y-m-d H:i:s')
    {
        $date = new \DateTimeImmutable('now');
        if ($format === null) {
            return $date->format('Y-m-d H:i:s');
        }
        return $format instanceof \Closure ? $format($date) : $date->format($format);
    }
}

if (!function_exists('today')) {
    function today(string $format = 'Y-m-d 00:00:00')
    {
        return (new \DateTimeImmutable('today'))->format($format);
    }
}

if (!function_exists('collect')) {
    function collect($items = [])
    {
        return \Nexus\Database\QueryCollection::make($items);
    }
}

if (!function_exists('optional')) {
    function optional($value, ?callable $callback = null)
    {
        $proxy = new class($value) {
            private $value;
            public function __construct($value) { $this->value = $value; }
            public function __get($key) { return null; }
            public function __call($method, $args) { return null; }
        };
        if (is_null($callback)) {
            return $value ?: $proxy;
        }
        return $callback();
    }
}

if (!function_exists('class_basename')) {
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : (string)$class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('config')) {
    /**
     * 读取 config/*.php 配置（点语法，如 config('captcha.attendance.enabled')）
     */
    function config(string $key, $default = null)
    {
        static $configs = [];
        $segments = explode('.', $key);
        $file = array_shift($segments);
        if (!array_key_exists($file, $configs)) {
            $path = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2) . '/') . 'config/' . $file . '.php';
            $configs[$file] = is_file($path) ? require $path : null;
        }
        $value = $configs[$file];
        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
        return $value ?? $default;
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return nexus_env($key, $default);
    }
}

if (!function_exists('blank')) {
    function blank($value): bool
    {
        return empty($value) && !is_bool($value);
    }
}

if (!function_exists('filled')) {
    function filled($value): bool
    {
        return !blank($value);
    }
}
