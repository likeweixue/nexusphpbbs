<?php
require "../include/bittorrent.php";
dbconn();
require_once(get_langfile_path("forums.php"));
loggedinorreturn();
parked();

$isChinese = str_starts_with(get_langfolder_cookie(), 'ch');
$L = $isChinese ? [
    'title' => '我的帖子收藏',
    'intro' => '这里列出了你在论坛收藏的帖子，点击标题可以跳转到对应楼层。',
    'col_post' => '帖子',
    'col_forum' => '所在版块',
    'col_author' => '作者',
    'col_topic' => '主题',
    'col_added' => '收藏时间',
    'action' => '操作',
    'remove' => '取消收藏',
    'empty' => '你还没有收藏任何帖子。在帖子右下角点击星标即可收藏。',
    'back' => '返回论坛',
    'removed' => '已取消收藏。',
    'total' => '共 %s 个收藏',
] : [
    'title' => 'My Post Bookmarks',
    'intro' => 'Posts you starred in the forum are listed here. Click the title to jump to the post.',
    'col_post' => 'Post',
    'col_forum' => 'Forum',
    'col_author' => 'Author',
    'col_topic' => 'Topic',
    'col_added' => 'Added',
    'action' => 'Action',
    'remove' => 'Remove',
    'empty' => 'You have not bookmarked any post yet. Click the star at the bottom of a post to bookmark it.',
    'back' => 'Back to forum',
    'removed' => 'Bookmark removed.',
    'total' => '%s bookmark(s)',
];

$userId = intval($CURUSER['id']);

// 取消收藏
if (($_GET['action'] ?? '') === 'delete') {
    $id = intval($_GET['id'] ?? 0);
    if ($id > 0) {
        sql_query("DELETE FROM post_bookmarks WHERE id = " . sqlesc($id) . " AND userid = " . sqlesc($userId));
        $Cache->delete_value('user_' . $userId . '_post_bookmark_list');
    }
    nexus_redirect('collections.php?removed=1');
}

$perpage = 20;
$total = get_row_count("post_bookmarks", "WHERE userid = " . sqlesc($userId));
list($pagertop, $pagerbottom, $limit) = pager($perpage, $total, "collections.php?");

stdhead($L['title']);
begin_main_frame($L['title']);
print("<p align=\"center\">" . htmlspecialchars($L['intro']) . "</p>");
if (!empty($_GET['removed'])) {
    print("<p align=\"center\" style=\"color:#080\">" . htmlspecialchars($L['removed']) . "</p>");
}
print("<p align=\"center\" class=\"small\">" . sprintf($L['total'], number_format($total)) . "</p>");

if ($total > 0) {
    print($pagertop);
    print("<table width=\"97%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" class=\"main\" align=\"center\">");
    print("<tr>");
    foreach (['col_post', 'col_topic', 'col_forum', 'col_author', 'col_added', 'action'] as $col) {
        print("<td class=\"colhead\" align=\"center\">" . htmlspecialchars($L[$col]) . "</td>");
    }
    print("</tr>");

    $sql = "SELECT b.id AS bookmark_id, b.postid, b.topicid, b.created_at, p.userid AS post_userid, p.body, p.added AS post_added,
                   t.subject, t.forumid, f.name AS forum_name, u.username
            FROM post_bookmarks b
            LEFT JOIN posts p ON p.id = b.postid
            LEFT JOIN topics t ON t.id = b.topicid
            LEFT JOIN forums f ON f.id = t.forumid
            LEFT JOIN users u ON u.id = p.userid
            WHERE b.userid = " . sqlesc($userId) . "
            ORDER BY b.id DESC " . $limit;
    $res = sql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $postUrl = sprintf('forums.php?action=viewtopic&topicid=%d&page=p%d#pid%d', intval($row['topicid']), intval($row['postid']), intval($row['postid']));
        $body = trim(strip_tags((string)format_comment($row['body'] ?? '', true, false, true, true, 200, false)));
        if (mb_strlen($body, 'UTF-8') > 120) {
            $body = mb_substr($body, 0, 120, 'UTF-8') . ' ......';
        }
        print("<tr>");
        print("<td class=\"rowfollow\" align=\"left\" style=\"max-width: 420px\"><a href=\"" . htmlspecialchars($postUrl) . "\">" . htmlspecialchars($body ?: ('#' . intval($row['postid']))) . "</a></td>");
        print("<td class=\"rowfollow\" align=\"left\">" . htmlspecialchars((string)$row['subject']) . "</td>");
        print("<td class=\"rowfollow\" align=\"left\">" . htmlspecialchars((string)$row['forum_name']) . "</td>");
        print("<td class=\"rowfollow\" align=\"left\">" . get_username(intval($row['post_userid'])) . "</td>");
        print("<td class=\"rowfollow\" align=\"center\" class=\"nowrap\">" . htmlspecialchars((string)$row['created_at']) . "</td>");
        print("<td class=\"rowfollow\" align=\"center\"><a href=\"collections.php?action=delete&amp;id=" . intval($row['bookmark_id']) . "\" onclick=\"return confirm('" . htmlspecialchars($L['remove'], ENT_QUOTES) . "?')\">" . htmlspecialchars($L['remove']) . "</a></td>");
        print("</tr>");
    }
    print("</table>");
    print($pagerbottom);
} else {
    print("<p align=\"center\"><i>" . htmlspecialchars($L['empty']) . "</i></p>");
}
print("<p align=\"center\"><a href=\"forums.php\" class=\"btn\">" . htmlspecialchars($L['back']) . "</a></p>");
end_main_frame();
stdfoot();
