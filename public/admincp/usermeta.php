<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '用户道具', 'cht' => '用戶道具', 'en' => 'User props'],
    'info' => ['chs' => '用户持有的道具（彩虹 ID、改名卡等）', 'cht' => '用戶持有的道具（彩虹 ID、改名卡等）', 'en' => 'Items held by users'],
    'table' => 'user_metas',
    'searchable' => ['meta_key'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'meta_key', 'label' => ['chs' => '道具', 'cht' => '道具', 'en' => 'Item'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'deadline', 'label' => ['chs' => '到期时间', 'cht' => '到期時間', 'en' => 'Deadline'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'created_at', 'label' => ['chs' => '获得时间', 'cht' => '獲得時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
