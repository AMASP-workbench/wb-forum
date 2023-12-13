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

defined('WB_PATH') or header('Location: ../../index.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require (!file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang);

if (isset($_GET['search']) and $_GET['search']==1) {
    include 'include_search.php';
    unset($_GET['search']);
} else {
    $title =& $wb->page['page_title'];
    $path = WB_PATH;
    $url = WB_URL;
    $pageurl = $url . PAGES_DIRECTORY . $wb->page['link'] . PAGE_EXTENSION;

    require WB_PATH . '/modules/forum/backend.php';

    if (is_array($forumcache)) {
        $forum_counts_query = $database->query("
			SELECT `forumid`, COUNT(`threadid`) AS `threadcount`
			FROM `" . TABLE_PREFIX . "mod_forum_thread`
			WHERE `forumid` IN(" . implode(',', array_keys($forumcache)) . ")
			GROUP BY `forumid`
		");

        $forum_counts = array();

        while ($fc = $forum_counts_query->fetchRow(MYSQLI_ASSOC)) {
            $forumcache[$fc['forumid']]['threadcount'] = $fc['threadcount'];
        }
    }
    if (!count($iforumcache) || !isset($iforumcache[0]) || !is_array($iforumcache[0]) || count($iforumcache[0]) == 0) {
        echo 	$MOD_FORUM['TXT_NO_FORUMS_B'];
    } else {
        require_once 'include_searchform.php';
        foreach ($iforumcache[0] as $forumid) {
            $forum_level1 =& $forumcache[ $forumid ];
            if (!has_access($forum_level1)) {
                continue;
            }
// Level 1: Grouping
?>
		<div class="board_tree">
			<div class="board_level1">
				<?php echo $forum_level1['title']; ?>
				<div class="board_description">
					<?php echo $forum_level1['description']; ?>
				</div>
			</div>

<?php
            if (isset($iforumcache[ $forumid ])) {
                foreach ($iforumcache[ $forumid ] as $sfid) {
                    $forum_level2 =& $forumcache[ $sfid ];
                    if (!has_access($forum_level2)) {
                        continue;
                    }
?>
		<div class="board_level2">
			<a href="<?php echo $url; ?>/modules/forum/forum_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $sfid; ?>">
			<?php echo $forum_level2['title']; ?>
			</a> <span class="board_themes">(<?php echo(isset($forum_level2['threadcount']) ? number_format($forum_level2['threadcount']) : '0'); ?><?php echo(isset($forum_level2['threadcount']) && $forum_level2['threadcount']==1 ? ' '.$MOD_FORUM['TXT_THEME_F'].')' : ' '.$MOD_FORUM['TXT_THEMES_F'].')'); ?></span>
			<div class="board_description">
				<?php echo $forum_level2['description']; ?>
			</div>
		</div>

<?php
                    if (DISPLAY_SUBFORUMS != false and !empty($iforumcache[ $sfid ])) {
                        $subforumbits = array();
                        foreach ($iforumcache[ $sfid ] as $subforumid) {
                            $forum_level3 =& $forumcache[ $subforumid ];
                            if (!has_access($forum_level3)) {
                                continue;
                            }

                            // Bugfix 201605-15 (aldus): $forum_level3['threadcount'] could be empty
                            if (!isset($forum_level3['threadcount'])) {
                                $forum_level3['threadcount'] = 0;
                            }

                            $themes = ($forum_level3['threadcount']==1 ? $MOD_FORUM['TXT_THEME_F'] :  $MOD_FORUM['TXT_THEMES_F']);
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
            }
?>
	 	</div>
<?php 

        }
    }
}
