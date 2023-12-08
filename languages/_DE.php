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

$module_description = 'Dieses Modul integriert ein einfaches Forum in ihre Webseite.';

$MOD_FORUM = array(
	// Frontend
	'TXT_SUBFORUMS_F'				=> 'Unterforen:',
	'TXT_THEMES_F'					=> 'Themen',
	'TXT_THEME_F'					=> 'Thema',
	'TXT_NO_ACCESS_F'				=> 'Zugriff verweigert!',
	'TXT_FIRST_F'					=> 'Erste',
	'TXT_PREVIOUS_F'				=> 'Vorherige',
	'TXT_NEXT_F'					=> 'N&auml;chste',
	'TXT_LAST_F'					=> 'Letzte',
	'TXT_FROM_F'					=> 'Von',
	'TXT_NO_TOPICS_F'				=> 'In diesem Forum existeren noch keine Themen!',
	'TXT_LAST_ARTICLE_F'			=> 'Letzter Beitrag:',
	'TXT_RESPONSES_F'				=> 'Antworten:',
	'TXT_NEW_TOPIC_F'				=> 'Neues Thema',
	'TXT_TEXT_TO_SHORT_F'		=> 'Text zu kurz!',
	'TXT_USERNAME_TO_SHORT_F'	=> 'Name zu kurz!',
	'TXT_WRONG_CAPTCHA_F'		=> 'Ung&uuml;ltiges Captcha!',
	'TXT_TOPIC_CREATED_F'		=> 'Thema erstellt!',
	'TXT_CREATE_NEW_TOPIC_F'	=> 'Neues Thema erstellen',
	'TXT_USERNAME_F'				=> 'Name:',
	'TXT_VERIFICATION_F'			=> 'Verifikation:',
	'TXT_TEXT_F'					=> 'Beitrag:',
	'TXT_TITLE_F'					=> 'Titel:',
	'TXT_SMILIES_F'				=> 'Smilies:',
	'TXT_SAVE_F'					=> 'Absenden',
	'TXT_RESET_F'					=> 'Zur&uuml;cksetzen',
	'TXT_CANCEL_F'					=> 'Abbrechen',
	'TXT_EDIT_F'					=> 'Editieren',
	'TXT_DELETE_F'					=> 'L&ouml;schen',
	'TXT_TITLE_TO_SHORT_F'		=> 'Titel zu kurz!',
	'TXT_ARTICLE_DELETED_F'		=> 'Beitrag gel&ouml;scht!',
	'TXT_ARTICLE_SAVED_F'		=> 'Beitrag gespeichert!',
	'TXT_EDIT_ARTICLE_F'			=> 'Beitrag bearbeiten',
	'TXT_CREATE_ANSWER_F'		=> 'Antwort verfassen:',
	'TXT_QUOTE_F'					=> 'Zitat',
	'TXT_REALLY_DELETE_F'		=> 'Wollen Sie diesen Beitrag wirklich l%F6schen?',
	'TXT_GUEST_F'					=> 'Gast',
	'TXT_PAGES_F'					=> 'Seiten:',

	'TXT_SEARCH_F'					=> 'Suche',
	'TXT_HITS_F'					=> 'Treffer',
	'TXT_NO_HITS_F'					=> 'Zu Ihrer Anfrage konnten wir leider keine Treffer ermitteln',
	'TXT_NO_SEARCH_STRING_F'		=> 'Bitte geben Sie einen Suchbegriff ein!',
	'TXT_SEARCH_RESULT_F'			=> 'SuchErgebnis',
	'TXT_READMORE_F'				=> 'lesen &raquo;',
	'TXT_MAILS_SEND_F'				=> ' Info-eMail(s) versendet',
	'TXT_MAIL_ERRORS_F'				=> ' Fehler beim Info-Mailversand',

	'TXT_MAILSUBJECT_NEW_POST'		=> 'Ein neuer Beitrag im Forum wurde eingestellt',
	'TXT_MAILTEXT_NEW_POST'			=> "Hallo ##USERNAME##, \n\nSie haben zum Thema \"##THREAD##\" einen Beitrag verfasst.\n" .
										"In diesem Thread hat ##POSTER## einen neuen Beitrag verfasst. \n\n".
										"Sie k&ouml;nnen Ihn nach dem Login hier abrufen: \n##LINK##\n",
	'TXT_MAILTEXT_NEW_POST_ADMIN'		=> "Hallo ##USERNAME##, \n\nZum Thema \"##THREAD##\" hat ##POSTER## einen Beitrag verfasst.\n\n" .
										"Sie k&ouml;nnen Ihn nach dem Login hier abrufen: \n##LINK##\n",

	// Backend
	'TXT_NO_FORUMS_B'				=> 'Keine Foren vorhanden.<br/>
										Erstellen Sie zun&auml;chst ein Forum auf der ersten Ebene.<br/>
										In dieses Forum (wie in alle der ersten Ebene) k&ouml;nnen Sie <i>nicht</i> posten!
										Die erste Ebene dient der Gruppierung der Foren.',
	'TXT_CREATE_FORUM_B'			=> 'Forum erstellen',
	'TXT_FORUMS_B'					=> 'Foren:',
	'TXT_FORUM_B'					=> 'Forum',
	'TXT_EDIT_FORUM_B'			=> 'Forum bearbeiten',
	'TXT_SETTINGS_B'				=> 'Einstellungen',
	'TXT_PERMISSIONS_B'			=> 'Berechtigungen',
	'TXT_TITLE_B'					=> 'Titel:',
	'TXT_DESCRIPTION_B'			=> 'Beschreibung:',
	'TXT_DISPLAY_ORDER_B'		=> 'Anzeigereihenfolge:',
	'TXT_PARENT_FORUM_B'			=> '&Uuml;bergeordnetes Forum:',
	'TXT_DELETE_B'					=> 'L&ouml;schen:',
	'TXT_DELETE_FORUM_B'			=> 'Dieses Forum l&ouml;schen',
	'TXT_REGISTRATED_B'			=> 'Registrierte Benutzer',
	'TXT_NOT_REGISTRATED_B'		=> 'Unregistrierte Benutzer',
	'TXT_BOTH_B'					=> 'Beide',
	'TXT_SAVE_B'					=> 'Speichern',
	'TXT_RESET_B'					=> 'Zur&uuml;cksetzen',
	'TXT_CANCEL_B'					=> 'Abbrechen',
	'TXT_READ_B'					=> 'Lesen:',
	'TXT_WRITE_B'					=> 'Schreiben:',
	'TXT_FORUMDISPLAY_PERPAGE_B'	=> 'Wieviele Themen sollen je Seite angezeigt werden?',
	'TXT_SHOWTHREAD_PERPAGE_B'	=> 'Wieviele Posts sollen je Seite angezeigt werden?',
	'TXT_PAGENAV_SIZES_B'	=> 'Seitennavigation mit verschiedenen Schriftgr&ouml;&szlig;en?',
	'TXT_DISPLAY_SUBFORUMS_B'	=> 'Unterforen auf der Startseite anzeigen?',
	'TXT_DISPLAY_SUBFORUMS_FORUMDISPLAY_B'	=> 'Unterforen in der Themen&uuml;bersicht anzeigen?',
	'TXT_FORUM_USE_CAPTCHA_B'	=> 'Sollen f&uuml;r G&auml;ste Captchas verwendet werden?',
	
	'TXT_ADMIN_GROUP_ID_B'		=> 'Zusätzliche (Administratoren)gruppe (darf Beitr&auml;ge bearbeiten)?<br />Zusätzlich zu denen, die ohnehin diese Seite bearbeiten dürfen!',
	'NO_ADDITIONAL_GROUP'		=> "keine zusätzliche Gruppe",
	
	'TXT_VIEW_FORUM_SEARCH_B'	=> 'Soll das Suchformular angezeigt werden?',
	'TXT_FORUM_MAX_SEARCH_HITS_B'	=> 'Maximale Anzahl der Suchtreffer?',
	'TXT_FORUM_SENDMAILS_ON_NEW_POSTS_B'	=> 'Die Autoren per Mail &uuml;ber neue Beitr&auml;ge im Thema benachrichtigen?',
	'TXT_FORUM_ADMIN_INFO_ON_NEW_POSTS_B'	=> 'Diese Adresse bei neuen Beitr&auml;gen informieren?',
	'TXT_FORUM_MAIL_SENDER_B'	=> 'Absenderadresse f&uuml;r E-Mails?',
	'TXT_FORUM_MAIL_SENDER_REALNAME_B'	=> 'Absendername f&uuml;r E-Mails?',
	'TXT_USE_SMILEYS_B'	=> 'Smileys verwenden?',
	'TXT_HIDE_EDITOR_B'	=> 'Editor verstecken?',
	
	//	0.5.9 
	
	'Forum_saved'	=> 'Forum wurde erfolgreich gespeichert!',
	'Forum_deleted'	=> 'Forum erfolgreich gelöscht!',
	'Error_no_title'	=> 'Bitte einen Titel angeben!',
	'Error_no_comment'	=> 'Bitte einen Kommentar zu diesem Forum angeben.',
	'Error_no_parent'	=> 'Übergeordnetes Forum ungültig!',
	'Error_no_subforum'	=> 'Ein Forum kann nicht sich selbst untergeordnet sein!',
	'Error_no_forum'	=> 'Forum ungültig!'
);
?>