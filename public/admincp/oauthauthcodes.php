<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'OAuth 授权码', 'cht' => 'OAuth 授權碼', 'en' => 'OAuth auth codes'],
    'info' => ['chs' => 'OAuth 授权码记录', 'cht' => 'OAuth 授權碼記錄', 'en' => 'OAuth auth codes'],
    'table' => 'oauth_auth_codes',
    'join' => 'LEFT JOIN oauth_clients ON oauth_clients.id = oauth_auth_codes.client_id',
    'select' => 'oauth_auth_codes.*, oauth_clients.name AS client_name',
    'order' => 'oauth_auth_codes.expires_at DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => '授权码 ID', 'cht' => '授權碼 ID', 'en' => 'Code ID'], 'format' => fn($v) => htmlspecialchars(mb_substr((string)$v, 0, 12, 'UTF-8'))],
        ['key' => 'user_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'client_name', 'label' => ['chs' => '客户端', 'cht' => '用戶端', 'en' => 'Client'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'revoked', 'label' => ['chs' => '已撤销', 'cht' => '已撤銷', 'en' => 'Revoked'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'expires_at', 'label' => ['chs' => '过期时间', 'cht' => '過期時間', 'en' => 'Expires'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
