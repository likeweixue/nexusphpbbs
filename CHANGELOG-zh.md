# 改造记录（相对 NexusPHP 1.10.2 论坛版）

## 版本历史

### 0.2.7（2026-11-09）— 修复签到/语言切换 500、消除 Notice 刷屏

- **修复签到 500**：`AttendanceRepository` 里对 `getAttendance()` 返回的 stdClass 调用 `->toJson()` 导致 `Call to undefined method stdClass::toJson()`。已改为安全序列化（Carbon 字段转字符串）。
- **修复语言切换 `?sitelanguage=` 500**：`login.php`、`signup.php` 里的 `in_array($lang_folder, $enabled)` 在语言列表为 NULL 时崩溃，统一加 `(array)` 强转。
- **消除 Notice/Warning 刷屏**：
  - `config.php` 里 `$AUTHORITY['torrentmanage']` 等 30+ 个键（论坛版精简后缺失）加 `?? ''` 兜底；
  - `stdfoot()` 里 `$cssupdatedate` 未定义（我之前改页脚脚本引入的 bug），改为从 `$GLOBALS` 读取。

> 说明：之前连续几轮"切换主题/清缓存/签到就 500"，根因都是 `settings` 表里 `main.defaultlang`、`main.site_language_enabled` 为 NULL 触发的语言解析崩溃，0.2.6 已修；本版把签到和语言切换入口这两个同样受影响的调用点补齐。

### 0.2.6（2026-11-09）— 修复切换/清缓存后 500、删除种子目录

- **修复切换主题或清缓存后全站 500**：`settings` 表中 `main.defaultlang`、`main.site_language_enabled` 若为 NULL（如半途安装、手动改动），清除缓存后语言解析会触发 `in_array(..., null)` 与 `NexusTranslator(null)` 致命错误。已对语言读取、语言列表、翻译器默认语言全部加空值兜底，无论数据库里是正常值还是 NULL 都能正常工作。
- **删除 `torrents/` 目录**：论坛版已移除种子上传/下载，该目录不再使用，从安装包与代码库中移除。
- 主题样式读取加固：主题 id 不存在于样式表时回退到默认样式，`get_style_addicode()` 空值返回空串。

### 0.2.5（2026-11-09）— 修复页脚脚本 500、管理组面板中文、论坛已读/未读图标

- **修复页脚脚本被当成 URL 请求**：`stdhead` 页脚有一段 heredoc 里写了 `<?php echo nexus_asset(...)?>`，heredoc 不会执行 PHP，浏览器于是把 `<?php echo ...?>` 当成地址去请求并报错。已改为字符串拼接；同时全量扫描确认没有其它 heredoc/字符串里残留 PHP 标签。
- **管理组面板（staffpanel.php）全部中文**：语言文件 `lang/*/lang_staffpanel.php` 使用的是变量赋值，而页面按数组读取，导致中文站点显示英文菜单。已改为返回数组 + 缺失条目回退简体中文。
- **论坛已读/未读图标换成 gif**：新增 `pic/forum_pic/common/forum_new.gif`（未读，黄色高亮）与 `forum_old.gif`（已读，暗色），列表页与主题页统一使用。
- 移除指向不存在页面的 `opensearch.php` 引用。

### 0.2.4（2026-11-09）— 修复：安装到第 4 步 500、跳过创建管理员

- **修复第 4 步（设置/初始数据）500**：
  - 安装包缺少 `config/allconfig.php`，第 4 步直接抛异常 → 已随包提供；
  - `Setting::query()` 在纯 PHP 版中不存在 → 改为 `get_row_count()`；
  - `runDatabaseSeeder()` 原先只做校验，第 3 步建表后数据为空 → 现在会自动导入 `database/install/data.sql`（已有数据则跳过）；
  - `initTrackerUrl()` 使用 `TrackerUrl::query()->create()`，补上 `QueryBuilder::create()`；`TrackerUrl::saveUrlCache()` 去掉 redis 依赖。
- **修复"安装跳过创建管理员"**：第 4 步崩溃后再次访问安装页会直接跳到"安装完成"并写锁，站点于是没有管理员、且带演示数据。现在：若数据库已有管理员则拒绝继续安装；否则必须按步骤走到第 5 步创建管理员。
- 安装步骤仍为 5 步：环境检测 → 写 .env → 建表 → 初始数据/设置 → 创建管理员。

### 0.2.3（2026-11-09）— 修复：全新安装时管理员建号失败

- 全新安装到第 5 步（创建管理员）时，如果站点设置尚未写入默认样式，`stylesheet` 字段为空会触发 SQL 报错 → 改为缺省样式 `3` 兜底。
- 说明：0.2.2 已修复安装向导第 2 步 500 与后台列表页样式；本版继续补齐安装最后一步。全新空库安装流程（步骤 2→5→登录）已完整实测通过。

### 0.2.2（2026-11-09）— 修复：安装向导 500、管理列表页样式丢失

- **修复安装向导第 2 步起 500**：
  - 安装包缺少 `.env.example`，步骤 2 读取模板时直接报错 → 已随包提供；
  - 安装早期 `$Cache` 尚未初始化导致 `get_setting()` 崩溃 → 数据库缓存访问全部加了兜底；
  - `DBPdo` 支持 `host:port` 与 unix socket 写法，`DB_PORT` 缺失时回退 3306；
  - 步骤 5 创建管理员时 `now()->toDateTimeString()`、`User::save()`、`User::toJson()` 缺失 → 已补齐纯 PHP 实现；
  - 安装失败时页面会直接显示错误原因（原先只是白屏/500）。
- **修复 `admincp/*` 列表页样式丢失**：后台列表页位于 `admincp/` 子目录，而 `stdhead()` 输出的 `styles/`、`pic/`、`js/` 都是相对路径，导致在子目录下 404。新增 `nexus_site_root()` / `nexus_asset()`，头部样式、脚本、图标按当前页面层级自动加上 `../` 前缀。
- 顺带修复：顶部信箱/报告/好友/RSS 图标、种子列表排序图标在子目录页面不显示的问题。
- `.env.example` 加入版本库（安装向导依赖它生成 `.env`）。

### 0.2.1（2026-11-09）— 修复：个人设置空白、全站语言回退

- **修复「个人设置 → 追踪器设置」页面空白**：搜索框分类表格依赖的 `categories`/`caticons` 关联在纯 PHP 版本中失效，已改为直接查询分类表、图标表与各分类表，并补上 `getTaxonomyLabel()`；语言下拉（English / 简体中文 / 繁體中文）恢复显示。
- **修复全站语言久为英文**：
  - 没有语言 cookie 时，优先使用用户资料中保存的语言（原先一律回退到站点默认语言）；
  - 登录时不再用站点默认语言覆盖用户资料中的语言，并自动补写语言 cookie；
  - 用户资料页保存语言后立即生效。
- **修复分类图标读取**：`caticons` 表没有 `image` 列，改为按 `folder` + 分类 `image` 拼接图标路径。
- 清理误打包进 `public/` 的 `app/`、`include/` 副本。

### 0.2.0（2026-11-09）— 零依赖版：不再需要 vendor / Composer / Laravel

- **彻底移除 `vendor/` 依赖**：程序、安装、后台全部改为纯 PHP + MySQL，服务器上只要 PHP 8.1+ 和 MySQL 5.7+，**不需要 Composer、不需要 vendor、不需要 Node.js**。
- **自写基础设施**（`nexus/`）：
  - `autoload.php`：轻量 PSR-4 自动加载器，替代 Composer 的 `vendor/autoload.php`；
  - `Database/QueryBuilder.php` + `QueryCollection.php` + `RawExpression.php`：纯 PHP 查询构建器，替代 Eloquent / Query Builder；
  - `Database/Arr.php`、`Support/Str.php`、`Support/HtmlString.php`、`Support/CarbonCompat.php`、`Support/helpers.php`：替代 Illuminate 的数组/字符串/日期/`now()`/`config()` 等工具；
  - `Translation/NexusTranslator.php`：纯 PHP 翻译器，读取 `resources/lang`，支持 `file.key`、`namespace::file.key` 与占位符替换。
- **模型层**：89 个模型全部改为纯 PHP（`ModelTable` 基类 + `ModelCompat`），去掉 Eloquent 关系、作用域、属性转换；需要关联数据的地方改为显式 SQL。
- **安装器**：不再调用 `artisan migrate / db:seed`，改为执行内置 SQL 脚本 `database/install/schema.sql`（126 张表）与 `data.sql`（初始数据）；安装后建管理员、写设置由页面直接完成。
- **删除的目录/文件**：`vendor/`、`artisan`、`bootstrap/`、`routes/`、`database/migrations`、`database/seeders`、`resources/`、`app/{Filament,Http,Livewire,Providers,Auth,Utils,Policies,Events,Listeners,Jobs,Logging,Console}`、`nexus/Cache/FileCacheStore.php`、`include/eloquent.php`、前端构建文件（`package.json`/`webpack.mix.js`/`tailwind.config.js`）、`composer.json`/`composer.lock`。
- **包体**：从 30MB 降到约 3MB（不再包含框架与前端构建产物）。
- **在线升级器停用**：`public/install/update` 现在只提示“覆盖文件 + 执行 upgrade.sql + 清缓存”。

#### 不再提供的功能（请提前评估）

| 功能 | 说明 |
| --- | --- |
| Filament 管理后台（`/nexusphp`） | 已移除；管理功能改由传统后台（`controlcenter.php` + `admincp/*` + `staffpanel.php`）承担 |
| REST API / OAuth（Sanctum、Passport） | 依赖 Laravel，已移除 |
| 插件商店与插件加载 | 依赖 Laravel 与迁移，已移除 |
| 在线升级 | 改为手动覆盖文件 + SQL |

#### 分支说明

- 0.2.x 发布在 `zero-dependency` 分支；`main` 保持 0.1.7 作为带后台/API 的存档版本。
- 后续常规功能改动只在 `zero-dependency` 分支维护。

#### 升级说明

- 从 0.1.x 升级：**先备份数据库与文件**，然后覆盖程序文件；0.1.x 的数据库结构可直接使用，无需迁移。
- 全新安装：上传后访问 `install/`，安装向导会执行 `database/install/schema.sql` 与 `data.sql`。

### 0.1.7（2026-11-09）— 管理系统改为传统风格 + 只读管理列表

- **入口**：顶部“管理系统”由 Filament 后台（`/nexusphp`）改为传统页面 `controlcenter.php`，与“管理组面板”同一套 `colhead` / `rowfollow` 表格样式，按“用户相关 / 系统相关 / OAuth 授权”分组，一页直达，没有多级菜单。
- **新增 23 个只读管理列表**（`public/admincp/`，均支持关键字搜索与分页）：
  - 用户：用户列表、用户道具、用户勋章、用户邀请、用户考核、签到记录、魔力记录、登录记录、修改记录、改名记录、访问令牌、通行密钥
  - 系统：勋章、考核 & 任务、消息模板、操作日志、IP 记录、插件
  - OAuth：客户端、身份验证、访问令牌、刷新令牌、授权码
- **菜单定义**：`app/Console/Admin/AdminMenu.php` 为纯 PHP 数组，不依赖 Filament；控制中心页还会列出原有传统管理功能（原 staffpanel 各分组 + 站点设定）。
- **可回退**：`ADMIN_PANEL=legacy`（默认）使用传统后台；设为 `filament` 时恢复原来的 `/nexusphp` 入口。
- **公共函数**：`get_forum_row()` 从 `forums.php` 移到 `include/functions.php` 并加 `function_exists` 保护，后台列表可在不加载论坛页的情况下复用。

#### 关于 vendor 依赖（重要）

本次只替换了后台入口，**`vendor/` 仍然必需**：论坛版从 0.1.0 起就是“传统页面运行在 Laravel 之上”的架构，`include/core.php` 启动即加载 `vendor/autoload.php`，`public` 与 `include` 中共有 400+ 处 Eloquent / Repository 调用（消息、签到、魔力、勋章、任务、附件、通知、迁移等）。实测移除 `vendor/` 后论坛、登录、首页等全部 500。

要真正做到“无 vendor”，需要单独的“去 Laravel 化”改造：把上述调用改为原生 SQL、用 SQL 脚本替代 225 个迁移、去掉 Capsule 数据库层，分模块回归后再从发布包中移除 `vendor/`。

#### 升级说明

- 本版本没有新的数据库迁移，直接覆盖文件即可。
- 建议执行一次 `php artisan cache:clear`，确认后台链接指向 `controlcenter.php`。

### 0.1.6（2026-11-09）— 帖子收藏（星标）/ 收藏列表

- **帖子星标**：每个帖子的“引用”按钮左侧新增五角星按钮，空心表示未收藏、金色实心表示已收藏；点击即时切换（Ajax），无需刷新，悬停提示“收藏该贴 / 取消收藏”。
  - 图标绘制在论坛自带的按钮图集 `pic/forum_pic/<lang>/forum.gif` 中，四种语言各一套。
  - 游客不可见；未登录用户不会看到星标。
- **收藏列表**：顶部“收藏”入口由已删除的 `torrents.php?inclbookmarked=1` 改为新的 `collections.php`（我的帖子收藏），列出收藏的帖子摘要（点击跳到对应楼层）、主题、版块、作者、收藏时间，支持取消收藏与分页。
- **数据**：新增表 `post_bookmarks`（`userid` / `topicid` / `postid` / 时间戳，`userid + postid` 唯一），迁移 `2026_11_09_000001_create_post_bookmarks_table`。

#### 升级说明

新增 1 个迁移，覆盖文件后执行：

```bash
php artisan migrate --force
php artisan cache:clear
```

> 不执行迁移会看不到星标：`post_bookmarks` 表不存在时收藏操作会失败。

### 0.1.5（2026-11-08）— 清理残留种子功能 / 魔力值说明可编辑 / 主题全宽

- **访客**：未登录时不再显示顶部“论坛”菜单项（原样只有一个孤立菜单，显得突兀），保留“登录 / 注册”。
- **主题宽度**：Blue Gene、Blasphemy Orange、Classic、Dark Passion、Bamboo Green 五个默认主题改为和“复古黄色”一样的全宽（100%）布局。
- **魔力值页面**：
  - 删除“1.0 / 5.0 / 10.0 / 100.0 GB 上传量”“10.0 / 100.0 GB 下载量”六个兑换项，删除对应的兑换处理逻辑。
  - 删除“做种每小时得到多少魔力值”的公式、公式图、当前每小时收益、奖励明细表，以及“其他获取方式”里种子/字幕/候选相关条目。
  - “魔力值到底是什么…”说明改为**可编辑面板**：工作人员在页面底部看到“编辑”按钮，进入后是文本框，底部居中提供 **提交 / 预览 / 取消** 三个按钮，支持 BBCode；内容保存在设置 `main.bonus_karma_note`。
  - 慈善捐赠去掉“分享率低于 X 且下载量大于 10GB”的筛选，改为平均分配给所有启用中的用户。
- **站点设置**：
  - 权限设置：删除“管理种子 / 删除种子 / 设定种子置顶 / 设定种子为促销 / 设定种子 H&R / 设定种子特殊标签 / 审核种子 / 种子自动通过审核 / 设置种子收费 / 请求续种 / 查看NFO / 查看种子结构 / 查看用户种子历史 / 查看被禁止的种子 / 对候选投反对票 / 添加候选 / 管理候选 / 发布种子 / 发布种子至特别区 / 查看特别区种子 / 移动种子 / 上传字幕 / 删除自己的字幕 / 管理字幕 / 评论管理 / 更新外部信息”等行。
  - 安全设置：删除“作弊者检测级别”（含“永远不怀疑”等级）。
  - 主设置：自动清理只保留优先级 4、5（验证/账号清理、古老主题与举报清理），删除与种子、做种魔力值绑定的优先级 1–3。
  - 魔力值设置：删除做种公式、基础做种魔力、起步体积、捐赠者倍数、官种/零魔标签、上传量/下载量兑换价格、分享率限制等做种相关项，只保留论坛行为与兑换项。
- **后台/清理任务**：`include/cleanup.php` 移除同伴清理、种子可见性、种子计数/促销/置顶/热门挑选、做种魔力值计算等与种子相关的定时逻辑，保留论坛帖子/主题计数等。
- **面板**：删除管理员面板中的“分类管理”“自定义字段管理”两行。

#### 升级说明

新增 2 个迁移，覆盖文件后执行：

```bash
php artisan migrate --force
php artisan cache:clear
```

| 迁移 | 作用 |
| --- | --- |
| `2026_11_08_000001_remove_torrent_related_panel_rows` | 删除“分类管理 / 自定义字段管理”两个面板入口 |
| `2026_11_08_000002_remove_torrent_related_settings` | 删除种子相关权限与魔力值设置、写入默认魔力值说明 |

### 0.1.4（2026-10-23）— 自定义菜单 / 访客浏览 / 论坛 RSS / 复古黄主题

- **自定义菜单**：`staffpanel.php` 系统面板“增加魔力/补签卡/邀请/上传量”下方新增“菜单栏设置”入口，打开 `custommenu.php` 可增加、删除、编辑、启用/停用、上移/下移菜单，支持二级子菜单、外链、新窗口打开。
  - 菜单保存在新表 `custom_menus`；没有自定义菜单时仍显示原来的默认菜单，添加第一条后替换默认菜单。
  - 新表由迁移 `2026_10_23_000001_create_custom_menus_table.php` 创建。
- **访客访问权限**：后台站点设置 → 主设置 → “站点在线”下方新增“站点访问权限”。
  - 默认“否”；开启后未登录访客只能浏览 `forums.php`（含版块/主题），顶部菜单只显示“论坛”，访问首页会跳转到论坛，其他页面仍需登录。
  - 设置键：`main.guest_forum_access`。
- **清理 PT 面板项**：删除模组面板中的“异常上传速度探测 / 全部客户端 / 上传者”三行，并删除 `public/uploaders.php`。
- **论坛 RSS**：新增 `getrss.php`，可按“分区新主题 / 板块新主题 / 主题新回复”生成 RSS 订阅地址，支持 RSS 2.0 输出和页面预览；原顶部 RSS 图标现在可以正常打开。
- **复古黄色主题**：新增 `public/styles/RetroYellow/`（`theme.css` 及配套图片），并在 `stylesheets` 表插入“复古黄色”记录；后台“默认界面风格”和用户控制面板中可选择。
- **迁移**：新增 3 个迁移（自定义菜单表、清理 PT 面板行、插入主题记录）。升级后请执行 `php artisan migrate --force`。

### 0.1.3（2026-10-22）— 清理首页与后台的 PT 种子功能

- **首页**：删除“种子 / 断种 / 做种数 / 下载数 / 同伴 / 做种下载比例 / 当前 Tracker 用户数 / 种子总大小 / 总上传量 / 总下载量 / 总数据量”等 PT 统计行；保留用户统计和等级统计。
- **首页**：新增论坛统计（与 `forums.php` 相同）：帖子总数、主题总数、今日帖子、论坛在线用户。
- **后台仪表盘**：删除“最新种子”“种子趋势”“种子统计”三个组件，只保留用户相关组件和系统信息。
- **后台侧边栏**：删除用户 H&R、用户认领、种子购买、Torrent 组（种子/标签/拒绝原因/种子操作记录）、Section 组（分类模式/主分类/Codec/Audio Codec/Standard/Team/Source/Media/Processing/分类图标/第二图标/自定义字段）、允许/拒绝客户端、上行/下行带宽、ISP、SeedBox、全站优惠、Tracker URL、Plugin Store。
- **后台设置页**：移除 H&R、SeedBox、Meilisearch 三个设置选项卡。
- 说明：如果后台仍看到 “H&R”“SeedBox” 标签页，那是已安装的 PT 插件提供的，请在 `plugins/` 中停用或删除对应插件。

### 0.1.2（2026-10-22）— 修复登录按钮

- **根因**：0.1.1 打包时误排除了 `public/vendor/`（jquery.loading 等前端资源）和 `resources/views/vendor/`，登录页的挑战脚本在 `jQuery('body').loading()` 处报错，按钮点击后没有反应。
- 恢复 `public/vendor/` 与 `resources/views/vendor/` 全部资源。
- 登录按钮由 `type="button"` 改为 `type="submit"`，密码框始终带 `name="password"`，支持直接点按钮和回车提交。
- 新增 `public/challenge.php`：登录挑战不再依赖 Laravel 的 `/api/challenge` 路由，普通 PHP 环境即可使用。
- `takelogin.php` 同时支持“挑战-响应”和“密码”两种登录方式；挑战接口或前端脚本异常时，页面会自动回退为密码提交。
- 前端脚本增加加载插件缺失、layer 缺失、请求失败等情况的兜底与提示。

### 0.1.1（2026-10-22）— 论坛化 / 去 Redis / 免 Composer

- 移除种子上传、下载、Tracker 等功能入口，保留论坛、用户、消息、勋章、签到、考核、后台等模块。
- 文件缓存替代 Redis，移除 Horizon，队列改为 sync，安装仅需 PHP + MySQL。
- `vendor/` 内置生产依赖，服务器无需执行 `composer install`。
- 安装向导简化，自动生成 APP_KEY/APP_URL。

### 0.1.0（2026-10-22）— 初始论坛版本

- 用户提供的原始论坛版本（基于 NexusPHP 1.10.2）。

---

## 改造目标

改动目标：**去掉 Redis 依赖，服务器只需要 PHP + MySQL；同时保证不需要在服务器上安装 Composer**。论坛页面与后台布局保持原样。

## 一、总体方案

| 原实现 | 新实现 |
| --- | --- |
| Redis 作为缓存（页面缓存、设置缓存、用户缓存、限流、锁、计数器、Hash/List/Set） | 新增 `Nexus\Cache\FileStore`，用文件实现 phpredis 常用 API，数据目录 `storage/framework/cache/nexus/` |
| 传统页面 `class_cache_redis` | 改为 `classes/class_cache_file.php`，对外方法完全一致，内部改用 `FileStore` |
| Laravel Cache 使用 redis 驱动 | 新增 `nexus` 缓存驱动（`Nexus\Cache\FileCacheStore`），与旧页面共用同一个 `FileStore`，键名和值格式保持一致 |
| `NexusLock` 继承 RedisLock | 改为文件缓存 TTL 锁 + `flock()`，保留“短时间不能重复提交”的限流语义 |
| 队列 Redis + Horizon + Supervisor | `QUEUE_CONNECTION=sync`，任务在进程内执行；移除 Horizon 与 `horizon:snapshot` |
| Session Redis | Session 使用 Laravel 原生文件驱动 |
| 安装检查 Redis 扩展、版本、连接 | 全部移除，只检查 MySQL 和 PHP 扩展 |
| `vendor` 需要 `composer install` | 已内置生产依赖（`composer install --no-dev`），并移除开发依赖 |

## 二、主要文件

### 新增

- `nexus/Cache/FileStore.php` — 文件缓存核心，提供 get/set/setex/del/unlink/exists/expire/incr、hash、list、set、scan/hScan、publish、multi 等 phpredis 兼容方法。
- `nexus/Cache/FileCacheStore.php` — Laravel Cache Store 适配器（含原子 add、cache lock、touch）。
- `classes/class_cache_file.php` — 替代 `class_cache_redis.php`，API 不变。
- `app/Console/Commands/CacheGc.php` — `php artisan cache:gc`，清理过期缓存文件。
- `public/install/tailwind.min.css` — 安装向导本地样式，避免依赖外网 CDN。
- `INSTALL-zh.md`、`CHANGELOG-zh.md` — 部署与改造文档。

### 修改

- `include/core.php`、`nexus/Install/install_update_start.php` — 改用 `class_cache_file`。
- `nexus/Database/NexusDB.php` — `NexusDB::redis()` 返回 `FileStore`（保持旧调用点不变）。
- `nexus/Database/NexusLock.php` — 文件锁实现。
- `nexus/Nexus.php` — 移除 Redis QueueManager，任务改为容器队列/sync 或进程内执行；修复未设置 `REQUEST_SCHEME` 时的 TypeError。
- `app/Providers/AppServiceProvider.php` — 注册 `nexus` 缓存驱动。
- `config/cache.php`、`config/session.php`、`config/queue.php` — 默认值改为文件缓存/file/sync，并对旧 `.env` 中的 `redis` 自动回退。
- `config/app.php` — 移除 Horizon ServiceProvider。
- `app/Http/Kernel.php` — `throttle` 改为普通 `ThrottleRequests`（基于缓存）。
- `app/Repositories/{IpLog,Tracker,Torrent,Cleanup,RequireSeedTorrent,Message,Dashboard}Repository.php`、`app/Models/TrackerUrl.php` — Redis 类型提示/常量改为 `FileStore`。
- `app/Jobs/*`、`app/Utils/*` — 随 `NexusDB::redis()` 自动切换，无需业务改动。
- `nexus/Install/Install.php`、`public/install/install.php`、`nexus/Install/update/update.php`、`nexus/Install/install/install.php`、`app/Console/Commands/NexusUpdate.php` — 去掉 Redis 检查/字段/版本提示；自动生成 APP_KEY、APP_URL，强制 `CACHE_STORE=nexus`、`SESSION_DRIVER=file`、`QUEUE_CONNECTION=sync`；自动探测 PHP CLI 路径。
- `app/Console/Kernel.php` — 增加每日缓存 GC；移除 Meilisearch、Tracker pieces hash 等纯种子相关定时任务。
- `routes/console.php` — 移除 `horizon:snapshot`。
- `include/functions.php` — 调试栏 Redis 字样改为 cache。
- `resources/lang/*/dashboard.php` — 后台系统信息“Redis 版本”改为“缓存后端”。
- `.env.example` — 移除 REDIS_*，加入 `CACHE_STORE=nexus` 等。
- `composer.json` / `composer.lock` / `vendor/` — 移除 `laravel/horizon`、`ext-redis`、`ext-pcntl`、`ext-posix`、`ext-zend-opcache`；只保留生产依赖。

### 删除

- `classes/class_cache_redis.php`、`classes/class_cache.php`
- `app/Providers/NexusHorizonServiceProvider.php`、`config/horizon.php`
- `tests/`、`phpunit.xml`、`docker-compose.yml` 等开发/容器文件

## 三、缓存实现说明

- 每个 key 一个文件：`storage/framework/cache/nexus/<md5前2位>/<md5>.cache`。
- 写入使用临时文件 + `rename()` 原子替换；Hash/List/Set 的读改写使用 per-key `flock()`。
- 过期时间写在文件头，读取时惰性删除；写入时约 1/200 概率触发小规模 GC，另有每日 `cache:gc` 全量清理。
- 缓存目录不可写时自动回退到系统临时目录；仍不可用则缓存失效但不影响页面打开。
- Laravel 与传统页面共用同一存储，登录挑战（Laravel 写入、`takelogin.php` 读取）、后台改设置后前台生效等跨端场景已验证。

## 四、锁说明

- 带 TTL 的锁（发帖间隔、邀请、举报、确认邮件重发等）：写入 `nexus_lock:<name>`，到期自动失效，保持原来的限流效果。
- 无 TTL 的锁：使用 `flock()`，进程结束自动释放。

## 五、验证情况

已在本地实际安装验证（PHP 8.4.23 + MySQL 8.4.6，无 Redis 服务、无 phpredis 连接）：

- 安装向导 step 1–6 全流程通过，建表 124 张、设置 396 条、管理员创建成功；
- 首页、论坛版块/主题、用户资料、控制面板、消息、管理面板、FAQ、规则、搜索、喊话框等页面 200 且无 PHP 错误；
- 后台 Filament 登录/仪表盘正常，静态资源正常加载；
- 挑战-响应登录（Laravel API + 传统 PHP 页面共享缓存）成功；
- `php artisan schedule:run`、`cache:clear`、`cache:gc`、`public/cron.php`、强制全量清理 `docleanup.php` 均正常；
- IP 日志缓存落库、按 pattern 删除缓存、缓存锁/限流均通过测试。

## 六、注意事项

- 本方案的文件缓存是**单机缓存**，适合单台 Web 服务器。多机负载均衡需要共享 `storage/` 或另行改回 Redis。
- `vendor/` 是 Laravel 框架本体，不能删除；本包已去掉开发依赖，**上传即可运行，无需 Composer**。
- 依赖版本保留上游 1.10.2 的锁定版本；如需安全升级，请在本地 `composer update` 并完整测试后再上传。
