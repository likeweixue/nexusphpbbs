<?php

namespace App\Models;


class Reward extends ModelTable
{
    public static string $table = 'magic';

    protected $fillable = ['torrentid', 'userid', 'value', ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
