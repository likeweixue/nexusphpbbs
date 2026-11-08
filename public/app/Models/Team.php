<?php

namespace App\Models;


use App\Models\Traits\NexusActivityLogTrait;

class Team extends ModelTable
{

    public static string $table = 'teams';

    protected $fillable = ['name', 'sort_index', 'mode',];

    public static function getLabelName()
    {
        return nexus_trans('searchbox.sub_category_team_label');
    }

    public function search_box()
    {
        return $this->belongsTo(SearchBox::class, 'mode', 'id');
    }
}
