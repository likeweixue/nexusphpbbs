<?php

namespace App\Models;

use Nexus\Database\NexusDB;

/**
 * 全站优惠（种子状态）模型（纯 PHP 版）。
 */
class TorrentState extends ModelTable
{
    public static string $table = 'torrents_state';

    public const NOTICE_NONE = 0;
    public const NOTICE_UNLIMITED = -1;

    public static function noticeOptions(): array
    {
        return [self::NOTICE_NONE => nexus_trans('label.notice_days.none'), self::NOTICE_UNLIMITED => nexus_trans('label.notice_days.unlimited')];
    }

    public static function listAll(): array
    {
        return NexusDB::select('select * from torrents_state order by begin is null, begin asc, id asc');
    }

    /**
     * 返回 [current => 当前生效的全站优惠, upcoming => 即将开始的全站优惠]
     */
    public static function resolveTimeline($moment = null): array
    {
        $now = $moment ? (is_string($moment) ? $moment : $moment->format('Y-m-d H:i:s')) : date('Y-m-d H:i:s');
        $current = null;
        $upcoming = null;
        foreach (self::listAll() as $state) {
            $begin = $state['begin'] ?? null;
            $deadline = $state['deadline'] ?? null;
            $begun = empty($begin) || $begin <= $now;
            $notExpired = empty($deadline) || $deadline >= $now;
            if ($begun && $notExpired) {
                if (!$current) {
                    $current = $state;
                }
                continue;
            }
            if (!empty($begin) && $begin > $now && !$upcoming) {
                $upcoming = $state;
            }
        }
        return ['current' => $current, 'upcoming' => $upcoming];
    }

    public static function currentGlobalState()
    {
        foreach (self::listAll() as $state) {
            $begin = $state['begin'] ?? null;
            $deadline = $state['deadline'] ?? null;
            $now = date('Y-m-d H:i:s');
            $begun = empty($begin) || $begin <= $now;
            $notExpired = empty($deadline) || $deadline >= $now;
            if ($begun && $notExpired) {
                return intval($state['global_sp_state']);
            }
        }
        return Torrent::PROMOTION_NORMAL;
    }
}
