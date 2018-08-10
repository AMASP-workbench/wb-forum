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
 
require('../../config.php');
require(WB_PATH . '/modules/admin.php');

include_once(WB_PATH .'/framework/module.functions.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

require_once( dirname(__FILE__)."/classes/class.forum_parser.php" );
$parser = new forum_parser();

$oSubway = addon\forum\classes\subway\subway::getInstance();

/**
 *  Initialize some vars
 */
$forum = NULL;

if (isset($_REQUEST['forumid'])) {
	
	$forum = $oSubway->select(
	    TABLE_PREFIX . "mod_forum_forum",
	    "*",
	    [
	        ["forumid" => intval($_REQUEST['forumid']) ],
	        ["bool"        => "and" ],
	        ["section_id"   => $section_id ],
	        ["bool"        => "and" ],
	        ["page_id"      => $page_id ]
	        
	    ],
	    false
	);
	// echo addon\forum\classes\subway\subway_tools::display( $forum );
	
	
	if ( 0 === count( $forum ) ) {
		$admin->print_error(
			$MOD_FORUM['TXT_NO_ACCESS_F'],
			ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id
		);
	}
}

require_once(WB_PATH . '/modules/forum/backend.php');

if(!function_exists("forum_str2js")) {
	function forum_str2js(&$s) {
		$a = array(
			"\\'"	=> "&apos;",
			"\""	=> "&quot;",
			'&auml;' => "%E4",
			'&Auml;' => "%C4",
			'&ouml;' => "%F6",
			'&Ouml;' => "%D6",
			'&uuml;' => "%FC",
			'&Uuml;' => "%DC",
			'&szlig;' => "%DF",
			'&euro;' => "%u20AC",
			'$' => "%24" 
		);
		$s = str_replace( array_keys($a), array_values($a), $s);
	}
}

ob_start();
print_forum_select_options( isset($forum) ? $forum['parentid'] : "" );
$forum_select_parent = ob_get_clean();

$page_values = array(
	'add_edit_titel'	=> (isset($forum['forumid']) ? $MOD_FORUM['TXT_EDIT_FORUM_B'].' - '.$forum['title'] : $MOD_FORUM['TXT_CREATE_FORUM_B']),
	'WB_URL'	=> WB_URL,
	'ADMIN_URL'	=> ADMIN_URL,
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'forum_id'		=> (isset($forum['forumid']) ? $forum['forumid'] : ''),
	'ftan'			=> (true === method_exists($admin, "getFTAN")) ? $admin->getFTAN() : "",
	'MOD_FORUM_TXT_SETTINGS_B'	=> $MOD_FORUM['TXT_SETTINGS_B'],
	'MOD_FORUM_TXT_TITLE_B'	=> $MOD_FORUM['TXT_TITLE_B'],
	'forum_title'	=> (isset($forum['title']) ? htmlspecialchars($forum['title']) : ''),
	'MOD_FORUM_TXT_DESCRIPTION_B'	=> $MOD_FORUM['TXT_DESCRIPTION_B'],
	'forum_description'	=> (isset($forum['description']) ? htmlspecialchars($forum['description']) : ''),
	'MOD_FORUM_TXT_DISPLAY_ORDER_B'	=> $MOD_FORUM['TXT_DISPLAY_ORDER_B'],
	'forum_displayorder'	=> (isset($forum['displayorder']) ? $forum['displayorder'] : ''),
	'MOD_FORUM_TXT_PARENT_FORUM_B'	=> $MOD_FORUM['TXT_PARENT_FORUM_B'],
	'forum_select_parent'	=> $forum_select_parent,
	'class_show_delete_forum'	=> ( isset($forum['forumid']) ? 'show_delete_forum' : 'hide_delete_forum' ),
	'MOD_FORUM_TXT_DELETE_B'	=> $MOD_FORUM['TXT_DELETE_B'],
	'MOD_FORUM_TXT_DELETE_FORUM_B'	=> $MOD_FORUM['TXT_DELETE_FORUM_B'],
	
	'MOD_FORUM_TXT_PERMISSIONS_B'	=> $MOD_FORUM['TXT_PERMISSIONS_B'],
	'MOD_FORUM_TXT_READ_B'	=> $MOD_FORUM['TXT_READ_B'],
	'MOD_FORUM_TXT_REGISTRATED_B'	=> $MOD_FORUM['TXT_REGISTRATED_B'],
	'MOD_FORUM_TXT_NOT_REGISTRATED_B'	=> $MOD_FORUM['TXT_NOT_REGISTRATED_B'],
	'MOD_FORUM_TXT_BOTH_B'		=> $MOD_FORUM['TXT_BOTH_B'],
	
	'forum_readaccess_reg_selected'	=> (isset($forum['readaccess']) && $forum['readaccess'] == 'reg' ? 'selected="selected"' : ''),
	'forum_readaccess_unreg_selected'	=> (isset($forum['readaccess']) && $forum['readaccess'] == 'unreg' ? ' selected="selected"' : ''),
	'forum_readaccess_both_selected'	=> (isset($forum['readaccess']) && $forum['readaccess'] == 'both' ? ' selected="selected"' : ''),
	
	'MOD_FORUM_TXT_WRITE_B'	=> $MOD_FORUM['TXT_WRITE_B'],
	'forum_writeaccess_reg_selected'	=> (isset($forum['writeaccess']) && $forum['writeaccess'] == 'reg' ? ' selected="selected"' : ''),
	'forum_writeaccess_unreg_selected'	=> (isset($forum['writeaccess']) && $forum['writeaccess'] == 'unreg' ? ' selected="selected"' : ''),
	'forum_writeaccess_both_selected'	=> (isset($forum['writeaccess']) && $forum['writeaccess'] == 'both' ? ' selected="selected"' : ''),
	
	'MOD_FORUM_TXT_SAVE_B'	=> $MOD_FORUM['TXT_SAVE_B'],
	'MOD_FORUM_TXT_RESET_B'	=> $MOD_FORUM['TXT_RESET_B'],
	'MOD_FORUM_TXT_CANCEL_B'	=> $MOD_FORUM['TXT_CANCEL_B'],
);

echo $parser->render(
	'add_edit_forum.lte',
	$page_values
);

	
	if( NULL === $forum )
	{
	    //$admin->print_footer();
	    return 0;
	}
	
	$query = "SELECT * FROM `".TABLE_PREFIX."mod_forum_thread` WHERE `section_id`=".$section_id." AND `page_id`=".$page_id." AND `forumid`=".$forum['forumid']." ORDER BY `threadid` DESC";
	$result = $database->query( $query );
	if( true === $database->is_error() )
	{
	    die($database->get_error());
    }
    
	if(0 === $result->numRows())
	{
	    $admin->print_footer();
	    return 0;
	}
	$edit_link = WB_URL."/modules/forum/edit_post.php";

?>
<p></p>
<h3>List of postings</h3>
<form id="forum_<?php echo $section_id; ?>" class="forum" action="<?php echo WB_URL; ?>/modules/forum/insertupdate_forum.php" method="post">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="forumid" value="<?php echo $forum['forumid']; ?>" />
<input type="hidden" name="postid" value="-1" />
<input type="hidden" name="class" value="-1" />
<input type="hidden" name="ts_val" value="<?php echo time(); ?>" />
<input type="hidden" name="job_" value="del" />
<?php echo (true === method_exists($admin, "getFTAN")) ? $admin->getFTAN() : ""; ?>

<ul class="forum_list_postings">

<?php

	$row_template = "
	<li class='{{ class }}'>
		<div class='forum_list_action'>
			<!--<a href='#'><img class='f_action' src='".THEME_URL."/images/modify_16.png' alt='' title='edit'></a>-->
			<a href='#' onclick=\"delete_thread('forum_".$section_id."',{{ id }},'{{ title }}','{{ class }}','{{ lang }}');\"><img class='f_action' src='".THEME_URL."/images/delete_16.png' alt='' title='delete'></a>
		</div>
		<div class='forum_list_id'>[ {{ id }} ]</div>
		<div class='forum_list_date'>{{ date }}</div>
		<div class='forum_list_title'><a href='#' onclick=\"edit_post('forum_".$section_id."',{{ id }},'{{ title }}','{{ class }}','".$edit_link."');\" >{{ title }}</a></div>
		
	</li>";
	
	
	while($temp_post = $result->fetchRow()) {
		forum_str2js( $temp_post['title'] );
		$t = array(
			'{{ class }}' => "thread",
			'{{ lang }}'	=> LANGUAGE,
			'{{ id }}' => $temp_post['threadid'],
			'{{ date }}' => date("Y-m-d - H:i:s",$temp_post['dateline']),
			'{{ title }}'	=> $temp_post['title']
		);
		echo str_replace( array_keys($t), array_values($t), $row_template );
		
		/**
		 *	postings zu dem faden
		 */
		$sub_query = "SELECT * FROM `".TABLE_PREFIX."mod_forum_post` WHERE `section_id`=".$section_id." AND `page_id`=".$page_id." AND `threadid`=".$temp_post['threadid']." ORDER BY `postid`";
		$sub_result = $database->query( $sub_query );
		if( true === $database->is_error() ) die($database->get_error());
		
		while($sub_post = $sub_result->fetchRow()) {
			forum_str2js($sub_post['text']);
			forum_str2js($sub_post['title']);
			$t = array(
				'{{ class }}' => "post",
				'{{ lang }}'	=> LANGUAGE,
				'{{ id }}' => $sub_post['postid'],
				'{{ date }}' => date("Y-m-d - H:i:s",$sub_post['dateline']),
				'{{ title }}'	=> substr($sub_post['text'],0,30)
			);
			echo str_replace( array_keys($t), array_values($t), $row_template );
		
		}
	}
?>
</ul>
</form>
<?php
    $admin->print_footer();
?>