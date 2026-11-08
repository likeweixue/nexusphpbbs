<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '改名记录', 'cht' => '改名記錄', 'en' => 'Username change logs'],
    'info' => ['chs' => '用户改名历史', 'cht' => '用戶改名歷史', 'en' => 'Username change history'],
    'table' => 'username_change_logs',
    'searchable' => ['username_old', 'username_new'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'username_old', 'label' => ['chs' => '原用户名', 'cht' => '原用戶名', 'en' => 'Old username'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'username_new', 'label' => ['chs' => '新用户名', 'cht' => '新用戶名', 'en' => 'New username'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'change_type', 'label' => ['chs' => '方式', 'cht' => '方式', 'en' => 'Type'], 'format' => fn($v) => intval($v)],
        ['key' => 'created_at', 'label' => ['chs' => '时间', 'cht' => '時間', 'en' => 'Time'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
