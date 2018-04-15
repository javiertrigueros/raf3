<?php
/**
 * Informacion sobre los módulos, tabs y herramientas
 * que están activos/deshabilitados
 * 
 * @package         arquematicsPlugin
 * @subpackage      util
 * @author          Javier Trigueros Martínez de los Huertos
 */
class arSystemInfo
{
    static private $instancia = null;
    //herramientas dentro de un tab. En este caso wall
    static  $tools = array(
                "arFileUpload" =>   array("pos" => "0",
                                          "name" => "glyphicon-paperclip",
                                          "show-icon" => true,

                                          "hasFileSystemDir" => true,
                                          "main-dir" => "arFileUpload",
                                          "subdirs" => "app_arquematics_plugin_image_wall_filters",  
                                          
                                          "merge" => false,
                                          "merge-alias" => false,
                                          "merge-icon" => false,
                                          "hasAdminCredential" => false),
        
                "arDocEditor" =>          array("pos" => "1",
                                          "name" => "glyphicon-file",
                                          "show-icon" => true,

                                          "hasFileSystemDir" => false,
                                          "main-dir" => "",
                                          "subdirs" => "",  
                                         
                                          //mezclar con arVectorialEditor
                                          "merge" => 'arVectorialEditor',
                                          "merge-alias" => 'documentEditor',
                                          "merge-icon" => 'fa fa-th',
                                          "hasAdminCredential" => false),
        
               "arVectorialEditor" =>  array("pos" => "5",
                                          "name" =>"glyphicon-th",
                                          "show-icon" => true,

                                          "hasFileSystemDir" => false,
                                          "main-dir" => "",
                                          "subdirs" => "",

                                          "merge" => 'arDocEditor',
                                          "merge-alias" => 'documentEditor',
                                          "merge-icon" => 'fa fa-th',
                                          "hasAdminCredential" => false),
        
                "arLink" =>         array("pos" => "3",
                                          "name" =>"glyphicon-link",
                                          "show-icon" => false,
                                          
                                          "hasFileSystemDir" => true,
                                          "main-dir" => "arWallLink",
                                          "subdirs" => "app_arquematics_plugin_image_link_filters",

                                          "merge" => false,
                                          "merge-alias" => false,
                                          "merge-icon" => false,
                                          "hasAdminCredential" => false),


        
                "arMap" =>          array("pos" => "4",
                                          "name" =>"glyphicon-map-marker",
                                          "show-icon" => false,

                                          "hasFileSystemDir" => false,
                                          "main-dir" => "",
                                          "subdirs" => "",  

                                          "merge" => 'arDocEditor',
                                          "merge-alias" => 'documentEditor',
                                          "merge" => false,
                                          "merge-alias" => false,
                                          "merge-icon" => false,
                                          "hasAdminCredential" => false),
        
                "arDrop" =>  array("pos" => "2",
                                          "name" =>"fa fa-cloud-upload",
                                          "show-icon" => true,

                                          "hasFileSystemDir" => false,
                                          "main-dir" => "",
                                          "subdirs" => "",

                                          "merge" => false,
                                          "merge-alias" => 'arDrop',
                                          "merge-icon" => false,
                                          "hasAdminCredential" => false)
        
                );
    //herramientas que son un tab

    static  $tabTools = array(
                "arWall" =>          array("pos" => "0",
                                           "name" => "fa fa-quote-left",
                                           "hasFileSystemDir" => false,
                                           "listControl" => '#wallMessage_groups',
                                           "hasAdminCredential" => false),
        
                "aBlog"  =>          array("pos" => "1",
                                           "name" => "fa fa-comment-o",
                                           "hasFileSystemDir" => false,
                                           "listControl" => '#a_blog_new_post_groups',
                                           "hasAdminCredential" => true),
        
                "aEvent" =>          array("pos" => "2",
                                           "name" => "fa fa-calendar",
                                           "hasFileSystemDir" => false,
                                           "listControl" => '#a_new_event_groups',
                                           "hasAdminCredential" => true)
                );
    
    function  __construct() {}
    
    /**
     * implementacion patrón Singleton
     * @return <arSystemInfo>
     */
    static public function getInstance()
    {
       if (self::$instancia == null) {
          self::$instancia = new arSystemInfo();
       }
       return self::$instancia;
    }
    /**
     * lista de tabs activos para un usuario, 
     * ordenados por el campo pos
     * 
     * @param <boolean> $isAdmin : true si tiene permisos de administrador
     * @return <array of array(pos, name, is_active, icon)>
     */
    public function getEnabledTabs($isAdmin)
    {
      $enabledTabs = array();
      
      $availableModules = array_keys(arSystemInfo::$tabTools);
      
      $enabledModules = sfConfig::get('sf_enabled_modules');
      
      if ($enabledModules && (count($enabledModules) > 0))
      {
        foreach ($enabledModules as $module)
        {
         
          if ((in_array($module, $availableModules)) 
                  && ($isAdmin || (!arSystemInfo::$tabTools[$module]['hasAdminCredential'])))
          {
            $enabledTabs[$module] = 
                    array(
                        'pos' => arSystemInfo::$tabTools[$module]['pos'],
                        'name' => $module,
                        'listControl' => arSystemInfo::$tabTools[$module]['listControl'],
                        'icon' => arSystemInfo::$tabTools[$module]['name'],
                        'is_active' => false);  
          }
        }
        
        if (in_array ( 'aBlog' , array_keys($enabledTabs))
          && (!Doctrine::getTable('aPage')
                 ->hasEnginePage('aBlog')))
        {
          unset($enabledTabs['aBlog']);
        }
      
        if (in_array ( 'aEvent' , array_keys($enabledTabs))
          && (!Doctrine::getTable('aPage')
                 ->hasEnginePage('aEvent')))
        {
          unset($enabledTabs['aEvent']);
        }
        
        //print_r($enabledTabs);
        //exit();
      
        if ($enabledTabs  && (count($enabledTabs) > 0))
        {
            DataAlgorithms::sortArray( $enabledTabs, 'pos', CSORT_ASC);  
     
            //por defecto el primer tab esta activo
            $enabledTabs['arWall']['is_active'] = true;
        } 
      }
      
      return $enabledTabs;
        
    }
    
    /**
     * devuelve la lista de herramientas activas para un
     * usuario ordenadas por la posicion
     * 
     * @param <boolean> $isAdmin : true si tiene permisos de administración
     * @return <array of array(pos, name, is_active, icon)>
     */
    public function getEnabledTools($isAdmin)
    {
      
      $enabledTools = array();
      
      $availableModules = array_keys(arSystemInfo::$tools);
      
      $enabledModules = sfConfig::get('sf_enabled_modules');
      
      $aliasModules = array();

      if ($enabledModules && (count($enabledModules) > 0))
      {
          foreach ($enabledModules as $module)
          {
              
            if (!(in_array($module, $aliasModules))
                  &&  (in_array($module, $availableModules))
                  && ($isAdmin || (!arSystemInfo::$tools[$module]['hasAdminCredential'])))
            {
                $enabledTools[] = 
                    array(
                        "hasFileSystemDir" => arSystemInfo::$tools[$module]["hasFileSystemDir"],
                        "main-dir" => arSystemInfo::$tools[$module]["main-dir"],
                        "subdirs" =>   arSystemInfo::$tools[$module]["subdirs"],
                        'pos' => arSystemInfo::$tools[$module]['pos'],
                        'name' => $module,
                        'show-icon' => arSystemInfo::$tools[$module]['show-icon'],
                        'is_active' => false,
                        'merge' => arSystemInfo::$tools[$module]['merge'],
                        'merge-alias' => arSystemInfo::$tools[$module]['merge-alias'],
                        'merge-icon' => arSystemInfo::$tools[$module]['merge-icon'],
                        'icon' => arSystemInfo::$tools[$module]['name']);
                
                if (arSystemInfo::$tools[$module]['merge'])
                {
                  $aliasModules[] = arSystemInfo::$tools[$module]['merge'];   
                }
            }
          }
      
          if (count($enabledTools) > 0)
          {
                DataAlgorithms::sortArray( $enabledTools , 'pos', CSORT_DESC);  
          }
           
      }
     
      return $enabledTools;
      
    }
   
}


