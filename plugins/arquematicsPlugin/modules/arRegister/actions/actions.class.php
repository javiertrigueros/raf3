<?php

require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class arRegisterActions extends BasesfGuardAuthActions
{
    
  public function preExecute()
  {
       sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
  
       if ($this->getUser()->isAuthenticated())
       {
        $this->getUser()->setFlash('notice', 'You are already registered and signed in!');
        $this->redirect('@homepage');
       }
  }
  
  /**
   * Send the request password email to the user
   *
   * @param object                $user           the user object
   * @param sfGuardForgotPassword $forgotPassword the forgot password record
   *
   * @return void
   */
  protected function sendRequestMail($user, $forgotPassword)
  {
    $i18n = $this->getContext()->getI18N();

    $message = $this->getMailer()->compose(
      sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com'),
      $user->email_address,
      $i18n->__('Forgot Password Request for %name%', array('%name%' => $user->username), 'sf_guard'),
      $this->getPartial('arRegister/send_request', array('user' => $user, 'forgot_password' => $forgotPassword))
    )->setContentType('text/html');

    $this->getMailer()->send($message);
  }
  
  
  /**
   * Send email to the user with new password
   *
   * @param object $user     user object
   * @param string $password user password
   *
   * @return void
   */
  protected function sendChangeMail($user, $password)
  {
    $i18n = $this->getContext()->getI18N();

    $message = $this->getMailer()->compose(
      sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com'),
      $user->email_address,
      $i18n->__('New Password for %name%', array('%name%' => $user->username) , 'sf_guard'),
      $this->getPartial('arRegister/new_password', array('user' => $user, 'password' => $password))
    )->setContentType('text/html');

    $this->getMailer()->send($message);
  }
  
  public function executeChange($request)
  {
    $this->forgotPassword = $this->getRoute()->getObject();
    $this->user = $this->forgotPassword->User;
    $this->form = new sfGuardChangeUserPasswordForm($this->user);

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->form->save();

        $this->forgotPassword->delete();

        $this->sendChangeMail($this->user, $request['sf_guard_user']['password']);

        $this->getUser()->setFlash('notice', 'Password updated successfully!');

        $this->redirect('@sf_guard_signin');
      }
    }
  }
  
  public function executeForgotPassword(sfWebRequest $request)
  {
    
    $this->culture = $this->getUser()->getCulture();
    
    $class = sfConfig::get('app_arquematics_plugin_forgot_password_form');
    $this->form = new $class();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->user = Doctrine_Core::getTable('sfGuardUser')
          ->retrieveByUsernameOrEmailAddress($this->form->getValue('email_address'));

        Doctrine_Core::getTable('sfGuardForgotPassword')
          ->deleteByUser($this->user);

        $forgotPassword = new sfGuardForgotPassword();
        $forgotPassword->user_id = $this->user->id;
        $forgotPassword->unique_key = md5(rand() + time());
        $forgotPassword->expires_at = new Doctrine_Expression('NOW()');
        $forgotPassword->save();

        $this->sendRequestMail($this->user, $forgotPassword);

        $this->getUser()->setFlash('notice', 'Check your e-mail! You should receive something shortly!');

        //$this->redirect(sfConfig::get('app_sf_guard_plugin_password_request_url', '@sf_guard_signin'));
      }
    } 
  }
    
  public function executeIndex(sfWebRequest $request)
  {
    
    $this->culture = $this->getUser()->getCulture();

    $class = sfConfig::get('app_arquematics_plugin_register_form');
    $this->form = new $class();
    
    $this->form->bind($request->getParameter($this->form->getName()));
    
    if ($request->isXmlHttpRequest() 
            && $request->isMethod(sfRequest::POST)
            && $this->form->isValid())
    {
        $event = new sfEvent($this, 'user.filter_register');
        $this->form = $this->dispatcher
          ->filter($event, $this->form)
          ->getReturnValue();

        $this->form->save();
        
        $this->data = array('url' =>  url_for('@sf_guard_signin'));
        
        $this->returnJson();
        
    }
    else if ($request->isXmlHttpRequest()
            && $request->isMethod(sfRequest::POST))
    {
        $this->getResponse()->setStatusCode(500,'Regirset Error' );
        //return sfView::HEADER_ONLY;
        
        $this->data =  $this->form->getErrors();
        
        $this->returnJson();
    }
    else if ($request->isMethod(sfRequest::POST)
          && $this->form->isValid())
    {
      
        $event = new sfEvent($this, 'user.filter_register');
        $this->form = $this->dispatcher
          ->filter($event, $this->form)
          ->getReturnValue();

        $this->form->save();
        
        //agrega el grupo por defecto
        //del usuario
        $this->redirect('@sf_guard_signin');
    }
    
    $class = sfConfig::get('app_arquematics_plugin_register_form');
    $this->form = new $class();
    
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
            $this->getResponse()->setHttpHeader('Content-type','application/json');   
        }
    
    }
   
}