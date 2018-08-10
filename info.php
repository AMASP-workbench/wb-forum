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

$module_directory	= 'forum';
$module_name		= 'Forum';
$module_function	= 'page';
$module_version		= '0.5.10';
$module_platform	= '2.11';
$module_license		= 'GNU General Public License';
$module_author		= 'Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)';
$module_home		= 'http://addon.websitebaker.org/pages/en/browse-add-ons.php?type=1';
$module_guid		= '44CF11ED-D38A-4B51-AF80-EE95F7C4C00D';
$module_description	= 'This module integrates a simple forum on your website.<br/>';

/**
 *	Detailed changelog at: https://github.com/AMASP-workbanch/wb-forum/commits/master
 *
 *	0.5.10	- Bugfixes inside installer
 *
 *	0.5.9	- Codeadditions in the backend
 *			- Add readme to the project (thanks to Tomno399 and EvaKi)
 *			- Rework forum-search
 *			- Start using templates (backend first).
 *
 *	0.5.8	- Codeadditions for the backend
 *			- Set all files to utf-8
 *			- Update headers
 *
 *	0.5.7	- Bugfix for missing var in "content.php" while editing post in frontend.
 *			- Add missing constructor to class class_forumcache.php.
 *			- Codechanges/Bugfixes for WB 2.8.3 SP6 and PHP7
 *			- Add missing files to the Project
 *
 *	0.5.6	- Upgrade and codechanges for WebsiteBaker 2.8.3 SP3 - (4.q 2014)
 *			- Add external Changelog.
 *			- Add missing license var.
 *			- Try to set the 'module_home' link to the WebsiteBaker AddOn repository.
 *			- Remove/change deprecated mysql_xxx (PHP-)function calls.
 *
 *	0.5.5	- Codechanges in add.php
 *
 *	Original Text prior 0.5.5 see changelog.md
 *
 */
?>