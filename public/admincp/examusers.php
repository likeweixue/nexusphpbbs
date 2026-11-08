<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '用户考核', 'cht' => '用戶考核', 'en' => 'Exam users'],
    'info' => ['chs' => '查看用户的考核记录与结果', 'cht' => '查看用戶的考核記錄與結果', 'en' => 'User exam records'],
    'table' => 'exam_users',
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'exam_id', 'label' => ['chs' => '考核 ID', 'cht' => '考核 ID', 'en' => 'Exam ID']],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'begin', 'label' => ['chs' => '开始时间', 'cht' => '開始時間', 'en' => 'Begin'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'end', 'label' => ['chs' => '结束时间', 'cht' => '結束時間', 'en' => 'End'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'is_done', 'label' => ['chs' => '已完成', 'cht' => '已完成', 'en' => 'Done'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
    ],
]);
