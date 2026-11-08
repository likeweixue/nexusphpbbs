<?php

namespace App\Models;

use App\Enums\ModelEventEnum;
use App\Models\Traits\NexusActivityLogTrait;

class AgentDeny extends ModelTable
{

    public static string $table = 'agent_allowed_exception';

    protected $fillable = [
        'family_id', 'name', 'peer_id', 'agent', 'comment'
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            fire_event(ModelEventEnum::AGENT_DENY_CREATED, $model);
        });
        static::updated(function ($model) {
            fire_event(ModelEventEnum::AGENT_DENY_UPDATED, $model);
        });
        static::deleted(function ($model) {
            fire_event(ModelEventEnum::AGENT_DENY_DELETED, $model);
        });
    }

}
