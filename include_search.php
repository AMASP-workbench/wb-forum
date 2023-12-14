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

require_once WB_PATH . '/modules/forum/config.php';
require_once WB_PATH . '/modules/forum/functions.php';

// global $database;

require_once __DIR__."/classes/class.subway.php";
$subway = new subway();

$search_string = strip_tags($database->escapeString($_GET['mod_forum_search']));

/**
 *	Storage for all "hits"
 */
$all_posts = array();

if (!empty($search_string))
{
	/**
	 *	Build the search-string
	 */
	$temp = explode(" ", $search_string);
	$sSearch = "'%".implode("%' OR '%", $temp)."%'";
	
	$sql = "SELECT `threadid`,`postid`,`title`,`text`,`section_id`,`page_id` FROM `".TABLE_PREFIX."mod_forum_post` WHERE `section_id`=".$section_id;
	$sql .= " AND ((`text` LIKE ".$sSearch.") OR (`title` LIKE ".$sSearch."))";

	$res = $subway->get_all($sql, $all_posts);
	
	if($subway->db->is_error()) {
		echo $subway->db->get_error();
		return 0;
	}
	
	// echo $subway->display($all_posts);
	
}

$out = "";

if(count($all_posts) > 0)
{
		
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_SEARCH_RESULT_F'] . ' ( '. count($all_posts) .' '.$MOD_FORUM['TXT_HITS_F'].' )</h3>';

		foreach($all_posts as &$f)
		{
			$owd_link = '<a href="'. WB_URL.'/modules/forum/thread_view.php?goto=' . $f['postid']. '">';

			// und einen "weiter"-Link bauen, kann man auch noch brauchen
			//$owd_link_full = $owd_link . $MOD_FORUM['TXT_READMORE_F'] .'</a>';


			// output zusammenschrauben:
			$out .= '<div class="mod_forum_hits">' . $owd_link;
				// $out .= $MOD_FORUM['TXT_FORUM_B'] . ':  <span class="mod_forum_hits_forum">'. $f['forum'] . '</span> &raquo; ';
				$out .= $MOD_FORUM['TXT_FORUM_B'] . ':  <span class="mod_forum_hits_forum">'. $f['threadid'] . '</span> &raquo; ';
				$out .= $MOD_FORUM['TXT_THEME_F'] . ': <span class="mod_forum_hits_title">'. highlightPhrase( $f['title'], $search_string) . '</span>';
			$out .= '</a></div>';

				// BB Code entfernen
				$text = strip_bbcode($f['text']);

				//Filter droplets from the page data
				preg_match_all('~\[\[(.*?)\]\]~', $text, $matches);

				foreach ($matches[1] as $match){
					$text = str_replace('[['.$match.']]', '', $text);
				}

				// highlightning
				$text = highlightPhrase( $text, $search_string );
				$text2 = buildPreview ( $text, $search_string, 120 );

				empty($text2) ? $text2 = owd_mksubstr($text, 80) : '';

			$out .= '<p class="mod_last_forum_entries_text">'. $text2 . '</p><br/><br/>' ;

		}

		$out .= '</div>';

} else {

	if ($search_string == '') {
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_SEARCH_STRING_F'] . '</h3>';
		$out .= '</div>';
	}else{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_HITS_F'] . '</h3>';
		$out .= '</div>';
	}

}

?>

<h1>Forum durchsuchen</h1>

<?php
include_once WB_PATH . '/modules/forum/include_searchform.php';
echo $out;
