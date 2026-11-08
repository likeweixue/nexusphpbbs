<?php
namespace Nexus\Plugin;

use App\Repositories\BaseRepository;

abstract class BasePlugin extends BaseRepository
{
    abstract function install();

    abstract function boot();

    /**
     * 插件迁移（0.2.x 无 vendor 版本不再支持 Laravel 迁移）。
     *
     * 插件如需要建表，请直接提供 SQL 并在安装时执行。
     */
    public function runMigrations($dir, $rollback = false)
    {
        do_log("[PLUGIN] migrations are not supported in the vendor-less edition: $dir", 'warning');
    }

    public static function checkMainApplicationVersion($silent = true): bool
    {
        $constantNameArr = [
            "static::COMPATIBLE_NP_VERSION",
            "static::COMPATIBLE_VERSION", //before use
        ];
        foreach ($constantNameArr as $constantName) {
            if (defined($constantName) && version_compare(VERSION_NUMBER, constant($constantName), '<')) {
                if ($silent) {
                    return false;
                }
                throw new \RuntimeException(sprintf(
                    "NexusPHP version: %s is too low, this plugin require: %s",
                    VERSION_NUMBER, constant($constantName)
                ));
            }
        }
        return true;
    }

    public function getNexusView($name): string
    {
        $reflection = new \ReflectionClass(get_called_class());
        $pluginRoot = dirname($reflection->getFileName(), 2);
        return $pluginRoot . "/resources/views/" . trim($name, "/");
    }

    public function trans($name): string
    {
        return nexus_trans($this->getTransKey($name));
    }

    public function getTransKey($name): string
    {
        return sprintf("%s::%s", static::ID, $name);
    }

    public static function getInstance(): static
    {
        return Plugin::getById(static::ID);
    }

    public function getVersion(): string
    {
        $constantName = "static::VERSION";
        return defined($constantName) ? constant($constantName) : '';
    }

    public function getId(): string
    {
        $className = str_replace("Repository", "", get_called_class());
        $plugin = call_user_func([$className, "make"]);
        return $plugin->getId();
    }
}
