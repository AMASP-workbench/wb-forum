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

function fetch_fontsize_from_page($pagenum, $selpage)
{
    $font_size = 10;

    if (PAGENAV_SIZES == false)
    {
        return 'style="font-size: '.$font_size.'pt;"';
    }

    if ($pagenum == $selpage)
    {
        $font_size = 12;
        return 'style="font-size: '.$font_size.'pt;"';
    }
    else if (($pagenum - 1) == $selpage OR ($pagenum + 1) == $selpage)
    {
        $font_size = 10;
    }
    else if (($pagenum - 2) == $selpage OR ($pagenum + 2) == $selpage)
    {
        $font_size = 9;
    }
    else if (($pagenum - 3) == $selpage OR ($pagenum + 3) == $selpage)
    {
        $font_size = 8;
    }

    return 'style="font-size: '.$font_size.'pt;"';
}

/**
 * v0.6
 *
 * @access public
 * @return bool
 **/
function has_access(array $forum): bool
{
    global $wb;
    if (!(
                $forum['readaccess'] == 'both'
            || ($forum['readaccess'] == 'reg'   &&  $wb->get_user_id())
            || ($forum['readaccess'] == 'unreg' && !$wb->get_user_id())
        )
    ) {
        return false;
    }
    return true;
}

/**
 * mod otherworld.de
 * $arrLevel mit global eingebunden wird in modify.php als ergebnis von
 *             SELF::getForumLevel bereitgestellt
 *
 */

function print_forums($parentid, $level = 0)
{
    global $forum_array, $section_id, $page_id, $arrLevel;

    if (!empty($forum_array[$parentid]))
    {
        foreach ($forum_array[$parentid ] AS $forumid => $forum)
        {

            echo '<li class="mod_forum_forum_level'.$arrLevel[$forumid].'">';
            echo '<a href="' . WB_URL . '/modules/forum/addedit_forum.php?page_id=' . $page_id . '&amp;section_id=' . $section_id . '&amp;forumid=' . $forumid . '">' . htmlspecialchars($forum['title']) . '</a>';
            if (!empty($forum_array["$forumid"]))
            {
                echo '<ul class="forum_list">';
                    print_forums($forumid, $level);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}

function getForumLevel($parentid = 0, $level = 1)
{
    global $database, $section_id, $page_id;

    static $out;

    $forumcache = [];
    $res = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_cache` WHERE `section_id` = '".$section_id."' AND `page_id` = '".$page_id."'");

    while ($cache_entry = $res->fetchRow( MYSQLI_ASSOC )) {
        ${$cache_entry['varname']} = unserialize($cache_entry['data']);
    }

    $iforumcache = [];
    foreach ($forumcache AS $forumid => $f) {
        $iforumcache[$f['parentid']][ $forumid ] = $forumid;
    }

    if (!empty($iforumcache[ $parentid ]))
    {

        foreach ($iforumcache[ $parentid ] AS $forumid)
        {
            $out[$forumid] = $level;

            if (!empty($iforumcache[ $forumid ]))
            {
                getForumLevel($forumid, ($level + 1));
            }
        }
    }

   return $out;

}

function print_forum_select_options($selectedforum, $parentid = 0, $level = 1)
{
    global $forumcache, $iforumcache, $section_id, $page_id;

    if (!empty($iforumcache["$parentid"]))
    {
        foreach ($iforumcache["$parentid"] AS $forumid)
        {
            $forum =& $forumcache["$forumid"];

            echo '<option value="' . $forumid . '"' . ($forumid == $selectedforum ? ' selected="selected"' : '') . '>' . construct_forum_depth_prefix($level) . htmlspecialchars($forum['title']) . "</option>\n";
            if (!empty($iforumcache["$forumid"]))
            {
                print_forum_select_options($selectedforum, $forumid, ($level + 1));
            }
        }
    }
}

function get_forum_overview($selectedforum, $parentid = 0, $level = 1)
{
    global $forumcache, $iforumcache, $section_id, $page_id;

    if (!empty($iforumcache["$parentid"]))
    {
        foreach ($iforumcache["$parentid"] AS $forumid)
        {
            $forum =& $forumcache["$forumid"];

            echo '<option value="' . $forumid . '"' . ($forumid == $selectedforum ? ' selected="selected"' : '') . '>' . construct_forum_depth_prefix($level) . htmlspecialchars($forum['title']) . "</option>\n";
            if (!empty($iforumcache["$forumid"]))
            {
                print_forum_select_options($selectedforum, $forumid, ($level + 1));
            }
        }
    }
}

function construct_forum_depth_prefix($level)
{
    $string = "";
    for ($i = 1; $i < $level; $i++)
    {
        $string .= '-- ';
    }

    return $string;
}

function parse_text($text)
{
    $smilepath = WB_URL . '/modules/forum/images/smile/';
    $smile_characters = [
        '(B)',
        '(b)',
        ':D',
        ':d',
        ':?',
        '=)',
        '(8)',
        ':0:',
        '(j)',
        '(J)',
        ':(',
        ':@',
        ':wall:',
        ':pray:',
        ':joint:',
        ':puke:',
        ':rolleyes:',
        ':)',
        ':P',
        ':p',
        ';)',
        ':-)',
        ';-)'
    ];

    $smile_images = [
        $smilepath . 'bier.gif',
        $smilepath . 'bier.gif',
        $smilepath . 'biggrin.gif',
        $smilepath . 'biggrin.gif',
        $smilepath . 'confused.gif',
        $smilepath . 'duivel.gif',
        $smilepath . 'gemeen.gif',
        $smilepath . 'hypocrite.gif',
        $smilepath . 'joint.gif',
        $smilepath . 'joint.gif',
        $smilepath . 'mad.gif',
        $smilepath . 'mad.gif',
        $smilepath . 'muur.gif',
        $smilepath . 'pray.gif',
        $smilepath . 'joint.gif',
        $smilepath . 'puke.gif',
        $smilepath . 'rolleyes.gif',
        $smilepath . 'smile.gif',
        $smilepath . 'tongue.gif',
        $smilepath . 'tongue.gif',
        $smilepath . 'wink.gif',
        $smilepath . 'smile.gif',
        $smilepath . 'wink.gif'
    ];

    foreach ($smile_images AS $key => $val)
    {
        $smile_images["$key"] = '<img src="' . $val . '" alt="" title=""/>';
    }

    return str_replace($smile_characters, $smile_images, $text);
}

function parse_bbcode($text, $quote)
{
    // BBCode to find...
    $in = [
        '/\[list\](.*?)\[\/list\]/ms'    => '<ul>\1</ul>',    //    #1
        '/\[list=([0-9]{1,})\](.*?)\[\/list\]/ms'    => '<ol class="with_counter" start="\1">\2</ol>',    //    #1.2

        '/\[li\](.*?)\[\/li\]/ms'        => '<li>\1</li>',    //    #2

        '/\[\*\](.*?)[\r|\n]/ms'        => '<li>\1</li>',    //    #3

        '/\[code\](.*?)\[\/code\]/ms'    => '<pre class="forum_code">\1</pre>',    //    #4

        '/\[img\](.*?)\[\/img\]/ms'    => '<img src="\1" alt="\1"/>',    //    #5

        '/\[b\](.*?)\[\/b\]/ms'            => '<strong>\1</strong>',
        '/\[i\](.*?)\[\/i\]/ms'            => '<em>\1</em>',
        '/\[u\](.*?)\[\/u\]/ms'            => '<u>\1</u>',
        '/\[s\](.*?)\[\/s\]/ms'            => '<strike>\1</strike>',
        '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms'    => '<a href="\1">\2</a>',
        '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms'    => '<span style="font-size: \1%;">\2</span>',
        '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms'    => '<span style="color: \1;">\2</span>',
        '/\[quote](.*?)\[\/quote\]/ms'    => '<fieldset style="background-color: #EEE; padding: 0 4px 2px; font-style: italic;"><legend>'.$quote.'</legend>\1</fieldset>'
    ];

    return nl2br(
        preg_replace(
            array_keys($in),
            array_values($in),
            $text
        )
    );
}

/**
 * strip_bb
 * otherworld.de
 * für die Vorschau brauchen wir TAG freie zeichen, denn wir wollen den text
 * nicht komplett anzeigen. daher laufen wir gefahr, tags nicht zu schlieﬂen, so
 * dass es uns unser gesamt-layout um die ohren haut.
 */

function strip_bbcode($text) {
    // BBCode to find...
    $in = array(
        '/\[list\](.*?)\[\/list\]/ms'    => '\1',    // #1
        '/\[list=[0-9]{1,}\](.*?)\[\/list\]/ms'    => '\1',    //    #1.2
        '/\[li\](.*?)\[\/li\]/ms'        => '\1',    // #2

        '/\[\*\](.*?)[\r|\n]/ms'        => '\1',    // #3

        '/\[code\](.*?)\[\/code\]/ms'    => '\1',    // #4

        '/\[img\](.*?)\[\/img\]/ms'        => '\1',        // #5

        '/\[b\](.*?)\[\/b\]/ms'            => '\1',
        '/\[i\](.*?)\[\/i\]/ms'            => '\1',
        '/\[u\](.*?)\[\/u\]/ms'            => '\1',
        '/\[s\](.*?)\[\/s\]/ms'            => '',
        '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms'    => '\2',
        '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms'    => '\2',
        '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms'    => '\2',
        '/\[quote](.*?)\[\/quote\]/ms'    => ''    // strip quotet text?
    );

    return preg_replace(
        array_keys($in),
        array_values($in),
        $text
    );
}

/**
 * Chops a string to the desired length chopping at a space.
 * errors on the side of shorter length, kinda like word wrap
 * but it cuts. This is useful for preview display purposes
 * used frequently when linking to the full description.
 *
 * @author ???
 * @param string  String to be shortened
 * @param integer  desired maximum length
 * @return string shortened string
 */
function owd_mksubstr($str, $len)
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
        $str = substr($str,0,$spc_pos) . ' &hellip;';

    return $str;
}

/**
 * Q: selfphp.de
 *
 */

function highlightPhrase ( $strHaystack, $strNeedle, $strColor = '', $bCase = FALSE )
{
    if ( empty ( $strColor ) )
    {
        // Standardfarbe: gelb
        $strColor = 'yellow';
    }

    $strModifier = '';
    if ( $bCase )
    {
        // Modifikator "i": Groﬂ- und Kleinschreibung ignorieren.
        $strModifier = 'i';
    }

    $strQuotedNeedle = preg_quote ( $strNeedle, '/' );

    $strPattern = '/' . $strQuotedNeedle . '/' . $strModifier;
    $strReplacement = '<span style="background-color: ' . $strColor . ';">$0</span>';

    return preg_replace ( $strPattern, $strReplacement, $strHaystack );
}

/**
 * Q: selfphp.de
 *
 */
function buildPreview ($strHaystack, $strNeedle, $strLength = 120, $bCase = TRUE )
{
    if (empty($strLength))
    {
        $strLength = 120;
    }

    $strModifier = '';
    if ($bCase)
    {
        // Modifikator "i": Groﬂ- und Kleinschreibung ignorieren.
        $strModifier = 'i';
    }

    $strQuotedNeedle = preg_quote ( $strNeedle, '/' );

    $strPattern = '/.{0,' . $strLength . '}' . $strQuotedNeedle .
                  '.{0,' . $strLength . '}/s' . $strModifier;

    $arMatches = [];

    if ( !preg_match($strPattern, $strHaystack, $arMatches))
    {
        return FALSE;
    }

    return $arMatches[0];
}
