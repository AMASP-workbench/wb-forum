<?php

/**
 *
 *  @module       Forum
 *  @version      0.6.8
 *  @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *  @license      GNU General Public License
 *  @platform     1.6.x
 *  @requirements PHP 8.1.x and higher
 *
 */

// Include config file
require_once '../../config.php';

// Validation:
$post_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_post` WHERE `postid` = '" . intval($_REQUEST['postid']) . "'");
$post = $post_query->fetchRow();

if(!$post)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$thread_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_thread` WHERE `threadid` = '" . intval($post['threadid']) . "'");
$thread = $thread_query->fetchRow();

if(!$thread)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$forum_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `forumid` = '" . intval($thread['forumid']) . "'");
$forum = $forum_query->fetchRow();

if(!$forum)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$section_id = $forum['section_id'];
$page_id = $forum['page_id'];
define('SECTION_ID', $section_id);
//define('PAGE_ID', $page_id);

require_once WB_PATH . '/modules/forum/backend.php';

$query_page = $database->query("
	SELECT * FROM `".TABLE_PREFIX."pages` AS `p`
	INNER JOIN `".TABLE_PREFIX."sections` AS `s` USING(`page_id`)
	WHERE `p`.`page_id` = '$page_id' AND `section_id` = '$section_id'
");

if(!$query_page->numRows())
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$page = $query_page->fetchRow();

	define('FORUM_DISPLAY_CONTENT', 'post_edit');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require WB_PATH . '/index.php';
}
