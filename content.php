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

defined('WB_PATH') or header('Location: ../../index.php');

/**
 *        Load Language file
 */
$lang = __DIR__."/languages/". LANGUAGE .".php";
require_once(!file_exists($lang) ? __DIR__."/languages/EN.php" : $lang);

require_once __DIR__."/classes/class.subway.php";
$subway = new subway();

$username = "";

global $post, $user, $forum, $thread, $page_id, $section_id, $forumcache, $iforumcache;

echo '<script src="frontend.js" type="text/javascript"></script>';

// [1]
echo forum::getInstance()->getFrontendTemplateCSS();

/**
 *	Test the user
 */
$user_can_create_topic = false;
$user_can_create_answer = false;
$user_can_edit = false;
$user_allowed_to_write = false;

$temp_user = $wb->get_user_id();
$temp_user_group = $wb->get_group_id();

if ($temp_user) {
    $temp_groups = explode(",", $wb->page['admin_groups']);
    $user_can_create_topic = in_array($temp_user_group, $temp_groups);
    $user_can_create_answer = in_array($temp_user_group, $temp_groups);
    $user_can_edit = in_array($temp_user_group, $temp_groups);
}
if ($temp_user == ADMIN_GROUP_ID) {
    $user_can_edit = true;
}

/**
 *	Guest are allowed to write?
 */
if (($forum['writeaccess'] == 'unreg') || ($forum['writeaccess'] == 'both')) {
    $user_can_create_answer = true;
    $user_can_create_topic = true;
}

// ####################### EDIT POST (SEARCH) ########################
if (FORUM_DISPLAY_CONTENT == 'search_the_forum')
{
    //include_once('include.search.php');
}
// ####################### DISPLAY CONTENTS OF A FORUM #########################
elseif (FORUM_DISPLAY_CONTENT == 'view_forum') {
    $perpage = FORUMDISPLAY_PERPAGE;
    if (!has_access($forum)) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 10]",
            "';history.back();'"
        );
        return 0;
    } else {
        include 'pagination.php';

        $home_link = WB_URL.PAGES_DIRECTORY.$wb->page['link'].PAGE_EXTENSION;
        $page_link = WB_URL.'/modules/forum/forum_view.php';
        $query = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_forum_forum` WHERE `forumid` = '". $forum['parentid']."'");

        $parent = $query->fetchRow();
?>

		<input type="hidden" name="forum_ts" value="<?php $t=time(); echo $t; $_SESSION['forum_ts']=$t; ?>" />
		<div class="thread_head">
			<div class="forum_head_home"><a href="<?php echo $home_link.'">'.PAGE_TITLE; ?></a></div>
<?php
        if ($parent['parentid'] == 0) {
?>
			<div class="thread_head_parent"><?php echo $parent['title']; ?></div>
<?php
        } else {
?>
				<div class="thread_head_parent"><a href="<?php echo $page_link.'?sid='.$section_id.'&pid='.$page_id.'&fid='.$forum['parentid'].'">'.$parent['title']; ?></a></div>
<?php } ?>
			<div class="thread_head_forum"><?php echo $forum['title']; ?></div>
		</div>

<?php
            if (DISPLAY_SUBFORUMS_FORUMDISPLAY != false and !empty($iforumcache["$forum[forumid]"])) {
                $subforumbits = array();
                foreach ($iforumcache["$forum[forumid]"] as $subforumid) {
                    $forum_sub =& $forumcache["$subforumid"];
                    if (!($forum_sub['readaccess'] == 'both' or ($forum_sub['readaccess'] == 'reg' and $wb->get_user_id()) or ($forum_sub['readaccess'] == 'unreg' and !$wb->get_user_id()))) {
                        continue;
                    }
                    $subforumbits[] = '<a href="' . WB_URL . '/modules/forum/forum_view.php?sid=' . $section_id . '&amp;pid=' . $page_id . '&amp;fid=' . $subforumid . '">' . $forum_sub['title'] . '</a>';
                }
                if (sizeof($subforumbits)) {
                    echo '<div class="thread_subs"><strong>'.$MOD_FORUM['TXT_SUBFORUMS_F'].' </strong>' . implode(', ', $subforumbits) . '</div>';
                }
            }

            if (!$threads or !$threads->numRows()) {
?>
			<table class="thread_page_nav" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td align="left" style="font-size: 10px;">
			<?php
            echo $MOD_FORUM['TXT_NO_TOPICS_F']
            ?>
			</td>
			<td align="right">
<?php
/* [1] */
                if (true === $user_can_create_topic) {
?>
				<span class="thread_new_topic">
					<a href="<?php echo WB_URL; ?>/modules/forum/thread_create.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>&amp;ts=<?php echo $t; ?>"><?php echo$MOD_FORUM['TXT_NEW_TOPIC_F']; ?>
					</a>
				</span>
<?php
                }
?>
				</td>
			</tr>
			</table>
<?php
            } else {
?>
			<table class="thread_page_nav" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td align="left" style="font-size: 10px;">
<?php
                echo $MOD_FORUM['TXT_PAGES_F'].' '.$pagenav;
?>
			</td>
			<td align="right">
<?php
/* [1] */
                if (true === $user_can_create_topic) {
?>
				<span class="thread_new_topic">
					<a href="<?php echo WB_URL; ?>/modules/forum/thread_create.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>"><?php echo$MOD_FORUM['TXT_NEW_TOPIC_F']; ?>
					</a>
				</span>
<?php
                }
?>
				</td>
			</tr>
			</table>

			<ul class="thread_list">
<?php
            $i ==0;
            while ($thread = $threads->fetchRow()) {
?>
				<li class="<?php echo(!$thread['open'] ? 'thread_item_closed' : 'thread_item'); echo($i++ % 2 ? ' odd' : ' even') ?>">
					<strong>
						<a href="<?php echo WB_URL; ?>/modules/forum/thread_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;tid=<?php echo $thread['threadid']; ?>"><?php echo htmlspecialchars($thread['title']); ?></a>
					</strong>
					<div class="thread_info"><?php echo $MOD_FORUM['TXT_LAST_ARTICLE_F'].' '.date(DATE_FORMAT.', '.TIME_FORMAT, $thread['lastpost'] + TIMEZONE).' - '.$MOD_FORUM['TXT_FROM_F'].' '; ?><?php echo htmlspecialchars($thread['display_name']).' - '.$MOD_FORUM['TXT_RESPONSES_F'].' '. number_format($thread['replycount']); ?></div>
				</li>
<?php
            }
?>
			</ul>
			<table class="thread_page_nav" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td align="left" style="font-size: 10px;">
<?php
            echo $MOD_FORUM['TXT_PAGES_F'].' '.$pagenav;
?>
			</td>
			<td align="right">
<?php
/* [1] */
            if (true === $user_can_create_topic) {
?>
				<span class="thread_new_topic">
					<a href="<?php echo WB_URL; ?>/modules/forum/thread_create.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>"><?php echo$MOD_FORUM['TXT_NEW_TOPIC_F']; ?>
					</a>
				</span>
<?php
            }
?>

				</td>
			</tr>
			</table>
<?php
        }
    }
}

// ##################### CREATE THREAD (FORM AND DATABASE) ######################
elseif (FORUM_DISPLAY_CONTENT == 'create_thread') {
    if (!isset($_SESSION['forum_ts'])) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 101]",
            "';history.back(2);'"
        );
        return 0;
    }
    $subway->register($_POST, 'forum_ts', null);
    if ((isset($_GET['ts']) && intval($_GET['ts']) !== $_SESSION['forum_ts']) && intval($_POST['forum_ts']) !== $_SESSION['forum_ts']) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 102]",
            "';history.back(2);'"
        );
        return 0;
    }

    //	if (!($forum['writeaccess'] == 'both' OR ($forum['writeaccess'] == 'reg' AND $wb->get_user_id()) OR ($forum['writeaccess'] == 'unreg' AND !$wb->get_user_id()))) {
    if (false === $user_can_create_topic) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 103]",
            "';history.back(2);'"
        );
        return 0;
    } else {
        if (isset($_POST['save'])) {
            if (strlen(trim($_POST['title'])) < 3) {
                echo $subway->print_error(
                    $MOD_FORUM['TXT_TITLE_TO_SHORT_F']." [Error: 201]",
                    "';history.back();'"
                );
                return 0;
            } elseif (strlen(trim($_POST['text'])) < 3) {
                echo $subway->print_error(
                    $MOD_FORUM['TXT_TEXT_TO_SHORT_F']." [Error: 202]",
                    "';history.back();'"
                );
                return 0;
            } elseif (strlen(trim(@$_POST['username'] ?? '')) < 3 and !$wb->get_user_id()) {
                echo $subway->print_error(
                    $MOD_FORUM['TXT_USERNAME_TO_SHORT_F']." [Error: 203]",
                    "';history.back();'"
                );
                return 0;
            }
            if (!$wb->get_user_id()) {
                $username =& $_POST['username'];
                if (FORUM_USE_CAPTCHA != false) {
                    if (isset($_POST['captcha']) and $_POST['captcha'] != '') {
                        if (!isset($_POST['captcha']) or !isset($_SESSION['captcha']) or $_POST['captcha'] != $_SESSION['captcha']) {
                            echo $subway->print_error(
                                $MOD_FORUM['TXT_WRONG_CAPTCHA_F']." [Error: 401]",
                                "';history.back();'"
                            );
                            return 0;
                        }
                    } else {
                        echo $subway->print_error(
                            $MOD_FORUM['TXT_WRONG_CAPTCHA_F']." [Error: 402]",
                            "';history.back();'"
                        );
                        return 0;
                    }
                    if (isset($_SESSION['captcha'])) {
                        unset($_SESSION['captcha']);
                    }
                }
            }
            $database->query("
				INSERT INTO `" . TABLE_PREFIX . "mod_forum_post`
					(`userid`, `title`, `dateline`, `text`, `username`, `page_id`, `section_id`, `search_text`)
				VALUES
					('" . $wb->get_user_id() . "', '" . trim($_POST['title']) . "', '" . time() . "', '" . trim($_POST['text']) . "', '" . $username . "', '$page_id', '$section_id','')
			");

            $id = $database->query("SELECT LAST_INSERT_ID() AS `id`");
            $id = $id->fetchRow();

            $database->query("
				INSERT INTO `" . TABLE_PREFIX . "mod_forum_thread`
					(`user_id`, `username`, `title`, `dateline`, `firstpostid`, `lastpostid`, `lastpost`, `forumid`, `open`, `page_id`, `section_id`)
				VALUES
					('" . $wb->get_user_id() . "', '" . @$username . "', '" . trim($database->escapeString($_POST['title'])) . "', '" . time() . "', '" . $id['id'] . "', '" . $id['id'] . "', '" . time() . "', '" . $forum['forumid'] . "', 1, '$page_id', '$section_id')
			");

            if ($database->is_error()) {
                die($database->get_error());
            }

            $tid = $database->query("SELECT LAST_INSERT_ID() AS `id`");
            $tid = $tid->fetchRow();

            $database->query("
				UPDATE `" . TABLE_PREFIX . "mod_forum_post` SET `threadid` = '$tid[id]' WHERE `postid` = '$id[id]'
			");

            $thread['threadid'] = $tid['id'];
            $mailing_result = "";
            include 'include_sendmails.php';

            // $mailing_result wird mit inhalt gefüllt, wenn es mails zu mailen gab
            $wb->print_success($MOD_FORUM['TXT_TOPIC_CREATED_F'] . $mailing_result, 'thread_view' . PAGE_EXTENSION . '?sid=' . SECTION_ID . '&pid=' . PAGE_ID . '&tid=' . $tid['id']);
        } else { ?>
			<script type="text/javascript">
			<!--
			function addsmiley(code)  {
			    var pretext = document.getElementById('messagebox').value;
			      this.code = code;
			      document.getElementById('messagebox').value = pretext + ' ' + code;
			}
			-->
			</script>
<?php
    $home_link = WB_URL.PAGES_DIRECTORY.$wb->page['link'].PAGE_EXTENSION;
?>
			<div class="newtopic_head">
			<div class="newtopic_head_link"><a href="<?php echo $home_link.'">'.PAGE_TITLE; ?></a></div>
			<div class="newtopic_head_forum"><a href="<?php echo WB_URL; ?>/modules/forum/forum_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>"><?php echo $forum['title'] ?></a></div>
			<div class="newtopic_head_create"><?php echo $MOD_FORUM['TXT_CREATE_NEW_TOPIC_F']; ?></div>
		</div>
			<form  class="forum_form" id="addform" name="addform" action="<?php echo WB_URL; ?>/modules/forum/thread_create.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>" method="post">
			<input type="hidden" name="forum_ts" value="<?php $t=time(); echo $t; $_SESSION['forum_ts']=$t; ?>" />
			<table cellpadding="2" cellspacing="0" align="center" border="0" style="width: 100%;">
			<colgroup>
				<col width="1%" />
				<col width="99%" />
			</colgroup>
			<tr>
				<td valign="top"><?php echo $MOD_FORUM['TXT_TITLE_F']; ?></td>
				<td><input type="text" name="title" class="forum_input" /></td>
			</tr>
<?php
            if (!$wb->get_user_id()) {
?>
				<tr>
					<td valign="top"><?php echo $MOD_FORUM['TXT_USERNAME_F']; ?></td>
					<td><input type="text" name="username" class="forum_input" value="<?php echo $MOD_FORUM['TXT_GUEST_F']; ?>" /></td>
				</tr>
<?php
                if (FORUM_USE_CAPTCHA != false) {
?>
				<tr>
					<td><?php $MOD_FORUM['TXT_VERIFICATION_F']; ?></td>
					<td><?php require_once(WB_PATH.'/include/captcha/captcha.php'); call_captcha(); ?></td>
				</tr>
<?php
                }
            }
?>
			<tr>
				<td valign="top" style="padding-top: 30px;"><?php echo $MOD_FORUM['TXT_TEXT_F']; ?></td>
				<td><textarea id="messagebox" name="text" class="forum_textarea" rows="100%" cols="100%"></textarea></td>
			</tr>
			<tr <?php echo FORUM_USE_SMILEYS ? '' : 'style="display:none"'?> >
				<td valign="top"><?php echo $MOD_FORUM['TXT_SMILIES_F']; ?></td>
				<td>
					<?php include WB_PATH . '/modules/forum/smilies.php'; ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<table cellpadding="2" cellspacing="0" border="0" class="forum_button_box">
							<colgroup>
								<col width="1%" />
								<col width="98%" />
								<col width="1%" />
							</colgroup>
						<tr>
							<td align="left">
								<input class="forum_save" type="submit" value="<?php echo $MOD_FORUM['TXT_SAVE_F']; ?>" />
							</td>
							<td align="center">
								<input class="forum_reset" type="reset" value="<?php echo $MOD_FORUM['TXT_RESET_F']; ?>" />
							</td>
							<td align="right">
								<input class="forum_cancel" type="button" value="<?php echo $MOD_FORUM['TXT_CANCEL_F']; ?>" onclick="javascript:history.back();" style="width: 150; margin-top: 5px;" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<input type="hidden" name="save" value="1" />
			<input type="hidden" name="fid" value="<?php echo $forum['forumid']; ?>" />
			<input type="hidden" name="pid" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="sid" value="<?php echo $section_id; ?>" />
			</form>
<?php
        }
    }
}
// ##################### CREATE THREAD (FORM AND DATABSE) ######################
elseif (FORUM_DISPLAY_CONTENT == 'view_thread') {
    $perpage = SHOWTHREAD_PERPAGE;

    if (!has_access($forum)) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_NO_ACCESS_F']." [Error 601]",
            "';history.back();'"
        );
        return 0;
    } else {
        $post_count = $database->query("SELECT COUNT(`postid`) AS `total` FROM `" . TABLE_PREFIX . "mod_forum_post` WHERE `threadid` = '" . intval($thread['threadid']) . "'");
        $post_count = $post_count->fetchRow();

        @$page = intval($_REQUEST['page']);

        if (!$page) {
            $page = 1;
        }

        $pagecount = ceil($post_count['total'] / SHOWTHREAD_PERPAGE);

        if (($page * SHOWTHREAD_PERPAGE) > $post_count['total']) {
            // Go to last page
            $page = $pagecount;
        }

        $start = ($page * SHOWTHREAD_PERPAGE) - SHOWTHREAD_PERPAGE;

        if ($start < 0) {
            $start = 0;
        }

        $posts = $database->query("
			SELECT `p`.*, `u`.*, IF(NOT ISNULL(`u`.`user_id`), `u`.`display_name`, `p`.`username`) AS `display_name`
			FROM `" . TABLE_PREFIX . "mod_forum_post` AS `p`
			LEFT JOIN `" . TABLE_PREFIX . "users` AS `u` ON(`u`.`user_id` = `p`.`userid`)
			WHERE `p`.`threadid` = '" . intval($thread['threadid']) . "'
			ORDER BY `p`.`dateline` ASC
			LIMIT $start, $perpage
		");

        // Construct Page Nav

        $page_url = WB_URL.'/modules/forum/thread_view.php?sid='.$section_id.'&amp;pid='.$page_id.'&amp;tid='.$thread['threadid'];

        $pagenav = '';
        for ($i = 1; $i <= $pagecount; $i++) {
            if ($page == $i) {
                $pagenav .= '<strong>['.$i.']</strong>&nbsp;';
            } else {
                $pagenav .= '<a href="'.$page_url.'&page='.$i.'">'.$i.'</a>&nbsp;';
            }
        }

        $home_link = WB_URL.PAGES_DIRECTORY.$wb->page['link'].PAGE_EXTENSION;
		include('include_searchform.php');
?>
		<div class="details_head">
			<div class="details_head_home"><a href="<?php echo $home_link.'">'.PAGE_TITLE; ?></a></div>
			<div class="details_head_forum"><a href="<?php echo WB_URL; ?>/modules/forum/forum_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>"><?php echo $forum['title'] ?></a></div>
			<div class="details_head_topic"><?php echo $thread['title']; ?></div>
		</div>
		<div class="details_page_nav">
<?php
            echo $MOD_FORUM['TXT_PAGES_F'].' '.$pagenav;
?>
		</div>
<?php
        $i = 0;
        while ($post = $posts->fetchRow()) {
            $i++;
            $postcount = ($page * $perpage) - $perpage + $i;
?>

			<table border="0" class="details_table" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="details_topic  <?php echo($i % 2 ? 'odd' : 'even') ?>">
<?php

 //	Can the user edit the post?

 $user_can_edit_and_delete = false;
 if( $post['userid'] == $wb->get_user_id()) {
        $user_can_edit_and_delete = true;
 }
if ($user['group_id'] == intval(ADMIN_GROUP_ID) || in_array( intval(ADMIN_GROUP_ID), explode(',', $user['groups_id']))) {
	$user_can_edit_and_delete = true;
}

if( 1 == $user['group_id'] ) {
    $user_can_edit_and_delete = true;
}


                if (true === $user_can_edit_and_delete) {
?>
						<span style="float:right">
							<a href="<?php echo WB_URL; ?>/modules/forum/post_edit.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;postid=<?php echo $post['postid']; ?>"><img src="images/edit.png" width="16" height="16" border="0" title="<?php echo $MOD_FORUM['TXT_EDIT_F']; ?>" alt="" /></a>
							<a id="delete" href="<?php echo WB_URL; ?>/modules/forum/post_delete.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;postid=<?php echo $post['postid']; ?>" onclick="return confirm(unescape('<?php echo $MOD_FORUM['TXT_REALLY_DELETE_F']; ?>'));"><img src="images/delete.png" width="16" height="16" border="0" title="<?php echo $MOD_FORUM['TXT_DELETE_F']; ?>" alt="" /></a>
						</span>
<?php
                }
?>
				<!-- owd | otherworld.de -->
				<a name="post<?php echo $post['postid']; ?>">#<?php echo number_format($postcount); ?></a> <strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
			</tr>
			<tr>
				<td class="details_info"><?php echo $MOD_FORUM['TXT_FROM_F'].' '; ?><?php echo htmlspecialchars($post['display_name']); ?> (<?php echo date(DATE_FORMAT . ', ' . TIME_FORMAT, $post['dateline'] + TIMEZONE); ?>)</td>
				</tr>
			<tr>
<?php
            $parsed_text = parse_bbcode(htmlspecialchars($post['text']), $MOD_FORUM['TXT_QUOTE_F']);
            if (FORUM_USE_SMILEYS) {
                $parsed_text = parse_text($parsed_text);
            }
?>
				<td class="details_text"><?php echo $parsed_text; ?>
				</td>
			</tr>
			</table>

<?php
        }
?>
		<div class="details_page_nav">
			<?php
            echo $MOD_FORUM['TXT_PAGES_F'].' '.$pagenav; ?>
		</div>
<?php
//		if ($forum['parentid'] AND ($forum['writeaccess'] == 'both' OR ($forum['writeaccess'] == 'reg' AND $wb->get_user_id()) OR ($forum['writeaccess'] == 'unreg' AND !$wb->get_user_id())))
/* [2] */
        if (true === $user_can_create_answer) {
?>
				<script type="text/javascript">
			<!--
			function addsmiley(code)  {
			    var pretext = document.getElementById('messagebox').value;
			      this.code = code;
			      document.getElementById('messagebox').value = pretext + ' ' + code;
			}
			function toggleEditor(elem) {
				$('#editor').toggle();
				elem.value = ($('#editor').css("display") != "none") ? "<?php echo $MOD_FORUM['TXT_HIDE_EDITOR_B'].'" : "'.$MOD_FORUM['TXT_CREATE_ANSWER_F'].'"'; ?>;
			}
			-->
			</script>
<?php
            if (FORUM_HIDE_EDITOR) {
                echo '<input type="button" id="toggleEditor" style="float: right; width: 150px; padding: 2px 0;" value="'.$MOD_FORUM['TXT_CREATE_ANSWER_F'].'" onclick="toggleEditor(this);" />';
            }
    $temp_time = time();
    $_SESSION['forum_ts'] = $temp_time;
?>
				<form action="<?php echo WB_URL; ?>/modules/forum/thread_reply.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>" method="post">
				<fieldset id="editor" style="margin-top: 10px; clear:right; padding:0; <?php echo (FORUM_HIDE_EDITOR) ? "display:none;" : ""?>">
				<legend style="margin-left:25px"><?php echo $MOD_FORUM['TXT_CREATE_ANSWER_F']; ?></legend>

				<input type="hidden" name="forum_ts" value="<?php echo $temp_time; ?>" />

				<table cellpadding="2" cellspacing="0" align="center" border="0" style="width: 100%;">
				<colgroup>
				<col width="1%" />
				<col width="99%" />
				</colgroup>
				<tr>
				<td valign="top"><?php echo $MOD_FORUM['TXT_TITLE_F']; ?></td>
				<td><input class="forum_input" type="text" name="title" value="<?php echo $thread['title']; ?>" /></td>
				</tr>
				<?php
                if (!$wb->get_user_id()) {
                    ?>
				<tr>
					<td valign="top"><?php echo $MOD_FORUM['TXT_USERNAME_F']; ?></td>
					<td><input class="forum_input" type="text" name="username" value="<?php echo $MOD_FORUM['TXT_GUEST_F']; ?>" /></td>
				</tr>
				<?php
                if (FORUM_USE_CAPTCHA != false) {
                    ?>
				<tr>
					<td><?php echo $MOD_FORUM['TXT_VERIFICATION_F']; ?></td>
					<td><?php require_once(WB_PATH.'/include/captcha/captcha.php');
                    call_captcha(); ?></td>
				</tr>
				<?php
                }
                } ?>
			<tr>
				<td valign="top" style="padding-top: 30px;"><?php echo $MOD_FORUM['TXT_TEXT_F']; ?></td>
				<td><textarea id="messagebox" name="text" class="forum_textarea" rows="100%" cols="100%"></textarea></td>
			</tr>
			<tr <?php echo FORUM_USE_SMILEYS ? '' : 'style="display:none"'?> >
				<td valign="top"><?php echo $MOD_FORUM['TXT_SMILIES_F']; ?></td>
				<td>
					<?php include(WB_PATH . '/modules/forum/smilies.php'); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<table class="forum_button_box" cellpadding="2" cellspacing="0" border="0">
						<colgroup>
							<col width="1%" />
							<col width="98%" />
							<col width="1%" />
						</colgroup>
						<tr>
							<td align="left">
								<input class="forum_save" type="submit" value="<?php echo $MOD_FORUM['TXT_SAVE_F']; ?>" />
							</td>
							<td align="center">
								<input class="forum_reset" type="reset" value="<?php echo $MOD_FORUM['TXT_RESET_F']; ?>" />
							</td>
							<td align="right">
								<input class="forum_cancel" type="button" value="<?php echo $MOD_FORUM['TXT_CANCEL_F']; ?>" onclick="javascript:history.back();" style="width: 150; margin-top: 5px;" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<input type="hidden" name="save" value="1" />
			<input type="hidden" name="tid" value="<?php echo $thread['threadid']; ?>" />
			<input type="hidden" name="pid" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="sid" value="<?php echo $section_id; ?>" />
			</form>
			</fieldset>
			<?php
}
    }
}
// ################## REPLY TO THREAD (DATABASE STUFF ONLY) #####################
elseif (FORUM_DISPLAY_CONTENT == 'reply_thread' &&	($forum['writeaccess'] !== 'reg' or ($forum['writeaccess'] == 'reg' && $wb->get_user_id()) or ($forum['writeaccess'] == 'unreg' and !$wb->get_user_id()))) {
    if (intval($_POST['forum_ts']) !== $_SESSION['forum_ts']) {
        //	$wb->print_error($MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 110]","javascript:history.back()");
    }
    $perpage = 15;
    if (strlen(trim($_POST['title'])) < 3) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_TITLE_TO_SHORT_F']." [Error 221]",
            "';history.back();'"
        );
        return 0;
    } elseif (strlen(trim($_POST['text'])) < 3) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_TEXT_TO_SHORT_F']." [Error 222]",
            "';history.back();'"
        );
        return 0;
    } elseif (@strlen(trim($_POST['username'])) < 3 and !$wb->get_user_id()) {
        echo $subway->print_error(
            $MOD_FORUM['TXT_USERNAME_TO_SHORT_F']." [Error 223]",
            "';history.back();'"
        );
        return 0;
    }

    if (!$wb->get_user_id()) {
        $username =& $_POST['username'];

        if (FORUM_USE_CAPTCHA != false) {
            if (isset($_POST['captcha']) and $_POST['captcha'] != '') {
                if (!isset($_POST['captcha']) or !isset($_SESSION['captcha']) or $_POST['captcha'] != $_SESSION['captcha']) {
                    echo $subway->print_error(
                        $MOD_FORUM['TXT_WRONG_CAPTCHA_F']." [Error 224]",
                        "';history.back();'"
                    );
                    return 0;
                }
            } else {
                echo $subway->print_error(
                    $MOD_FORUM['TXT_WRONG_CAPTCHA_F']." [Error 225]",
                    "';history.back();'"
                );
                return 0;
            }

            if (isset($_SESSION['captcha'])) {
                unset($_SESSION['captcha']);
            }
        }
    }

    /**
     * otherworld - FULLTEXT search implementiert
     * mit suchlänge ab 3 zeichen (für arme)
     */

    //$_search_string = strip_bbcode($_POST['text']);
    // macht aus 3-Zeichen-Wörtern längere, um berücksichtigt zu werden:
    // aus PHP wird PHP_x_PHP
    $_search_string  = preg_replace("/\b([a-zöäüﬂ0-9]{3})\b/i", "$1_x_$1", trim($_POST['title'])) ;
    $_search_string .= preg_replace("/\b([a-zöäüﬂ0-9]{3})\b/i", "$1_x_$1", strip_bbcode($_POST['text'])) ;

    // weiter im original: ===================================================
    $database->query("
		INSERT INTO `" . TABLE_PREFIX . "mod_forum_post`
			(`userid`, `title`, `dateline`, `text`, `search_text`, `username`, `threadid`, `page_id`, `section_id`)
		VALUES
			('" . $wb->get_user_id() . "', '" . trim($_POST['title']) . "', '" . time() . "', '" . trim($_POST['text']) . "', '" . $_search_string . "', '" . $username . "', '$thread[threadid]', '$page_id', '$section_id')
	");
    if ($database->is_error()) {
        die("[111] ".$username."   ".$database->get_error());
    }
    $id = $database->query("SELECT LAST_INSERT_ID() AS id");
    $id = $id->fetchRow();

    $database->query("
		UPDATE `" . TABLE_PREFIX . "mod_forum_thread` SET `replycount`=`replycount` + 1, `lastpostid` = '" . $id['id'] . "', `lastpost` = '" . time() . "' WHERE `threadid` = '$thread[threadid]'
	");

    $replycount = $thread['replycount'] + 1;

    $lastpage = ceil($replycount / $perpage);

    /**
     * otherworld.de
     * Mails an die anderen im Thread absetzen:
     * wenn das in der config.php so gewünscht ist.
     */
    $mailing_result = "";
    include 'include_sendmails.php';

    // $mailing_result wird mit inhalt gefüllt, wenn es mails zu mailen gab
    $wb->print_success($MOD_FORUM['TXT_ARTICLE_SAVED_F'] . $mailing_result, 'thread_view' . PAGE_EXTENSION . '?sid=' . SECTION_ID . '&pid=' . PAGE_ID . '&tid=' . $thread['threadid'] . '&page=' . $lastpage);
}
// ##################### DELETE POST (DATABSE STUFF ONLY) ######################
elseif (FORUM_DISPLAY_CONTENT == 'post_delete') {

//	if (!((in_array(intval(ADMIN_GROUP_ID), explode(',', $user['groups_id'])) OR $user['group_id'] == intval(ADMIN_GROUP_ID)) AND intval(ADMIN_GROUP_ID) !== 0))
    if ($user_can_edit_and_delete === false) {
        echo $subway->print_error($MOD_FORUM['TXT_NO_ACCESS_F']." [Error: 304]", "';history.back();'");
        return 0;
    }

    if ($post['postid'] == $thread['firstpostid']) {
        $database->query("
			DELETE `thread`, `post`
			FROM `" . TABLE_PREFIX . "mod_forum_thread` AS `thread`
			LEFT JOIN `" . TABLE_PREFIX . "mod_forum_post` AS `post` USING(`threadid`)
			WHERE `thread`.`threadid` = '" . intval($thread['threadid']) . "'
		");
    } else {
        $database->query("
			DELETE `post`
			FROM `" . TABLE_PREFIX . "mod_forum_post` AS `post`
			WHERE `post`.`postid` = '" . intval($post['postid']) . "'
		");

        $lastpost = $database->query("
			SELECT * FROM `" . TABLE_PREFIX . "mod_forum_post`
			WHERE `threadid` = '" . $thread['threadid'] . "'
			ORDER BY `dateline` DESC
			LIMIT 1
		");

        $lastpost = $lastpost->fetchRow();

        $database->query("
			UPDATE `" . TABLE_PREFIX . "mod_forum_thread`
			SET
				`replycount` = `replycount` - 1,
				`lastpostid` = '" . $lastpost['postid'] . "',
				`lastpost` = '" . $lastpost['dateline'] . "'
			WHERE `threadid` = '" . $thread['threadid'] . "'
		");
    }

    $wb->print_success($MOD_FORUM['TXT_ARTICLE_DELETED_F'], 'forum_view' . PAGE_EXTENSION . '?sid=' . SECTION_ID . '&pid=' . PAGE_ID . '&fid=' . $forum['forumid']);
}
// ####################### EDIT POST (FORM AND DATABSE) ########################
elseif (FORUM_DISPLAY_CONTENT == 'post_edit') {
    if ($user_can_edit_and_delete === false) {
        echo $subway->print_error($MOD_FORUM['TXT_NO_ACCESS_F'], "';history.back();'");
        return 0;
    }

    if (isset($_POST['save'])) {
        if (intval($_POST['forum_ts']) !== $_SESSION['forum_ts']) {
            echo $subway->print_error(
                $MOD_FORUM['TXT_NO_ACCESS_F'],
                "';history.back();'"
            );
            return 0;
        }
        if (strlen(trim($_POST['title'])) < 3) {
            echo $subway->print_error($MOD_FORUM['TXT_TITLE_TO_SHORT_F'], "';history.back();'");
            return 0;
        } elseif (strlen(trim($_POST['text'])) < 3) {
            echo $subway->print_error(
                $MOD_FORUM['TXT_TEXT_TO_SHORT_F'],
                "';history.back();'"
            );
            return 0;
        } elseif (@strlen(trim($_POST['username'])) < 3 and !$post['userid']) {
            echo $subway->print_error(
                $MOD_FORUM['TXT_USERNAME_TO_SHORT_F'],
                "';history.back();'"
            );
            return 0;
        }

        if (!$post['userid']) {
            $username =& $_POST['username'];
        }


        /**
         * otherworld - FULLTEXT search implementiert
         * mit suchlänge ab 3 zeichen (für arme)
         */

        //$_search_string = strip_bbcode($_POST['text']);
        // macht aus 3-Zeichen-Wörtern längere, um berücksichtigt zu werden:
        // aus PHP wird PHP_x_PHP
        $_search_string  = preg_replace("/\b([a-zöäüß0-9]{3})\b/i", "$1_x_$1", trim($_POST['title']))." " ;
        $_search_string .= preg_replace("/\b([a-zöäüß0-9]{3})\b/i", "$1_x_$1", strip_bbcode($_POST['text'])) ;


        $database->query("
			UPDATE `" . TABLE_PREFIX . "mod_forum_post`
			SET
				`title` = '" . trim($_POST['title']) . "',
				`text` = '" . trim($_POST['text']) . "',
				`search_text` = '".$_search_string."'
			WHERE
				`postid` = '" . intval($post['postid']) . "'
		");

        if ((!$post['userid']) and $post['postid'] == $thread['firstpostid']) {
            $database->query("
			UPDATE `" . TABLE_PREFIX . "mod_forum_thread`
			SET
				`username` = '$username'
			WHERE `threadid` = '" . $thread['threadid'] . "'
			");
        }

        $wb->print_success($MOD_FORUM['TXT_ARTICLE_SAVED_F'], 'thread_view' . PAGE_EXTENSION . '?sid=' . SECTION_ID . '&pid=' . PAGE_ID . '&tid=' . $thread['threadid']);
    } else {
        //	2016-05-10:	Bugfix
        //				For some resons $homelink is missing here
        $home_link = WB_URL.PAGES_DIRECTORY.$wb->page['link'].PAGE_EXTENSION; ?>
			<script type="text/javascript">
			<!--
			function addsmiley(code)  {
			    var pretext = document.getElementById('messagebox').value;
			      this.code = code;
			      document.getElementById('messagebox').value = pretext + ' ' + code;
			}
			-->
			</script>

			<div class="edit_head">
			<div class="edit_head_home"><a href="<?php echo $home_link.'">'.PAGE_TITLE; ?></a></div>
			<div class="edit_head_forum"><a href="<?php echo WB_URL; ?>/modules/forum/forum_view.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>&amp;fid=<?php echo $forum['forumid']; ?>"><?php echo $forum['title'] ?></a></div>

			<div class="edit_head_topic"><?php echo $thread['title']; ?> answer</div>
			<div class="edit_head_edit"><?php echo $MOD_FORUM['TXT_EDIT_ARTICLE_F']; ?></div>
		</div>

			<form class="forum_form" id="addform" name="addform" action="<?php echo WB_URL; ?>/modules/forum/post_edit.php?sid=<?php echo $section_id; ?>&amp;pid=<?php echo $page_id; ?>" method="post">
			<input type="hidden" name="forum_ts" value="<?php $t=time();
        echo $t;
        $_SESSION['forum_ts']=$t; ?>" />
			<table cellpadding="2" cellspacing="0" align="center" border="0" style="width: 100%;">
			<colgroup>
				<col width="1%" />
				<col width="99%" />
			</colgroup>
			<tr>
				<td valign="top"><?php echo $MOD_FORUM['TXT_TITLE_F']; ?></td>
				<td><input class="forum_input" type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" /></td>
			</tr>
<?php if (!$post['userid']) { ?>
			<tr>
				<td valign="top"><?php echo $MOD_FORUM['TXT_USERNAME_F']; ?></td>
				<td><input class="forum_input" type="text" name="username" value="<?php echo htmlspecialchars($post['username']); ?>" /></td>
			</tr>
<?php } ?>
			<tr>
				<td valign="top" style="padding-top: 30px;"><?php echo $MOD_FORUM['TXT_TEXT_F']; ?></td>
				<td><textarea class="forum_textarea" id="messagebox" name="text" rows="100%" cols="100%"><?php echo htmlspecialchars($post['text']); ?></textarea></td>
			</tr>
			<tr <?php echo FORUM_USE_SMILEYS ? '' : 'style="display:none"'?> >
				<td valign="top"><?php echo $MOD_FORUM['TXT_SMILIES_F']; ?></td>
				<td>
					<?php include(WB_PATH . '/modules/forum/smilies.php'); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<table class="forum_button_box" cellpadding="2" cellspacing="0" border="0">
						<tr>
							<colgroup>
								<col width="1%" />
								<col width="98%" />
								<col width="1%" />
							</colgroup>
							<td align="left">
								<input class="forum_save" type="submit" value="<?php echo $MOD_FORUM['TXT_SAVE_F']; ?>" />
							</td>
							<td align="center">
								<input class="forum_reset" type="reset" value="<?php echo $MOD_FORUM['TXT_RESET_F']; ?>" />
							</td>
							<td align="right">
								<input class="forum_cancel" type="button" value="<?php echo $MOD_FORUM['TXT_CANCEL_F']; ?>" onclick="javascript:history.back();" style="width: 150; margin-top: 5px;" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<input type="hidden" name="save" value="1" />
			<input type="hidden" name="postid" value="<?php echo $post['postid']; ?>" />
			<input type="hidden" name="sid" value="<?php echo $section_id; ?>" />
			</form>
<?php
    }
}//elseif's

echo '<script src="frontend_body.js" type="text/javascript"></script>';
