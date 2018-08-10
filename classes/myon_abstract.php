<?php

/**
 *  This is an experimental abstract class for modules for WB, especially for the 2.12.x.
 *  Keep in mind that this one is a private project and has nothing to do with
 *  the official WB core/module development.
 *
 *	@project        myon (µon)
 *	@version		0.1.0
 *	@authors		Dietrich Roland Pehlke (Aldus)
 *	@license		GNU General Public License
 *	@platform		WB 2.12.0
 *	@requirements	PHP >= 7.2.1
 *
 */

namespace addon\forum\classes;

abstract class myon_abstract
{
    /**
     *  Array with the language(-array) of the child-object.
     *  @type   array
     *
     */
    public $language = array();
    
    /**
     *  Array with the names of all parents (desc. order)
     *  @type   array
     *
     */
    public $parents = array();
    
    /**
     *  The module directory from the info.php of the child.
     *  @type   string
     *
     */
    public $module_directory = "";

    /**
     *  The module name from the info.php of the child.
     *  @type   string
     *
     */
    public $module_name = "";
    
    /**
     *  The module function from the info.php of the child.
     *  @type   string
     *
     */
    public $module_function = "";

    /**
     *  The module version from the info.php of the child.
     *  @type   string
     *
     */
    public $module_version = "";
    
    /**
     *  The module platform from the info.php of the child.
     *  @type   string
     *
     */
    public $module_platform = "";
    
    /**
     *  The module author from the info.php of the child.
     *  @type   string
     *
     */
    public $module_author = "";

    /**
     *  The module license from the info.php of the child.
     *  @type   string
     *
     */
    public $module_license = "";
    
    /**
     *  The module license terms from the info.php of the child.
     *  @type   string
     *
     */
    public $module_license_terms = "";
    
    /**
     *  The module description from the info.php of the child.
     *  @type   string
     *
     */
    public $module_description = "";
    
    /**
     *  The module guid from the info.php of the child.
     *  @type   string
     *
     */
    public $module_guid = "";

    /**
     *  @var    object  The reference to the *Singleton* instance of this class.
     *  @notice         Keep in mind that a child-object has to have his own one!
     */
    static $instance;

    /**
     *  Return the instance of this class.
     *
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
            static::$instance->getParents();
            static::$instance->getModuleInfo();
            static::$instance->getLanguageFile();
            static::$instance->initialize();
        }
        return static::$instance;
    }

    /**
     *  Try to get all parents form the current instance as a simple linear list.
     */
    private function getParents()
    {
        // First the class itself
        static::$instance->parents[] = get_class(static::$instance);
        
        // Now the parents        
        $aTempParents = class_parents( static::$instance, true );
        foreach($aTempParents as $sParentname)
        {
            static::$instance->parents[] = $sParentname;
        }
    }
    
    /**
     *  Try to read the module specific info.php from the module-Directory
     *  and update the current class-properties.
     *
     */
    final private function getModuleInfo()
    {
        
        foreach(static::$instance->parents as $sModuleDirectory)
        {
            //  strip namespace
            $aTemp = explode("\\", $sModuleDirectory);
            $sModuleDirectory = array_pop($aTemp);
            
            //$sLookUpPath = __DIR__."/../../modules/".$sModuleDirectory."/info.php";
            $sLookUpPath = WB_PATH."/modules/".$sModuleDirectory."/info.php";
            
            if( file_exists($sLookUpPath) )
            {
                require $sLookUpPath;

                if(isset($module_name)) static::$instance->module_name = $module_name;
                if(isset($module_directory)) static::$instance->module_directory = $module_directory;
                if(isset($module_function)) static::$instance->module_function = $module_function;
                if(isset($module_version)) static::$instance->module_version = $module_version;
                if(isset($module_platform)) static::$instance->module_platform = $module_platform;
                if(isset($module_author)) static::$instance->module_author = $module_author;
                if(isset($module_license)) static::$instance->module_license = $module_license;
                if(isset($module_license_terms)) static::$instance->module_license_terms = $module_license_terms;
                if(isset($module_description)) static::$instance->module_description = $module_description;
                if(isset($module_guid)) static::$instance->module_guid = $module_guid;

                break;
            }
        }
    }
    
    /**
     *  Try to get a module-spezific language file.
     */
    final private function getLanguageFile()
    {
        if(defined("WB_PATH"))
        {
            foreach( static::$instance->parents as $sClassName)
            {
                //  strip namespace
                $aTemp = explode("\\", $sClassName);
                $sClassName = array_pop($aTemp);

                $lookUpPath = WB_PATH."/modules/".$sClassName."/languages/";
                if(file_exists($lookUpPath.LANGUAGE.".php"))
                {
                    require $lookUpPath.LANGUAGE.".php";
                } elseif ( file_exists($lookUpPath."EN.php"))
                {
                    require $lookUpPath."EN.php";
                } else {
                    
                    continue;
                }
            
                $tempName = "MOD_".strtoupper($sClassName);
                if(isset(${$tempName}))
                {
                    static::$instance->language = ${$tempName}; 
                    break;
                }
            }
        }
    }

    /**
     *  Abstact declarations - to be overwrite by the child-instance.
     */
    abstract protected function initialize();
}