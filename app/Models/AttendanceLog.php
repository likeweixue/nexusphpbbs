<?php

namespace App\Models;

class AttendanceLog extends ModelTable
{
    public static string $table = 'attendance_logs';

    protected $fillable = ['uid', 'points', 'date', 'is_retroactive'];

    public $timestamps = true;

}
