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

// prevent this file from being accessed directly
if(!defined('WB_PATH'))
{
	header('Location: index.php');
	exit;
}

if(!defined('SKIP_CACHE')) define('SKIP_CACHE', 1);
require_once(WB_PATH . '/modules/forum/backend.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

require_once( dirname(__FILE__)."/classes/class.forum_parser.php" );
$parser = new forum_parser();

$oFORUM = addon\forum\classes\forum::getInstance();

// echo addon\forum\classes\subway\subway_tools::display( $oFORUM );

// return 0;

$oSubway = addon\forum\classes\subway\subway::getInstance();

$forums = $oSubway->select(
    TABLE_PREFIX . "mod_forum_forum",
    "*",
    [ 
        ["section_id"  => $section_id ],
        ["bool"        => "and" ],
        ["page_id"     => $page_id ],
   //     ["bool"        => "and" ],
   //     ["like"        => [ "title", "%aldus%" ] ],
        ["order by"    => "displayorder" ],
        ["order rule"  => "ASC" ]
    ],
    true
);

if($oSubway->isError()) {
	/**
	 *	There has been an error during the last query: 
	 */
	$message = $oSubway2->getError();
	$forum_list = "";
} elseif (0 == count($forums) ) { // $forums->numRows()) {

	/**
	 *	No results found - no forums to list here
	 */
	$message = $MOD_FORUM['TXT_NO_FORUMS_B'];
	$forums_list = "";
	
} else {

    // echo addon\forum\classes\subway\subway_tools::display( $oSubway );
	
	/**
	 *	List the forums
	 *	
	 */
	$message = "";
	
	ob_start();
	$forum_array = array();
	//while ($forum = $forums->fetchRow( MYSQL_ASSOC ))
	foreach($forums as $forum)
	{
		$forum_array[ $forum['parentid'] ][ $forum['forumid'] ] = $forum;
	}

	// Zuordnung Foren -> Level:
	$arrLevel = getForumLevel();

	print_forums(0);

	$forums_list = "<ul class='forum_list'>".ob_get_clean()."</ul>";
}

/**
 *	Collecting the values/datas for the page
 */
$page_data = array(
	'WB_PATH' => WB_PATH,
	'WB_URL' => WB_URL,
	'section_id'	=> $section_id,
	'page_id'	=> $page_id,
	'MOD_FORUM_TXT_CREATE_FORUM_B'	=> $MOD_FORUM['TXT_CREATE_FORUM_B'],
	'MOD_FORUM_TXT_FORUMS_B'	=> $MOD_FORUM['TXT_FORUMS_B'],
	'TEXT_HELP'	=> $MENU["HELP"],
	'TEXT_SETTINGS'	=> $TEXT['SETTINGS'],
	'message'		=> $message,
	'forums_list'	=> $forums_list
);

echo $parser->render(
	"modify.lte",
	$page_data
);

