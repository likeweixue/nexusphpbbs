<?php

namespace App\Repositories;

use Nexus\Database\NexusDB;

/**
 * IP 访问记录（纯 PHP 版）。
 *
 * 0.2.x 不再把 IP 记录写进缓存，直接在请求结束时聚合写入 iplog 表由 cron 处理；
 * 这里只保留传统页面调用的入口，避免破坏既有流程。
 */
class IpLogRepository extends BaseRepository
{
    public const CACHE_KEY_PREFIX = 'iplog';

    public const CACHE_TIME = 86400 * 7;

    public static function saveToCache($userId, $uri = null, $ipArr = null): void
    {
        if (!is_numeric($userId) || $userId <= 0) {
            return;
        }
        $uri = $uri ?? (parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '');
        $ip = $ipArr[0] ?? getip();
        $key = self::CACHE_KEY_PREFIX . ':' . $userId . ':' . $ip . ':' . $uri;
        $count = intval(NexusDB::cache_get($key)) + 1;
        NexusDB::cache_put($key, $count, 3600);
    }
}
