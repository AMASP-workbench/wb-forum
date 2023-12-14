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
 
require_once '../../config.php';
require_once WB_PATH.'/modules/admin.php';

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

/**
 *	Parser for wb 2.8.3
 */
require_once __DIR__."/classes/class.forum_parser.php";
$parser = new forum_parser();

require_once __DIR__."/libs/parsedown/Parsedown.php";
$source = file_get_contents(__DIR__."/README.md");
$Parsedown = new Parsedown();
$html = $Parsedown->text($source);

$page_values = array(
	'WB_URL'	    => WB_URL,
	'ADMIN_URL'		=> ADMIN_URL,
	'TEXT_OK'		=> 'Ok',
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'content'		=> $html,
	'ftan'	=> (true === method_exists($admin, "getFTAN")) ? $admin->getFTAN() : ""
);

echo $parser->render(
	"help.lte",
	$page_values
);


$admin->print_footer();
