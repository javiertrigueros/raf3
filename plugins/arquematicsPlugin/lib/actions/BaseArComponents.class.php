<?php

/**
 * BaseArComponents Components base.
 * 
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class BaseArComponents extends sfComponents
{
  public function preExecute()
  {
    sfProjectConfiguration::getActive()
           ->loadHelpers(array('Url','I18N','Partial','a','ar'));

  }
  
  public function checkView()
  {
    
      $this->aUser = $this->getUser();

      return $this->isAuth = is_object($this->aUser) 
              &&  $this->aUser->isAuthenticated();
      
  }
  
  protected function loadUser()
  {
      $this->aUser = $this->getUser();
      $this->authUser = $this->aUser->getGuardUser();
      $this->aUserProfile = $this->authUser->getProfile();
      $this->culture = $this->getUser()->getCulture();
      $this->userid = $this->authUser->getId();
  }
  
  protected function isCMSAdmin()
  {
      $user = $this->getUser();
      return ($user->hasCredential('admin') || $user->hasCredential('cms_admin'));
  }
  
  protected function isBlogAuthor()
  {
      $user = $this->getUser();
      return ($user->hasCredential('admin') || $user->hasCredential('blog_author'));
  }
  
  protected function isBlogAdmin()
  {
      $user = $this->getUser();
      return ($user->hasCredential('admin') || $user->hasCredential('blog_admin'));
  }
  
  
}