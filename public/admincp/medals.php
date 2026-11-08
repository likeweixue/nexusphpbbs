<?php
require_once(__DIR__ . '/_init.php');
$getTypes = [
    \App\Models\Medal::GET_TYPE_EXCHANGE => ['chs' => '魔力兑换', 'cht' => '魔力兌換', 'en' => 'Exchange'],
    \App\Models\Medal::GET_TYPE_GRANT => ['chs' => '手动授予', 'cht' => '手動授予', 'en' => 'Grant'],
];
admincp_render_list([
    'title' => ['chs' => '勋章', 'cht' => '勳章', 'en' => 'Medals'],
    'info' => ['chs' => '查看与维护勋章定义', 'cht' => '查看與維護勳章定義', 'en' => 'Medal definitions'],
    'table' => 'medals',
    'searchable' => ['name'],
    'order' => 'id ASC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'name', 'label' => ['chs' => '名称', 'cht' => '名稱', 'en' => 'Name'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'get_type', 'label' => ['chs' => '获得方式', 'cht' => '獲得方式', 'en' => 'Get type'], 'format' => function ($v) use ($getTypes) {
            return htmlspecialchars(admincp_t($getTypes[intval($v)] ?? ['chs' => (string)$v, 'en' => (string)$v]));
        }],
        ['key' => 'price', 'label' => ['chs' => '价格', 'cht' => '價格', 'en' => 'Price'], 'format' => fn($v) => number_format(intval($v))],
        ['key' => 'duration', 'label' => ['chs' => '有效期(天)', 'cht' => '有效期(天)', 'en' => 'Duration (days)'], 'format' => fn($v) => intval($v)],
        ['key' => 'inventory', 'label' => ['chs' => '库存', 'cht' => '庫存', 'en' => 'Inventory'], 'format' => fn($v) => intval($v)],
        ['key' => 'priority', 'label' => ['chs' => '排序', 'cht' => '排序', 'en' => 'Priority'], 'format' => fn($v) => intval($v)],
    ],
]);
