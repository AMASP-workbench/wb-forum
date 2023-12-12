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

$sql = 'SELECT DISTINCT (`u`.`email`),`u`.`display_name`
			FROM `'.TABLE_PREFIX.'mod_forum_post` `p`
				INNER JOIN `'.TABLE_PREFIX.'users` `u` ON(`p`.`userid` = `u`.`user_id`)
			WHERE `threadid` = ' . $thread['threadid'] .'
			  AND `u`.`email` <> "' . (isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "") .'" ';

$res = $database->query($sql);

$poster = $author = isset($_SESSION['USERNAME']) && $_SESSION['USERNAME'] !='' ? $_SESSION['USERNAME'] : $MOD_FORUM['TXT_GUEST_F'];
$mails_ok = 0;
$mails_error = 0;
$mail_subject = WEBSITE_TITLE . ': ' . $MOD_FORUM['TXT_MAILSUBJECT_NEW_POST'];
$arr_search = array('##THREAD##', '##LINK##');
$_link = WB_URL . '/modules/forum/thread_view.php?goto='. $id['id'];
$arr_replace = array(trim($_POST['title']), $_link);

if( isset($res) AND $res->numRows() > 0)
{
	$mail_body = str_replace($arr_search, $arr_replace , $MOD_FORUM['TXT_MAILTEXT_NEW_POST'] );
	while(FORUM_SENDMAILS_ON_NEW_POSTS && $row = $res->fetchRow())
	{
		$_body = str_replace('##USERNAME##', $row['display_name'], $mail_body);
		$_body = str_replace('##POSTER##', $poster, $mail_body);
		//$wb->mail($fromaddress, $toaddress, $mail_subject, $mail_body, $fromname='')
		if ($row['email'] != FORUM_ADMIN_INFO_ON_NEW_POSTS)
			$versand = $wb->mail(FORUM_MAIL_SENDER, $row['email'], $mail_subject, $_body, FORUM_MAIL_SENDER_REALNAME);

		if ($versand) {
			$mails_ok ++;
		}else{
			$mails_error ++;
		}
	}
}// if $res
// notification to admin on new posts if address is given 
if (strpos(FORUM_ADMIN_INFO_ON_NEW_POSTS,'@') !== false && (!isset($_SESSION['EMAIL']) || FORUM_ADMIN_INFO_ON_NEW_POSTS != $_SESSION['EMAIL'])) {
	$admin_name = "ADMIN";
	$sql = 'SELECT `display_name` FROM `'.TABLE_PREFIX.'users` WHERE `email` = "' . FORUM_ADMIN_INFO_ON_NEW_POSTS .'"';
	$res = $database->query($sql);
	while($row = $res->fetchRow()) {
		$admin_name = $row['display_name'];
	}
	
	$mail_body = str_replace($arr_search, $arr_replace , $MOD_FORUM['TXT_MAILTEXT_NEW_POST_ADMIN'] );
	$mail_body = str_replace('##USERNAME##', $admin_name, $mail_body);
	$_body = str_replace('##POSTER##', $poster, $mail_body);
	$versand = $wb->mail(FORUM_MAIL_SENDER, FORUM_ADMIN_INFO_ON_NEW_POSTS, $mail_subject, $_body, FORUM_MAIL_SENDER_REALNAME);
	if ($versand) {
		$mails_ok ++;
	}else{
		$mails_error ++;
	}	
}
if ($mails_ok || $mails_error) {
	$mailing_result = '<br/>' . $mails_ok . $MOD_FORUM['TXT_MAILS_SEND_F'] . '<br/>' . $mails_error .  $MOD_FORUM['TXT_MAIL_ERRORS_F'];
}
// die ($tmp);
// die( htmlentities( nl2br($debug) ));