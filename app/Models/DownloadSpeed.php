<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class DownloadSpeed extends ModelTable
{

    public static string $table = 'downloadspeed';

    protected $fillable = ['name'];
}
