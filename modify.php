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

defined('WB_PATH') OR header('Location: ../../index.php');

if(!defined('SKIP_CACHE')) define('SKIP_CACHE', 1);
require_once(WB_PATH . '/modules/forum/backend.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

require_once __DIR__."/classes/class.forum_parser.php";
$parser = new forum_parser();

$forums = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `section_id` = '".$section_id."' AND `page_id` = '".$page_id."' ORDER BY `displayorder` ASC");

if($database->is_error()) {
    /**
     *    There has been an error during the last query:
     */
    $message = $database->get_error();
    $forum_list = "";
} elseif (0 == $forums->numRows()) {

    /**
     *    No results found - no forums to list here
     */
    $message = $MOD_FORUM['TXT_NO_FORUMS_B'];
    $forums_list = "";

} else {

    /**
     *    List the forums
     *
     */
    $message = "";

    ob_start();
    $forum_array = array();
    while ($forum = $forums->fetchRow( MYSQLI_ASSOC ))
    {
        $forum_array[ $forum['parentid'] ][ $forum['forumid'] ] = $forum;
    }

    // Zuordnung Foren -> Level:
    $arrLevel = getForumLevel();

    print_forums(0);

    $forums_list = "<ul class='forum_list'>".ob_get_clean()."</ul>";
}

/**
 *    Collecting the values/datas for the page
 */
$page_data = array(
    'WB_PATH'                       => WB_PATH,
    'WB_URL'                        => WB_URL,
    'section_id'                    => $section_id,
    'page_id'                       => $page_id,
    'MOD_FORUM_TXT_CREATE_FORUM_B'  => $MOD_FORUM['TXT_CREATE_FORUM_B'],
    'MOD_FORUM_TXT_FORUMS_B'        => $MOD_FORUM['TXT_FORUMS_B'],
    'TEXT_HELP'                     => $MENU["HELP"],
    'TEXT_SETTINGS'                 => $TEXT['SETTINGS'],
    'message'                       => $message,
    'forums_list'                   => $forums_list
);

echo $parser->render(
    "modify.lte",
    $page_data
);

