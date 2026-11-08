<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '登录记录', 'cht' => '登入記錄', 'en' => 'Login logs'],
    'info' => ['chs' => '用户登录时间、IP 与浏览器', 'cht' => '用戶登入時間、IP 與瀏覽器', 'en' => 'Login records'],
    'table' => 'login_logs',
    'searchable' => ['ip', 'client'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'ip', 'label' => ['chs' => 'IP', 'cht' => 'IP', 'en' => 'IP'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'country', 'label' => ['chs' => '国家', 'cht' => '國家', 'en' => 'Country'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'city', 'label' => ['chs' => '城市', 'cht' => '城市', 'en' => 'City'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'client', 'label' => ['chs' => '客户端', 'cht' => '用戶端', 'en' => 'Client'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'created_at', 'label' => ['chs' => '登录时间', 'cht' => '登入時間', 'en' => 'Time'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
