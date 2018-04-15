<?php

/**
 * 
 */
class arquematicsMenuPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '0.1';
  
  static $registered = false;
  
  public function initialize()
  {
      
    if (!self::$registered)
    {
        $this->dispatcher->connect('ar.addslot', array('arMenu', 'addSlot'));
 
        $this->dispatcher->connect('ar.deleteslot', array('arMenu', 'deleteSlot'));
  
     
      // This was inadvertently removed just prior to 1.4. Now apostrophe:migrate hooks up properly again
      self::$registered = true;
    }
    
  }
 
}
