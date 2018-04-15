<?php

/**
 * arGroup components.
 *
 * @package    arquematicsPlugin
 * @subpackage aMedia
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arGroupComponents extends BaseArComponents
{
  
  public function executeListUsers(sfWebRequest $request)
  {
      $this->class = 'members';
      
      $this->profileList = Doctrine_Core::getTable('sfGuardUserProfile')
                                ->getUsersList($this->aUserProfile->getId(),
                                               $this->page);
      
  }
  
  public function executeListFriends(sfWebRequest $request)
  {
      $this->listFriends = $this->aUserProfile->getUsersAccepted($this->page, true);
  }
  
  public function executeListIgnore(sfWebRequest $request)
  {
      $this->listFriends = $this->aUserProfile->getUsersAccepted($this->page, false);
  }
  
  
  public function executeShowListSelect(sfWebRequest $request)
  {
     //si no ha creado listas no se mostrará el control
     $this->hasList = $this->aUserProfile->countAdminList() > 0;
     $this->activeListControl = $this->tab['listControl'];
     $this->selectContainer = '#control-list-select-buttons-'.$this->tab['name'];
  }
  
  public function executeShowButtomRequest(sfWebRequest $request)
  {
     $this->loadUser();
     
     //$this->form = new arProfileListHasProfileForm(null, array('aUserProfile' => $this->aUserProfile));
     $this->form = new arAddFriendToListForm(null, array('aUserProfile' => $this->aUserProfile));
  }
  
}