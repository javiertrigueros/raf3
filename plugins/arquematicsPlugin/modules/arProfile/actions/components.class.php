<?php

/**
 * arProfileComponents components.
 *
 * @package    cs
 * @subpackage aPerson
 * @author     P'unk Avenue
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arProfileComponents extends BaseArComponents
{
 
  public function executeShowProfile(sfWebRequest $request)
  {
    $this->arRouteProfileImage = $this->aRouteUserProfile->getProfileImage();
    
    $this->formImage = new arProfileUploadForm($this->arRouteProfileImage);
          
    $this->formFirstLast = new arProfileFirstLastForm($this->aRouteUserProfile);
          
    $this->formDescription = new arProfileDescriptionForm($this->aRouteUserProfile);
   
  }
  
  public function executeShowProfileWall(sfWebRequest $request)
  {
   
    $this->aRouteUserProfile = ($this->aUserProfileFilter)?$this->aUserProfileFilter:$this->aUserProfile;
            
    $this->arRouteProfileImage = $this->aRouteUserProfile->getProfileImage();
    
    
    $queryInfo = Doctrine_Core::getTable('sfGuardUserProfile')
            ->mutualFriendRequestExt(
                    $this->aUserProfile->getId(),
                    $this->aRouteUserProfile->getId(),
                    1,
                    (int)sfConfig::get('app_arquematics_plugin_wall_mutual_friends_view', 7) );
    
    //primera página de usuarios
    $this->arMutualFriends = $queryInfo['results'];
    $this->arMutualFriendsCount = $queryInfo['count'];
    
    /*
    $userid = (int)$request->getParameter('userid');
    
    $this->showGotoWall = (($this->aRouteUserProfile->getId() === $this->aUserProfile->getId()) 
            && ($this->aUserProfile->getId()!= $userid));*/
    
  }
  
  
  public function executeShowProfileBlockCounter(sfWebRequest $request)
  {
      $this->arUserMessagesCount = $this->aRouteUserProfile->countUserMessages();
      $this->arMutualFriendsCount = $this->aUserProfile->countMutualUsersAccepted($this->aRouteUserProfile->getId());
  }
  
}