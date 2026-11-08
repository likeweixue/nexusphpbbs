<?php
/**
 * 轻量 PSR-4 自动加载器（替代 Composer 的 vendor/autoload.php）
 *
 * 只注册应用自身的命名空间：Nexus\、App\、Database\Seeders\。
 * 第三方的 Illuminate / Filament 等依赖在“无 vendor”版本中已不再使用。
 */
spl_autoload_register(function ($class) {
    static $prefixes = null;
    if ($prefixes === null) {
        $base = dirname(__DIR__) . '/';
        $prefixes = [
            'Nexus\\' => $base . 'nexus/',
            'App\\' => $base . 'app/',
            'Database\\Seeders\\' => $base . 'database/seeders/',
            'Database\\Factories\\' => $base . 'database/factories/',
        ];
    }
    foreach ($prefixes as $prefix => $dir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }
        $relative = substr($class, strlen($prefix));
        $file = $dir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/Support/helpers.php';
