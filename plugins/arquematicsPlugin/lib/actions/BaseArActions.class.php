<?php
/**
 * BaseArActions Actions Base.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class BaseArActions extends BaseaActions
{
  
  public function preExecute()
  {
   sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
   //mirar esto en segun que caso
   aEngineTools::preExecute($this);
  }
  
  public function checkView()
  {
    
      $this->aUser = $this->getUser();

      return $this->isAuth = is_object($this->aUser) 
              &&  $this->aUser->isAuthenticated();
      
  }
  
  
  /**
   * carga los objetos del usuario
   */
  public function loadUser()
  { 
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
  
  protected function hasBlogCredential()
  {
      $user = $this->getUser();
      return ($user && isset($user)) 
             && ($user->hasCredential('admin') || $user->hasCredential('blog_admin') || $user->hasCredential('blog_author'));
  }
  
  /**
  * devuelve contenido JSON
  */
  public function returnJson($sendHttpHeader = true)
  {
    if (!isset($this->data))
    {
        $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => "no go",
                    "values" => array()); 
    }
    
    $this->setLayout(false);
    $this->setTemplate('json','API');
    sfConfig::set('sf_web_debug', false);
    if ($sendHttpHeader)
    {
     $this->getResponse()->setHttpHeader('Content-type','application/json; charset=utf-8');
     $this->getResponse()->setHttpHeader('Accept','application/json');      
    }
    
  }
  
  /**
  * devuelve contenido simple
  */
  public function returnRawData($sendHttpHeader = true)
  {
    if (!isset($this->data))
    {
        $this->data =  'error no data'; 
    } 
    $this->setLayout(false);
    $this->setTemplate('raw','API');
    sfConfig::set('sf_web_debug', false);
    
    if ($sendHttpHeader)
    {
     $this->getResponse()->setHttpHeader('Content-type','text/plain');   
    }
    
  }
  
}
