<?php

namespace App\Models;

class UserModifyLog extends ModelTable
{
    public static string $table = 'user_modify_logs';

    protected $fillable = ['user_id', 'content', ];

    public $timestamps = true;


}
