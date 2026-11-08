<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '通行密钥', 'cht' => '通行密鑰', 'en' => 'Passkeys'],
    'info' => ['chs' => '用户绑定的 Passkey 登录凭据', 'cht' => '用戶綁定的 Passkey 登入憑據', 'en' => 'Passkey credentials'],
    'table' => 'user_passkeys',
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'user_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'AAGUID', 'label' => ['chs' => 'AAGUID', 'cht' => 'AAGUID', 'en' => 'AAGUID'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'counter', 'label' => ['chs' => '计数器', 'cht' => '計數器', 'en' => 'Counter'], 'format' => fn($v) => intval($v)],
        ['key' => 'created_at', 'label' => ['chs' => '绑定时间', 'cht' => '綁定時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
