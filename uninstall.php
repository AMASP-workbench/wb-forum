<?php

/**
 *
 *  @module       Forum
 *  @version      0.6.8
 *  @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Bianka Martinovic, Dietrich Roland Pehlke (last)
 *  @license      GNU General Public License
 *  @platform     1.6.x
 *  @requirements PHP 8.1.x and higher
 *
 */

defined('WB_PATH') OR header('Location: ../../index.php');

$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_forum`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_cache`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_post`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_thread`");
$database->query("DROP TABLE `" . TABLE_PREFIX . "mod_forum_settings`");
