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

global $database;

// insert settings if not exist
$sql = "SELECT * from `".TABLE_PREFIX."mod_forum_settings` WHERE `section_id` = ".$section_id;
$query_settings = $database->query($sql);
if ($query_settings === false || $query_settings->numRows()  == 0) {
	$sql = "INSERT INTO `".TABLE_PREFIX."mod_forum_settings` VALUES(0,".$section_id.", 5, 5, 0, 1, 1, 1, 1, 1, 30, 0, '', 'admin@admin.de', 'WEBSite Forum', 1, 0, '')";
	$database->query($sql);
}

// Get Settings from DB
$sql = "SELECT * from `".TABLE_PREFIX."mod_forum_settings` WHERE `section_id` = ".$section_id;
$query_settings = $database->query($sql);
$settings = $query_settings->fetchRow( MYSQLI_ASSOC );

// Einträge, die in der Themenübersicht je Seite angezeigt werden sollen
define('FORUMDISPLAY_PERPAGE', $settings['FORUMDISPLAY_PERPAGE']);

// Einträge, die je Seite in einem Thema angezeigt werden sollen
define('SHOWTHREAD_PERPAGE', $settings['SHOWTHREAD_PERPAGE']);

// Legt fest, ob für die Zahlen in der Seitennavigation verschiedene Schriftgröﬂen verwendet werden sollen
define('PAGENAV_SIZES', $settings['PAGENAV_SIZES']);

// Unterforen auf der Foren-Startseite anzeigen?
define('DISPLAY_SUBFORUMS', $settings['DISPLAY_SUBFORUMS']);

// Unterforen in der Themenübersicht anzeigen?
define('DISPLAY_SUBFORUMS_FORUMDISPLAY', $settings['DISPLAY_SUBFORUMS_FORUMDISPLAY']);

// Sollen für Gäste Captchas zum schreiben verwendet werden?
define('FORUM_USE_CAPTCHA', $settings['FORUM_USE_CAPTCHA']);

// ID der Gruppe der Administratoren (Dürfen Beiträge + Themen ändern/löschen)
define('ADMIN_GROUP_ID', $settings['ADMIN_GROUP_ID']);

// Soll das Suchformular angezeigt werden ?
define('VIEW_FORUM_SEARCH', $settings['VIEW_FORUM_SEARCH']);

// max. Ausgabe von x Treffern in der Suchfunktion
define('FORUM_MAX_SEARCH_HITS', $settings['FORUM_MAX_SEARCH_HITS']);

// sollen Mails versendet werden, wenn neue Posts eingehen?
define('FORUM_SENDMAILS_ON_NEW_POSTS', $settings['FORUM_SENDMAILS_ON_NEW_POSTS']);

// Diese Adresse bei neuen Beiträgen informieren?'
define('FORUM_ADMIN_INFO_ON_NEW_POSTS', $settings['FORUM_ADMIN_INFO_ON_NEW_POSTS']);

// Sender of notification emails on new posts
define('FORUM_MAIL_SENDER', $settings['FORUM_MAIL_SENDER']);

// Sender's name
define('FORUM_MAIL_SENDER_REALNAME', $settings['FORUM_MAIL_SENDER_REALNAME']);

// use smileys
define('FORUM_USE_SMILEYS', $settings['FORUM_USE_SMILEYS']);

// show hide/unhide button instead of post editor
define('FORUM_HIDE_EDITOR', $settings['FORUM_HIDE_EDITOR']);

// remember user data
define('FORUM_USERS', $settings['FORUM_USERS']);
