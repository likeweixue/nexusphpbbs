<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '消息模板', 'cht' => '訊息範本', 'en' => 'Message templates'],
    'info' => ['chs' => '站内信与通知模板', 'cht' => '站內信與通知範本', 'en' => 'Message templates'],
    'table' => 'message_templates',
    'searchable' => ['name', 'content'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'language_id', 'label' => ['chs' => '语言 ID', 'cht' => '語言 ID', 'en' => 'Language ID'], 'format' => fn($v) => intval($v)],
        ['key' => 'content', 'label' => ['chs' => '内容', 'cht' => '內容', 'en' => 'Content'], 'format' => fn($v) => htmlspecialchars(mb_substr((string)$v, 0, 200, 'UTF-8'))],
        ['key' => 'updated_at', 'label' => ['chs' => '更新时间', 'cht' => '更新時間', 'en' => 'Updated'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
