<?php

namespace App\Models;

use Nexus\Database\NexusDB;

class TorrentCustomField extends ModelTable
{
    public static string $table = 'torrents_custom_fields';

    public $timestamps = true;

    protected $fillable = [
        'name', 'label', 'type', 'required', 'is_single_row', 'options', 'help', 'display', 'priority'
    ];

    public static function getCheckboxOptions(): array
    {
        $result = [];
        $records = self::query()->get();
        foreach ($records as $value) {
            $result[$value->id] = sprintf('%s[%s]', $value->name, $value->label);
        }
        return $result;
    }

}
