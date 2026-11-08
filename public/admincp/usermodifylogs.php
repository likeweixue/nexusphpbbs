<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '修改记录', 'cht' => '修改記錄', 'en' => 'User modify logs'],
    'info' => ['chs' => '用户资料被修改的历史', 'cht' => '用戶資料被修改的歷史', 'en' => 'User modify history'],
    'table' => 'user_modify_logs',
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'user_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'content', 'label' => ['chs' => '修改内容', 'cht' => '修改內容', 'en' => 'Content'], 'format' => fn($v) => nl2br(htmlspecialchars((string)$v))],
        ['key' => 'created_at', 'label' => ['chs' => '时间', 'cht' => '時間', 'en' => 'Time'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
