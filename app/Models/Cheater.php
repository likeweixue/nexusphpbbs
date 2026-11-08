<?php

namespace App\Models;


class Cheater extends ModelTable
{
    public static string $table = 'cheaters';

    protected $fillable = [
        'added', 'userid', 'torrentid', 'uploaded', 'downloaded', 'anctime', 'seeders', 'leechers', 'hit',
        'dealtby', 'dealtwith', 'comment',
    ];
}
