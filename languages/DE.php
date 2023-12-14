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

$module_description = 'Dieses Modul integriert ein einfaches Forum in ihre Webseite.';

$MOD_FORUM = [
    // Frontend
    'TXT_ARTICLE_DELETED_F'          => 'Beitrag gelöscht!',
    'TXT_ARTICLE_SAVED_F'            => 'Beitrag gespeichert!',
    'TXT_CANCEL_F'                   => 'Abbrechen',
    'TXT_CREATE_ANSWER_F'            => 'Antwort verfassen:',
    'TXT_CREATE_NEW_TOPIC_F'         => 'Neues Thema erstellen',
    'TXT_DELETE_F'                   => 'Löschen',
    'TXT_EDIT_ARTICLE_F'             => 'Beitrag bearbeiten',
    'TXT_EDIT_F'                     => 'Editieren',
    'TXT_FIRST_F'                    => 'Erste',
    'TXT_FROM_F'                     => 'Von',
    'TXT_GUEST_F'                    => 'Gast',
    'TXT_LAST_ARTICLE_F'             => 'Letzter Beitrag:',
    'TXT_LAST_F'                     => 'Letzte',
    'TXT_NEW_TOPIC_F'                => 'Neues Thema',
    'TXT_NEXT_F'                     => 'Nächste',
    'TXT_NO_ACCESS_F'                => 'Zugriff verweigert!',
    'TXT_NO_FORUMS_F'                => 'Keine Foren vorhanden.',
    'TXT_NO_TOPICS_F'                => 'In diesem Forum existeren noch keine Themen!',
    'TXT_PAGES_F'                    => 'Seiten:',
    'TXT_PREVIOUS_F'                 => 'Vorherige',
    'TXT_QUOTE_F'                    => 'Zitat',
    'TXT_REALLY_DELETE_F'            => 'Wollen Sie diesen Beitrag wirklich l%F6schen?',
    'TXT_RESET_F'                    => 'Zurücksetzen',
    'TXT_RESPONSES_F'                => 'Antworten:',
    'TXT_SAVE_F'                     => 'Absenden',
    'TXT_SMILIES_F'                  => 'Smilies:',
    'TXT_SUBFORUMS_F'                => 'Unterforen:',
    'TXT_TEXT_F'                     => 'Beitrag:',
    'TXT_TEXT_TO_SHORT_F'            => 'Text zu kurz!',
    'TXT_THEME_F'                    => 'Thema',
    'TXT_THEMES_F'                   => 'Themen',
    'TXT_TITLE_F'                    => 'Titel:',
    'TXT_TITLE_TO_SHORT_F'           => 'Titel zu kurz!',
    'TXT_TOPIC_CREATED_F'            => 'Thema erstellt!',
    'TXT_USERNAME_F'                 => 'Name:',
    'TXT_USERNAME_TO_SHORT_F'        => 'Name zu kurz!',
    'TXT_VERIFICATION_F'             => 'Verifikation:',
    'TXT_WRONG_CAPTCHA_F'            => 'Ungültiges Captcha!',

    'TXT_SEARCH_F'                   => 'Suche',
    'TXT_HITS_F'                     => 'Treffer',
    'TXT_NO_HITS_F'                  => 'Zu Ihrer Anfrage konnten wir leider keine Treffer ermitteln.',
    'TXT_NO_SEARCH_STRING_F'         => 'Bitte geben Sie einen Suchbegriff ein!',
    'TXT_SEARCH_RESULT_F'            => 'Suchergebnis',
    'TXT_READMORE_F'                 => 'lesen »',
    'TXT_MAILS_SEND_F'               => ' Info-eMail(s) versendet',
    'TXT_MAIL_ERRORS_F'              => ' Fehler beim Info-Mailversand',

    'TXT_MAILSUBJECT_NEW_POST'       => 'Ein neuer Beitrag im Forum wurde eingestellt',
    'TXT_MAILTEXT_NEW_POST'          => "Hallo ##USERNAME##, \n\nSie haben zum Thema \"##THREAD##\" einen Beitrag verfasst.\n" .
                                        "In diesem Thread hat ##POSTER## einen neuen Beitrag verfasst. \n\n".
                                        "Sie k&ouml;nnen Ihn nach dem Login hier abrufen: \n##LINK##\n",
    'TXT_MAILTEXT_NEW_POST_ADMIN'    => "Hallo ##USERNAME##, \n\nZum Thema \"##THREAD##\" hat ##POSTER## einen Beitrag verfasst.\n\n" .
                                        "Sie k&ouml;nnen Ihn nach dem Login hier abrufen: \n##LINK##\n",

    // Backend
    'TXT_NO_FORUMS_B'                => 'Keine Foren vorhanden.<br/>
                                        Erstellen Sie zunächst ein Forum auf der ersten Ebene.<br/>
                                        In dieses Forum (wie in alle der ersten Ebene) können Sie <i>nicht</i> posten!
                                        Die erste Ebene dient der Gruppierung der Foren.',
    'TXT_BOTH_B'                     => 'Beide',
    'TXT_CANCEL_B'                   => 'Abbrechen',
    'TXT_CREATE_FORUM_B'             => 'Forum erstellen',
    'TXT_DATE_B'                     => 'Datum:',
    'TXT_DELETE_B'                   => 'L&ouml;schen:',
    'TXT_DELETE_FORUM_B'             => 'Dieses Forum löschen',
    'TXT_DESCRIPTION_B'              => 'Beschreibung:',
    'TXT_DISPLAY_ORDER_B'            => 'Position:',
    'TXT_DISPLAY_SUBFORUMS_B'        => 'Unterforen auf der Startseite anzeigen?',
    'TXT_DISPLAY_SUBFORUMS_FORUMDISPLAY_B' => 'Unterforen in der Themenübersicht anzeigen?',
    'TXT_EDIT_FORUM_B'               => 'Forum bearbeiten',
    'TXT_FORUM_B'                    => 'Forum',
    'TXT_FORUM_USE_CAPTCHA_B'        => 'Sollen für Gäste Captchas verwendet werden?',
    'TXT_FORUMDISPLAY_PERPAGE_B'     => 'Wieviele Themen sollen je Seite angezeigt werden?',
    'TXT_FORUMS_B'                   => 'Foren:',
    'TXT_NOT_REGISTRATED_B'          => 'Unregistrierte Benutzer',
    'TXT_PAGENAV_SIZES_B'            => 'Seitennavigation mit verschiedenen Schriftgr&ouml;&szlig;en?',
    'TXT_PARENT_FORUM_B'             => 'Übergeordnetes Forum:',
    'TXT_PERMISSIONS_B'              => 'Berechtigungen',
    'TXT_POSTINGS_B'                 => 'Beiträge',
    'TXT_READ_B'                     => 'Lesen:',
    'TXT_READONLY_B'                 => 'In diesem Forum können keine Beiträge erstellt werden',
    'TXT_REGISTRATED_B'              => 'Registrierte Benutzer',
    'TXT_RESET_B'                    => 'Zurücksetzen',
    'TXT_SAVE_B'                     => 'Speichern',
    'TXT_SETTINGS_B'                 => 'Einstellungen',
    'TXT_SHOWTHREAD_PERPAGE_B'       => 'Wieviele Posts sollen je Seite angezeigt werden?',
    'TXT_TITLE_B'                    => 'Titel:',
    'TXT_WRITE_B'                    => 'Schreiben:',

    'TXT_ADMIN_GROUP_ID_B'          => 'Zusätzliche (Administratoren)gruppe (darf Beiträge bearbeiten)?<br />Zusätzlich zu denen, die ohnehin diese Seite bearbeiten dürfen!',
    'NO_ADDITIONAL_GROUP'           => "keine zusätzliche Gruppe",
    
    'TXT_VIEW_FORUM_SEARCH_B'       => 'Soll das Suchformular angezeigt werden?',
    'TXT_FORUM_MAX_SEARCH_HITS_B'   => 'Maximale Anzahl der Suchtreffer?',
    'TXT_FORUM_SENDMAILS_ON_NEW_POSTS_B'    => 'Die Autoren per Mail über neue Beiträge im Thema benachrichtigen?',
    'TXT_FORUM_ADMIN_INFO_ON_NEW_POSTS_B'   => 'Diese Adresse bei neuen Beiträgen informieren?',
    'TXT_FORUM_MAIL_SENDER_B'           => 'Absenderadresse für E-Mails?',
    'TXT_FORUM_MAIL_SENDER_REALNAME_B'  => 'Absendername für E-Mails?',
    'TXT_USE_SMILEYS_B'    => 'Smileys verwenden?',
    'TXT_HIDE_EDITOR_B'    => 'Editor verstecken?',

    'Forum_saved'       => 'Forum wurde erfolgreich gespeichert!',
    'Forum_deleted'     => 'Forum erfolgreich gelöscht!',
    'Error_no_title'    => 'Bitte einen Titel angeben!',
    'Error_no_comment'  => 'Bitte einen Kommentar zu diesem Forum angeben.',
    'Error_no_parent'   => 'Übergeordnetes Forum ungültig!',
    'Error_no_subforum' => 'Ein Forum kann nicht sich selbst untergeordnet sein!',
    'Error_no_forum'    => 'Forum ungültig!'
];
