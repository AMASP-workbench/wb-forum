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

require_once WB_PATH . '/modules/forum/config.php';

if (!defined('SKIP_CACHE')) {
    $forumcache = array();
    $cache = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_cache` WHERE `section_id` = '".$section_id."' AND `page_id` = '".$page_id."'");
    if ($cache) {
	while ($cache_entry = $cache->fetchRow()) {
		${$cache_entry['varname']} = unserialize($cache_entry['data']);
	}
	$iforumcache = array();
        if (is_array($forumcache)) {
            foreach ($forumcache as $forumid => $f) {
                if(!isset($iforumcache[$f['parentid']])) {
                    $iforumcache[$f['parentid']] = array();
                }
                $iforumcache[$f['parentid']]["$forumid"] = $forumid;
            }
        }
	}
}

require_once WB_PATH . '/modules/forum/functions.php';

$user_id = (
    isset($_SESSION['USER_ID'])
	? $_SESSION['USER_ID']
	: ''
);

$user = $database->query("SELECT * FROM `" . TABLE_PREFIX . "users` WHERE `user_id` = '" . $user_id . "'");

if ($user) {
	$user = $user->fetchRow();
} else {
	$user = null;
}
