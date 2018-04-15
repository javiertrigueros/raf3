<?php

class arLinkComponents  extends BaseArComponents
{

  public function preExecute()
  {
   sfProjectConfiguration::getActive()->loadHelpers(array('I18N','Partial'));

   parent::preExecute();
  }
  
  
  public function executeShowLinkControl(sfWebRequest $request)
  {
    $this->loadUser();
      
    $this->formLink = new arWallLinkForm();
      
    $this->linksIds = $this->getUser()->getAttribute('arWallLink',array(),'wall');
    
    $this->hasContent = (count($this->linksIds) > 0);

    $activeTool = $this->getUser()->getAttribute('activeTool', false, 'wall');
    $this->showTool = ($activeTool == 'arWallLink');
    
    $this->sessionLinks = array();
    
    if ($this->hasContent)
    {
         $this->listLinks = Doctrine_Core::getTable('arWallLink')
                              ->getByIds($this->linksIds, $this->aUserProfile->getId());
         
         foreach ($this->listLinks as $link)
         {
             $this->sessionLinks[] =  $link->getUrl();
         }
         
    }
  }
}