<?php

/**
 *
 *	@module			Forum
 *	@version		0.6
 *	@authors		Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *	@license		GNU General Public License
 *	@platform		2.8.x
 *	@requirements	PHP 5.6.x and higher
 *
 */

// Include config file
require_once '../../config.php';

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

	define('FORUM_DISPLAY_CONTENT', 'search_the_forum');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require WB_PATH . '/index.php';

}
