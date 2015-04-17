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

if(!defined('WB_PATH')) {
	exit("Cannot access this file directly");
}
if(!file_exists(WB_PATH . '/modules/forum/languages/' . LANGUAGE . '.php')) {
	require_once(WB_PATH . '/modules/forum/languages/EN.php');
} else {
	require_once(WB_PATH . '/modules/forum/languages/' . LANGUAGE . '.php');
}

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/forum/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/forum/frontend.css');
   echo "\n</style>\n";
}

if (function_exists('ini_set')) {
	ini_set( 'arg_separator.output', '&amp;');
}

if (isset($_GET['search']) AND $_GET['search']==1 )
{
	include 'include_search.php';
}
else{
		$title =& $wb->page['page_title'];
		$path = WB_PATH;
		$url = WB_URL;
		$pageurl = $url . PAGES_DIRECTORY . $wb->page['link'] . PAGE_EXTENSION;

		require(WB_PATH . '/modules/forum/backend.php');

		if(is_array($forumcache)){
			$forum_counts_query = $database->query("
				SELECT forumid, COUNT(threadid) AS threadcount
				FROM " . TABLE_PREFIX . "mod_forum_thread
				WHERE forumid IN(" . implode(',', array_keys($forumcache)) . ")
				GROUP BY forumid
					");
	
			$forum_counts = array();
		
			while ($fc = $forum_counts_query->fetchRow()) {
				$forumcache["$fc[forumid]"]['threadcount'] = $fc['threadcount'];
			}
		}

		require_once('include_searchform.php');

 		if (!count($iforumcache) ||!is_array($iforumcache[0]) || count($iforumcache[0]) == 0) {
    	echo 	$MOD_FORUM['TXT_NO_FORUMS_B'];	
  	} else {	
			foreach ($iforumcache[0] AS $forumid) {
			$forum_level1 =& $forumcache["$forumid"];
			if (!($forum_level1['readaccess'] == 'both' OR ($forum_level1['readaccess'] == 'reg' AND $wb->get_user_id()) OR ($forum_level1['readaccess'] == 'unreg' AND !$wb->get_user_id()))) {
						continue;
					}
		?>


		<div class="board_tree">
			<div class="board_level1">
				<?php echo $forum_level1['title']; ?>
				<div class="board_description">
					<?php echo $forum_level1['description']; ?>
				</div>
			</div>
				<?php
		if (isset($iforumcache["$forumid"])) {
		foreach ($iforumcache["$forumid"] AS $sfid) {
		$forum_level2 =& $forumcache["$sfid"];
		if (!($forum_level2['readaccess'] == 'both' OR ($forum_level2['readaccess'] == 'reg' AND $wb->get_user_id()) OR ($forum_level2['readaccess'] == 'unreg' AND !$wb->get_user_id()))) {
					continue;
				}
		?>
			<div class="board_level2">
				<a href="<?php echo $url; ?>/modules/forum/forum_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $sfid; ?>">
					<?php echo $forum_level2['title']; ?>
				</a> <span class="board_themes">(<?php echo (isset($forum_level2['threadcount']) ? number_format($forum_level2['threadcount']) : '0'); ?><?php echo (isset($forum_level2['threadcount']) && $forum_level2['threadcount']==1 ? ' '.$MOD_FORUM['TXT_THEME_F'].')' : ' '.$MOD_FORUM['TXT_THEMES_F'].')'); ?></span>
				<div class="board_description">
					<?php echo $forum_level2['description']; ?>
				</div>
			</div>

					<?php
		if (DISPLAY_SUBFORUMS != false AND !empty($iforumcache["$sfid"])) {
			$subforumbits = array();
			foreach ($iforumcache["$sfid"] AS $subforumid) {
				$forum_level3 =& $forumcache["$subforumid"];
				if (!($forum_level3['readaccess'] == 'both' OR ($forum_level3['readaccess'] == 'reg' AND $wb->get_user_id()) OR ($forum_level3['readaccess'] == 'unreg' AND !$wb->get_user_id()))) {
					continue;
				}
				$themes = (@$forum_level3['threadcount']==1 ? $MOD_FORUM['TXT_THEME_F'] :  $MOD_FORUM['TXT_THEMES_F']);
				$subforumbits[]  = '<div class="board_level3">';
				$subforumbits[] .= '<a href="' . $url . '/modules/forum/forum_view.php?sid=' . $section_id . '&amp;pid=' . $page_id . '&amp;fid=' . $subforumid . '">' . $forum_level3['title'];
				$subforumbits[] .= '</a>';
				$subforumbits[] .= '<span class="board_themes"> (' . number_format(@$forum_level3['threadcount']) . ' '.$themes.')</span>';
				$subforumbits[] .= '<div class="board_description">'.$forum_level3['description'].'</div>';
				$subforumbits[] .= '</div>';
			}
			if (sizeof($subforumbits)) {
				echo  implode('', $subforumbits);
			}
		}
		}
		echo '</div>';
		}
}
}
}


?>