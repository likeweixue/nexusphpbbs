<?php

namespace App\Models;

class UserRequireSeedTorrent extends ModelTable
{
    public static string $table = 'user_require_seed_torrents';

    protected $fillable = ['user_id', 'torrent_id', 'seed_time_begin', 'uploaded_begin', 'last_settlement_at'];

    public $timestamps = true;
}
