<?php
require "../include/bittorrent.php";
dbconn();

// 保持和原种子收藏一致的响应格式，方便前端复用
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/plain; charset=utf-8");

if (empty($CURUSER)) {
    echo "failed";
    exit;
}

$postid = intval($_GET['postid'] ?? 0);
$res = sql_query("SELECT id, topicid, userid FROM posts WHERE id = " . sqlesc($postid) . " LIMIT 1");
$post = mysql_fetch_assoc($res);
if (!$post) {
    echo "failed";
    exit;
}

$topicid = intval($post['topicid']);
$forumRes = sql_query("SELECT f.id, f.minclassread FROM topics t INNER JOIN forums f ON f.id = t.forumid WHERE t.id = " . sqlesc($topicid) . " LIMIT 1");
$forum = mysql_fetch_assoc($forumRes);
if (!$forum || intval($forum['minclassread']) > intval(get_user_class())) {
    echo "failed";
    exit;
}

$userId = intval($CURUSER['id']);
$exists = sql_query("SELECT id FROM post_bookmarks WHERE userid = " . sqlesc($userId) . " AND postid = " . sqlesc($postid) . " LIMIT 1");
if (mysql_num_rows($exists) > 0) {
    $row = mysql_fetch_assoc($exists);
    sql_query("DELETE FROM post_bookmarks WHERE id = " . sqlesc($row['id']));
    $Cache->delete_value('user_' . $userId . '_post_bookmark_list');
    echo "deleted";
} else {
    sql_query("INSERT INTO post_bookmarks (userid, topicid, postid, created_at, updated_at) VALUES ("
        . sqlesc($userId) . ", " . sqlesc($topicid) . ", " . sqlesc($postid) . ", " . sqlesc(date('Y-m-d H:i:s')) . ", " . sqlesc(date('Y-m-d H:i:s')) . ")");
    $Cache->delete_value('user_' . $userId . '_post_bookmark_list');
    echo "added";
}
