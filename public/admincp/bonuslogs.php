<?php
require_once(__DIR__ . '/_init.php');

$bonusOptions = [
    \App\Models\BonusLogs::BUSINESS_TYPE_CANCEL_HIT_AND_RUN => '取消 H&R',
    2 => '购买勋章', 3 => '购买补签卡', 4 => '置顶', 5 => '发帖奖励',
    6 => '兑换上传量', 7 => '兑换邀请', 8 => '自定义头衔', 9 => '购买贵宾',
    10 => '赠送魔力', 11 => '免广告', 12 => '慈善赠送', 13 => '抽奖',
    14 => '兑换下载量', 15 => '购买临时邀请', 16 => '购买彩虹 ID',
    17 => '购买改名卡', 18 => '赠送勋章', 19 => '购买种子', 20 => '考核未通过扣减',
];

admincp_render_list([
    'title' => ['chs' => '魔力记录', 'cht' => '魔力記錄', 'en' => 'Bonus logs'],
    'info' => ['chs' => '魔力值的获得与消耗流水', 'cht' => '魔力值的獲得與消耗流水', 'en' => 'Bonus point logs'],
    'table' => 'bonus_logs',
    'order' => 'id DESC',
    'columns' => [
        ['key' => 'id', 'label' => ['chs' => 'ID', 'cht' => 'ID', 'en' => 'ID']],
        ['key' => 'uid', 'label' => ['chs' => '用户', 'cht' => '用戶', 'en' => 'User'], 'format' => fn($v) => admincp_username($v)],
        ['key' => 'business_type', 'label' => ['chs' => '类型', 'cht' => '類型', 'en' => 'Type'], 'format' => function ($v) use ($bonusOptions) {
            return htmlspecialchars($bonusOptions[intval($v)] ?? (string)$v);
        }],
        ['key' => 'old_total_value', 'label' => ['chs' => '原总量', 'cht' => '原總量', 'en' => 'Old total'], 'format' => fn($v) => number_format(floatval($v), 1)],
        ['key' => 'value', 'label' => ['chs' => '变动', 'cht' => '變動', 'en' => 'Value'], 'format' => fn($v) => number_format(floatval($v), 1)],
        ['key' => 'new_total_value', 'label' => ['chs' => '新总量', 'cht' => '新總量', 'en' => 'New total'], 'format' => fn($v) => number_format(floatval($v), 1)],
        ['key' => 'comment', 'label' => ['chs' => '备注', 'cht' => '備註', 'en' => 'Comment'], 'format' => fn($v) => htmlspecialchars((string)$v)],
        ['key' => 'created_at', 'label' => ['chs' => '时间', 'cht' => '時間', 'en' => 'Time'], 'format' => fn($v) => admincp_time($v)],
    ],
]);
