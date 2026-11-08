<?php
require_once("../include/bittorrent.php");
dbconn();
loggedinorreturn();
parked();

if (get_user_class() < UC_MODERATOR) {
    permissiondenied();
}

require_once(ROOT_PATH . 'app/Console/Admin/AdminMenu.php');

$lang = get_langfolder_cookie();
$isChinese = str_starts_with($lang, 'ch');
$langKey = str_starts_with($lang, 'cht') ? 'cht' : ($isChinese ? 'chs' : 'en');
$L = $isChinese ? [
    'title' => '管理系统',
    'intro' => '管理站点用户、日志、勋章、插件与授权；没有下拉多级菜单，一页直达。',
    'col_name' => '选项名称',
    'col_info' => '说明',
    'back' => '返回管理组面板',
    'legacy_panel' => '传统管理功能（管理组面板）',
    'filament_only' => '（仅新版界面可用）',
    'filament_hint' => '新版后台界面（需要 vendor 依赖）',
] : [
    'title' => 'Management System',
    'intro' => 'Manage users, logs, medals, plugins and OAuth. One page, no nested menus.',
    'col_name' => 'Option Name',
    'col_info' => 'Info',
    'back' => 'Back to staff panel',
    'legacy_panel' => 'Legacy admin functions',
    'filament_only' => '(new UI only)',
    'filament_hint' => 'New admin UI (requires vendor)',
];

$items = \App\Console\Admin\AdminMenu::items();
$groups = \App\Console\Admin\AdminMenu::GROUPS;
$filamentAvailable = is_dir(ROOT_PATH . 'vendor/filament');
$filamentBase = rtrim(nexus_env('FILAMENT_PATH', 'nexusphp'), '/');

function admincp_item_url(array $item, bool $filamentAvailable, string $filamentBase): ?string
{
    if (!empty($item['handler'])) {
        return 'admincp/' . $item['handler'];
    }
    if ($filamentAvailable && !empty($item['route'])) {
        return $filamentBase . '/' . $item['route'];
    }
    return null;
}

stdhead($L['title']);
?>
<h1 align="center"><?php echo htmlspecialchars($L['title']) ?></h1>
<p align="center"><?php echo htmlspecialchars($L['intro']) ?></p>
<p align="center">
    <a href="staffpanel.php"><?php echo htmlspecialchars($L['back']) ?></a>
</p>
<?php
foreach ($groups as $groupKey => $groupNames) {
    $groupItems = array_values(array_filter($items, fn($item) => $item['group'] === $groupKey));
    if (empty($groupItems)) {
        continue;
    }
    print("<h2 align=\"left\">" . htmlspecialchars(\App\Console\Admin\AdminMenu::text($groupNames, $langKey)) . "</h2>");
    print("<table width=\"80%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">");
    print("<tr><td class=\"colhead\" align=\"left\">" . htmlspecialchars($L['col_name']) . "</td><td class=\"colhead\" align=\"left\">" . htmlspecialchars($L['col_info']) . "</td></tr>");
    foreach ($groupItems as $item) {
        $name = \App\Console\Admin\AdminMenu::text($item['name'], $langKey);
        $info = \App\Console\Admin\AdminMenu::text($item['info'], $langKey);
        $url = admincp_item_url($item, $filamentAvailable, $filamentBase);
        if ($url === null) {
            $info .= ' <span class="small">' . htmlspecialchars($L['filament_only']) . '</span>';
            $cell = "<strong>" . htmlspecialchars($name) . "</strong>";
        } else {
            $cell = "<strong><a href=\"" . htmlspecialchars($url) . "\">" . htmlspecialchars($name) . "</a></strong>";
        }
        print("<tr><td class=\"rowfollow\" align=\"left\">" . $cell . "</td> <td class=\"rowfollow\" align=\"left\">" . htmlspecialchars($info) . "</td></tr>");
    }
    print("</table>");
    print("<br />");
}

$filamentNote = $filamentAvailable
    ? "<a href=\"" . htmlspecialchars($filamentBase) . "\">" . htmlspecialchars($L['filament_hint']) . "</a>"
    : htmlspecialchars($L['filament_hint']) . "（未启用）";
?>
<p align="center"><?php echo $filamentNote ?></p>

<h2 align="left"><?php echo htmlspecialchars($L['legacy_panel']) ?></h2>
<table width="80%" border="1" cellspacing="0" cellpadding="5" align="center">
    <tr>
        <td class="colhead" align="left"><?php echo htmlspecialchars($L['col_name']) ?></td>
        <td class="colhead" align="left"><?php echo htmlspecialchars($L['col_info']) ?></td>
    </tr>
    <?php foreach ([['sysoppanel', UC_SYSOP], ['adminpanel', UC_ADMINISTRATOR], ['modpanel', UC_MODERATOR]] as [$table, $minClass]): ?>
        <?php
        if (get_user_class() < $minClass) {
            continue;
        }
        $res = sql_query("SELECT * FROM " . $table . " ORDER BY id");
        while ($row = mysql_fetch_assoc($res)) {
            $name = $lang_staffpanel[$row['name']] ?? $row['name'];
            $info = $lang_staffpanel[$row['info']] ?? $row['info'];
            ?>
            <tr>
                <td class="rowfollow" align="left"><strong><a href="<?php echo htmlspecialchars($row['url']) ?>"><?php echo htmlspecialchars($name) ?></a></strong></td>
                <td class="rowfollow" align="left"><?php echo htmlspecialchars($info) ?></td>
            </tr>
            <?php
        }
        ?>
    <?php endforeach; ?>
    <tr>
        <td class="rowfollow" align="left"><strong><a href="settings.php"><?php echo htmlspecialchars($isChinese ? '站点设定' : 'Site Settings') ?></a></strong></td>
        <td class="rowfollow" align="left"><?php echo htmlspecialchars($isChinese ? '站点在线状态、注册、界面、账号与权限等设置' : 'Site online, registration, styles, accounts and permissions') ?></td>
    </tr>
</table>
<?php
stdfoot();
