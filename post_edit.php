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

// Validation:
$post_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_post WHERE postid = '" . intval($_REQUEST['postid']) . "'");
$post = $post_query->fetchRow();

if(!$post)
{
	exit(header('Location: ' . WB_URL . PAGES_DIRECTORY));
}

$thread_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($post['threadid']) . "'");
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

$section_id = $forum['section_id'];
$page_id = $forum['page_id'];
define('SECTION_ID', $section_id);
//define('PAGE_ID', $page_id);

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

	define('FORUM_DISPLAY_CONTENT', 'post_edit');
	define('PAGE_CONTENT', WB_PATH . '/modules/forum/content.php');

	require(WB_PATH . '/index.php');
}

?>