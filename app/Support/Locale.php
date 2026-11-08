<?php

namespace App\Support;

/**
 * 语言代码映射（替代原 App\Http\Middleware\Locale 的静态方法，纯 PHP 实现）
 */
class Locale
{
    public static array $languageMaps = [
        'en' => 'en',
        'chs' => 'zh_CN',
        'cht' => 'zh_TW',
    ];

    public static function toLocale($lang)
    {
        return self::$languageMaps[$lang] ?? $lang;
    }

    public static function getLocaleFromCookie($default = 'en')
    {
        $lang = get_langfolder_cookie();
        return self::$languageMaps[$lang] ?? $default;
    }

    public static function getDefault()
    {
        $defaultLang = get_setting('main.defaultlang');
        return self::$languageMaps[$defaultLang] ?? $defaultLang;
    }
}
