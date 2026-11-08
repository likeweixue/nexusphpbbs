中文 | [English](/README-EN.md)

# NexusPHP 论坛版（去种子 · 免 Redis · 免 Composer）

基于 [NexusPHP](https://nexusphp.org/) 1.10.2（Laravel 12 + FilamentPHP 5）的论坛版本。

> 当前版本：**0.2.2**（零依赖版：不需要 vendor / Composer / Laravel，只需要 PHP + MySQL）

- 已移除种子上传、下载、Tracker 等 PT 功能，保留论坛、用户、消息、勋章、签到、考核、道具、管理后台等模块，页面布局不变。
- **不需要 Redis**，缓存/会话/限流/锁全部使用本机文件缓存，安装只需要 **PHP + MySQL**。
- **不需要 Composer**，`vendor/` 已随包提供（已剔除开发依赖），上传即可运行。

## 环境要求

- PHP 8.2 – 8.5，扩展：`bcmath ctype curl fileinfo json mbstring openssl pdo_mysql tokenizer xml mysqli gd gmp zip intl sqlite3 pdo_sqlite`
- MySQL 5.7.8+ / 8.0+（推荐 8.0+）
- 站点根目录指向 `public/`
- 不需要 Redis、Composer、Node.js

## 快速开始

1. 上传本目录到服务器；
2. 把网站根目录设置为 `public/`；
3. 配置 Nginx 伪静态（`try_files $uri $uri/ /nexus.php?$query_string;`）；
4. 访问 `/install/install.php`，填写 MySQL 信息即可；
5. 安装完成后删除 `public/install/`；
6. 添加计划任务（每分钟 `php artisan schedule:run`，每 5 分钟 `php public/cron.php`）。

**完整步骤、Nginx 配置、权限设置、常见问题请查看：[INSTALL-zh.md](INSTALL-zh.md)**

## 功能

- 论坛（版块、主题、回复、置顶、锁定、举报、管理）
- 用户（注册、登录、二次验证、Passkey、等级、权限、邀请、签到、考核、勋章、道具）
- 消息 / 短消息 / staff 消息 / 公告 / 投票 / 友情链接 / 常见问题
- 管理后台（Filament，`/nexusphp`）
- 多语言、插件、JSON API
- 自动备份
- ...（种子、H&R、求种、字幕、认领等 PT 功能已移除）

## 管理后台

```
https://你的域名/nexusphp
```

使用安装时创建的管理员账号登录。

## 目录结构

```
app/            Laravel 应用代码
nexus/          核心类库（含文件缓存 Nexus\Cache\FileStore）
include/        传统页面公共代码
public/         Web 根目录（入口 index.php、nexus.php）
classes/        传统页面类库
storage/        缓存、会话、日志（需可写）
vendor/         生产依赖（已内置，请勿删除）
```

## 许可

GPL-2.0，遵循原项目许可证。原项目：https://github.com/xiaomlove/nexusphp
