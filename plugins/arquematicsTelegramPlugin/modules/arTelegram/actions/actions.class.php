<?php


class arTelegramActions extends BaseArActions
{
  public function preExecute()
  {
   sfProjectConfiguration::getActive()
           ->loadHelpers(array('a','ar','I18N','Partial'));

  }
  
  public function executeIndex(sfWebRequest $request)
  {
       if ($this->checkView())
       {
         $this->loadUser();
         
         $this->data = $this->aUserProfile->getData();
          
         $profileImage = $this->aUserProfile->getProfileImage();
       
         $this->data['icon'] = ($profileImage)?
               url_for("@user_resource?type=arProfileUpload&name=".$profileImage->getBaseName()."&format=".$profileImage->getExtension()."&size=small"):
               '/arquematicsPlugin/images/unknown.small.jpg';
          
         $this->data['url'] = url_for('@user_profile?username='.$this->aUserProfile->getUsername());
       
         $this->data['url_user_list'] = url_for('@user_list');

         $this->data['url_user_friend'] = url_for('@user_list_friends');

         $this->data['lang'] = $this->culture;
          
         $this->data['log_out'] = url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout'));
          
         $this->data['cms_admin'] = $this->isCMSAdmin()?'true':'false';
         
         $this->isAdmin = $this->isCMSAdmin();
         
       }
       else {
         $this->redirect('@homepage');
       }
      
  }
   
}