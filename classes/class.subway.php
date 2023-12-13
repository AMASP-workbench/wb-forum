<?php

//    No direct file access
if(count(get_included_files())==1) die(header("Location: ../../index.php",TRUE,301));
if(!defined('WB_PATH')) die(header("Location: ../../index.php",TRUE,301));

/**
 *    Nothing more and nothing less than
 *    some helper-functions.
 *
 */

require_once __DIR__."/class.forum_parser.php";

class subway extends forum_parser
{

    protected    $guid = "E9B57628-F304-4CD3-AE6B-A56C359C2F60";

    protected    $author = "Dietrich Roland Pehlke (Aldus)";

    protected    $version = "0.2.0";

    public $db = NULL;

    public function __construct()
    {
        parent::__construct();

        $this->db = &$GLOBALS['database'];

        if (true === method_exists($this->db,"escapeString"))
        {
            if (isset($_POST['title']))
            {
                $_POST['title'] = $this->db->escapeString($_POST['title']);
            }

            if (isset($_POST['text']))
            {
                $_POST['text'] = $this->db->escapeString( $_POST['text']);
            }
        }
    }

    public function print_error( $sMessage="", $sLink="" )
    {
        global $TEXT;

        $values = [
            'BACK'    => $TEXT['BACK'],
            'MESSAGE'    => $sMessage,
            'LINK'    =>     $sLink
        ];

        return $this->render(
            "error.lte",
            $values
        );
    }

    public function register(&$aLookUp, $aKey="", $aDefault=NULL)
    {
        if (!isset($aLookUp[$aKey])) $aLookUp[$aKey] = $aDefault;
    }

    public function display($any)
    {
        $s = "<pre>";
        ob_start();
            print_r($any);
        $s .= ob_get_clean();
        $s .= "</pre>";
        return $s;
    }

    public function get_pageinfo($aID)
    {
        $r = $this->db->query("SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$aID);

        if ($r)
        {
            return $r->fetchRow(MYSQLI_ASSOC); // WB_URL.PAGES_DIRECTORY.$r.PAGE_EXTENSION;
        }
    }

    public function get_all($sql, &$aStorage)
    {
        $result = $this->db->query($sql);
        if (!$result)
        {
            return 0;
        }
        else
        {
            while($ref = $result->fetchRow(MYSQLI_ASSOC))
            {
                $aStorage[] = $ref;
            }
        }
    }
}
