<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '访问令牌', 'cht' => '存取權杖', 'en' => 'Access tokens'],
    'info' => ['chs' => '用户创建的 API 访问令牌', 'cht' => '用戶建立的 API 存取權杖', 'en' => 'Personal access tokens'],
    'table' => 'personal_access_tokens',
    'searchable' => ['name'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'tokenable_id', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'abilities', 'label' => ['chs' => '权限', 'cht' => '權限', 'en' => 'Abilities'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'last_used_at', 'label' => ['chs' => '最后使用', 'cht' => '最後使用', 'en' => 'Last used'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'expires_at', 'label' => ['chs' => '过期时间', 'cht' => '過期時間', 'en' => 'Expires'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'created_at', 'label' => ['chs' => '创建时间', 'cht' => '建立時間', 'en' => 'Created'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
