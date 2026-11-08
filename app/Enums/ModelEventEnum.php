<?php

namespace App\Enums;

use App\Models\AgentAllow;
use App\Models\AgentDeny;
use App\Models\HitAndRun;
use App\Models\Message;
use App\Models\News;
use App\Models\Snatch;
use App\Models\Torrent;
use App\Models\User;

final class ModelEventEnum {
    const TORRENT_CREATED = 'torrent_created';
    const TORRENT_UPDATED = 'torrent_updated';
    const TORRENT_DELETED = 'torrent_deleted';

    const USER_CREATED = 'user_created';
    const USER_UPDATED = 'user_updated';
    const USER_DELETED = 'user_deleted';
    const USER_ENABLED = 'user_enabled';
    const USER_DISABLED = 'user_disabled';

    const NEWS_CREATED = 'news_created';

    const HIT_AND_RUN_CREATED = 'hit_and_run_created';
    const HIT_AND_RUN_UPDATED = 'hit_and_run_updated';
    const HIT_AND_RUN_DELETED = 'hit_and_run_deleted';

    const AGENT_ALLOW_CREATED = 'agent_allow_created';
    const AGENT_ALLOW_UPDATED = 'agent_allow_updated';
    const AGENT_ALLOW_DELETED = 'agent_allow_deleted';

    const AGENT_DENY_CREATED = 'agent_deny_created';
    const AGENT_DENY_UPDATED = 'agent_deny_updated';
    const AGENT_DENY_DELETED = 'agent_deny_deleted';

    const SNATCHED_UPDATED = 'snatched_updated';
    const MESSAGE_CREATED = 'message_created';

    public static array $eventMaps = [
        self::TORRENT_CREATED => ['model' => Torrent::class],
        self::TORRENT_UPDATED => ['model' => Torrent::class],
        self::TORRENT_DELETED => ['model' => Torrent::class],

        self::USER_CREATED => ['model' => User::class],
        self::USER_UPDATED => ['model' => User::class],
        self::USER_DELETED => ['model' => User::class],
        self::USER_ENABLED => ['model' => User::class],
        self::USER_DISABLED => ['model' => User::class],

        self::NEWS_CREATED => ['model' => News::class],

        self::SNATCHED_UPDATED => ['model' => Snatch::class],

        self::MESSAGE_CREATED => ['model' => Message::class],

        self::HIT_AND_RUN_CREATED => ['model' => HitAndRun::class],
        self::HIT_AND_RUN_UPDATED => ['model' => HitAndRun::class],
        self::HIT_AND_RUN_DELETED => ['model' => HitAndRun::class],

        self::AGENT_ALLOW_CREATED => ['model' => AgentAllow::class],
        self::AGENT_ALLOW_UPDATED => ['model' => AgentAllow::class],
        self::AGENT_ALLOW_DELETED => ['model' => AgentAllow::class],

        self::AGENT_DENY_CREATED => ['model' => AgentDeny::class],
        self::AGENT_DENY_UPDATED => ['model' => AgentDeny::class],
        self::AGENT_DENY_DELETED => ['model' => AgentDeny::class],
    ];
}
