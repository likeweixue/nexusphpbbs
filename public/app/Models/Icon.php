<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class Icon extends ModelTable
{

    public static string $table = 'caticons';

    protected $fillable = ['name', 'folder', 'cssfile', 'multilang', 'secondicon', 'designer', 'comment'];
}
