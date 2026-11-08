<?php

namespace App\Models;

class LoginLog extends ModelTable
{
    public static string $table = 'login_logs';

    public $timestamps = true;

    protected $fillable = [
        'uid', 'ip', 'country', 'city', 'client'
    ];


}
