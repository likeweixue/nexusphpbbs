<?php

namespace App\Models;


class RequireSeedTorrent extends ModelTable
{
    public static string $table = 'require_seed_torrents';

    protected $fillable = ['torrent_id'];

    public $timestamps = true;
}
