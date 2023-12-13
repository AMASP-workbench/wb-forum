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
    
    protected function getFrontendTemplate(): string
    {
        global $wb;
        
        return ($wb->page['template'] === "") ? DEFAULT_TEMPLATE : $wb->page['template']; 
    }
}