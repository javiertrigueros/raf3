<?php


class arWallComponents extends BaseArComponents
{
 
  public function preExecute()
  {
   sfProjectConfiguration::getActive()->loadHelpers(array('I18N','Partial'));

   parent::preExecute();
  }
  
  /**
   * :TODO mira los permisos y hacerlos mas consistentes arSystemInfo
   * 
   * @return type
   */
  protected function hasAdminCredential()
  {
      $user = $this->getUser();
      return ($user->hasCredential('admin') || $user->hasCredential('blog_admin') || $user->hasCredential('blog_author'));
  }
  
  
  public function executeShowCommentForm(sfWebRequest $request)
  {
      $this->form = new arWallCommentForm();
      
  }
  
  public function executeShowCommentFormTemplate(sfWebRequest $request)
  {
      $this->loadUser();
      
      $this->userUrl = url_for('user_profile',$this->aUserProfile);
      
      $this->arProfileImage = false;
      $this->arProfileImage = Doctrine_Core::getTable('arProfileUpload')
                  ->retrieveByUserId($this->aUserProfile->getId()); 
      
      $this->form = new arWallCommentForm();
      
  }
  
  
  
  public function executeShowTabsTools(sfWebRequest $request)
  {
      $sysInfo = arSystemInfo::getInstance();
      
      $this->enabledTools = $sysInfo->getEnabledTabs($this->hasAdminCredential());
      
      $this->hasEnabledTools = (count($this->enabledTools) > 0);
    
  }
  
  public function executeShowSearch(sfWebRequest $request)
  {
    $this->loadUser();
    $this->form = new arSearchUser();
  }
 
  public function executeShowTabsControls(sfWebRequest $request)
  {
      $sysInfo = arSystemInfo::getInstance();
      
      $this->enabledTools = $sysInfo->getEnabledTabs($this->hasAdminCredential());
      
      $this->hasEnabledTools = (count($this->enabledTools) > 0);
      
      $this->activeTabModule = ($this->hasEnabledTools)?$this->enabledTools[0]['name']:'';
      
      $this->form = new arWallMessageForm(null, array('aUserProfile' => $this->aUserProfile));
      $this->formBlog   = new arBlogNewPostForm(null, array('aUserProfile' => $this->aUserProfile));
      $this->formEvent  = new arBlogNewEventForm(null, array('aUserProfile' => $this->aUserProfile));
  }
  
  /**
   * muestra las herramientas activas 
   * 
   * @param sfWebRequest $request 
   */
  public function executeShowButtonTools(sfWebRequest $request)
  {
      $sysInfo = arSystemInfo::getInstance();
      
      $this->enabledTools = $sysInfo->getEnabledTools($this->hasAdminCredential());
      
      $this->hasEnabledTools = (count($this->enabledTools) > 0);
  }
}