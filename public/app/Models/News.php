<?php

namespace App\Models;

use App\Models\Traits\NexusActivityLogTrait;

class News extends ModelTable
{

    public static string $table = 'news';

    protected $fillable = [
        'userid', 'added', 'title', 'body', 'notify',
    ];

    protected $casts = [
        'added' => 'datetime',
    ];



}
