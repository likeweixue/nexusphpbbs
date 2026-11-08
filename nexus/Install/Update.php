<?php

namespace Nexus\Install;

/**
 * 在线升级器（无 vendor 版本停用）。
 *
 * 0.2.x 不再保留 Laravel 迁移与 artisan 命令，升级方式改为：
 *   1. 覆盖程序文件；
 *   2. 执行 database/install/upgrade.sql（如本次版本提供）；
 *   3. 清理 storage 缓存。
 *
 * 保留此类仅为兼容旧入口（public/install/update），访问时提示停用。
 */
class Update
{
    public function doLog(string $message): void
    {
        do_log($message);
    }

    public function renderDisabledPage(): void
    {
        $message = '在线升级器已在 0.2.x 停用：请覆盖程序文件后，按发布说明执行 database/install/upgrade.sql，并清理 storage 缓存。';
        if (function_exists('stdhead')) {
            stdhead('Online updater disabled');
            stdmsg('Online updater disabled', $message);
            stdfoot();
        } else {
            echo $message;
        }
        exit;
    }
}
