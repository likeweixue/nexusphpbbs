<?php

namespace App\Models;

use Nexus\Database\NexusDB;

class TorrentBuyLog extends ModelTable
{
    public static string $table = 'torrent_buy_logs';

    public $timestamps = true;

    protected $fillable = ['uid', 'torrent_id', 'price', 'channel'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function torrent()
    {
        return $this->belongsTo(Torrent::class, 'torrent_id');
    }

}
