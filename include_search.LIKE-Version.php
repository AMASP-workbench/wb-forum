<?php

/**
 *
 *    @module       Forum
 *    @version      0.6.8
 *    @authors      Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *    @license      GNU General Public License
 *    @platform     1.6.x
 *    @requirements PHP 8.1.x and higher
 *
 */

defined('WB_PATH') OR header('Location: ../../index.php');

include WB_PATH . '/modules/forum/config.php';
include WB_PATH . '/modules/forum/functions.php';

global $database;

//var_dump($_POST);
$search_string = strip_tags( mysql_real_escape_string($_POST['mod_forum_search']));


if (!empty($search_string))
{
	$sql = "SELECT f.title as forum,
				   p.*

			FROM ".TABLE_PREFIX."mod_forum_post p
				JOIN  ".TABLE_PREFIX."mod_forum_thread t USING(threadid)
				JOIN  ".TABLE_PREFIX."mod_forum_forum f ON (t.forumid = f.forumid)

			WHERE f.title LIKE '%$search_string%' OR
				  p.title LIKE '%$search_string%' OR
				  p.text LIKE '%$search_string%'";

		// WHERE MATCH (p.title,p.text) AGAINST ('$search_string')

	$res = $database->query($sql);
}



if( isset($res) AND $res->numRows() > 0)
{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_SEARCH_RESULT_F'] . ' ( '. $res->numRows() .' '.$MOD_FORUM['TXT_HITS_F'].' )</h3>';

		while($f = $res->fetchRow())
		{
			$out .= '<p class="mod_last_forum_entries_forum">'. $f['forum'] . '</p>';
			$out .= '<p class="mod_last_forum_entries_title">'. highlightPhrase( $f['title'], $search_string) . '</p>';

				// BB Code entfernen
				$text = strip_bbcode($f['text']);

				//Filter droplets from the page data
				preg_match_all('~\[\[(.*?)\]\]~', $text, $matches);

				foreach ($matches[1] as $match){
					$text = str_replace('[['.$match.']]', '', $text);
				}

				// highlightning
				$text = highlightPhrase( $text, $search_string );
				$text = buildPreview ( $text, $search_string, 120 );

			$out .= '<p class="mod_last_forum_entries_text">'. $text  . '</p>';
			$out .= '<span class="mod_last_forum_entries_link"><a href="'.
				WB_URL.'/modules/forum/thread_view.php?' .
				'sid='.$f['section_id'].
				'&amp;pid='.$f['page_id'].
				'&amp;tid='.$f['threadid'].
				'#post'. $f['postid'].
				'">'.$MOD_FORUM['TXT_READMORE_F'] .'</a></span>';

		}//while

		$out .= '</div>';

}else{

	if ($search_string == '') {
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_SEARCH_STRING_F'] . '</h3>';
		$out .= '</div>';
	}else{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_HITS_F'] . '</h3>';
		$out .= '</div>';
	}

}//else treffer

?>

<h1>Forum durchsuchen</h1>

<?php include WB_PATH . '/modules/forum/include_searchform.php' ?>

<?php echo $out ?>