<?php

/**
 * arProfile actions.
 *
 * @package    arquematicsPlugin
 * @subpackage arProfile
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arProfileActions extends BaseArActions
{
  /**
   * solo el propio usuario puede editar un perfil
   * 
   * @return boolean
   */
  private function canEditProfile()
  {
      return ($this->aUserProfile->getId() === $this->aRouteUserProfile->getId());
  }
  
  public function executeFacebookConnect(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
          
        $config = sfConfig::get('app_melody_facebook');

        $config['callback'] = '@user_profile_configure?username='.$this->aUserProfile->getUsername();
        
        $this->getUser()->connect('facebook', $config);
    
    }
    else
    {
       $this->getUser()->connect('facebook'); 
    }
    
  }
  
  public function executeLinkedinConnect(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
          
        $config = sfConfig::get('app_melody_linkedin');

        $config['callback'] = '@user_profile_configure';
        
        $this->getUser()->connect('linkedin', $config);
    
    }
    else
    {
       $this->getUser()->connect('linkedin'); 
    }
    
  }
  
  public function executeTwitterConnect(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
          
        $config = sfConfig::get('app_melody_twitter');

        $config['callback'] = '@user_profile_configure';
        
        $this->getUser()->connect('twitter', $config);
    
    }
    else
    {
       $this->getUser()->connect('twitter'); 
    }
    
  }
  
  public function executeGoogleConnect(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
          
        $config = sfConfig::get('app_melody_google');

        $config['callback'] = '@user_profile_configure';
        
        $this->getUser()->connect('google', $config);
    
    }
    else
    {
       $this->getUser()->connect('google'); 
    }
    
  }
  

  public function executeFacebookUser(sfWebRequest $request)
  {
    $this->data = $this->getUser()->getMelody('facebook')->getMe();
    
    $this->returnJson();
  }
  
  
  public function executeConfigure(sfWebRequest $request)
  {
      if ($this->checkView())
      {
        $this->loadUser();
        
        $this->aRouteUserProfile = $this->aUserProfile;
       
        $this->canEdit = $this->canEditProfile();
        
        if ($this->canEdit)
        {
           $this->arRouteProfileImage = $this->aRouteUserProfile->getProfileImage();
    
           $this->form = new arProfileUploadForm($this->arRouteProfileImage);
            
           $this->formFirstLast = new arProfileFirstLastForm($this->aRouteUserProfile);
                                
           
           $this->formPassForm = new arProfilePassForm();
           
           //$this->formPassForm = new sfGuardChangeUserPasswordForm($this->authUser);
        }
        else
        {
          $this->redirect('@homepage');  
        }
      }
      else
      {
        $this->redirect('@homepage');
      }
     
  }
  
  
  /**
   * 
   * @param sfWebRequest $request
   */
  public function executeIndex(sfWebRequest $request)
  {
      if ($this->checkView())
      {
        $this->loadUser();
        
        $this->aRouteUserProfile = $this->getRoute()->getObject();
       
        $this->canEdit = $this->canEditProfile();
        
        $this->arRouteProfileImage = $this->aRouteUserProfile->getProfileImage();
    
        $this->form = new arProfileUploadForm($this->arRouteProfileImage);
        
      }
      else
      {
        $this->redirect('@homepage');
      }
     
  }
  
  public function executeProfileInfo(sfWebRequest $request)
  {
      $this->page = aTools::getCurrentPage();
      $this->can_view = ($this->page && $this->page->userHasPrivilege('view')) || empty($this->page);
      
      $this->aUser = $this->getRoute()->getObject();
      
      $authUser = $this->getUser();
      $this->is_auth = (is_object($authUser) &&  $authUser->isAuthenticated());
        
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
      if ($this->can_view && is_object($this->aUser) && ($this->is_auth))
      {
           $this->authUser = $authUser->getGuardUser();
           $this->can_edit = 
              ($this->page && $this->page->userHasPrivilege('edit'))
              || ($this->is_auth && ($this->aUser->getId() == $this->authUser->getId()));
          
           $this->aUserProfile = $this->aUser->getProfile();
            
           $this->data =  array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arProfile/showProfileContent', 
                            array('aUser' => $this->aUser,
                                  'aUserProfile' => $this->aUserProfile)),
                    "values" => array());
              
      }
      
      //devuelve el contenido en Json de $this->data
      $this->returnJson();
  }
  
  
  private function processForm(sfWebRequest $request, $form, $jsonData = true)
  {
       $form->bind($request->getParameter($form->getName()));
       
        if ($this->canEditProfile()
            && $request->isMethod(sfRequest::POST)
            && $form->isValid())
        {
            $objSave = $form->save();

            if ($jsonData)
            {
               $this->data =   array("status" => 200,
                                "errors" => '',
                                "HTML" => '',
                                "values" => array('id' => $objSave->getId(),
                                                  'first_last' => $objSave->getFirstLast(),
                                                  'description' => $objSave->getDescription()));  
            }
           
        }
        else if ($jsonData)
        {
           $this->data =   array("status" => 500,
                                "errors" => $form->getErrors(),
                                "HTML" => "",
                                "values" => ''); 
        }
  }
  
   /**
   * 
   * @param sfWebRequest $request 
   */
  public function executeUpdateName(sfWebRequest $request)
  {
      
     if ($this->checkView())
     {
        $this->loadUser();
        
        $this->aRouteUserProfile = $this->getRoute()->getObject();
        //usuario que se esta editando

        $this->form = new arProfileFirstLastForm($this->aRouteUserProfile);
        
        $this->processForm($request, $this->form);
        
        $this->returnJson();
     }
     else
     {
         $this->getResponse()->setStatusCode(404,'Not Found' );
         return sfView::HEADER_ONLY;
     }
    
  }
  
  public function executeUpdatePass(sfWebRequest $request)
  {
     if ($this->checkView())
     {
        $this->loadUser();
        
        $this->aRouteUserProfile = $this->aUserProfile;
        
        $this->form = new arProfilePassForm();
        
        $this->form->bind($request->getParameter($this->form->getName()));
       
        if ($this->canEditProfile()
            && $request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
            $this->authUser->setPassword($this->form->getValue('password'));
         
            $this->authUser->save();
            
            $this->getResponse()->setStatusCode(200,'ok' );
            return sfView::HEADER_ONLY; 
        }
        else
        {
          $this->getResponse()->setStatusCode(500,'Internal Error' );
          return sfView::HEADER_ONLY; 
        }
       
     }
     else {
         $this->getResponse()->setStatusCode(500,'Internal Error' );
         return sfView::HEADER_ONLY;
     }
  }
  
  
  public function executeUpdateDescription(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
        
         //usuario que se esta editando
        $this->aRouteUserProfile = $this->getRoute()->getObject();
        $this->form = new arProfileDescriptionForm($this->aRouteUserProfile);
        
        $this->processForm($request, $this->form);
    }
     
    $this->returnJson();
  }
  
  public function executeShowMutualFriends(sfWebRequest $request)
  {
     if ($this->checkView())
     {
       $this->loadUser();
       
       $this->aRouteUserProfile = $this->getRoute()->getObject();
        
        $pag = (int) $request->getParameter('pag');
        
        $queryInfo = Doctrine_Core::getTable('sfGuardUserProfile')
                        ->mutualFriendRequestExt(
                                $this->aUserProfile->getId(),
                                $this->aRouteUserProfile->getId(),
                                $pag,
                                (int)sfConfig::get('app_arquematics_plugin_mutual_friends_view'));
        
        $this->arMutualFriends =  $queryInfo['results'];
        $this->countMutualFriends =  $queryInfo['count'];
        
        if ($request->isXmlHttpRequest())
        {
            if ($queryInfo['count'] > 0)
            {
                $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('count' => $queryInfo['count'],
                                      'haveToPaginate' => $queryInfo['pager']->haveToPaginate(),
                                      'isLastPage' => $queryInfo['pager']->isLastPage()),
                    "HTML" => get_partial('arProfile/listMutualFriendsExt', 
                                array('arMutualFriends' => $queryInfo['results'],
                                      'aUserProfile' => $this->aUserProfile)));
            }
            else 
            {
                $this->data = array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" => '');
            }
            
            $this->returnJson();
        }
        
     }
      
  }
  
  public function executeListMutualFriends(sfWebRequest $request)
  {
     if ($this->checkView())
     {
        $this->loadUser();
        
        $this->aRouteUserProfile = $this->getRoute()->getObject();
        
        $pag = (int) $request->getParameter('pag');
        
        $queryInfo = Doctrine_Core::getTable('sfGuardUserProfile')
                        ->mutualFriendRequestExt(
                                $this->aRouteUserProfile->getId(),
                                $this->aUserProfile->getId(),
                                $pag,
                                (int)sfConfig::get('app_arquematics_plugin_wall_wall_mutual_friends_view', 7));
        
        
        if ($queryInfo['count'] > 0)
        {
             $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('count' => $queryInfo['count'],
                                      'haveToPaginate' => $queryInfo['pager']->haveToPaginate(),
                                      'isLastPage' => $queryInfo['pager']->isLastPage()),
                    "HTML" => get_partial('arProfile/listMutualFriends', 
                                array('arMutualFriends' => $queryInfo['results'],
                                      'aUserProfile' => $this->aUserProfile)));
        }
        else 
        {
            $this->data = array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" => '');
        }
      }
      
      $this->returnJson();
  }
  
  /**
   *
   * Actualiza uno de los campos del perfil
   * 
   * @param sfWebRequest $request 
   */
  /*
  public function executeUpdateField(sfWebRequest $request)
  {
      $authUser = $this->getUser();
      $this->is_auth = (is_object($authUser) &&  $authUser->isAuthenticated());
      
      $this->aUser = $this->getRoute()->getObject();
      
      $this->page = aTools::getCurrentPage();
      $this->can_edit = $this->is_auth && ($this->page && $this->page->userHasPrivilege('edit')) ;
      $this->can_edit = ($this->can_edit || (($this->is_auth && is_object($this->aUser)) && ($this->aUser->getId() == $authUser->getGuardUser()->getId())));
      
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
      
      $formValues = $request->getParameter('profile');
      
      if (isset($formValues['field_name']) 
              && (($formValues['field_name'] == 'FirstLastForm')
                  || ($formValues['field_name'] == 'DescriptionForm')))
      {
          
          $this->form = new $formValues['field_name'];
      
          $this->form->bind($formValues);
      
          if ($this->can_edit && $this->form->isValid())
          {
           $this->form->updateObject();
           
           $obj = $this->form->getObject();
           
           $this->aUserProfile = $this->aUser->getProfile();
           
           $retValue = "";
           $frm = "";
           if ($formValues['field_name'] == 'FirstLastForm')
           {
             $retValue = $obj->getFirstLast();
             $this->aUserProfile->setFirstLast($obj->getFirstLast());
            
             $this->aUserProfile->save();
             
             $firstLastForm = new FirstLastForm($this->aUserProfile);
             
             $frm = get_partial('arProfile/firstLastForm', 
                    array('aUser' => $this->aUserProfile,
                        'form' => $firstLastForm));
           }
           else
           {
              $retValue = $obj->getDescription();
              $this->aUserProfile->setDescription($obj->getDescription());
              
              $this->aUserProfile->save();
              
              $descriptionForm = new DescriptionForm($this->aUserProfile);
              
              $frm = get_partial('arProfile/descriptionForm', 
                    array('aUser' => $this->aUserProfile,
                        'form' => $descriptionForm));
           }
           
           
           $this->data =   array("status" => 200,
                                "errors" => '',
                                "HTML" => $retValue,
                                "values" => array('frm' => $frm));
            }
            else
            {
                $this->data =   array("status" => 500,
                                "errors" => $this->form->getErrors(),
                                "HTML" => "",
                                "values" => $formValues);
            }
          
      }
      
      
      //devuelve el contenido en Json de $this->data
      //como html para que IE no tenga problemas
      $this->returnJson();
      
  }*/
  
  public function executeSendImage(sfWebRequest $request)
  {
      $authUser = $this->getUser();
      $this->is_auth = (is_object($authUser) &&  $authUser->isAuthenticated());
      
      $this->aUser = $this->getRoute()->getObject();
      
      $this->page = aTools::getCurrentPage();

      $this->can_edit = (($this->is_auth && is_object($this->aUser)) && ($this->aUser->getId() == $authUser->getGuardUser()->getId()));

      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
      
      $this->form = new arProfileUploadForm();
      $formValues = $request->getParameter($this->form->getName());
      $formFiles = $request->getFiles($this->form->getName());

      $this->form->bind($formValues,$formFiles);
    
      if ($this->can_edit && $this->form->isValid())
      {
          $file = $this->form->getValue('name');
          
          try
          {
              $fileName = $file->save();
              
              $arProfileUpload = new arProfileUpload();
              $arProfileUpload->setName($file->getOriginalName());
              $arProfileUpload->setFileName($fileName);
              $arProfileUpload->setIsProfile(true);
              //NOTA: es la id del usuario que estamos viendo
              //no el auth
              $arProfileUpload->setUserId($this->aUser->getId());
              $arProfileUpload->setMimeContentType($file->getType());
              $arProfileUpload->save();
              
              
              $this->data =   array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => get_partial('arProfile/imageBig', 
                                                        array('image' => $arProfileUpload,
                                                              'aUser' => $this->aUser)),
                                    "values" => array("small" => url_for("@user_resource?type=arProfileUpload&name=".$arProfileUpload->getBaseName()."&format=".$arProfileUpload->getExtension()."&size=small")
                                      ));
          }
          catch (Exception $e)
          {
              
              $this->data =   array("status" => 500,
                                "errors" => 'Error saving data'.$e->getTraceAsString(),
                                "HTML" => "",
                                "values" => array('small' => ''));
              
          }
          
      }
      else
      {
           $this->data =   array("status" => 500,
                                "errors" => $this->form->getErrors(),
                                "HTML" => "",
                                "values" => array());
      }
      //devuelve el contenido en Json de $this->data
      //como html para que IE no tenga problemas
      $this->returnJson(false);
     
  }
  
  
}
