<?php
ob_start();
require_once("../include/bittorrent.php");
dbconn();
loggedinorreturn();
$isChineseStaff = str_starts_with(get_langfolder_cookie(), 'ch');
//加载当前语言的管理面板文案（语言文件返回数组）
$lang_staffpanel = $lang_staffpanel ?? [];
$langFile = ROOT_PATH . get_langfile_path('staffpanel.php');
if (file_exists($langFile)) {
	$loaded = require $langFile;
	if (is_array($loaded)) {
		$lang_staffpanel = array_merge($lang_staffpanel, $loaded);
	}
}
//回退：当前语言缺失的条目，用简体中文补全，避免中文站点出现英文菜单
if (!str_starts_with(get_langfolder_cookie(), 'en')) {
	$fallbackFile = ROOT_PATH . 'lang/chs/lang_staffpanel.php';
	if (is_file($fallbackFile)) {
		$fallback = require $fallbackFile;
		if (is_array($fallback)) {
			$lang_staffpanel = array_merge($fallback, $lang_staffpanel);
		}
	}
}
$staffT = function (string $key) use ($lang_staffpanel, $isChineseStaff): string {
	if (isset($lang_staffpanel[$key]) && $lang_staffpanel[$key] !== '') {
		return $lang_staffpanel[$key];
	}
	if ($isChineseStaff && is_file(ROOT_PATH . 'lang/chs/lang_staffpanel.php')) {
		static $chs = null;
		if ($chs === null) {
			$chs = require ROOT_PATH . 'lang/chs/lang_staffpanel.php';
		}
		if (!empty($chs[$key])) {
			return $chs[$key];
		}
	}
	return $key;
};
stdhead($staffT("Administration"));
print("<h1 align=center>" . ($staffT("Administration")) . "</h1>");
if (get_user_class() < UC_MODERATOR)
{
	stdmsg("Error", "Access denied!!!");
	stdfoot();
	exit;
}
begin_main_frame();

///////////////////// SysOp Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_SYSOP) {
	echo("<h1 align=center>..:: " . ($staffT("For SysOp Only")) . "  ::..</h1>");
	print("<br />");
	print("<br />");
	print("<table width=80% border=1 cellspacing=0 cellpadding=5 align=center>");
	echo("<td class=colhead align=left>" . ($staffT("Option Name")) . "</td><td class=colhead align=left>" . ($staffT("Info")) . "</td>");
	$query = "SELECT * FROM sysoppanel";
	$sql = sql_query($query);
	$sysopRows = [];
	while ($row = mysql_fetch_array($sql)) {
		$sysopRows[] = $row;
	}
	$menuName = $isChineseStaff ? '菜单栏设置' : 'Menu Settings';
	$menuInfo = $isChineseStaff ? '增加、减少、调整网站的菜单' : 'Add, remove or reorder site menus';
	$menuRowPrinted = false;
	foreach ($sysopRows as $row) {
		$id = $row['id'];
		$name = $lang_staffpanel[$row['name']] ?? $row['name'];
		$url = $row['url'];
		$info = $lang_staffpanel[$row['info']] ?? $row['info'];

		echo("<tr><td class=rowfollow align=left><strong><a href=$url>$name</a></strong></td> <td class=rowfollow align=left>$info</td></tr>");
		if ($row['url'] === 'increment-bulk.php') {
			echo("<tr><td class=rowfollow align=left><strong><a href=custommenu.php>$menuName</a></strong></td> <td class=rowfollow align=left>$menuInfo</td></tr>");
			$menuRowPrinted = true;
		}
	}
	if (!$menuRowPrinted) {
		echo("<tr><td class=rowfollow align=left><strong><a href=custommenu.php>$menuName</a></strong></td> <td class=rowfollow align=left>$menuInfo</td></tr>");
	}
	print("</table>");
	print("<br />");
	print("<br />");
}
///////////////////// Admin Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_ADMINISTRATOR) {
	echo("<h1 align=center>..:: " . ($staffT("For Administrator Only")) . " :..</h1>");
	print("<br />");
	print("<br />");
	print("<table width=80% border=1 cellspacing=0 cellpadding=5 align=center>");
	echo("<td class=colhead align=left>" . ($staffT("Option Name")) . "</td><td class=colhead align=left>" . ($staffT("Info")) . "</td>");
	$query = "SELECT * FROM adminpanel";
	$sql = sql_query($query);
	while ($row = mysql_fetch_array($sql)) {
		$id = $row['id'];
		$name =  $lang_staffpanel[$row['name']] ?? $row['name'];
		$url = $row['url'];
		$info = $lang_staffpanel[$row['info']] ?? $row['info'];

		echo("<tr><td class=rowfollow align=left><strong><a href=$url>$name</a></strong></td> <td class=rowfollow align=left>$info</td></tr>");
	}
	print("</table>");
	print("<br />");
	print("<br />");
}
///////////////////// Moderator Only \\\\\\\\\\\\\\\\\\\\\\\\\\\\
if (get_user_class() >= UC_MODERATOR) {
	echo("<h1 align=center>..:: " . ($staffT("For Moderator Only")) . "  ::..</h1>");
	print("<br />");
	print("<br />");
	print("<table width=80% border=1 cellspacing=0 cellpadding=5 align=center>");
	echo("<td class=colhead align=left>" . ($staffT("Option Name")) . "</td><td class=colhead align=left>" . ($staffT("Info")) . "</td>");
	$query = "SELECT * FROM modpanel";
	$sql = sql_query($query);
	while ($row = mysql_fetch_array($sql)) {
		$id = $row['id'];
		$name =  $lang_staffpanel[$row['name']] ?? $row['name'];
		$url = $row['url'];
		$info = $lang_staffpanel[$row['info']] ?? $row['info'];

		echo("<tr><td class=rowfollow align=left><strong><a href=$url>$name</a></strong></td> <td class=rowfollow align=left>$info</td></tr>");
	}

	print("</table>");
	print("<br />");
	print("<br />");
}
end_main_frame();
stdfoot();
?>
