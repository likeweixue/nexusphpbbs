<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'OAuth 客户端', 'cht' => 'OAuth 用戶端', 'en' => 'OAuth clients'],
    'info' => ['chs' => 'OAuth 客户端应用', 'cht' => 'OAuth 用戶端應用', 'en' => 'OAuth clients'],
    'table' => 'oauth_clients',
    'searchable' => ['name', 'provider'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'provider', 'label' => ['chs' => '提供方', 'cht' => '提供方', 'en' => 'Provider'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'user_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'revoked', 'label' => ['chs' => '已撤销', 'cht' => '已撤銷', 'en' => 'Revoked'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'created_at', 'label' => ['chs' => '创建时间', 'cht' => '建立時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
