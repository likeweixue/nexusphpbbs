<?php

namespace App\Models;
class ForumMod extends ModelTable
{
    public static string $table = 'forummods';

    protected $fillable = ['forumid', 'userid'];

}
