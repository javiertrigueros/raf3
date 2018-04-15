<?php

/**
 * 
 */
class arquematicsPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '0.1';
    
  
  public function initialize()
  {
      $enabledModules = sfConfig::get('sf_enabled_modules', array());
      
      if (sfConfig::get('app_arquematics_encrypt',false))
      {
        $this->dispatcher->connect('arUser.publicKeyAdd', array('sfGuardUserProfile', 'publicKeyAdd'));  
      }
      
      
      if (sfConfig::get('app_arquematics_send_private_key',false))
      {
        $this->dispatcher->connect('arUser.sendPrivateKey', array('sfGuardUserProfile', 'sendPrivateKey'));   
      }
      
      
      
     /*
      // si esta activo el modulo sfGuardRegister
      if (in_array('sfGuardRegister', $enabledModules))
      {
          $this->dispatcher->connect('arquematics.site-creation', $this, 'generateSite');
      }
      */
      
    
  }
  
}
