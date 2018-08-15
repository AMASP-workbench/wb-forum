<?php

/**
 *
 *	@module			Forum
 *	@version		0.5.10
 *	@authors		Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)
 *	@license		GNU General Public License
 *	@platform		2.8.x
 *	@requirements	PHP 5.6.x and higher
 *
 */

global $section_id;
if(!isset($section_id))
{
    if(isset($_GET['sid']))
    {
        $section_id= intval($_GET['sid']);
    } else {
        $section_id = 0;
    }
}

global $page_id;
if(!isset($page_id))
{
    if(isset($_GET['pid']))
    {
        $page_id= intval($_GET['pid']);
    } else {
        $page_id = 0;
    }
}

// die(var_dump( $section_id ));
// Include config file
require('../../config.php');

if(isset($_REQUEST['goto'])) $_REQUEST['tid'] = $_REQUEST['goto'];

if(!isset($_REQUEST['tid'])) die("E: 120023");

$oSubway2 = addon\forum\classes\subway\subway::getInstance();

/**
 * prüfen, ob wir auf einen einzelnes posting weiterleiten sollen
 * wenn wir das an dieser stelle prüfen, müssen wir $_pages nicht
 * für jeden link ausrechnen. sehr performant :)
 */

if (isset($_GET['goto']))
{ 
	$_post_id = intval($_GET['goto']);

	$sql = "SELECT f.title as forum,
				  p.postid, p.threadid, p.title, p.text, p.page_id, p.section_id

			FROM ".TABLE_PREFIX."mod_forum_post p
				JOIN  ".TABLE_PREFIX."mod_forum_thread t USING(threadid)
				JOIN  ".TABLE_PREFIX."mod_forum_forum f ON (t.forumid = f.forumid)

			WHERE p.postid = ".$_post_id."

			LIMIT 1";

	$res = $database->query($sql);

	if( isset($res) AND $res->numRows() > 0)
	{

	$f = $res->fetchRow( MYSQL_ASSOC );


		// Anzahl Datensätze zählen, die vor unserem liegen, brauch wir für den Link:
		$sql = 'SELECT COUNT(*) as total FROM '.TABLE_PREFIX.'mod_forum_post WHERE threadid = ' . $f['threadid'] . ' AND postid <= ' . $f['postid'];
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
$thread = [];
$bSuccess = $oSubway2->execute_query(
    "SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($_REQUEST['tid']) . "'",
    true,
    $thread,
    false
);
//$thread_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($_REQUEST['tid']) . "'");
//$thread = $thread_query->fetchRow( MYSQL_ASSOC );

if( false === $bSuccess )
{
    die( $oSubway2->getError() );
	//die(header('Location: ' . WB_URL . PAGES_DIRECTORY));
} else {
    // echo $oSubway2->display( $thread );
}

$forum = [];
$bSuccess = $oSubway2->execute_query(
    "SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE forumid = '" . intval($thread['forumid']) . "'",
    true,
    $forum,
    false
);

//$forum_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE forumid = '" . intval($thread['forumid']) . "'");
//$forum = $forum_query->fetchRow( MYSQL_ASSOC );

if( false === $bSuccess )
{
    die( $oSubway2->getError() );
	//die(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$section_id = $forum['section_id'];
	$page_id = $forum['page_id'];
	define('SECTION_ID', $section_id);
}

require_once(WB_PATH . '/modules/forum/backend.php');

$page = [];
$oSubway2->execute_query(
    "
	SELECT * FROM ".TABLE_PREFIX."pages AS p
	INNER JOIN ".TABLE_PREFIX."sections AS s USING(page_id)
	WHERE p.page_id = '$page_id' AND section_id = '$section_id' ",
    true,
    $page,
    false
);

if(0 == count( $page ))
{   echo var_dump($oSubway2);
    die("Oh my g!");
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	define('FORUM_DISPLAY_CONTENT', 'view_thread');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require(WB_PATH . '/index.php');
}

?>