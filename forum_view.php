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

// Include config file
require_once '../../config.php';

if (!isset($_REQUEST['fid']))
{
    $tempLookForResult = $database->query(
        "SELECT `forum_id` FROM `".TABLE_PREFIX."mod_forum_forum` WHERE parent_id > 0 LIMIT 1"
    );
    $tempInfo = $tempLookForResult->fetchRow();
    $_REQUEST['fid'] = $tempInfo['forum_id'];
}

// Validation:
$forum_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `forumid` = '" . intval($_REQUEST['fid']) . "'");
$forum = $forum_query->fetchRow();

if (!$forum)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$section_id = $forum['section_id'];
	$page_id = $forum['page_id'];
	define('SECTION_ID', $section_id);
}

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

	define('FORUM_DISPLAY_CONTENT', 'view_forum');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require WB_PATH . '/index.php';
}

