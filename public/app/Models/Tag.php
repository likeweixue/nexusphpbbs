<?php

namespace App\Models;

use App\Models\Traits\NexusActivityLogTrait;

class Tag extends ModelTable
{

    public static string $table = 'tags';

    public $timestamps = true;

    protected $fillable = [
        'id', 'name', 'color', 'priority', 'created_at', 'updated_at',
        'font_size', 'font_color', 'padding', 'margin', 'border_radius',
        'mode', 'description'
    ];

    const DEFAULTS = [
        [
            'id' => 1,
            'name' => '禁转',
            'color' => '#ff0000',
        ],
        [
            'id' => 2,
            'name' => '首发',
            'color' => '#8F77B5',
        ],
        [
            'id' => 3,
            'name' => '官方',
            'color' => '#0000ff',
        ],
        [
            'id' => 4,
            'name' => 'DIY',
            'color' => '#46d5ff',
        ],
        [
            'id' => 5,
            'name' => '国语',
            'color' => '#6a3906',
        ],
        [
            'id' => 6,
            'name' => '中字',
            'color' => '#006400',
        ],
        [
            'id' => 7,
            'name' => 'HDR',
            'color' => '#38b03f',
        ],
    ];

    public static function listSpecial(): array
    {
        return array_filter([
            Setting::get('bonus.official_tag'),
            Setting::get('bonus.zero_bonus_tag'),
        ]);
    }






}
