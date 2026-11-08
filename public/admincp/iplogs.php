<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'IP 记录', 'cht' => 'IP 記錄', 'en' => 'IP logs'],
    'info' => ['chs' => '用户 IP 访问历史', 'cht' => '用戶 IP 存取歷史', 'en' => 'IP history'],
    'table' => 'iplog',
    'searchable' => ['ip', 'uri'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'userid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'ip', 'label' => ['chs' => 'IP', 'cht' => 'IP', 'en' => 'IP'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'uri', 'label' => ['chs' => '访问页面', 'cht' => '存取頁面', 'en' => 'URI'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'count', 'label' => ['chs' => '次数', 'cht' => '次數', 'en' => 'Count'], 'format' => fn($v) => intval($v)],
        ['key' => 'access', 'label' => ['chs' => '最近访问', 'cht' => '最近存取', 'en' => 'Last access'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
