<?php

namespace App\Models;


class Forum extends ModelTable
{
    public static string $table = 'forums';

    protected $fillable = ['sort', 'name', 'description', 'minclassread', 'minclasswrite', 'postcount', 'topiccount', 'minclasscreate', 'forid'];

    public function moderators()
    {
        return $this->belongsToMany(User::class, "forummods", "forumid", "userid");
    }
}
