<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '用户勋章', 'cht' => '用戶勳章', 'en' => 'User medals'],
    'info' => ['chs' => '查看用户已获得的勋章', 'cht' => '查看用戶已獲得的勳章', 'en' => 'Medals owned by users'],
    'table' => 'user_medals',
    'select' => 'user_medals.*, medals.name AS medal_name',
    'join' => 'LEFT JOIN medals ON medals.id = user_medals.medal_id',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'medal_name', 'label' => ['chs' => '勋章', 'cht' => '勳章', 'en' => 'Medal'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'expire_at', 'label' => ['chs' => '到期时间', 'cht' => '到期時間', 'en' => 'Expire at'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'priority', 'label' => ['chs' => '展示顺序', 'cht' => '展示順序', 'en' => 'Priority'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'created_at', 'label' => ['chs' => '获得时间', 'cht' => '獲得時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
