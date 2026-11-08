<?php

namespace App\Repositories;

use Nexus\Database\NexusDB;

/**
 * SeedBox 记录（纯 PHP 版，仅保留传统页面用到的方法）。
 */
class SeedBoxRepository extends BaseRepository
{
    public const APPROVAL_COUNT_CACHE_KEY = 'seed_box_approval_count';

    public static function isSeedBoxFromUserRecords($uid, $ip): array
    {
        $row = NexusDB::getOne(
            'seed_box_records',
            'uid = ' . sqlesc($uid) . ' and ip = ' . sqlesc($ip) . ' and status = 1',
            'id'
        );
        return ['result' => !empty($row)];
    }

    public function renderIcon($ip, $uid): string
    {
        if (empty($ip) || empty($uid)) {
            return '';
        }
        $isSeedBox = self::isSeedBoxFromUserRecords($uid, $ip)['result'];
        if (!$isSeedBox) {
            return '';
        }
        return ' <img class="seed_box" src="pic/trans.gif" alt="SeedBox" title="SeedBox" />';
    }

    public function store(array $params): int
    {
        return NexusDB::insert('seed_box_records', $params);
    }

    public function delete($id, $uid = 0): int
    {
        $where = 'id = ' . sqlesc($id);
        if ($uid) {
            $where .= ' and uid = ' . sqlesc($uid);
        }
        return NexusDB::delete('seed_box_records', $where);
    }
}
