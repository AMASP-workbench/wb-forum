<?php

/**
 *
 *	@module			Forum
 *	@version		0.6
 *	@authors		Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *	@license		GNU General Public License
 *	@platform		2.8.x
 *	@requirements	PHP 5.6.x and higher
 *
 */


require_once '../../config.php';

require_once __DIR__."/classes/class.validate.request.php";
$oValidate = new c_validate_request();

$fields = array(
	'section_id'	=> array('type'	=> 'integer+', 'default'	=> NULL),
	'page_id'		=> array('type' => 'integer+', 'default'	=> NULL),
	'forumid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'postid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'class'			=> array('type' => 'string'  , 'default'	=> NULL)
);

foreach($fields as $name => $options) {
	$temp = $oValidate->get_request( $name, $options['default'], $options['type'] );
	if( NULL === $temp) die();
	${$name} = $temp;
}

require_once WB_PATH . '/modules/admin.php';

/**
 *        Load Language file
 */
$lang = __DIR__."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

require_once __DIR__."/classes/class.forum_parser.php";
$parser = new forum_parser();

$result = ($class=="post")
	? $database->query("SELECT * from `".TABLE_PREFIX."mod_forum_post` where `postid`=".$postid)
	: $database->query("SELECT * from `".TABLE_PREFIX."mod_forum_thread` where `threadid`=".$postid)
	;

if($database->is_error()) die($database->get_error());
$post_data = $result->fetchRow( MYSQLI_ASSOC );

$values = array(
	"section_id"	=> $section_id,
	"page_id"		=> $page_id,
	"class"			=> $class,
	"forumid"		=> $forumid,
	"postid"		=> $postid,
	"WB_URL"		=> WB_URL,
	"FTAN"			=> (true === method_exists($admin, "getFTAN")) ? $admin->getFTAN() : "",
	"title"			=> $post_data['title'],
	"text"			=> ($class=="post")
		? $post_data['text']
		: $database->get_one( "SELECT `text` from `".TABLE_PREFIX."mod_forum_post` where `threadid`=".$postid)
);

echo $parser->render(
	"edit_post.lte",
	$values
);

$admin->print_footer();
