<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '考核 & 任务', 'cht' => '考核 & 任務', 'en' => 'Exams & tasks'],
    'info' => ['chs' => '查看考核与任务定义', 'cht' => '查看考核與任務定義', 'en' => 'Exam definitions'],
    'table' => 'exams',
    'searchable' => ['name'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'type', 'label' => ['chs' => '类型', 'cht' => '類型', 'en' => 'Type'], 'format' => function ($v) {
            return intval($v) === \App\Models\Exam::TYPE_TASK ? (admincp_lang() === 'en' ? 'Task' : '任务') : (admincp_lang() === 'en' ? 'Exam' : '考核');
        }],
        ['key' => 'begin', 'label' => ['chs' => '开始时间', 'cht' => '開始時間', 'en' => 'Begin'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'end', 'label' => ['chs' => '结束时间', 'cht' => '結束時間', 'en' => 'End'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'duration', 'label' => ['chs' => '时长(天)', 'cht' => '時長(天)', 'en' => 'Duration'], 'format' => fn($v) => intval($v)],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => intval($v)],
    ],
]);
