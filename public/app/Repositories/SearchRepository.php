<?php

namespace App\Repositories;

/**
 * 搜索相关常量（纯 PHP 版，0.2.x 只做数据库搜索）。
 */
class SearchRepository extends BaseRepository
{
    public const SEARCH_AREA_TITLE = '0';
    public const SEARCH_AREA_DESC = '1';
    public const SEARCH_AREA_OWNER = '3';
    public const SEARCH_AREA_IMDB = '4';

    public const SEARCH_AREAS = [
        self::SEARCH_AREA_TITLE => 'search.title',
        self::SEARCH_AREA_DESC => 'search.description',
        self::SEARCH_AREA_OWNER => 'search.owner',
        self::SEARCH_AREA_IMDB => 'search.imdb',
    ];
}
