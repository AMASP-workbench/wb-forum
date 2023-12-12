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

/**
 * This function will be called by the search function and returns the results
 * via print_excerpt2() for the specified SECTION_ID
 *
 * @param array $search
 * @return boolean
 */
function forum_search($search) {
	
	require_once __DIR__."/classes/class.subway.php";
	$subway = new subway();

	$all_results = array();
	$subway->get_all(
		"SELECT * FROM `".TABLE_PREFIX."mod_forum_post`  WHERE `section_id` ='{$search['section_id']}'",
		$all_results
	);
  	
  	$result = true;
  	
  	foreach($all_results as $found_item) {
  	
  		$page_info = $subway->get_pageinfo( $found_item['page_id'] );
  		 
  		$item = array(
        	'page_link' => WB_URL."/modules/forum/thread_view.php?goto=".$found_item['threadid'],
        	'page_link_target' => "",
        	'page_title' => $page_info['page_title'],
        	'page_description' => $found_item['title'], // $found_item['text']
        	'page_modified_when' => $found_item['dateline'],
        	'page_modified_by' => 1,
        	'text' => $found_item['text'],
        	'max_excerpt_num' => $search['default_max_excerpt']
  		);
  		if ( print_excerpt2($item, $search)) $result = true;
  	}
	
	return $result;
}