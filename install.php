<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!defined('WB_URL'))
{
	exit;
}

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_forum` (
  forumid int(10) unsigned NOT NULL auto_increment,
  section_id int(10) NOT NULL,
  page_id int(10) NOT NULL,
  title varchar(255) NOT NULL,
  description mediumtext NOT NULL,
  parentid int(10) unsigned NOT NULL default '0',
  displayorder int(10) unsigned NOT NULL default '0',
  lastpostinfo mediumtext NOT NULL,
  readaccess enum('reg','unreg','both') NOT NULL default 'both',
  writeaccess enum('reg','unreg','both') NOT NULL default 'both',
  PRIMARY KEY  (forumid)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_cache` (
  varname varchar(255) NOT NULL,
  section_id int(10) unsigned NOT NULL default '0',
  page_id int(10) unsigned NOT NULL default '0',
  `data` mediumtext NOT NULL,
  UNIQUE KEY `UNIQUE` (varname,section_id,page_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_post` (
  postid int(10) unsigned NOT NULL auto_increment,
  threadid int(10) unsigned NOT NULL default '0',
  username varchar(250) NOT NULL,
  userid int(10) unsigned NOT NULL default '0',
  title varchar(250) NOT NULL,
  dateline int(10) unsigned NOT NULL default '0',
  `text` mediumtext NOT NULL,
  `search_text` mediumtext NOT NULL,
  page_id int(10) unsigned NOT NULL default '0',
  section_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (postid),
  KEY threadid (threadid),
  FULLTEXT KEY Volltext (title,search_text)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_thread` (
  threadid int(10) unsigned NOT NULL auto_increment,
  user_id int(10) unsigned NOT NULL default '0',
  username varchar(250) NOT NULL,
  title varchar(250) NOT NULL,
  firstpostid int(10) unsigned NOT NULL default '0',
  lastpostid int(10) unsigned NOT NULL default '0',
  lastpost int(10) unsigned NOT NULL default '0',
  forumid int(10) unsigned NOT NULL default '0',
  `open` int(2) NOT NULL,
  replycount int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  sticky tinyint(1) NOT NULL default '0',
  page_id int(10) unsigned NOT NULL default '0',
  section_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (threadid),
  KEY titel (title),
  KEY forumid (forumid)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_settings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `section_id` smallint(6) NOT NULL,
  `FORUMDISPLAY_PERPAGE` tinyint(4) NOT NULL,
  `SHOWTHREAD_PERPAGE` tinyint(4) NOT NULL,
  `PAGENAV_SIZES` tinyint(4) NOT NULL,
  `DISPLAY_SUBFORUMS` tinyint(4) NOT NULL,
  `DISPLAY_SUBFORUMS_FORUMDISPLAY` tinyint(4) NOT NULL,
  `FORUM_USE_CAPTCHA` tinyint(4) NOT NULL,
  `ADMIN_GROUP_ID` smallint(6) NOT NULL,
  `VIEW_FORUM_SEARCH` tinyint(4) NOT NULL,
  `FORUM_MAX_SEARCH_HITS` smallint(6) NOT NULL,
  `FORUM_SENDMAILS_ON_NEW_POSTS` tinyint(4) NOT NULL,
  `FORUM_ADMIN_INFO_ON_NEW_POSTS` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_MAIL_SENDER` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_MAIL_SENDER_REALNAME` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_USE_SMILEYS` tinyint(4) NOT NULL,
  `FORUM_HIDE_EDITOR` tinyint(4) NOT NULL,
  `FORUM_USERS` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

/**
* otherworld.de
* Auskommentiert, da die Suche einen Fehler auswirft;
* es gibt auch noch keine modul eigene search.php


// STEP 2.1:	Module query info
$field_info = array();
$field_info['page_id'] = 'page_id';
$field_info['title'] = 'title';
$field_info['link'] = 'link';
$field_info = serialize($field_info);
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('module', 'forum', '$field_info')");

$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, CONCAT('test.php?do=bla&x=', [TP]mod_forum_post.threadid) AS link FROM [TP]mod_forum_post, [TP]pages WHERE ";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_start', '$query_start_code', 'forum')");

$query_body_code = "
[TP]pages.page_id = [TP]mod_forum_post.page_id AND [TP]mod_forum_post.title [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\' OR
[TP]pages.page_id = [TP]mod_forum_post.page_id AND [TP]mod_forum_post.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_body', '$query_body_code', 'forum')");
// STEP 2.4.:	Query end
$query_end_code = "";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`, `value`, `extra`) VALUES ('query_end', '$query_end_code', 'forum')");

*/

?>