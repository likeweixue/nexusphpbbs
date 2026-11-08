<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'OAuth 访问令牌', 'cht' => 'OAuth 存取權杖', 'en' => 'OAuth access tokens'],
    'info' => ['chs' => 'OAuth 发放的访问令牌', 'cht' => 'OAuth 發放的存取權杖', 'en' => 'OAuth access tokens'],
    'table' => 'oauth_access_tokens',
    'join' => 'LEFT JOIN oauth_clients ON oauth_clients.id = oauth_access_tokens.client_id',
    'select' => 'oauth_access_tokens.*, oauth_clients.name AS client_name',
    'order' => 'oauth_access_tokens.created_at DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => '令牌 ID', 'cht' => '權杖 ID', 'en' => 'Token ID'], 'format' => fn($v) => htmlspecialchars(mb_substr((string)$v, 0, 12, 'UTF-8'))],
        ['key' => 'user_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'client_name', 'label' => ['chs' => '客户端', 'cht' => '用戶端', 'en' => 'Client'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'revoked', 'label' => ['chs' => '已撤销', 'cht' => '已撤銷', 'en' => 'Revoked'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'expires_at', 'label' => ['chs' => '过期时间', 'cht' => '過期時間', 'en' => 'Expires'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'created_at', 'label' => ['chs' => '创建时间', 'cht' => '建立時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
