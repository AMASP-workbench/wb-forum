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

require_once '../../config.php';

$admin_header = false; // suppress to print the header, so no new FTAN will be set
require_once WB_PATH . '/modules/admin.php';

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
else {
	$admin->print_header();
}

require_once __DIR__."/classes/class.validate.request.php";
$oValidate = new c_validate_request();

$fields = array(
	'section_id'	=> array('type'	=> 'integer+', 'default'	=> NULL),
	'page_id'		=> array('type' => 'integer+', 'default'	=> NULL),
	'forumid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'postid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'class'			=> array('type' => 'string', 'default'	=> NULL),
	'title'			=> array('type' => 'string', 'default'	=> NULL),
	'text'			=> array('type' => 'string', 'default'	=> NULL)
);

foreach($fields as $name => $options) {
	$temp = $oValidate->get_request( $name, $options['default'], $options['type'] );
	if( NULL === $temp) die();
	${$name} = $temp;
}

/**
 *	Some 'parsing' for 'title' and 'text'
 */
if(method_exists($database, "escapeString")) {
	$text = $database->escapeString($text);
	$title = $database->escapeString($title);
} else {
	$text = str_replace( array("<","#", "/*"), "", htmlspecialchars($text) );
	$title = str_replace( array("<","#", "/*"), "", htmlspecialchars($title) );
}

require_once WB_PATH.'/modules/admin.php';

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

if ($class=="post") {
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_post` set `title`='".$title."',`text`='".$text."' WHERE `postid`=".$postid );
} else {
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_thread` set `title`='".$title."' WHERE `threadid`=".$postid );
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_post` set `title`='".$title."',`text`='".$text."' WHERE `threadid`=".$postid." LIMIT 1" );
}

if($database->is_error()) die($database->get_error());

$admin->print_success("Forum gespeichert! [1]", WB_URL . '/modules/forum/addedit_forum.php?page_id=' . $page_id . '&section_id=' . $section_id."&forumid=".$forumid);
return 0;
