<?php
require_once(__DIR__ . '/_init.php');
admincp_render_list([
    'title' => ['chs' => '插件', 'cht' => '外掛', 'en' => 'Plugins'],
    'info' => ['chs' => '已安装的插件列表', 'cht' => '已安裝的外掛列表', 'en' => 'Installed plugins'],
    'table' => 'plugins',
    'searchable' => ['display_name', 'package_name'],
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'display_name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'package_name', 'label' => ['chs' => '包名', 'cht' => '包名', 'en' => 'Package'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'installed_version', 'label' => ['chs' => '版本', 'cht' => '版本', 'en' => 'Version'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'status', 'label' => ['chs' => '状态', 'cht' => '狀態', 'en' => 'Status'], 'format' => fn($v) => intval($v)],
        ['key' => 'created_at', 'label' => ['chs' => '安装时间', 'cht' => '安裝時間', 'en' => 'Installed'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
