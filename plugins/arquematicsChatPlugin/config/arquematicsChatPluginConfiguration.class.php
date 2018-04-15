<?php

/**
 * 
 */
class arquematicsChatPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '0.1';
    
  
  public function initialize()
  {
      $enabledModules = sfConfig::get('sf_enabled_modules', array());
      
      
      $this->dispatcher->connect('user.change_authentication', array('arSessionHistory', 'doAuthChange'));
      
      $this->dispatcher->connect('user.filter_register', array('sfGuardUserProfile', 'doRegister'));
     /*
      // si esta activo el modulo sfGuardRegister
      if (in_array('sfGuardRegister', $enabledModules))
      {
          $this->dispatcher->connect('arquematics.site-creation', $this, 'generateSite');
      }
      */
      
      //$this->dispatcher->connect('user.filter_register', array('ofUser', 'doRegister'));
     /*
      // si esta activo el modulo sfGuardRegister
      if (in_array('sfGuardRegister', $enabledModules))
      {
          $this->dispatcher->connect('arquematics.site-creation', $this, 'generateSite');
      }
      */
      
    
  }
 
}
