<?php

/**
 * aMap Components.
 *
 * @package    arquematicsPlugin
 * @subpackage aMap
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arMapComponents extends BaseArComponents
{
  public function preExecute()
  {
   sfProjectConfiguration::getActive()->loadHelpers(array('I18N','Partial'));

   parent::preExecute();
  }
  
  public function executeShowMapControl(sfWebRequest $request)
  {
    $this->loadUser();
      
    $this->form = new arGmapsLocateForm();
    
    $this->mapsIds = $this->getUser()->getAttribute('arGmapsLocate',array(),'wall');
    
    $this->hasContent = (count($this->mapsIds) > 0);

    $activeTool = $this->getUser()->getAttribute('activeTool', false, 'wall');
    $this->showTool = ($activeTool == 'arGmapsLocate');

    if ($this->hasContent)
    {
         $this->listLocate = Doctrine_Core::getTable('arGmapsLocate')
                 ->getByIds($this->mapsIds, $this->aUserProfile->getId());
         
    }
  }
  
  public function executeView(sfWebRequest $request)
  {
     $this->locate = $this->aRouteUserProfile->getLastLocation($this->aUserProfile->getId());
  }
  
  public function executeViewEdit(sfWebRequest $request)
  {
      $this->locate = $this->aRouteUserProfile->getLastLocation($this->aUserProfile->getId());
      $this->form = new arGmapsLocateForm($this->locate, array('showFormatedAddress' => true));
  }
}