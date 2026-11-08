<?php
//  后台列表页公共代码：权限校验、分页、搜索、表格渲染
require_once(dirname(__DIR__, 2) . '/include/bittorrent.php');
dbconn();
loggedinorreturn();
parked();

if (get_user_class() < UC_MODERATOR) {
    permissiondenied();
}

// 当前页面对应的传统页面文件；搜索、分页等链接都以站点根目录为基准重新拼接
$GLOBALS['admincpPageFile'] = basename($_SERVER['SCRIPT_FILENAME'] ?? '');

/**
 * 当前语言简称：chs / cht / en
 */
function admincp_lang(): string
{
    $lang = get_langfolder_cookie();
    if (str_starts_with($lang, 'cht')) {
        return 'cht';
    }
    return str_starts_with($lang, 'ch') ? 'chs' : 'en';
}

/**
 * 取语言文案，缺失时回退到中文
 */
function admincp_t(array $map): string
{
    $lang = admincp_lang();
    return $map[$lang] ?? $map['chs'] ?? ($map['en'] ?? '');
}

/**
 * 表格列定义：$columns = [ ['key' => 'id', 'label' => ['chs' => 'ID', 'en' => 'ID'], 'format' => callable|null], ... ]
 */
function admincp_render_list(array $options): void
{
    $title = admincp_t($options['title']);
    $columns = $options['columns'];
    $table = $options['table'];
    $select = $options['select'] ?? '*';
    $where = $options['where'] ?? '';
    $order = $options['order'] ?? '1 DESC';
    $searchable = $options['searchable'] ?? [];
    $join = $options['join'] ?? '';
    $perpage = intval($options['perpage'] ?? 30);

    $keyword = trim((string)($_GET['keyword'] ?? ''));
    $page = max(1, intval($_GET['page'] ?? 1));

    if ($keyword !== '' && !empty($searchable)) {
        $escape = function (string $value) {
            return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
        };
        $parts = [];
        $kw = $escape($keyword);
        foreach ($searchable as $column) {
            $parts[] = "`$column` LIKE " . sqlesc('%' . $kw . '%');
        }
        $keywordWhere = '(' . implode(' OR ', $parts) . ')';
        $where = $where === '' ? "WHERE $keywordWhere" : "($where) AND $keywordWhere";
    }

    $countResult = \Nexus\Database\NexusDB::select(sprintf("SELECT COUNT(*) AS total FROM `%s` %s %s", $table, $join, $where));
    $total = intval($countResult[0]['total'] ?? 0);
    $offset = ($page - 1) * $perpage;

    $pagerBase = admincp_page_url() . '?keyword=' . rawurlencode($keyword) . '&page=';
    $prefix = $pagerBase;
    list($pagertop, $pagerbottom) = ['', ''];
    if ($total > $perpage) {
        $pagertop = nexus_pager_bar($total, $perpage, $page, $prefix);
        $pagerbottom = $pagertop;
    }

    stdhead($title);
    print("<h1 align=\"center\">" . htmlspecialchars($title) . "</h1>");
    if (!empty($options['info'])) {
        print("<p align=\"center\">" . htmlspecialchars(admincp_t($options['info'])) . "</p>");
    }
    print("<p align=\"center\"><a href=\"../controlcenter.php\">" . htmlspecialchars(admincp_t(['chs' => '返回管理系统', 'cht' => '返回管理系統', 'en' => 'Back to management system'])) . "</a></p>");

    if (!empty($searchable)) {
        print("<div align=\"center\" style=\"margin-bottom: 10px;\">");
        print("<form method=\"get\" action=\"" . htmlspecialchars(admincp_page_url()) . "\">");
        print("<input type=\"text\" name=\"keyword\" value=\"" . htmlspecialchars($keyword) . "\" style=\"width: 220px\" placeholder=\"" . htmlspecialchars(admincp_t(['chs' => '搜索关键字', 'cht' => '搜尋關鍵字', 'en' => 'Search keyword'])) . "\">");
        print(" <input type=\"submit\" class=\"btn\" value=\"" . htmlspecialchars(admincp_t(['chs' => '搜索', 'cht' => '搜尋', 'en' => 'Search'])) . "\">");
        if ($keyword !== '') {
            print(" <a href=\"" . htmlspecialchars(admincp_page_url()) . "\">" . htmlspecialchars(admincp_t(['chs' => '重置', 'cht' => '重設', 'en' => 'Reset'])) . "</a>");
        }
        print("</form></div>");
    }

    print($pagertop);
    print("<table width=\"97%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">");
    print("<tr>");
    foreach ($columns as $column) {
        print("<td class=\"colhead\" align=\"left\">" . htmlspecialchars(admincp_t($column['label'])) . "</td>");
    }
    print("</tr>");

    $sql = sprintf("SELECT %s FROM `%s` %s %s ORDER BY %s LIMIT %d, %d", $select, $table, $join, $where, $order, $offset, $perpage);
    $res = \Nexus\Database\NexusDB::select($sql);
    if (empty($res)) {
        print("<tr><td class=\"rowfollow\" align=\"center\" colspan=\"" . count($columns) . "\">" . htmlspecialchars(admincp_t(['chs' => '暂无记录', 'cht' => '暫無記錄', 'en' => 'No records'])) . "</td></tr>");
    }
    foreach ($res as $row) {
        print("<tr>");
        foreach ($columns as $column) {
            $value = $row[$column['key']] ?? '';
            if (!empty($column['format'])) {
                $value = ($column['format'])($value, $row);
            } else {
                $value = htmlspecialchars((string)$value);
            }
            print("<td class=\"rowfollow\" align=\"left\">" . $value . "</td>");
        }
        print("</tr>");
    }
    print("</table>");
    print($pagerbottom);
    print("<p align=\"center\" class=\"small\">" . sprintf(admincp_t(['chs' => '共 %s 条记录', 'cht' => '共 %s 條記錄', 'en' => 'Total %s records']), number_format($total)) . "</p>");
    stdfoot();
}

/**
 * 简易分页条
 */
function nexus_pager_bar(int $total, int $perpage, int $current, string $prefix): string
{
    $pages = (int)ceil($total / $perpage);
    $html = '<div align="center" style="margin: 6px 0;">';
    $prev = max(1, $current - 1);
    $next = min($pages, $current + 1);
    $html .= $current > 1 ? "<a href=\"" . htmlspecialchars($prefix . $prev) . "\">&laquo; " . admincp_t(['chs' => '上一页', 'cht' => '上一頁', 'en' => 'Prev']) . "</a>" : "<span class=\"gray\">&laquo; " . admincp_t(['chs' => '上一页', 'cht' => '上一頁', 'en' => 'Prev']) . "</span>";
    $start = max(1, $current - 3);
    $end = min($pages, $current + 3);
    for ($i = $start; $i <= $end; $i++) {
        $html .= $i === $current ? " <b>" . $i . "</b> " : " <a href=\"" . htmlspecialchars($prefix . $i) . "\">" . $i . "</a> ";
    }
    $html .= $current < $pages ? " <a href=\"" . htmlspecialchars($prefix . $next) . "\">" . admincp_t(['chs' => '下一页', 'cht' => '下一頁', 'en' => 'Next']) . " &raquo;</a>" : " <span class=\"gray\">" . admincp_t(['chs' => '下一页', 'cht' => '下一頁', 'en' => 'Next']) . " &raquo;</span>";
    $html .= "</div>";
    return $html;
}

/**
 * 用户名（带链接）
 */
function admincp_username($userid): string
{
    $userid = intval($userid);
    if ($userid <= 0) {
        return '<span class="gray">-</span>';
    }
    return get_username($userid);
}

/**
 * 格式化时间
 */
/**
 * 当前后台列表页的站点根目录相对地址，如 admincp/users.php
 */
function admincp_page_url(): string
{
    return 'admincp/' . ($GLOBALS['admincpPageFile'] ?: 'users.php');
}

function admincp_time($value): string
{
    if (empty($value)) {
        return '';
    }
    return htmlspecialchars((string)$value);
}
