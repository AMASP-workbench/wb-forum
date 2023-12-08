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

// The module description
$module_description = 'This module integrates a simple forum in your Website Baker website.';

$MOD_FORUM = array(
	// Frontend
	'TXT_SUBFORUMS_F'				=> 'Subforums:',
	'TXT_THEMES_F'					=> 'Themes',
	'TXT_THEME_F'					=> 'Theme',
	'TXT_NO_ACCESS_F'				=> 'Access denied!',
	'TXT_FIRST_F'					=> 'First',
	'TXT_PREVIOUS_F'				=> 'Previous',
	'TXT_NEXT_F'					=> 'Next',
	'TXT_LAST_F'					=> 'Last',
	'TXT_FROM_F'					=> 'By',
	'TXT_NO_TOPICS_F'				=> 'No topics in this forum!',
	'TXT_LAST_ARTICLE_F'			=> 'Last entry:',
	'TXT_RESPONSES_F'				=> 'Respond:',
	'TXT_NEW_TOPIC_F'				=> 'New topic',
	'TXT_TEXT_TO_SHORT_F'			=> 'Text too short!',
	'TXT_USERNAME_TO_SHORT_F'		=> 'Name too short!',
	'TXT_WRONG_CAPTCHA_F'			=> 'Wrong Captcha!',
	'TXT_TOPIC_CREATED_F'			=> 'Topic created!',
	'TXT_CREATE_NEW_TOPIC_F'		=> 'Create new topic',
	'TXT_USERNAME_F'				=> 'Name:',
	'TXT_VERIFICATION_F'			=> 'Verification:',
	'TXT_TEXT_F'					=> 'Text:',
	'TXT_TITLE_F'					=> 'Title:',
	'TXT_SMILIES_F'					=> 'Smilies:',
	'TXT_SAVE_F'					=> 'Submit',
	'TXT_RESET_F'					=> 'Reset',
	'TXT_CANCEL_F'					=> 'Cancel',
	'TXT_EDIT_F'					=> 'Edit',
	'TXT_DELETE_F'					=> 'Delete',
	'TXT_TITLE_TO_SHORT_F'			=> 'Title too short!',
	'TXT_ARTICLE_DELETED_F'			=> 'Article deleted!',
	'TXT_ARTICLE_SAVED_F'			=> 'Article saved!',
	'TXT_EDIT_ARTICLE_F'			=> 'Edit article',
	'TXT_CREATE_ANSWER_F'			=> 'Create answer:',
	'TXT_QUOTE_F'					=> 'Cite',
	'TXT_REALLY_DELETE_F'			=> 'Do you really want to delete this article?',
	'TXT_GUEST_F'					=> 'Guest',
	'TXT_PAGES_F'					=> 'Pages:',

	// New in 0.4.0
	'TXT_SEARCH_F'					=> 'Search',
	'TXT_HITS_F'					=> 'Hits',
	'TXT_NO_HITS_F'					=> 'There are no hits for your query',
	'TXT_NO_SEARCH_STRING_F'		=> 'Please enter a expression to search for!',
	'TXT_SEARCH_RESULT_F'			=> 'Search result',
	'TXT_READMORE_F'				=> 'read &raquo;',
	'TXT_MAILS_SEND_F'				=> ' Info-email(s) sent',
	'TXT_MAIL_ERRORS_F'				=> ' Errors while sending info-mails',
	'TXT_MAILSUBJECT_NEW_POST'		=> 'There is a new Post in the Forum',
	'TXT_MAILTEXT_NEW_POST'			=>	"Hallo ##USERNAME##, \n\nYou posted in thread \"##THREAD##\" .\n" .
										"There is a new post by ##POSTER## in this thread \n\n".
										"You can read the post by following this link: \n##LINK##\n",
	'TXT_MAILTEXT_NEW_POST_ADMIN'	=>	"Hello ##USERNAME##, \n\nThere is a new post by ##POSTER## in \"##THREAD##\" .\n\n" .
										"You can read the post by following this link: \n##LINK##\n",
	// Backend
	'TXT_NO_FORUMS_B'				=> 'There are no forums in this category!<br />You will have to create one as a root-element first!',
	'TXT_CREATE_FORUM_B'			=> 'Create forum',
	'TXT_FORUMS_B'					=> 'Forums:',
	'TXT_FORUM_B'					=> 'Forum',
	'TXT_EDIT_FORUM_B'				=> 'Edit forum',
	'TXT_SETTINGS_B'				=> 'Settings',
	'TXT_PERMISSIONS_B'				=> 'Permissions',
	'TXT_TITLE_B'					=> 'Title:',
	'TXT_DESCRIPTION_B'				=> 'Description:',
	'TXT_DISPLAY_ORDER_B'			=> 'Display order:',
	'TXT_PARENT_FORUM_B'			=> 'Parent Forum:',
	'TXT_DELETE_B'					=> 'Delete:',
	'TXT_DELETE_FORUM_B'			=> 'Delete this forum',
	'TXT_REGISTRATED_B'				=> 'Registered users',
	'TXT_NOT_REGISTRATED_B'			=> 'Anonymous users',
	'TXT_BOTH_B'					=> 'Both Registered and Anonymous',
	'TXT_SAVE_B'					=> 'Save',
	'TXT_RESET_B'					=> 'Reset',
	'TXT_CANCEL_B'					=> 'Cancel',
	'TXT_READ_B'					=> 'Read:',
	'TXT_WRITE_B'					=> 'Write:',
	'TXT_FORUMDISPLAY_PERPAGE_B'	=> 'Number of threads per page?',
	'TXT_SHOWTHREAD_PERPAGE_B'		=> 'Number of posts per page?',
	'TXT_PAGENAV_SIZES_B'			=> 'Use different font sizes for page navigation?',
	'TXT_DISPLAY_SUBFORUMS_B'		=> 'Show sub threads on start page?',
	'TXT_DISPLAY_SUBFORUMS_FORUMDISPLAY_B'	=> 'Show sub threads in thread view?',
	'TXT_FORUM_USE_CAPTCHA_B'		=> 'Use captchas for guests?',
	
	'TXT_ADMIN_GROUP_ID_B'			=> 'Additional group (has permission to edit/delete posts)?<br />Keep in mind: the group witch can edit this page got it already!',
	'NO_ADDITIONAL_GROUP'			=> "no additional group",
	
	'TXT_VIEW_FORUM_SEARCH_B'		=> 'Show search field?',
	'TXT_FORUM_MAX_SEARCH_HITS_B'	=> 'Maximum search hits to show?',
	'TXT_FORUM_SENDMAILS_ON_NEW_POSTS_B'	=> 'Send email notifiaction on new post in thread?',
	'TXT_FORUM_ADMIN_INFO_ON_NEW_POSTS_B'	=> 'Inform this address on every new post?',
	'TXT_FORUM_MAIL_SENDER_B'		=> 'Email sender address?',
	'TXT_FORUM_MAIL_SENDER_REALNAME_B'	=> 'Email sender name?',	
	'TXT_USE_SMILEYS_B'				=> 'Use Smileys?',
	'TXT_HIDE_EDITOR_B'				=> 'Hide Editor?',
    'TXT_POSTINGS_B'                => 'Postings',
	
	//	0.5.9 
	
	'Forum_saved'	=> 'Forum saved!',
	'Forum_deleted'	=> 'Forum deleted!',
	'Error_no_title'	=> 'Please insert a titel!',
	'Error_no_comment'	=> 'Please insert a comment for this forum.',
	'Error_no_parent'	=> 'Parent forum fouls!',
	'Error_no_subforum'	=> 'You can not set a forum himself as a subforum!',
	'Error_no_forum'	=> 'Forum fouls!'
);
