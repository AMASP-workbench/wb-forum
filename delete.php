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

defined('WB_PATH') OR header('Location: ../../index.php');

//Delete all
$database->query("
	DELETE `thread`, `forum`, `post`
	FROM `" . TABLE_PREFIX . "mod_forum_forum` AS `forum`
	LEFT JOIN `" . TABLE_PREFIX . "mod_forum_thread` AS `thread` ON(`thread`.`forumid` = `forum`.`forumid`)
	LEFT JOIN `" . TABLE_PREFIX . "mod_forum_post` AS `post` ON(`thread`.`threadid` = `post`.`threadid`)
	WHERE `forum`.`section_id` = '$section_id'
");

//Remove Cache - we won't need it any more =)
$database->query("DELETE FROM `" . TABLE_PREFIX . "mod_forum_cache` WHERE `section_id` = '$section_id'");
