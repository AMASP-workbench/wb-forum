<?php

/**
 *
 *	@module			Forum
 *	@version		0.6
 *	@authors		Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke, Bianka Martinovic (last)
 *	@license		GNU General Public License
 *	@platform		2.8.x
 *	@requirements	PHP 5.6.x and higher
 *
 */

//	No direct file access
if(count(get_included_files())==1) die(header("Location: ../../index.php",TRUE,301));
if(!defined('WB_PATH')) die(header("Location: ../../index.php",TRUE,301));

class forum_parser
{

	const IS_WB		= 0x0001;
	const IS_WBCE	= 0x0002;
	const IS_LEPTON	= 0x0004;
	
	public $twig_loaded = false;
	public $loader = NULL;
	public $parser = NULL;
	public $template_path = "";
	public $CMS_PATH = "";
	public $CMS_URL	= "";
	public $CMS		= 0;
	
    /**
     * constructor
     **/
	public function __construct() {
		$this->initWorld();
	}
	
    /**
     * render
     * uses Twig if available; simple replacement of {{var}} otherwise
     *
     * @param string sFilename
     * @param array  aData
     * @return string
     **/
	public function render($sFilename, &$aData)
    {
		if( true === $this->twig_loaded ) {
			return $this->parser->render( $sFilename, $aData );
		}
		
		if(!file_exists($this->template_path.$sFilename)) {
			die( "File not found: ".$sFilename );
		}
		$sReturnvalue = file_get_contents($this->template_path.$sFilename);
		
		$this->strip_twig_tags($sReturnvalue);
		
		foreach($aData as $key => $value) {
			$sReturnvalue = str_replace("{{ ".$key." }}", $value, $sReturnvalue);
		}
		
		return $sReturnvalue;
	}
	
	/**
	 *	We are not using the twig engine ... we will have to strip
	 *	the twig specific code from the "template":
	 *
	 *	@param	string	Any source - pass by reference!
	 *
	 */
	public function strip_twig_tags(&$string) {
		$pattern = array(
			"/{%(.*?)%}/",	// execute statement
			"/{#(.*?)#}/"	// comments
		);
		
		$string = preg_replace($pattern, "", $string);
	}
	
	private function initWorld() {
		if(defined("LEPTON_PATH")) {
			$this->CMS_PATH = LEPTON_PATH;
			$this->CMS_URL = LEPTON_URL;
			$this->CMS = self::IS_LEPTON;
		} else if (defined("WB_PATH") ) {
			$this->CMS_PATH = WB_PATH;
			$this->CMS_URL = WB_URL;
			$this->CMS = self::IS_WB;
		}
		
		if(defined("NEW_WBCE_TAG")) {
			$this->CMS = self::IS_WBCE;
		}
		
		$this->template_path = dirname(dirname(__FILE__))."/templates/";
		
		switch( $this->CMS ) {
			case self::IS_LEPTON :
			
				// LEPTON-CMS
				$look_up_path = $this->CMS_PATH."/modules/lib_twig/Twig/Autoloader.php";
				break;
			
			case self::IS_WBCE :
				// WBCE
				$look_up_path = $this->CMS_PATH."/modules/twig/classes/Sensio/Twig/lib/Twig/Autoloader.php";
				break;
				
			case self::IS_WB :
				// WB
				$look_up_path = $this->CMS_PATH."/include/Sensio/Twig/1/lib/Twig/Autoloader.php";
				break;
				
			default:
				$look_up_path = "";
		}

		if(file_exists($look_up_path)) {
    	    if(!class_exists("Twig_Autoloader", true))
		    {
			    require_once( $look_up_path );
			    Twig_Autoloader::register();
			}
		
			$this->loader = new Twig_Loader_Filesystem( $this->template_path );
		
			$this->parser = new Twig_Environment( $this->loader, array(
				'cache' => false,
				'debug' => true
			) );
		
			$this->twig_loaded = true;
		}
	}
}
