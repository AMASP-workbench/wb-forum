<?php

/**
 *
 *    @module       Forum
 *    @version      0.6.7
 *    @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *    @license      GNU General Public License
 *    @platform     2.8.x
 *    @requirements PHP 8.1.x and higher
 *
 */

require_once "functions.php";

$thread_count = $database->query("SELECT COUNT(`threadid`) AS `total` FROM `" . TABLE_PREFIX . "mod_forum_thread` WHERE `forumid` = '" . intval($forum['forumid']) . "'");
$thread_count = $thread_count->fetchRow( MYSQLI_ASSOC );

$page = (isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1);
$pagecount = ceil($thread_count['total'] / FORUMDISPLAY_PERPAGE);

if (($page * FORUMDISPLAY_PERPAGE) > $thread_count['total']) {
	// Go to last page
	$page = $pagecount;
}
$start = ($page * FORUMDISPLAY_PERPAGE) - FORUMDISPLAY_PERPAGE;
if ($start < 0) {
	$start = 0;
}
$threads = $database->query("
	SELECT `t`.*, `u`.*, IF(NOT ISNULL(`u`.`user_id`), `u`.`display_name`, `t`.`username`) AS `display_name`
	FROM `" . TABLE_PREFIX . "mod_forum_thread` AS `t`
	LEFT JOIN `" . TABLE_PREFIX . "users` AS `u` ON(`u`.`user_id` = `t`.`user_id`)
	WHERE `t`.`forumid` = '" . intval($forum['forumid']) . "'
	ORDER BY `t`.`lastpost` DESC
	LIMIT $start, $perpage
");

// Construct Page Nav
$page_url = WB_URL.'/modules/forum/forum_view.php?sid='.$section_id.'&amp;pid='.$page_id.'&amp;fid='.$forum['forumid'];

$pagenav = '';
for($i = 1; $i <= $pagecount; $i++){
	if ($page == $i){
		$pagenav .= '<span '.(fetch_fontsize_from_page($i,$page)).'><strong>['.$i.']</strong></span>&nbsp;';
	} else {
		$pagenav .= '<span '.(fetch_fontsize_from_page($i,$page)).'><a href="'.$page_url.'&page='.$i.'">'.$i.'</a></span>&nbsp;';
	}
}
