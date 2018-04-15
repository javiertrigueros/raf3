<?php

/**
 * arGroups actions.
 *
 * @package    arquematicsPlugin
 * @subpackage aMedia
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arGroupActions extends BaseArActions
{
    
  public function executeIndex(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->countUsers = Doctrine::getTable('sfGuardUserProfile')
                                    ->getCount($this->aUserProfile->getId());
          
          $this->isLastPage =  1 >= Doctrine::getTable('sfGuardUserProfile')
                                        ->totalPages($this->aUserProfile->getId());
          
          $this->formSearch = new arSearchUser();
          
          $this->formProfileList = new arProfileListForm();
          
          $this->formProfileEdit = new arProfileListEditForm();
          
          $this->formListAdd = new arListAddForm();
          
          $this->formGetList = new arGetUserListForm();
          
          $this->formListDelete = new arListDeleteForm();
          
          $this->formListDeleteAll = new arListDeleteAllForm();
          
          $this->formFriendRequestNoList = new arFriendForm();
      }
      else
      {
        $this->redirect('@homepage');
      }
  }
  
  public function executeShowFriends(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();

          $this->formSearch = new arSearchFriend();
          
          $this->form = new arProfileListHasProfileForm(
                                null, 
                                array('aUserProfile' => $this->aUserProfile));
          
      }
      else
      {
        $this->redirect('@homepage');
      }
  }
  
  public function executeFriendsByNameAutoComplete(sfWebRequest $request)
  {
      $this->form = new arSearchFriend();
      $this->form->bind($request->getParameter($this->form->getName()));
      
       if ($this->checkView() 
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
           $searchText = trim($this->form->getValue('search'));
           if (strlen($searchText) > 0)
           {
               $this->loadUser();
               
               $isSubscriber = $this->form->getValue('is_subscriber');
               
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => Doctrine_Core::getTable('sfGuardUserProfile')
                                    ->getFriendsListByStringLimited($this->userid, $searchText, $isSubscriber));  
                                                               
                

           }     
           
      }
      
      $this->returnJson();
  }
  
  public function executeFriendsByName(sfWebRequest $request)
  {
      
      $this->form = new arSearchFriend();
      $this->form->bind($request->getParameter($this->form->getName()));
       
      if ($this->checkView() 
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          $this->loadUser();
          
          $page = $this->form->getValue('page');
          $searchText = trim($this->form->getValue('search'));
          $isSubscriber = $this->form->getValue('is_subscriber');
          
          //$isSubscriber=true listado de subscriptores
          if ((strlen($searchText) > 0) && ($isSubscriber))
          {
               $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')
                       ->totalPagesFriendsByString($this->userid, $searchText, $isSubscriber);
            
               $listFriends = Doctrine_Core::getTable('sfGuardUserProfile')
                            ->getUsersAcceptedByString($this->userid, $searchText, $page, $isSubscriber);
               
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arGroup/listFriends', array('listFriends' => $listFriends)),
                    "values" => array('isLastPage' => ($page >= $totalPages)));
                   
          }
          else if ((strlen($searchText) > 0) && (!$isSubscriber))
          {
               $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')
                                ->totalPagesFriendsByString($this->userid, $searchText, $isSubscriber);
            
               $listFriends = Doctrine_Core::getTable('sfGuardUserProfile')
                                ->getUsersAcceptedByString($this->userid, $searchText, $page, $isSubscriber);
               
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arGroup/listIgnore', array('listFriends' => $listFriends, 'aUserProfile' => $this->aUserProfile)),
                    "values" => array('isLastPage' => ($page >= $totalPages)));
              
          }
          else if ($isSubscriber)
          {
             $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')
                            ->totalPagesFriends($this->userid, true);
            
             $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_component('arGroup','listFriends', array('page' => $page,'aUserProfile' => $this->aUserProfile)),
                    "values" => array('isLastPage' => ($page >= $totalPages)
                       ));
          }
          else 
          {
             $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')->totalPagesFriends($this->userid, false);
            
             $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_component('arGroup','listIgnore', array('page' => $page,'aUserProfile' => $this->aUserProfile)),
                    "values" => array('isLastPage' => ($page >= $totalPages)
                       ));
          }
      }
      else 
      {
           $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
      }
          
      
      $this->returnJson();
      
  }
  
  public function executeAddFriendConfirmation(sfWebRequest $request)
  {
     
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arProfileListHasProfileForm(
             null, 
             array('aUserProfile' => $this->aUserProfile));
          
          $this->form->bind($request->getParameter($this->form->getName()));
          
          if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
          {
              $friendProfileId = $this->form->getValue('profile_id');
              
              $profileListIds = $this->form->getValue('profile_list_id');
              
              $isAccept = $this->form->getValue('is_accept');
              
              $this->aUserProfile->confirmRequest($friendProfileId, $profileListIds, $isAccept);
             
              $friend = Doctrine_Core::getTable('sfGuardUserProfile')->retrieveById($friendProfileId);
              
              $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => ($isAccept)? 
                                get_partial('arGroup/profileAcceptRequest',array('profile' => $friend)):
                                get_partial('arGroup/profileIgnoreRequest',
                                        array('profile' => $friend,
                                              'aUserProfile' => $this->aUserProfile)),
                    "values" => $request->getParameter($this->form->getName())); 
          }
          else 
          {
           $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName()));
          }
      }
  
      $this->returnJson();
  }
  
  public function executeAddFriendToList(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arAddFriendToListForm(null, array('aUserProfile' => $this->aUserProfile));
          $this->form->bind($request->getParameter($this->form->getName()));
   
          if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
          {
              
              $this->form->save();
             
              $profile_id = $this->form->getValue('profile_id');
                       
              $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" =>  '',
                    "values" => array('profileId' => $profile_id));
             
          }
      }
      
      $this->returnJson();
  }
  
  public function executeAddFriendRequestNoList(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arFriendForm(null, array('aUserProfile' => $this->aUserProfile));
          $this->form->bind($request->getParameter($this->form->getName()));
   
          if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
          {
             $this->form->save();
             
             $profileId = $this->form->getValue('friend_id');
            
             $arProfileFriend = Doctrine::getTable('sfGuardUserProfile')
                                    ->retrieveById($profileId);
             
             $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" =>  '',
                    "values" => array(
                        'profileId' => $profileId,
                        'add_user' => $arProfileFriend->canAddUser($this->aUserProfile->getId()),
                        'remove_request' => $arProfileFriend->canRemoveRequest(),
                        'remove_suscriptor' => $arProfileFriend->canRemoveSuscriptor(),
                        ));
          }
          else
          {
               $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName()));
          }
      }
      else 
      {
          $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName())); 
      }
      
      $this->returnJson();
  }
  
  public function executeAddFriendRequest(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arListAddForm(null, array('aUserProfile' => $this->aUserProfile));
          $this->form->bind($request->getParameter($this->form->getName()));
   
          if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
          {
             $this->form->save();
             
             $listId = $this->form->getValue('id');
             $profileId = $this->form->getValue('profile_id');
             $arProfileFriend = Doctrine_Core::getTable('sfGuardUserProfile')
                                    ->retrieveById($profileId);
             
             $this->data =   array("status" => 200,
                    "errors" => array(),
                 
                    "HTML" =>  get_partial('arGroup/profileSmall',array(
                                        'aUserProfile' => $this->aUserProfile,
                                        'class' => 'members-list',
                                        'profile' => $arProfileFriend,
                                        'display' => false)),
                    "values" => array('id' => $listId,
                                      'add_user' => $arProfileFriend->canAddUser($this->aUserProfile->getId()),
                                      'remove_request' => $arProfileFriend->canRemoveRequest(),
                                      'remove_suscriptor' => $arProfileFriend->canRemoveSuscriptor(),
                                      'profile_id' => $profileId));
          }
          else 
          {
            $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName()));
          }
      }
     
      
      $this->returnJson();
  }
  
  
  
  public function executeEditListName(sfWebRequest $request)
  {

      if ($this->checkView())
      {
        $this->loadUser();
           
        $this->form = new arProfileListEditForm(null, array('aUserProfile' => $this->aUserProfile));
        $this->form->bind($request->getParameter($this->form->getName()));
      
        if ($request->isMethod(sfRequest::POST)
          && $this->form->isValid())
        {
            $listId = $this->form->getValue('id');
            $arList = Doctrine_Core::getTable('arProfileList')->retrieveById($listId);
          
            $arList->setName($this->form->getValue('name'));
          
            $arList->save();
          
            $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" =>  '',
                    "values" => array('id' => $arList->getId(),
                                      'name' => $arList->getName()));
          
        }
        else{
          $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" =>  '',
                    "values" => $request->getParameter($this->form->getName())); 
        }
      }

      $this->returnJson();
  }
  
  public function executeCreateList(sfWebRequest $request)
  {
      if ($this->checkView())
      {
           $this->loadUser();
           
           $this->form = new arProfileListForm(null, array('aUserProfile' => $this->aUserProfile));
           $this->form->bind($request->getParameter($this->form->getName()));
           
           $countList = $this->aUserProfile->countAdminList();
           
           if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid()
                && ($countList < sfConfig::get('app_arquematics_plugin_max_list_items', 6)))
           {
                
               $newList = $this->aUserProfile->createList(
                       $this->form->getValue('name'),
                       $this->form->getValue('users_list'));
               
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" =>  get_partial('arGroup/profileList', array(
                                        'class' => 'members',
                                        'list' => $newList,
                                        'display' => false)),
                    "values" => array(
                        'isLasListToAdd' => (($countList + 1) >= (int)sfConfig::get('app_arquematics_plugin_max_list_items', 6)),
                        'id' => $newList->getId()));
           }
           else
           {
               $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName())); 
           }
           
      }
     
      
      $this->returnJson();
  }
  
  public function executeGetUserList(sfWebRequest $request)
  {
        if ($this->checkView())
        {
            $this->loadUser();
             
            $this->form = new arGetUserListForm(null, array('aUserProfile' => $this->aUserProfile));
            $this->form->bind($request->getParameter($this->form->getName()));
            
            if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
            {
                $listId = $this->form->getValue('id');
                $page = $this->form->getValue('page');
            
             
                $arProfileList = Doctrine_Core::getTable('arProfileList')
                                    ->retrieveById($listId);
                
                $totalPages = $arProfileList->totalPages();
                
                $profileList = Doctrine_Core::getTable('sfGuardUserProfile')
                                    ->getUsersByList($listId, $page);
             
             
                $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arGroup/listUsers',array(
                                        'aUserProfile' => $this->aUserProfile,
                                        'class' => 'members-list',
                                        'profileList' => $profileList,
                                        'display' => false)),
                    "values" => array('id' => $arProfileList->getId(),
                                      'name' => ucfirst($arProfileList->getName()),
                                      'count' => $arProfileList->count(),
                                      'isLastPage' => ($page >= $totalPages),
                                      'items' => $arProfileList->getListData()));
                
            }
            else
            {
                $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName()));  
            }
        }
       
        $this->returnJson();
  }
  
 
  
  public function executeDeleteList(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arListDeleteAllForm(null, array('aUserProfile' => $this->aUserProfile));
          $this->form->bind($request->getParameter($this->form->getName()));
          
          if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
          {
              $listId = $this->form->getValue('id');
              $this->aUserProfile->deleteList($listId);
              
              $countList = $this->aUserProfile->countAdminList();
              
              $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array(
                        'canAddList' => ($countList < (int)sfConfig::get('app_arquematics_plugin_max_list_items', 6)),
                        'list_id' => $listId));
          }
          else
          {
               $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName())); 
          }
      }
      
      $this->returnJson();
  }
  
  public function executeListDeleteAll(sfWebRequest $request)
  {
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => "",
                    "values" => array());
      
      $this->form = new arListDeleteAllForm();
      $this->form->bind($request->getParameter($this->form->getName()));
      
       if ($this->checkView() 
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          $this->loadUser();
           
          $listId = $this->form->getValue('id');
          $arList = Doctrine_Core::getTable('arProfileList')->retrieveById($listId);
          
          $arList->deleteAll($this->userid, $this->aUserProfile->getMainList());
          
          $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array('list_id' => $listId));
          
      }
      
      $this->returnJson();
      
  }
  
  public function executeDeleteFriend(sfWebRequest $request)
  {
      
      if ($this->checkView())
      {
         $this->loadUser();
         
         $this->form = new arListDeleteForm(null, array('aUserProfile' => $this->aUserProfile));
         $this->form->bind($request->getParameter($this->form->getName()));
      
         if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
         {
             $listId = $this->form->getValue('id');
             $profileId = $this->form->getValue('profile_id');
             
             $this->aUserProfile->deleteFriendRequest($listId, $profileId); 
             
             $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arGroup/profileSmall',array(
                                        'aUserProfile' => $this->aUserProfile, 
                                        'class' => 'members',
                                        'profile' => Doctrine_Core::getTable('sfGuardUserProfile')->retrieveById($profileId),
                                        'display' => false)),
                    "values" => array('profile_id' => $profileId));
         }
         else 
         {
            $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $request->getParameter($this->form->getName()));
         }
      }
      
      $this->returnJson();
  }
  
  
  public function executeListUsersByNameAutoComplete(sfWebRequest $request)
  {
     
      $this->form = new arSearchUser();
      $this->form->bind($request->getParameter($this->form->getName()));
      
       if ($this->checkView() 
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
           $searchText = trim($this->form->getValue('search'));
           if (strlen($searchText) > 0)
           {
                $this->loadUser();
                /*
                $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => $this->aUserProfile->getUsersListByStringLimited($searchText));
               
           */
                $this->data =  $this->aUserProfile->getUsersListByStringLimited($searchText);    
           }   
      }
      
      $this->returnJson();
  }
  
  public function executeListUsersByName(sfWebRequest $request)
  {
      
      $this->form = new arSearchUser();
      $this->form->bind($request->getParameter($this->form->getName()));
       
      if ($this->checkView() 
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          $this->loadUser();
          
          $page = $this->form->getValue('page');
          $searchText = trim($this->form->getValue('search'));
          
          if (strlen($searchText) > 0)
          {
              $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')
                            ->totalPagesByString($this->aUserProfile->getId(),
                                                $searchText);
          
              $profileList = Doctrine_Core::getTable('sfGuardUserProfile')
                                ->getUsersListByString(
                                        $this->aUserProfile->getId(),
                                        $searchText, 
                                        $page);
              
              $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arGroup/listUsers', array('aUserProfile' => $this->aUserProfile,'profileList' => $profileList,'class' => 'members')),
                    "values" => array('isLastPage' => ($page >= $totalPages)
                       ));
          }
          else
          {
              
             $totalPages = Doctrine_Core::getTable('sfGuardUserProfile')
                                ->totalPages($this->aUserProfile->getId());
          
             $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_component('arGroup','listUsers', array('aUserProfile' => $this->aUserProfile, 'page' => $page)),
                    "values" => array(
                        /*'count' =>  Doctrine_Core::getTable('sfGuardUserProfile')
                                        ->getCount($this->aUserProfile->getId()),*/
                        'isLastPage' => ($page >= $totalPages)
                       ));
          }
          
          
          
      }
      else 
      {
           $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
      }
          
      
      $this->returnJson();
  }
  
  
  
}
