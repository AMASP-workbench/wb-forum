<?php

/**
 *
 *  @module       Forum
 *  @version      0.6.8
 *  @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Bianka Martinovic, Dietrich Roland Pehlke (last)
 *  @license      GNU General Public License
 *  @platform     1.6.x
 *  @requirements PHP 8.1.x and higher
 *
 */

require WB_PATH.'/modules/forum/info.php';

/**
 *	Update the WB settings/inserts for the search
 *
 */

//	[1] Delete old entries
$database->query("DELETE FROM `" . TABLE_PREFIX . "search` WHERE `value` = 'forum'");
$database->query("DELETE FROM `" . TABLE_PREFIX . "search` WHERE `extra` = 'forum'");

//	[2] Inser new entries
$temp_field_info = array(
	'page_id'	=> 'page_id',
	'title'		=> 'title',
	'link'		=> 'link'
);
$field_info = serialize($temp_field_info);
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('module', 'forum', '$field_info')");

//	[2.2]	Query start
$query_start_code = "SELECT `[TP]pages`.`page_id`, `[TP]pages`.`page_title`, `[TP]pages`.`link` FROM `[TP]mod_forum_post`, `[TP]pages` WHERE ";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_start', '$query_start_code', 'forum')");

//	[2.3]	Query body
$query_body_code = "
`[TP]pages`.`page_id` = `[TP]mod_forum_post`.`page_id` AND `[TP]mod_forum_post`.`title` [O] \'[W][STRING][W]\' AND `[TP]pages`.`searching` = \'1\' OR
`[TP]pages`.`page_id` = `[TP]mod_forum_post`.`page_id` AND `[TP]mod_forum_post`.`text` [O] \'[W][STRING][W]\' AND `[TP]pages`.`searching` = \'1\'
";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_body', '$query_body_code', 'forum')");

// [2.4]	Query end
$query_end_code = "";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_end', '$query_end_code', 'forum')");

// [3]	Set charset to utf-8
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_forum_cache` CHARACTER SET = utf8;");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_forum_forum` CHARACTER SET = utf8;");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_forum_post` CHARACTER SET = utf8;");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_forum_settings` CHARACTER SET = utf8;");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_forum_thread` CHARACTER SET = utf8;");
