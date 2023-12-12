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

$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_forum`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_cache`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_post`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_thread`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_settings`");
