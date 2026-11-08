<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '签到记录', 'cht' => '簽到記錄', 'en' => 'Attendance logs'],
    'info' => ['chs' => '用户每日签到与获赠魔力记录', 'cht' => '用戶每日簽到與獲贈魔力記錄', 'en' => 'Attendance records'],
    'table' => 'attendance_logs',
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'date', 'label' => ['chs' => '签到日期', 'cht' => '簽到日期', 'en' => 'Date'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'points', 'label' => ['chs' => '获得魔力', 'cht' => '獲得魔力', 'en' => 'Points'], 'format' => fn($v) => number_format(intval($v))],
        ['key' => 'is_retroactive', 'label' => ['chs' => '补签', 'cht' => '補簽', 'en' => 'Retroactive'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'created_at', 'label' => ['chs' => '记录时间', 'cht' => '記錄時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
