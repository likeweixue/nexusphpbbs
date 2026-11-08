<?php

namespace App\Models;

use App\Models\Traits\NexusActivityLogTrait;
use Nexus\Database\NexusDB;

class TorrentDenyReason extends ModelTable
{

    public static string $table = 'torrent_deny_reasons';

    public $timestamps = true;

    protected $fillable = ['name', 'hits', 'priority',];

}
