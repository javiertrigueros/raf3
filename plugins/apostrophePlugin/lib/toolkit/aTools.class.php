<?php
/**
 * You can extend me at the project level by overriding this file with your own version and extending BaseaTools
 * @package    apostrophePlugin
 * @subpackage    toolkit
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class aTools extends BaseaTools
{
  static protected $iconTranslate = array(
      'Blog' => 'fa fa-comment-o',
      'Events' => 'fa fa-calendar',
      'Categories' => 'fa fa-tag',
      'Tags' => 'fa fa-tags',
      'Media' => 'fa fa-youtube-play'
  );
  
  static public function getIconTranslate($name)
  {
     return aTools::$iconTranslate[$name]; 
  }
    
  /**
   * cuentalos comentarios no moderados
   * 
   * @return <int>
   */
  static public function countNotModerated()
  {
    return Doctrine::getTable('arComment')->countNotModerated();
  }
  
}
