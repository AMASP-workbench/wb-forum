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

$module_directory   = 'forum';
$module_name        = 'Forum';
$module_function    = 'page,preinit';
$module_version     = '0.6.7';
$module_platform    = '1.6';
$module_license     = 'GNU General Public License';
$module_author      = 'Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic, florian (last)';
$module_home        = 'http://addon.websitebaker.org/pages/en/browse-add-ons.php?type=1';
$module_guid        = '44CF11ED-D38A-4B51-AF80-EE95F7C4C00D';
$module_description = 'This module integrates a simple forum on your website.<br/>';

/**
 *  0.6.8   - Add preinit ans additional class.
 *  0.6.7   - fixes for WBCE 1.6.1 and PHP 8.2.
 *  0.6.6   - fixes for PHP8.1 (Bernd)
 *  0.6.4   - fix issues with rights to post / edit / delete (2nd try)
 *  0.6.3   - fix issues with rights to post / edit / delete
 *  0.6.2   - remove forgotten debug output/exit
 *  0.6.1   - fixed double-escaped POST data in content.php
 *
 *  0.6     - added backticks to SQL statements
 *          - some layout tweaks
 *          - removed paranthesis from include* and require*
 *          - removed closing ?> from end-of-file
 *          - reformatted some files using CSFixer
 *          - added missing $admin->print_footer();
 *
 *  0.5.11    - fixes for MySQL-Strict (Bernd)
 *
 *  0.5.10    - Bugfixes inside installer
 *
 *  0.5.9   - Codeadditions in the backend
 *          - Add readme to the project (thanks to Tomno399 and EvaKi)
 *          - Rework forum-search
 *          - Start using templates (backend first).
 *
 *  0.5.8   - Codeadditions for the backend
 *          - Set all files to utf-8
 *          - Update headers
 *
 *  0.5.7    - Bugfix for missing var in "content.php" while editing
 *             post in frontend.
 *          - Add missing constructor to class class_forumcache.php.
 *          - Codechanges/Bugfixes for WB 2.8.3 SP6 and PHP7
 *          - Add missing files to the Project
 *
 *  0.5.6   - Upgrade and codechanges for WebsiteBaker 2.8.3 SP3 - (4.q 2014)
 *          - Add external Changelog.
 *          - Add missing license var.
 *          - Try to set the 'module_home' link to the WebsiteBaker
 *             AddOn repository.
 *          - Remove/change deprecated mysql_xxx (PHP-)function calls.
 *
 *  0.5.5    - Codechanges in add.php
 *
 *  Original Text prior 0.5.5 see changelog.md
 */
