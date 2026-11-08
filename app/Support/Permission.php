<?php

namespace App\Support;

/**
 * 权限判断工具（纯 PHP 版，替代原 App\Auth\Permission）。
 */
class Permission
{
    public static function canSetTorrentSpecialTag(): bool
    {
        return user_can('torrent-set-special-tag');
    }

    public static function canManageUserBasicInfo(): bool
    {
        return user_can('prfmanage');
    }

    public static function canManageUserConfidentialInfo(): bool
    {
        return user_can('cruprfmanage');
    }

    public static function canManageTorrent(): bool
    {
        return user_can('torrentmanage');
    }
}
