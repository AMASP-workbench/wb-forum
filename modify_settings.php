<?php

/**
 *
 *  @module       Forum
 *  @version      0.6.8
 *  @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *  @license      GNU General Public License
 *  @platform     1.6.x
 *  @requirements PHP 8.1.x and higher
 *
 */

// STEP 1:	Initialize
require_once '../../config.php';
require_once WB_PATH.'/modules/admin.php';

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
require_once WB_PATH .'/framework/module.functions.php';

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

require_once __DIR__."/classes/class.forum_parser.php";
$parser = new forum_parser();

?>

<h2><?php echo $MOD_FORUM['TXT_FORUM_B'].' '.$MOD_FORUM['TXT_SETTINGS_B']; ?></h2>
<?php
// include the button to edit the optional module CSS files
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('forum');
}

// Get Settings from DB
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_forum_settings` WHERE `section_id` = '.$section_id.'';
if($query_settings = $database->query($sql)) {
	$settings = $query_settings->fetchRow();
}

/**
 *	Build group select
 */
$this_page_admins = $database->get_one("SELECT `admin_groups` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$page_id);
$this_page_admins = explode(",",$this_page_admins);

$sGroupSelectHTML = "<select name='admin_group_id' size='1' >\n<option value='0'>".$MOD_FORUM['NO_ADDITIONAL_GROUP']."</option>\n";
$query_groups = $database->query('SELECT * FROM `'.TABLE_PREFIX.'groups`');
while ($group = $query_groups->fetchRow(MYSQLI_ASSOC)){

	if (in_array( $group['group_id'], $this_page_admins)) {
		$sGroupSelectHTML .= "\n<option disabled='disabled' value='".$group['group_id']."'>".$group['name']."</option>";

	} elseif ($group['group_id'] == $settings['ADMIN_GROUP_ID']) {
		$sGroupSelectHTML .= "\n<option selected='selected' value='".$group['group_id']."'>".$group['name']."</option>";

	} else {
		$sGroupSelectHTML .= "\n<option value='".$group['group_id']."'>".$group['name']."</option>";
	}
}
$sGroupSelectHTML .= "\n</select>\n";

$sHTMLchecked = "checked='checked'";

$page_data = array(
	'WB_PATH'	=> WB_PATH,
	'WB_URL'	=> WB_URL,
	'ADMIN_URL'	=> ADMIN_URL,
	'page_id'	=> $page_id,
	'section_id'	=> $section_id,
	'FTAN'	=> (true === method_exists($admin, "getFTAN") ? $admin->getFTAN() : "" ),
	'MOD_FORUM_TXT_FORUMDISPLAY_PERPAGE_B' => $MOD_FORUM['TXT_FORUMDISPLAY_PERPAGE_B'],
	'settings_FORUMDISPLAY_PERPAGE'	=> $settings['FORUMDISPLAY_PERPAGE'],
	'MOD_FORUM_TXT_SHOWTHREAD_PERPAGE_B'	=> $MOD_FORUM['TXT_SHOWTHREAD_PERPAGE_B'],
	'settings_SHOWTHREAD_PERPAGE'	=> $settings['SHOWTHREAD_PERPAGE'],
	'MOD_FORUM_TXT_PAGENAV_SIZES_B'	=> $MOD_FORUM['TXT_PAGENAV_SIZES_B'],
	'settings_PAGENAV_SIZES_checked'	=> $settings['PAGENAV_SIZES'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_DISPLAY_SUBFORUMS_B'	=> $MOD_FORUM['TXT_DISPLAY_SUBFORUMS_B'],
	'settings_DISPLAY_SUBFORUMS_checked'	=> $settings['DISPLAY_SUBFORUMS'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_DISPLAY_SUBFORUMS_FORUMDISPLAY_B'	=> $MOD_FORUM['TXT_DISPLAY_SUBFORUMS_FORUMDISPLAY_B'],
	'settings_DISPLAY_SUBFORUMS_FORUMDISPLAY_checked'	=> $settings['DISPLAY_SUBFORUMS_FORUMDISPLAY'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_FORUM_USE_CAPTCHA_B'	=> $MOD_FORUM['TXT_FORUM_USE_CAPTCHA_B'],
	'settings_FORUM_USE_CAPTCHA_checked'	=> $settings['FORUM_USE_CAPTCHA'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_USE_SMILEYS_B'	=> $MOD_FORUM['TXT_USE_SMILEYS_B'],
	'settings_FORUM_USE_SMILEYS_checked'	=> $settings['FORUM_USE_SMILEYS'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_HIDE_EDITOR_B' => $MOD_FORUM['TXT_HIDE_EDITOR_B'],
	'settings_FORUM_HIDE_EDITOR_checked'	=> $settings['FORUM_HIDE_EDITOR'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_VIEW_FORUM_SEARCH_B'	=> $MOD_FORUM['TXT_VIEW_FORUM_SEARCH_B'],
	'settings_VIEW_FORUM_SEARCH_checked'	=> $settings['VIEW_FORUM_SEARCH'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_FORUM_MAX_SEARCH_HITS_B'	=> $MOD_FORUM['TXT_FORUM_MAX_SEARCH_HITS_B'],
	'settings_FORUM_MAX_SEARCH_HITS'	=> $settings['FORUM_MAX_SEARCH_HITS'],
	'MOD_FORUM_TXT_FORUM_SENDMAILS_ON_NEW_POSTS_B'	=> $MOD_FORUM['TXT_FORUM_SENDMAILS_ON_NEW_POSTS_B'],
	'settings_FORUM_SENDMAILS_ON_NEW_POSTS_checked' => $settings['FORUM_SENDMAILS_ON_NEW_POSTS'] == 1 ? $sHTMLchecked : "",
	'MOD_FORUM_TXT_FORUM_ADMIN_INFO_ON_NEW_POSTS_B'	=> $MOD_FORUM['TXT_FORUM_ADMIN_INFO_ON_NEW_POSTS_B'],
	'settings_FORUM_ADMIN_INFO_ON_NEW_POSTS'	=> htmlspecialchars($settings['FORUM_ADMIN_INFO_ON_NEW_POSTS']),
	'MOD_FORUM_TXT_FORUM_MAIL_SENDER_B'	=> $MOD_FORUM['TXT_FORUM_MAIL_SENDER_B'],
	'settings_FORUM_MAIL_SENDER'	=> htmlspecialchars($settings['FORUM_MAIL_SENDER']),
	'MOD_FORUM_TXT_FORUM_MAIL_SENDER_REALNAME_B'	=> $MOD_FORUM['TXT_FORUM_MAIL_SENDER_REALNAME_B'],
	'settings_FORUM_MAIL_SENDER_REALNAME'	=> htmlspecialchars($settings['FORUM_MAIL_SENDER_REALNAME']),
	'MOD_FORUM_TXT_SAVE_B'	=> $MOD_FORUM['TXT_SAVE_B'],
	'MOD_FORUM_TXT_CANCEL_B'	=> $MOD_FORUM['TXT_CANCEL_B'],

	'MOD_FORUM_TXT_ADMIN_GROUP_ID_B'	=> $MOD_FORUM['TXT_ADMIN_GROUP_ID_B'],
	'group_select'	=> $sGroupSelectHTML
);

echo $parser->render(
	"settings.lte",
	$page_data
);

$admin->print_footer();