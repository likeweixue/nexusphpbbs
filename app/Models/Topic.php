<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class Topic extends ModelTable
{

    public static string $table = 'topics';

    protected $fillable = ['userid', 'subject', 'locked', 'forumid', 'firstpost', 'lastpost', 'sticky', 'hlcolor', 'views'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forumid');
    }

    public function firstPost()
    {
        return $this->belongsTo(Post::class, "firstpost");
    }

    public function lastPost()
    {
        return $this->belongsTo(Post::class, "lastpost");
    }
}
