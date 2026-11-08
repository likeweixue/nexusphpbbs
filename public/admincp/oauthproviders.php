<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'OAuth 身份验证', 'cht' => 'OAuth 身分驗證', 'en' => 'OAuth providers'],
    'info' => ['chs' => '第三方登录提供方', 'cht' => '第三方登入提供方', 'en' => 'Auth providers'],
    'table' => 'oauth_providers',
    'searchable' => ['name', 'client_id'],
    'order' => 'priority ASC, id ASC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'client_id', 'label' => ['chs' => 'Client ID', 'cht' => 'Client ID', 'en' => 'Client ID'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'enabled', 'label' => ['chs' => '启用', 'cht' => '啟用', 'en' => 'Enabled'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'priority', 'label' => ['chs' => '排序', 'cht' => '排序', 'en' => 'Priority'], 'format' => fn($v) => intval($v)],
    ],
]);
