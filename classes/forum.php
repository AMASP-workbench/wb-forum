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
 
class forum
{
    public static $instance;

    /**
     *  Return the instance of this class.
     *
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    /**
     *  See details about the applied technique:
     *  https://forum.wbce.org/viewtopic.php?id=1598
     *
     */
    public function getFrontendTemplateCSS(): string
    {
        $html = "";
        
        $lookupPath = "/templates/".$this->getFrontendTemplate()."/frontend/forum/frontend.css";
        if (file_exists(WB_PATH.$lookupPath))
        {
            $html .= "
                \n<!--(MOVE) JS BODY BTM- -->
                \n<script>
                $('head').append(\"<link rel='stylesheet' type='text/css' href='".WB_URL.$lookupPath."' />\");
                </script>
                <!--(END)-->\n";
        }
        
        return $html;
    }
    
    /**
     *  See details about the applied technique:
     *  https://forum.wbce.org/viewtopic.php?id=1598
     *
     */
    public function getBackendThemeCSS(): string
    {
        $html = "";
        
        $lookupPath = "/templates/".DEFAULT_THEME."/backend/forum/backend.css";
        if (file_exists(WB_PATH.$lookupPath))
        {
            $html .= "
                \n<!--(MOVE) JS BODY BTM- -->
                \n<script>
                $('head').append(\"<link rel='stylesheet' type='text/css' href='".WB_URL.$lookupPath."' />\");
                </script>
                <!--(END)-->\n";
        }
        
        return $html;
    }
    
    protected function getFrontendTemplate(): string
    {
        global $wb;
        
        return (($wb->page['template'] ?? "") === "") ? DEFAULT_TEMPLATE : $wb->page['template']; 
    }
}