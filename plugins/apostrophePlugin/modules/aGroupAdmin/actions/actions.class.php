<?php
/**
 * See lib/base in this plugin for the actual code. You can extend that
 * class in your own application level override of this file
 * @package    Apostrophe
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class aGroupAdminActions extends BaseaGroupAdminActions
{
 public function preExecute()
 {
     sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
     
    $this->configuration = new aGroupAdminGeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new aGroupAdminGeneratorHelper();

    aTools::setAllowSlotEditing(false);
  }
}