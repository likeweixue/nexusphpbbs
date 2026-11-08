<?php
require_once(__DIR__ . '/_init.php');

$classOptions = [];
foreach (\App\Models\User::$classes as $classId => $classInfo) {
    $classOptions[intval($classId)] = $classInfo['text'];
}

admincp_render_list([
    'title' => ['chs' => '用户列表', 'cht' => '用戶列表', 'en' => 'Users'],
    'info' => ['chs' => '按用户名 / 邮箱搜索用户，点击用户名进入用户资料', 'cht' => '按用戶名 / 郵箱搜尋用戶，點擊用戶名進入用戶資料', 'en' => 'Search by username or email, click a username to view details'],
    'table' => 'users',
    'searchable' => ['username', 'email'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'username', 'label' => ['chs' => '用户名', 'cht' => '用戶名', 'en' => 'Username'], 'format' => fn($v) => admincp_username_by_name($v)],
        ['key' => 'email', 'label' => ['chs' => '邮箱', 'cht' => '郵箱', 'en' => 'Email'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'class', 'label' => ['chs' => '等级', 'cht' => '等級', 'en' => 'Class'], 'format' => fn($v) => get_user_class_name(intval($v), false, false, true)],
        ['key' => 'uploaded', 'label' => ['chs' => '上传量', 'cht' => '上傳量', 'en' => 'Uploaded'], 'format' => fn($v) => mksize($v)],
        ['key' => 'downloaded', 'label' => ['chs' => '下载量', 'cht' => '下載量', 'en' => 'Downloaded'], 'format' => fn($v) => mksize($v)],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'enabled', 'label' => ['chs' => '启用', 'cht' => '啟用', 'en' => 'Enabled'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'last_access', 'label' => ['chs' => '最后访问', 'cht' => '最後存取', 'en' => 'Last access'], 'format' => fn($v) => admincp_time($v)],
    ],
]);

function admincp_username_by_name($username): string
{
    return htmlspecialchars((string)$username);
}
