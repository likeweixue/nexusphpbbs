<?php

namespace App\Models;

class TorrentSecret extends ModelTable
{
    public static string $table = 'torrents_secret';

    protected $fillable = ['uid', 'torrent_id', 'secret'];
}
