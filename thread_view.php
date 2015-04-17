<?php

/*

   Website Baker Project <http://www.websitebaker.org/>
   Copyright (C) 2004-2008, Ryan Djurovich

   Website Baker is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   Website Baker is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Website Baker; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Include config file
require('../../config.php');
//require_once WB_PATH . '/modules/forum/functions.php';



/**
 * pr�fen, ob wir auf einen einzelnes posting weiterleiten sollen
 * wenn wir das an dieser stelle pr�fen, m�ssen wir $_pages nicht
 * f�r jeden link ausrechnen. sehr performant :)
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

	$f = $res->fetchRow();


		//anzahl Datens�tze z�hlen, die vor unserem liegen, brauch wir f�r den Link:
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

	exit(header('Location: ' . $owd_link));


	}//isset($res)


}
// Validation:
$thread_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($_REQUEST['tid']) . "'");
$thread = $thread_query->fetchRow();

if(!$thread)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$forum_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE forumid = '" . intval($thread['forumid']) . "'");
$forum = $forum_query->fetchRow();

if(!$forum)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$section_id = $forum['section_id'];
	$page_id = $forum['page_id'];
	define('SECTION_ID', $section_id);
	// define('PAGE_ID', $page_id);
}

require_once(WB_PATH . '/modules/forum/backend.php');

$query_page = $database->query("
	SELECT * FROM ".TABLE_PREFIX."pages AS p
	INNER JOIN ".TABLE_PREFIX."sections AS s USING(page_id)
	WHERE p.page_id = '$page_id' AND section_id = '$section_id'
");

if(!$query_page->numRows())
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}
else
{
	$page = $query_page->fetchRow();

	define('FORUM_DISPLAY_CONTENT', 'view_thread');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require(WB_PATH . '/index.php');
}

?>