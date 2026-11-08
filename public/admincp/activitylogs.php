<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '操作日志', 'cht' => '操作日誌', 'en' => 'Activity logs'],
    'info' => ['chs' => '用户与后台的操作日志', 'cht' => '用戶與後台的操作日誌', 'en' => 'Activity logs'],
    'table' => 'activity_log',
    'searchable' => ['description', 'log_name', 'event'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'log_name', 'label' => ['chs' => '通道', 'cht' => '通道', 'en' => 'Log name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'description', 'label' => ['chs' => '描述', 'cht' => '描述', 'en' => 'Description'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'event', 'label' => ['chs' => '事件', 'cht' => '事件', 'en' => 'Event'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'causer_id', 'label' => ['chs' => '操作者', 'cht' => '操作者', 'en' => 'Causer'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'created_at', 'label' => ['chs' => '时间', 'cht' => '時間', 'en' => 'Time'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
