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

if (defined('VIEW_FORUM_SEARCH') AND VIEW_FORUM_SEARCH)
{
	$link = $database->get_one('SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = ' . (int) PAGE_ID);
	
	$searchVal = (isset($_REQUEST['mod_forum_search']))
		? htmlentities(htmlspecialchars(stripslashes($_REQUEST['mod_forum_search'])))
		: ""
		;

	require_once __DIR__."/classes/class.forum_parser.php";
	$parser = new forum_parser();
	
	$page_data = array(
		'action'	  => WB_URL . PAGES_DIRECTORY . $link . PAGE_EXTENSION,
		'searchVal'	  => $searchVal,
		'TEXT_SEARCH' => $TEXT["SEARCH"],
		'submit_text' => $TEXT["SEARCH"]
	
	);
	
	echo $parser->render(
		"search_form.lte",
		$page_data
	);
}
