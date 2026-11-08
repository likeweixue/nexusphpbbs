<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class Isp extends ModelTable
{

    public static string $table = 'isp';

    protected $fillable = ['name'];
}
