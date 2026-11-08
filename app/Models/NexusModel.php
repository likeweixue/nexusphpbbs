<?php

namespace App\Models;

/**
 * 模型基类（纯 PHP 版）。
 *
 * 只保留传统页面实际用到的通用能力：静态工具方法；数据读写请使用
 * NexusDB::table() / NexusDB::select() 等原生查询接口。
 */
class NexusModel
{
    /**
     * Check is valid date string
     *
     * @see https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
     * @param $name
     * @param string $format
     * @return bool
     */
    public function isValidDate($name, $format = 'Y-m-d H:i:s'): bool
    {
        $date = $this->{$name} ?? null;
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function getDeadlineText($field = 'deadline')
    {
        $raw = $this->{$field} ?? null;
        if (in_array($raw, [null, '0000-00-00 00:00:00', ''], true)) {
            return nexus_trans("label.permanent");
        }
        return sprintf('%s: %s', nexus_trans('label.deadline'), $raw);
    }

    public static function listStaticProps($dataSource, $textTransPrefix, $onlyKeyValue = false, $valueField = 'text'): array
    {
        $result = $dataSource;
        $keyValue = [];
        foreach ($result as $key => &$info) {
            if (str_contains($textTransPrefix, '%s')) {
                $transKey = sprintf($textTransPrefix, $key);
            } else {
                $transKey = "$textTransPrefix.$key";
            }
            $text = $textTransPrefix ? nexus_trans($transKey) : $info['text'];
            $info['text'] = $text;
            $keyValue[$key] = $info[$valueField];
        }
        if ($onlyKeyValue) {
            return $keyValue;
        }
        return $result;
    }

}
