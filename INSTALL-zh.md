# NexusPHP 论坛版（去种子功能 · 免 Redis · 免 Composer）

本目录是基于 **NexusPHP 1.10.2** 的论坛版代码，已按需求做了以下调整：

- **移除种子上传 / 下载 / Tracker 相关入口**（保留论坛、用户、消息、勋章、签到、考核、管理后台等原有功能与页面布局）。
- **不再需要 Redis**：缓存、会话、限流、定时任务锁全部改为 PHP 文件缓存，数据库只需要 MySQL。
- **不需要在服务器上执行 `composer install`**：`vendor/` 已随包提供，并已剔除开发依赖（phpunit、faker、mockery、laravel-lang 等）。
- 安装向导已简化：只填写 **MySQL 连接信息 + 时区**，其余自动完成。

> 重要：`vendor/` 不能删除。Laravel 框架本身、Filament 管理后台、Livewire 等全部位于 `vendor/` 中，删除后程序无法启动。这里的“免依赖”是指**服务器上不需要安装 Composer、不需要联网下载依赖**，而不是不需要这个目录。

---

## 一、环境要求

| 项目 | 要求 |
| --- | --- |
| PHP | 8.2 / 8.3 / 8.4 / 8.5（推荐 8.3 或 8.4，已在 8.4 实测） |
| 数据库 | MySQL 5.7.8+ / 8.0+（推荐 8.0+，字符集 utf8mb4） |
| Web 服务器 | Nginx 或 Apache，站点根目录必须指向 `public/` |
| Redis | **不需要** |
| Composer | **不需要** |
| Node.js | **不需要**（前端资源已编译好） |

### PHP 扩展

```
bcmath ctype curl fileinfo json mbstring openssl pdo_mysql tokenizer xml
mysqli gd gmp zip intl sqlite3 pdo_sqlite
```

> `sqlite3` 与 `pdo_sqlite` 用于后台“插件市场”的只读数据表，绝大多数 PHP 环境默认已开启；若安装向导提示缺少，请在 PHP 扩展中启用。

### 需要开启的函数

```
symlink putenv proc_open proc_get_status exec
```

> 安装向导需要通过 `exec()` 调用 `php artisan migrate / db:seed`。宝塔、1Panel 等面板默认均可用。

### 目录权限

以下目录需要 PHP 运行用户可写：

```
storage/                 （含 storage/framework/cache、sessions、views、logs）
bootstrap/cache/
attachments/
bitbucket/
public/                 （安装时创建 attachments、bitbucket 软链接）
项目根目录               （安装时写入 .env 与 dont_delete_install.lock）
```

安装完成并删除 `public/install/` 后，项目根目录可以恢复为只读（`.env` 已生成）。

安装完成后可以把 `public/install/` 整个删除。

---

## 二、安装步骤

1. **上传代码**  
   把本目录全部上传到服务器，例如 `/www/wwwroot/nexusphp-bbs`。  
   也可以直接上传 `nexusphp-bbs.zip`，在服务器上解压，速度更快。

2. **设置站点根目录**  
   Nginx / Apache 的网站根目录（Document Root）必须设置为：

   ```
   /www/wwwroot/nexusphp-bbs/public
   ```

3. **配置伪静态（Nginx）**

   ```nginx
   server {
       listen 80;
       server_name bbs.example.com;
       root /www/wwwroot/nexusphp-bbs/public;
       index index.php;

       client_max_body_size 100M;

       location / {
           try_files $uri $uri/ /nexus.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/run/php/php8.3-fpm.sock;  # 按实际 PHP-FPM 地址修改
           fastcgi_index index.php;
           include fastcgi_params;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       }
   }
   ```

   这条 `try_files` 同时负责 `/api/...`（登录挑战接口）和 `/nexusphp`（管理后台）的路由，请勿遗漏。

   Apache 用户请在 `public/` 下添加 `.htaccess`：

   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^ nexus.php [L]
   ```

4. **访问安装向导**

   ```
   https://你的域名/install/install.php
   ```

   按提示操作：
   - 环境检查（全部通过后点 Next）
   - 填写 MySQL 地址、端口、库名、用户名、密码，选择时区
   - 自动建表、导入初始数据
   - 创建管理员账号

   安装成功后会生成 `dont_delete_install.lock`，并提示删除 `public/install`。请务必删除该目录。

5. **配置计划任务**

   ```cron
   # Laravel 定时任务（考核、签到、备份、清理提醒等），每分钟
   * * * * * cd /www/wwwroot/nexusphp-bbs && /usr/bin/php artisan schedule:run >> /dev/null 2>&1

   # NexusPHP 传统清理任务（删除过期日志、清理无效账号等），每 5 分钟
   */5 * * * * cd /www/wwwroot/nexusphp-bbs && /usr/bin/php public/cron.php >> /dev/null 2>&1
   ```

   > `php` 路径请换成服务器实际的 PHP CLI 路径（宝塔一般为 `/www/server/php/83/bin/php`，1Panel 可在运行环境中查看）。  
   > 如果命令行 PHP 不在 PATH 中，也可以把 `PHP_PATH=/www/server/php/83/bin/php` 写进 `.env`，安装向导会自动使用。

6. **登录管理后台**

   ```
   https://你的域名/nexusphp
   ```

   使用安装时创建的管理员账号登录即可。

---

## 三、从旧版升级 / 保留原有数据

如果你已经有正在运行的 NexusPHP 论坛，只想替换成这个版本并保留数据：

1. **先备份**数据库、`attachments/`、`bitbucket/`、`.env`。
2. 上传新代码时**不要覆盖**原来的 `.env`、`attachments/`、`bitbucket/`。
3. 检查 `.env` 中以下三项（没有就加上，值写错也没关系，程序会自动回退）：

   ```env
   CACHE_STORE=nexus
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

   旧的 `REDIS_HOST`、`CACHE_DRIVER=redis` 等可以保留，也可以删除，程序不会再连接 Redis。
4. 执行数据库迁移（0.1.4 起新增了自定义菜单表、主题记录、收藏表等，必须执行）：

   ```bash
   php artisan migrate --force
   php artisan cache:clear
   ```

5. 删除旧的 `storage/framework/cache/nexus/` 内容（如果是从本版本升级）。
6. 保留原来的 `dont_delete_install.lock`，不要重复安装；计划任务按上面的配置即可。

> 如果旧站启用了 Redis 队列（Horizon / Supervisor），升级后可以停止并删除这些进程，本版本不再需要。

## 三之二、新功能入口

- **自定义菜单**：登录管理组账号 → `staffpanel.php` → 系统面板“菜单栏设置”，或直接访问 `custommenu.php`。
- **访客浏览**：`settings.php` → 主设置 → “站点访问权限”选“是”，未登录访客只能浏览论坛。
- **论坛 RSS**：访问 `getrss.php`，选择分区/板块/主题生成订阅地址；顶部 RSS 图标也可进入。
- **复古黄色主题**：`settings.php` 的“默认界面风格”或用户控制面板里选择“复古黄色”（所有主题现在都是全宽布局）。
- **魔力值说明**：`mybonus.php` 页面底部有“编辑”按钮（管理组可见），可修改魔力值介绍并预览。
- **帖子收藏**：帖子右下角“引用”左边的星标可收藏/取消收藏；顶部“收藏”进入 `collections.php` 查看自己的帖子收藏。

## 四、缓存说明（替代 Redis）

- 缓存目录：`storage/framework/cache/nexus/`
- 会话目录：`storage/framework/sessions/`
- 清空缓存：

  ```bash
  php artisan cache:clear
  # 或者直接删除缓存文件
  rm -rf storage/framework/cache/nexus/*
  ```

- 系统每天 03:30 自动执行 `php artisan cache:gc` 清理过期缓存文件，也可手动执行。
- 缓存为**本机文件**，因此本方案适用于单台服务器。如果以后要做多台 Web 负载均衡，需要把 `storage/` 放到共享存储，或改用 Redis（本版本未内置）。

缓存配置在 `.env`：

```env
CACHE_STORE=nexus
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

> 如果你是从旧版本复制过来的 `.env`，安装向导会自动改写成上面的值；程序也做了兜底：即使旧 `.env` 里还是 `redis`，也会自动回退到文件缓存/同步队列。

---

## 五、与原版 1.10.2 的主要差异

| 项目 | 原版 | 本版本 |
| --- | --- | --- |
| Redis | 必需 | 不需要，文件缓存替代 |
| Horizon 队列面板 | 需要 Redis | 已移除 |
| Composer 依赖 | 需自行 `composer install` | `vendor/` 已内置（仅生产依赖） |
| 安装向导 | 需填 Redis 信息、检查 Redis 版本 | 只需 MySQL + 时区 |
| 队列 | Redis + Horizon Worker | `sync` 同步执行，无需 Supervisor |
| 种子功能 | 完整 PT | 已移除入口 |

---

## 六、常见问题

**1. 打开首页 500 / 空白**

- 检查 `storage/`、`bootstrap/cache/` 是否可写；
- 查看 `storage/logs/laravel.log` 与 `.env` 中的 `LOG_FILE`（默认 `/tmp/nexus.log`）；
- 确认 PHP 版本、扩展是否满足要求；
- 确认站点根目录指向 `public/`。

**2. 安装时提示 “can not create env file”**

项目根目录没有写权限。执行：

```bash
chown -R www:www /www/wwwroot/nexusphp-bbs
chmod -R 775 storage bootstrap/cache attachments bitbucket
```

**3. 管理后台样式丢失 / 404**

伪静态没有配置好。确认 `location /` 的 `try_files` 指向 `/nexus.php?$query_string`，并且站点根目录是 `public/`。

**4. 安装后想重新安装**

删除项目根目录的 `dont_delete_install.lock` 和 `.env`，再访问 `/install/install.php`。  
（请先备份数据库，重新安装会重建表。）

**5. 为什么还有 `vendor`，能不能删？**

不能。Laravel、Filament、Livewire 等框架代码都在 `vendor/` 里。本版本已经把开发依赖删掉，并保证**上传即可运行，不需要 Composer**。

**6. 登录按钮点击没反应 / 登录后没跳转**

0.1.2 已修复该问题。若仍遇到：

- 确认 `public/vendor/` 目录存在，且浏览器能访问 `/vendor/jquery-loading/jquery.loading.min.js`；
- 清除浏览器缓存后重试；
- 打开浏览器 F12 Console，看是否有 JS 报错；
- 0.1.2 已支持“挑战接口失败自动回退密码登录”，即使 `/api` 伪静态没配好也能登录。

**7. 依赖安全更新**

`composer.lock` 保留了上游 1.10.2 的依赖版本。如需升级依赖，请在本地执行 `composer update` 并充分测试后再上传 `vendor/`，不要直接在服务器上盲目更新。

---

## 七、安全建议

- 安装完成后删除 `public/install/`；
- 全站启用 HTTPS，并在后台把 `安全设置 → 强制 HTTPS` 打开；
- 定期备份数据库与 `attachments/`、`bitbucket/`；
- 不要把项目根目录直接暴露为站点根目录（必须指向 `public/`）；
- 管理员账号请使用强密码，并开启二次验证（后台支持）。

---

## 八、版权

本项目基于 [NexusPHP](https://nexusphp.org/) 1.10.2（GPL-2.0）修改，遵循原项目许可证。  
原项目地址：https://github.com/xiaomlove/nexusphp
