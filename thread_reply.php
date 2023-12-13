<?php

/**
 *
 *    @module       Forum
 *    @version      0.6.8
 *    @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *    @license      GNU General Public License
 *    @platform     1.6.x
 *    @requirements PHP 8.1.x and higher
 *
 */

// Include config file
require_once '../../config.php';

// Validation:
//	[1| no 'tid' (thread id) given
if(!isset($_REQUEST['tid'])) die(header('Location: ' . WB_URL . PAGES_DIRECTORY));

$thread_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_thread` WHERE `threadid` = '" . intval($_REQUEST['tid']) . "'");

//	[2]	There was an query error
if( $database->is_error()) die( "[Error: 2005] ".$database->get_error());

//	[3] Result list is empty - no matches found
if( $thread_query->numRows() == 0 ) die(header('Location: ' . WB_URL . PAGES_DIRECTORY));

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

require_once WB_PATH . '/modules/forum/backend.php';

$query_page = $database->query("
	SELECT * FROM `".TABLE_PREFIX."pages` AS `p`
	INNER JOIN `".TABLE_PREFIX."sections` AS `s` USING(`page_id`)
	WHERE `p`.`page_id` = '$page_id' AND `section_id` = '$section_id'
");

if(0 == $query_page->numRows())
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$page = $query_page->fetchRow();

define('FORUM_DISPLAY_CONTENT', 'reply_thread');
define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

require WB_PATH . '/index.php';
