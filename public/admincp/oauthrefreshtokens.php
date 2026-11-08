<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => 'OAuth 刷新令牌', 'cht' => 'OAuth 重新整理權杖', 'en' => 'OAuth refresh tokens'],
    'info' => ['chs' => 'OAuth 发放的刷新令牌', 'cht' => 'OAuth 發放的重新整理權杖', 'en' => 'OAuth refresh tokens'],
    'table' => 'oauth_refresh_tokens',
    'order' => 'expires_at DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => '令牌 ID', 'cht' => '權杖 ID', 'en' => 'Token ID'], 'format' => fn($v) => htmlspecialchars(mb_substr((string)$v, 0, 12, 'UTF-8'))],
        ['key' => 'access_token_id', 'label' => ['chs' => '关联访问令牌', 'cht' => '關聯存取權杖', 'en' => 'Access token'], 'format' => fn($v) => htmlspecialchars(mb_substr((string)$v, 0, 12, 'UTF-8'))],
        ['key' => 'revoked', 'label' => ['chs' => '已撤销', 'cht' => '已撤銷', 'en' => 'Revoked'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'expires_at', 'label' => ['chs' => '过期时间', 'cht' => '過期時間', 'en' => 'Expires'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
