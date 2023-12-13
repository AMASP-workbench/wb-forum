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

if (isset($_REQUEST['goto'])) $_REQUEST['tid'] = $_REQUEST['goto'];

if (!isset($_REQUEST['tid'])) die("E: 120023");

/**
 * prüfen, ob wir auf einen einzelnes posting weiterleiten sollen
 * wenn wir das an dieser stelle prüfen, müssen wir $_pages nicht
 * für jeden link ausrechnen. sehr performant :)
 */

// [1]
echo forum::getInstance()->getFrontendTemplateCSS();

if (isset($_GET['goto']))
{
	$_post_id = intval($_GET['goto']);

	$sql = "SELECT `f`.`title` as `forum`,
				  `p`.`postid`, `p`.`threadid`, `p`.`title`, `p`.`text`, `p`.`page_id`, `p`.`section_id`
			FROM `".TABLE_PREFIX."mod_forum_post` `p`
				JOIN `".TABLE_PREFIX."mod_forum_thread` `t` USING(`threadid`)
				JOIN `".TABLE_PREFIX."mod_forum_forum` `f` ON (`t`.`forumid` = `f`.`forumid`)
			WHERE `p`.`postid` = ".$_post_id."
			LIMIT 1";

	$res = $database->query($sql);

	if( isset($res) AND $res->numRows() > 0)
	{

	   $f = $res->fetchRow( MYSQLI_ASSOC );

		//anzahl Datensätze zählen, die vor unserem liegen, brauch wir für den Link:
		$sql = 'SELECT COUNT(*) as `total` FROM `'.TABLE_PREFIX.'mod_forum_post` WHERE `threadid` = ' . $f['threadid'] . ' AND `postid` <= ' . $f['postid'];
		$res2 = $database->query($sql);
		$_count = $res2->fetchRow();
		$section_id = $f['section_id'];
		include_once WB_PATH . '/modules/forum/config.php';
		$_pages = ceil($_count['total'] / SHOWTHREAD_PERPAGE);

    	// Location Ziel
    	$owd_link = WB_URL.'/modules/forum/thread_view.php?' .
    								'sid='.$f['section_id'].
    								'&pid='.$f['page_id'].
    								'&tid='.$f['threadid'].
    								'&page='.$_pages .
    								'#post'. $f['postid'];
    	//die($owd_link);
    	unset($_GET['goto']);

    	die(header('Location: ' . $owd_link));


	}//isset($res)


}
// Validation:
$thread_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_thread` WHERE `threadid` = '" . intval($_REQUEST['tid']) . "'");
$thread = $thread_query->fetchRow( MYSQLI_ASSOC );

if (!$thread)
{
	die(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$forum_query = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `forumid` = '" . intval($thread['forumid']) . "'");
$forum = $forum_query->fetchRow( MYSQLI_ASSOC );

if (!$forum)
{
	die(header('Location: ' . WB_URL . PAGES_DIRECTORY));
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

if (0 == $query_page->numRows())
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$page = $query_page->fetchRow( MYSQLI_ASSOC );

	define('FORUM_DISPLAY_CONTENT', 'view_thread');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require WB_PATH . '/index.php';
}
