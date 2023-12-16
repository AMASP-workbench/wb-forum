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

//    No direct file access
if(count(get_included_files())==1) die(header("Location: ../../index.php",TRUE,301));
if(!defined('WB_PATH')) die(header("Location: ../../index.php",TRUE,301));

class forum_parser
{
    const IS_WB     = 0x0001;
    const IS_WBCE   = 0x0002;
    const IS_LEPTON = 0x0004;
    
    public $twig_loaded = false;
    public $loader = NULL;
    public $parser = NULL;
    public $template_path = "";
    public $CMS_PATH = "";
    public $CMS_URL    = "";
    public $CMS        = 0;
    
    /**
     * constructor
     **/
    public function __construct()
    {
        $this->initWorld();
    }
    
    /**
     * render
     * Uses Twig if available; simple replacement of {{var}} otherwise
     *
     * @param string sFilename
     * @param array  aData
     * @return string
     **/
    public function render($sFilename, &$aData): string
    {
        if (true === $this->twig_loaded )
        {
            return $this->parser->render($sFilename, $aData);
        }
        
        if (!file_exists($this->template_path.$sFilename))
        {
            die("[1098] File not found: ".$sFilename);
        }
        
        $sReturnvalue = file_get_contents($this->template_path.$sFilename);
        
        $this->strip_twig_tags($sReturnvalue);
        
        foreach($aData as $key => $value)
        {
            $sReturnvalue = str_replace("{{ ".$key." }}", $value, $sReturnvalue);
        }
        
        return $sReturnvalue;
    }
    
    /**
     *  We are not using the twig engine ... we will have to strip
     *  the twig specific code from the "template":
     *
     *  @param  string  $source  Any source - call by reference!
     *
     */
    public function strip_twig_tags(&$source): void
    {
        $pattern = [
            "/{%(.*?)%}/",  // execute statement
            "/{#(.*?)#}/"   // comments
        ];
        
        $string = preg_replace($pattern, "", $source);
    }
    
    private function initWorld(): void
    {
        if (defined("LEPTON_PATH"))
        {
            $this->CMS_PATH = LEPTON_PATH;
            $this->CMS_URL = LEPTON_URL;
            $this->CMS = self::IS_LEPTON;
        }
        else if (defined("WB_PATH"))
        {
            $this->CMS_PATH = WB_PATH;
            $this->CMS_URL = WB_URL;
            $this->CMS = self::IS_WB;
        }
        
        if (defined("NEW_WBCE_TAG"))
        {
            $this->CMS = self::IS_WBCE;
        }
        
        $this->template_path = dirname(__FILE__, 2)."/templates/";

        $this->parser = getTwig($this->template_path);

        $this->twig_loaded = true;
    }
}
