<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class UploadSpeed extends ModelTable
{

    public static string $table = 'uploadspeed';

    protected $fillable = ['name'];
}
