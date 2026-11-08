<?php

namespace App\Console\Admin;

/**
 * 后台管理菜单定义（传统 PHP 版管理后台）。
 *
 * 只包含静态数据，不依赖 Filament / Laravel 运行时，没有 vendor 也能加载。
 * 每一项：
 *  - name: 中文（简体 / 繁体）/ 英文名称
 *  - info: 说明
 *  - handler: 传统页面处理文件（public/admincp/*.php），为空表示只能在新版后台查看
 *  - route: 新版 Filament 后台的相对路径（有 vendor 时可用）
 */
class AdminMenu
{
    public const GROUPS = [
        'user' => ['chs' => '用户相关', 'cht' => '用戶相關', 'en' => 'User'],
        'system' => ['chs' => '系统相关', 'cht' => '系統相關', 'en' => 'System'],
        'oauth' => ['chs' => 'OAuth 授权', 'cht' => 'OAuth 授權', 'en' => 'OAuth'],
    ];

    public static function items(): array
    {
        return [
            // ---------- 用户 ----------
            [
                'group' => 'user',
                'name' => ['chs' => '用户列表', 'cht' => '用戶列表', 'en' => 'Users'],
                'info' => ['chs' => '搜索、查看用户资料，调整等级 / 状态', 'cht' => '搜尋、查看用戶資料，調整等級 / 狀態', 'en' => 'Search and view users'],
                'handler' => 'users.php',
                'route' => 'users',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '用户道具', 'cht' => '用戶道具', 'en' => 'User props'],
                'info' => ['chs' => '用户持有的道具（彩虹 ID、改名卡等）', 'cht' => '用戶持有的道具（彩虹 ID、改名卡等）', 'en' => 'Items held by users'],
                'handler' => 'usermeta.php',
                'route' => 'user-metas',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '用户勋章', 'cht' => '用戶勳章', 'en' => 'User medals'],
                'info' => ['chs' => '查看用户已获得的勋章', 'cht' => '查看用戶已獲得的勳章', 'en' => 'Medals owned by users'],
                'handler' => 'usermedals.php',
                'route' => 'user-medals',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '用户邀请', 'cht' => '用戶邀請', 'en' => 'Invites'],
                'info' => ['chs' => '查看邀请记录与注册状态', 'cht' => '查看邀請記錄與註冊狀態', 'en' => 'Invite records'],
                'handler' => 'invites.php',
                'route' => 'invites',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '用户考核', 'cht' => '用戶考核', 'en' => 'Exam users'],
                'info' => ['chs' => '查看用户的考核记录与结果', 'cht' => '查看用戶的考核記錄與結果', 'en' => 'User exam records'],
                'handler' => 'examusers.php',
                'route' => 'exam-users',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '签到记录', 'cht' => '簽到記錄', 'en' => 'Attendance logs'],
                'info' => ['chs' => '用户每日签到与获赠魔力记录', 'cht' => '用戶每日簽到與獲贈魔力記錄', 'en' => 'Attendance records'],
                'handler' => 'attendancelogs.php',
                'route' => 'attendance-logs',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '魔力记录', 'cht' => '魔力記錄', 'en' => 'Bonus logs'],
                'info' => ['chs' => '魔力值的获得与消耗流水', 'cht' => '魔力值的獲得與消耗流水', 'en' => 'Bonus point logs'],
                'handler' => 'bonuslogs.php',
                'route' => 'bonus-logs',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '登录记录', 'cht' => '登入記錄', 'en' => 'Login logs'],
                'info' => ['chs' => '用户登录时间、IP 与浏览器', 'cht' => '用戶登入時間、IP 與瀏覽器', 'en' => 'Login records'],
                'handler' => 'loginlogs.php',
                'route' => 'login-logs',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '修改记录', 'cht' => '修改記錄', 'en' => 'User modify logs'],
                'info' => ['chs' => '用户资料被修改的历史', 'cht' => '用戶資料被修改的歷史', 'en' => 'User modify history'],
                'handler' => 'usermodifylogs.php',
                'route' => 'user-modify-logs',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '改名记录', 'cht' => '改名記錄', 'en' => 'Username change logs'],
                'info' => ['chs' => '用户改名历史', 'cht' => '用戶改名歷史', 'en' => 'Username change history'],
                'handler' => 'usernamechangelogs.php',
                'route' => 'username-change-logs',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '访问令牌', 'cht' => '存取權杖', 'en' => 'Access tokens'],
                'info' => ['chs' => '用户创建的 API 访问令牌', 'cht' => '用戶建立的 API 存取權杖', 'en' => 'Personal access tokens'],
                'handler' => 'tokens.php',
                'route' => 'tokens',
            ],
            [
                'group' => 'user',
                'name' => ['chs' => '通行密钥', 'cht' => '通行密鑰', 'en' => 'Passkeys'],
                'info' => ['chs' => '用户绑定的 Passkey 登录凭据', 'cht' => '用戶綁定的 Passkey 登入憑據', 'en' => 'Passkey credentials'],
                'handler' => 'passkeys.php',
                'route' => 'user-passkeys',
            ],

            // ---------- 系统 ----------
            [
                'group' => 'system',
                'name' => ['chs' => '站点设置（新版）', 'cht' => '站點設定（新版）', 'en' => 'Settings (new)'],
                'info' => ['chs' => '新版设置界面；传统设置仍可用「站点设定」', 'cht' => '新版設定介面；傳統設定仍可用「站點設定」', 'en' => 'New settings UI'],
                'handler' => '',
                'route' => 'settings',
                'filament_only' => true,
            ],
            [
                'group' => 'system',
                'name' => ['chs' => '勋章', 'cht' => '勳章', 'en' => 'Medals'],
                'info' => ['chs' => '查看与维护勋章定义', 'cht' => '查看與維護勳章定義', 'en' => 'Medal definitions'],
                'handler' => 'medals.php',
                'route' => 'medals',
            ],
            [
                'group' => 'system',
                'name' => ['chs' => '考核 & 任务', 'cht' => '考核 & 任務', 'en' => 'Exams & tasks'],
                'info' => ['chs' => '查看考核与任务定义', 'cht' => '查看考核與任務定義', 'en' => 'Exam definitions'],
                'handler' => 'exams.php',
                'route' => 'exams',
            ],
            [
                'group' => 'system',
                'name' => ['chs' => '消息模板', 'cht' => '訊息範本', 'en' => 'Message templates'],
                'info' => ['chs' => '站内信与通知模板', 'cht' => '站內信與通知範本', 'en' => 'Message templates'],
                'handler' => 'messagetemplates.php',
                'route' => 'message-templates',
            ],
            [
                'group' => 'system',
                'name' => ['chs' => '操作日志', 'cht' => '操作日誌', 'en' => 'Activity logs'],
                'info' => ['chs' => '用户与后台的操作日志', 'cht' => '用戶與後台的操作日誌', 'en' => 'Activity logs'],
                'handler' => 'activitylogs.php',
                'route' => 'activity-logs',
            ],
            [
                'group' => 'system',
                'name' => ['chs' => 'IP 记录', 'cht' => 'IP 記錄', 'en' => 'IP logs'],
                'info' => ['chs' => '用户 IP 访问历史', 'cht' => '用戶 IP 存取歷史', 'en' => 'IP history'],
                'handler' => 'iplogs.php',
                'route' => 'ip-logs',
            ],
            [
                'group' => 'system',
                'name' => ['chs' => '插件', 'cht' => '外掛', 'en' => 'Plugins'],
                'info' => ['chs' => '已安装的插件列表', 'cht' => '已安裝的外掛列表', 'en' => 'Installed plugins'],
                'handler' => 'plugins.php',
                'route' => 'plugins',
            ],

            // ---------- OAuth ----------
            [
                'group' => 'oauth',
                'name' => ['chs' => '客户端', 'cht' => '用戶端', 'en' => 'Clients'],
                'info' => ['chs' => 'OAuth 客户端应用', 'cht' => 'OAuth 用戶端應用', 'en' => 'OAuth clients'],
                'handler' => 'oauthclients.php',
                'route' => 'clients',
            ],
            [
                'group' => 'oauth',
                'name' => ['chs' => '身份验证', 'cht' => '身分驗證', 'en' => 'Providers'],
                'info' => ['chs' => '第三方登录提供方', 'cht' => '第三方登入提供方', 'en' => 'Auth providers'],
                'handler' => 'oauthproviders.php',
                'route' => 'providers',
            ],
            [
                'group' => 'oauth',
                'name' => ['chs' => 'OAuth 访问令牌', 'cht' => 'OAuth 存取權杖', 'en' => 'OAuth access tokens'],
                'info' => ['chs' => 'OAuth 发放的访问令牌', 'cht' => 'OAuth 發放的存取權杖', 'en' => 'OAuth access tokens'],
                'handler' => 'oauthaccesstokens.php',
                'route' => 'access-tokens',
            ],
            [
                'group' => 'oauth',
                'name' => ['chs' => 'OAuth 刷新令牌', 'cht' => 'OAuth 重新整理權杖', 'en' => 'OAuth refresh tokens'],
                'info' => ['chs' => 'OAuth 发放的刷新令牌', 'cht' => 'OAuth 發放的重新整理權杖', 'en' => 'OAuth refresh tokens'],
                'handler' => 'oauthrefreshtokens.php',
                'route' => 'refresh-tokens',
            ],
            [
                'group' => 'oauth',
                'name' => ['chs' => 'OAuth 授权码', 'cht' => 'OAuth 授權碼', 'en' => 'OAuth auth codes'],
                'info' => ['chs' => 'OAuth 授权码记录', 'cht' => 'OAuth 授權碼記錄', 'en' => 'OAuth auth codes'],
                'handler' => 'oauthauthcodes.php',
                'route' => 'auth-codes',
            ],
        ];
    }

    public static function text(array $field, string $lang): string
    {
        if (isset($field[$lang])) {
            return $field[$lang];
        }
        if (str_starts_with($lang, 'ch')) {
            return $field[str_starts_with($lang, 'cht') ? 'cht' : 'chs'] ?? ($field['chs'] ?? '');
        }
        return $field['en'] ?? '';
    }
}
