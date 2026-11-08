<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '用户邀请', 'cht' => '用戶邀請', 'en' => 'Invites'],
    'info' => ['chs' => '查看邀请记录与注册状态', 'cht' => '查看邀請記錄與註冊狀態', 'en' => 'Invite records'],
    'table' => 'invites',
    'searchable' => ['invitee', 'invitee_register_username', 'invitee_register_email'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'inviter', 'label' => ['chs' => '邀请人', 'cht' => '邀請人', 'en' => 'Inviter'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'invitee', 'label' => ['chs' => '被邀请邮箱', 'cht' => '被邀請郵箱', 'en' => 'Invitee email'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'invitee_register_username', 'label' => ['chs' => '注册用户名', 'cht' => '註冊用戶名', 'en' => 'Registered username'], 'format' => fn($v) => admincp_username_by_name($v)],
        ['key' => 'time_invited', 'label' => ['chs' => '邀请时间', 'cht' => '邀請時間', 'en' => 'Invited at'], 'format' => fn($v) => admincp_time($v)],
        ['key' => 'valid', 'label' => ['chs' => '有效', 'cht' => '有效', 'en' => 'Valid'], 'format' => fn($v) => intval($v) ? 'YES' : 'NO'],
        ['key' => 'expired_at', 'label' => ['chs' => '过期时间', 'cht' => '過期時間', 'en' => 'Expire at'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
