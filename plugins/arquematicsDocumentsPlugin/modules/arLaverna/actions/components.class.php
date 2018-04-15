<?php


class arLavernaComponents extends BaseArComponents
{
  
  
  public function executeShowControl(sfWebRequest $request)
  {    
      $this->loadUser();
      // ficheros en la session
      
      $this->lavernaDocs = $this->getUser()->getAttribute('arLavernaDoc',array(),'wall');
      $this->hasContent = (count($this->lavernaDocs) > 0);
      
      $activeTool = $this->getUser()->getAttribute('activeTool', false, 'wall');
      $this->showTool = ($activeTool == 'arLavernaDoc');
      
      if ($this->hasContent)
      {
         $this->lavernaDocs =  Doctrine_Core::getTable('arLavernaDoc')
                 ->getByIds($this->lavernaDocs, $this->aUserProfile->getId());
      }
  }
  
}