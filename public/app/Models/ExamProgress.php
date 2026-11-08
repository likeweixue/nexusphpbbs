<?php

namespace App\Models;

class ExamProgress extends ModelTable
{
    public static string $table = 'exam_progress';

    protected $fillable = ['exam_user_id', 'exam_id', 'uid', 'index', 'init_value', 'value', 'torrent_id'];

    public $timestamps = true;
}
