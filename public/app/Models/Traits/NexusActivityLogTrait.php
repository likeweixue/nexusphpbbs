<?php

namespace App\Models\Traits;

/**
 * 活动日志占位（无 vendor 版本不再依赖 spatie/laravel-activitylog）。
 *
 * 需要记录日志的地方请直接写 activity_log 表。
 */
trait NexusActivityLogTrait
{
    public function getActivitylogOptions()
    {
        return null;
    }
}
