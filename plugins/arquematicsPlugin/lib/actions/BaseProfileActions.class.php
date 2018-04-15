<?php

class BaseProfileActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->getUser()->setFlash('notice', 'You are already registered and signed in!');
      $this->redirect('@homepage');
    }

    $class = sfConfig::get('app_arquematics_plugin_register_form', 'telvyRegisterForm');
    $this->form = new $class();

    if ($request->isMethod('post'))
    {
      
    
      //juntando el captcha
      $captcha = array(
       'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
       'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
      );
      
      $params = $request->getParameter($this->form->getName());
      $this->form->bind(array_merge(
              $params,
              array('recaptcha' => $captcha)));
        
      if ($this->form->isValid())
      {
        $event = new sfEvent($this, 'user.filter_register');
        $this->form = $this->dispatcher
          ->filter($event, $this->form)
          ->getReturnValue();

        $user = $this->form->save();
        //nuevo nombre de usuario
        $user->setUsername(sfGuardUser::newGuid());
        $user->save();
        
        //agrega al grupo por defecto
        //del usuario
        $user->addGroupByName(sfConfig::get('app_arquematics_plugin_default_user_group', 'basic'));
    
        $postParams = $request->getParameter($this->form->getName());
          
        $domainName = $postParams['domain_name'];
        $password = $postParams['password'];
        
        //crea un nuevo cliente
        $organization = $postParams['organization'];
        $customer = new Customer();
        $customer->setName(trim($organization));
        $customer->setEmail($user->getEmailAddress());
        $customer->save();
        
        //crea un nuevo site del usuario
        $site = new SitesAvailable();
        //relaciones
        $site->setUserId($user->getId());
        $site->setCustomerId($customer->getId());
        //campos
        $site->setName(trim($domainName));
        $site->setSitePass(md5(trim($password)));

        $site->save();
        
        
        
        /*
        
        $this->getUser()->signIn($user);
        
        $this->setLayout(false);
        $this->setTemplate('errorNoDef');

        //$this->redirect('@homepage');
         * 
         */
      }
    }
  }
}
