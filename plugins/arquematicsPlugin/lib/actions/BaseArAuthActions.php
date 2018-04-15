<?php

require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');
/**
 * BaseArAuthActions Actions Base.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class BaseArAuthActions extends BasesfGuardAuthActions
{
    
 public function preExecute()
 {
      
       sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
 }
  private function savePublicAndStoreKey(
          $user,
          $storeKey,
          $publicKey)
  {
       if (sfConfig::get('app_arquematics_encrypt',false))
       {
           //guarda la clave si no esta guardada
           $profile = $user->getProfile();
           if ($profile && is_object($profile) 
               && (!$profile->getKeySaved()))
           {
               $this->dispatcher->notify(new sfEvent($profile, 
                   'arUser.publicKeyAdd', 
                   array(
                       'store_key' => $storeKey,
                       'public_key' => $publicKey)));
           }
        }
      
  }
  
  
  private function sendMailWithPrivateKeyToUser($user, $values)
  {
        if (isset($values['private_key'])
           && (strlen(trim($values['private_key'])) > 0))
        {
            $privateKey =  "--------BEGIN KEY--------\n";
            $privateKey .= $values['private_key'];
            $privateKey .= "--------END KEY------\n";

            $currentUser = $user->getGuardUser();
            $currentProfile = $user->getProfile();
            
            $i18n = $this->getContext()->getI18N();

            $message = $this->getMailer()->compose();
            $message->setSubject($i18n->__('Hello %first_name%', array('%first_name%' => $currentUser->getFirstName()), 'arquematics'));
            $message->setTo($currentProfile->getEmailAddress());
            $message->setFrom(sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com'));
            
            $html = $this->getPartial('arAuth/send_hello_and_key_html',array('user' => $currentUser,  'profile' => $currentProfile, 'privateKey' => $privateKey));
            $message->setBody($html, 'text/html');
            
            $text = $this->getPartial('arAuth/send_hello_and_key_plane',array('user' => $currentUser, 'profile' => $currentProfile, 'privateKey' => $privateKey));
            $message->addPart($text, 'text/plain');   
            

            $this->getMailer()->send($message);

            $this->dispatcher->notify(new sfEvent($currentProfile, 
                                            'arUser.sendPrivateKey', array()));
            
        
            return $privateKey;
        }
        
  }
  
  public function executeSigninAfterOAuth($request)
  {
    $this->aUser = $this->getUser();
     
    if (is_object($this->aUser) &&
            $this->aUser->isAuthenticated())
    {
        $this->authUser = $this->aUser->getGuardUser();
        $this->aUserProfile = $this->authUser->getProfile();
        $this->culture = $this->getUser()->getCulture();
        $this->userid = $this->authUser->getId();
        
        $this->userBackForm = new arUserBackForm();
        $this->form = new arFormSignin();
    
    }
    else
    {
      return $this->redirect('@homepage');  
    }
      
  }
    
  public function executeSignin($request)
  {
    $user = $this->getUser();
    if ($user->isAuthenticated())
    {
      return $this->redirect('@homepage');
    }

    $this->form = new arFormSignin();
    
    $this->form->bind($request->getParameter($this->form->getName()));
    
    if ($request->isXmlHttpRequest() 
            && $request->isMethod('post')
            && $this->form->isValid())
    {

       $values = $this->form->getValues();
       $user = $this->getUser();

       $user->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

       $currentUser = $user->getGuardUser();
       $currentProfile = $currentUser->getProfile();
        
       if (sfConfig::get('app_arquematics_send_private_key',false))
       {
         $this->sendMailWithPrivateKeyToUser($user, $values);  
       }
       
       $this->savePublicAndStoreKey($user,$values['store_key'], $values['public_key']);
       
       $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

       
       $this->data =   array(
          'url' => url_for(('' != $signinUrl) ? $signinUrl : '@homepage'),
          'token' => md5(strtotime($currentProfile->created_at . " GMT")),
        );
       
       $this->returnJson();

    }
    else if ($request->isXmlHttpRequest())
    {
        $this->data = $this->form->getErrors();
        $this->returnJson();
        /*
        $this->getResponse()->setStatusCode(500,'Error auth' );
        return sfView::HEADER_ONLY;*/
    }
    else if ($request->isMethod('post') 
            && $this->form->isValid())
    {
        $values = $this->form->getValues();
        $user = $this->getUser();
        
        $user->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

        if (sfConfig::get('app_arquematics_send_private_key',false))
        {
          $this->sendMailWithPrivateKeyToUser($user, $values);  
        }
        
        $this->savePublicAndStoreKey($user,$values['store_key'], $values['public_key']);

        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

        return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
    }
    
    //si no se hace ningun tipo de peticion
    $this->userBackForm = new arUserBackForm();
    $this->form = new arFormSignin();
    
    $class = sfConfig::get('app_sf_guard_plugin_register_form', 'sfGuardRegisterForm');
    $this->formRegister = new $class();
    
  }
    
    
    public function executeUserBack($request)
    {
       $this->form = new arUserBackForm();
        
       $this->form->bind($request->getParameter($this->form->getName()));
      
        
       if ($request->isMethod(sfRequest::POST)
          && $this->form->isValid())
       {
            $values = $this->form->getValues();
            
            $this->user = Doctrine_Core::getTable('sfGuardUser')
                            ->retrieveByUsernameOrEmailAddress($values['username']);
            
            if ($this->user)
            {
                $this->userProfile = $this->user->getProfile();
            
                $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url');

                if (sfConfig::get('app_arquematics_send_private_key',false)
                    && (!$this->userProfile->getKeySaved()))
                {
                   $this->userProfile->saveMailKeys();
                   
                   $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'isAuthenticated' => $this->getUser()->isAuthenticated(),
                        'url' => url_for(('' != $signinUrl) ? $signinUrl : '@homepage'),
                        'id' => $this->userProfile->getId(),
                        'publicMailKey' => !$this->userProfile->getKeySaved()? $this->userProfile->getPublicMailKey():'',
                        'token' => md5(strtotime($this->userProfile->getCreatedAt() . " GMT")),
                        'hasPublicKey' => $this->userProfile->getKeySaved() ,
                        'username' => $this->userProfile->getUsername()),
                        
                    "HTML" => '');
                }
                else
                {
                   $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'isAuthenticated' => $this->getUser()->isAuthenticated(),
                        'url' => url_for(('' != $signinUrl) ? $signinUrl : '@homepage'),
                        'id' => $this->userProfile->getId(),
                        'token' => md5(strtotime($this->userProfile->getCreatedAt() . " GMT")),
                        'hasPublicKey' => $this->userProfile->getKeySaved() ,
                        'username' => $this->userProfile->getUsername()),
                        
                    "HTML" => '');
                }
            }
            else
            {
                $this->data = array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" => ''); 
            }
            
       }
        else {
            $this->data = array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" => '');
       }
      
        $this->returnJson();
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
                    "HTML" => "",
                    "values" => array()); 
        }
    
        $this->setLayout(false);
        $this->setTemplate('json','API');
        sfConfig::set('sf_web_debug', false);
        if ($sendHttpHeader)
        {
            $this->getResponse()->setHttpHeader('Content-type','application/json');   
        }
    
    }
    
    
}
