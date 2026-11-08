<?php

namespace App\Repositories;

use Nexus\Database\NexusDB;

/**
 * 种子认领（纯 PHP 版，仅保留 ajax 调用入口；论坛版种子功能已移除）。
 */
class ClaimRepository extends BaseRepository
{
    public function store($uid, $torrentId)
    {
        return ['ret' => 1, 'msg' => 'claims are not supported in the forum edition'];
    }

    public function delete($id, $uid = 0)
    {
        return ['ret' => 1, 'msg' => 'claims are not supported in the forum edition'];
    }
}
