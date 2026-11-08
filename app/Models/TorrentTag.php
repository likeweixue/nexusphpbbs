<?php

namespace App\Models;

class TorrentTag extends ModelTable
{
    public static string $table = 'torrent_tags';

    public $timestamps = true;

    protected $fillable = [
        'torrent_id', 'tag_id', 'priority'
    ];


}
