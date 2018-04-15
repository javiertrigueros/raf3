<?php
/**
 * Ayuda para trabajar con las urls internas del proyecto
 * 
 * @package         arquematicsPlugin
 * @subpackage      util
 * @author          Javier trigueros
 */
class arMenuInfo
{

   const WALL = 0;
   const BLOG = 1;
   const EVENTS = 2;
   const ADMINBLOG = 3;
   const ADMINEVENTS = 4;
   const ADMINCAT = 5;
   const ADMINTAG = 6;
   const ADMINMEDIA = 7;
   const PAGE = 8;
   const HOME = 9;
   const USERS = 10;
   const GROUPS = 11;
   
   static public $urlBackData = array(
       0 => array( 
                'text' => 'Go to Wall',
                'catalog' => 'adminMenu',
                'url' => '@wall?pag=1'),
       1 => array( 
                'text' => 'Go to Blog',
                'catalog' => 'adminMenu',
                'url' => '@a_blog'),
       2 => array( 
                'text' => 'Go to Events',
                'catalog' => 'adminMenu',
                'url' => '@a_event'),
       3 => array( 
                'text' => 'Go to Admin Blog',
                'catalog' => 'adminMenu',
                'url' => '@a_blog_admin'),
       4 => array( 
                'text' => 'Go to Admin Events',
                'catalog' => 'adminMenu',
                'url' => '@a_event_admin'),
       5 => array( 
                'text' => 'Go to Admin Categories',
                'catalog' => 'adminMenu',
                'url' => 'aCategoryAdmin/index'),
        6 => array( 
                'text' => 'Go to Admin Tags',
                'catalog' => 'adminMenu',
                'url' => 'aTagAdmin/index'),
        7 => array( 
                'text' => 'Go to Admin Media',
                'catalog' => 'adminMenu',
                'url' => 'aMedia/index'),
        8 => array( 
                'text' => 'Back',
                'catalog' => 'adminMenu',
                'url' => 'homepage'),
        9 => array( 
                'text' => 'Home page',
                'catalog' => 'adminMenu',
                'url' => 'homepage'),
        10 => array( 
                'text' => 'Manage users',
                'catalog' => 'apostrophe',
                'url' => 'aUserAdmin/index'),
        11 => array( 
                'text' => 'Manage groups',
                'catalog' => 'apostrophe',
                'url' => 'aGroupAdmin/index')
       
   );
   
   public function __get($name)
   {
      if(defined("self::$name"))
      {
         return constant("self::$name");
         
      }
      trigger_error ("$name  isn't defined");
   }
   
   
   static public function get($menuId)
   {
       $info = arMenuInfo::$urlBackData[$menuId];
       
       return array(
          'text' => __($info['text'], null, $info['catalog']),
          'url' => url_for($info['url'])
       );
      
   }
   
}


