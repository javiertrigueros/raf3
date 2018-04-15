<?php

require dirname(__FILE__) . '/../lib/BaseaCategoryAdminActions.class.php';

/**
 * 
 * aCategoryAdmin actions.
 * @package    aBlogPlugin
 * @subpackage aCategoryAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class aCategoryAdminActions extends BaseaCategoryAdminActions
{
 public function preExecute()
 {
     sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
     
    $this->configuration = new aCategoryAdminGeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new aCategoryAdminGeneratorHelper();

    aTools::setAllowSlotEditing(false);
  }

  
}
