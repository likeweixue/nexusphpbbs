<?php

namespace Nexus\Translation;

/**
 * 纯 PHP 翻译器（替代 Illuminate\Translation\Translator）。
 *
 * 从 resources/lang/<locale>/<file>.php 读取数组形式的语言文件，
 * 支持 "file.key"、"file.nested.key"、"namespace::file.key" 以及 :attribute 占位符替换。
 */
class NexusTranslator
{
    protected string $defaultLocale;

    protected string $fallbackLocale;

    protected string $basePath;

    protected string $locale;

    protected array $namespaces = [];

    protected array $loaded = [];

    public function __construct(string $defaultLocale = 'en', string $fallbackLocale = 'en', ?string $defaultPath = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->fallbackLocale = $fallbackLocale;
        $this->basePath = $defaultPath ?? (defined('ROOT_PATH') ? ROOT_PATH . 'resources/lang' : dirname(__DIR__) . '/resources/lang');
        $this->locale = $defaultLocale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function addNamespace(string $namespace, string $path): void
    {
        $this->namespaces[$namespace] = rtrim($path, '/') . '/';
    }

    protected function resolvePath(string $group, string $locale): ?string
    {
        $namespace = null;
        if (str_contains($group, '::')) {
            [$namespace, $group] = explode('::', $group, 2);
        }
        $file = str_replace('.', '/', $group);
        if ($namespace !== null) {
            $base = $this->namespaces[$namespace] ?? null;
            if ($base === null) {
                return null;
            }
            $candidates = [$base . $locale . '/' . $file . '.php', $base . $file . '.php'];
        } else {
            $candidates = [$this->basePath . '/' . $locale . '/' . $file . '.php'];
        }
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }
        return null;
    }

    protected function load(string $group, string $locale): array
    {
        $cacheKey = $locale . '|' . $group;
        if (array_key_exists($cacheKey, $this->loaded)) {
            return $this->loaded[$cacheKey] ?? [];
        }
        $path = $this->resolvePath($group, $locale);
        if ($path === null) {
            return $this->loaded[$cacheKey] = [];
        }
        $lines = include $path;
        return $this->loaded[$cacheKey] = is_array($lines) ? $lines : [];
    }

    protected function lookup(string $key, string $locale): ?string
    {
        if (str_contains($key, '::')) {
            [$namespace, $rest] = explode('::', $key, 2);
            $key = $namespace . '::' . $rest;
        }
        $segments = explode('.', $key);
        while (count($segments) > 0) {
            $candidate = implode('.', $segments);
            $lines = $this->load($candidate, $locale);
            if (!empty($lines)) {
                $value = $lines;
                $remaining = array_slice(explode('.', $key), count($segments));
                foreach ($remaining as $segment) {
                    if (is_array($value) && array_key_exists($segment, $value)) {
                        $value = $value[$segment];
                    } else {
                        $value = null;
                        break;
                    }
                }
                if (is_string($value)) {
                    return $value;
                }
            }
            array_pop($segments);
        }
        return null;
    }

    public function has(string $key, ?string $locale = null): bool
    {
        $locale = $locale ?? $this->locale;
        return $this->lookup($key, $locale) !== null;
    }

    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? $this->locale;
        $line = $this->lookup($key, $locale);
        if ($line === null && $locale !== $this->fallbackLocale) {
            $line = $this->lookup($key, $this->fallbackLocale);
        }
        if ($line === null) {
            return $key;
        }
        foreach ($replace as $search => $value) {
            $line = str_replace([':' . $search, ':' . ucfirst($search)], (string)$value, $line);
        }
        return $line;
    }

    public function get($key, array $replace = [], $locale = null): string
    {
        return $this->trans((string)$key, $replace, $locale);
    }

    public function choice($key, $number, array $replace = [], $locale = null): string
    {
        $line = $this->trans((string)$key, $replace, $locale);
        $segments = explode('|', $line);
        $index = ((int)$number === 1) ? 0 : 1;
        return trim($segments[$index] ?? $segments[0]);
    }
}
