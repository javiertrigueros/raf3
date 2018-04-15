<?php

/**
 * aMap actions.
 *
 * @package    arquematicsPlugin
 * @subpackage aMap
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arMapActions extends BaseArActions
{
 
   /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {

      $this->page = aTools::getCurrentPage();
      $this->is_page_view = ($this->page && $this->page->userHasPrivilege('view')) || empty($this->page);
      
      $this->aUser = $this->getRoute()->getObject();
      
      //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->is_page_view && is_object($this->aUser)) );
      
      $authUser = $this->getUser();
      $this->is_auth_user = (is_object($authUser) &&  $authUser->isAuthenticated());
      $this->is_page_edit = 
              ($this->page && $this->page->userHasPrivilege('edit'))
              || ($this->is_auth_user && ($this->aUser->getId() == $authUser->getGuardUser()->getId()));

      
  }
  
  public function executeCancel(sfWebRequest $request)
  {
    
     $this->object = $this->getRoute()->getObject();
     
     $objectIds = $this->getUser()->getAttribute('arGmapsLocate',array(),'wall');
                
     $this->getUser()->setAttribute('activeTool', 'arGmapsLocate', 'wall');
     
     if ($this->checkView()
         && $request->isMethod(sfRequest::POST) 
         && (is_object($this->object)) 
         && in_array($this->object->getId(),$objectIds))
     {
        if(($key = array_search($this->object->getId(), $objectIds)) !== false) 
        {
            unset($objectIds[$key]);
        }
        
        $this->getUser()->setAttribute('arGmapsLocate', $objectIds, 'wall');
        
        //si no tiene mensages que dependan de la dirección
        if (count($this->object->getMessages()) == 0)
        {
          $this->object->delete();  
        }
        
        $this->data =   array("status" => 200,
                            "errors" => array(),
                            "HTML" => '',
                            "values" => array());
     }
     
     $this->returnJson();
      
  }
  
  public function executeWallSendMap(sfWebRequest $request)
  {
      
       $this->form = new arGmapsLocateForm();
       $formValues = $request->getParameter($this->form->getName());
       $this->form->bind($formValues);
          
      if($this->checkview() 
        && $request->isMethod(sfRequest::POST)
        && $this->form->isValid())
      {
          $this->loadUser();
          
          $hash = trim($this->form->getValue('hash'));
         
          $locationObj = Doctrine::getTable('arGmapsLocate')->getByHash($hash);
         
          if (!($locationObj && is_object($locationObj)))
          {
            $locationObj = $this->form->save();
          }
          else{
             $this->form = new arGmapsLocateForm($locationObj);
             $this->form->bind($formValues);
             if ($this->form->isValid())
             {
                 $locationObj = $this->form->save();
             }
          }
         
          
          // guarda los datos en la session
          $objIds = $this->getUser()->getAttribute('arGmapsLocate',array(),'wall');
          $objIds[] = $locationObj->getId();
          $this->getUser()->setAttribute('arGmapsLocate', $objIds, 'wall');
        
          $this->getUser()->setAttribute('activeTool', 'arGmapsLocate' , 'wall');
          
          if (sfConfig::get('app_arquematics_encrypt', false))
          {
              
              $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array(
                        'id' => $locationObj->getId(),
                        'hash' => $locationObj->getHash(),
                        'content' => '',
                        'content_enc' => $locationObj->getEncryptTxt($this->aUserProfile), 
                    )); 
          }
          else
          {
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array(
                        'id' => $locationObj->getId(),
                        'hash' => $locationObj->getHash(),
                        'content' => $locationObj->getFormatedAddress(),
                        'content_enc' => ''
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
      
      //devuelve el contenido en Json de $this->data
      $this->returnJson();
  }
  
  
  public function executeSendProfileMap(sfWebRequest $request)
  {
      
      if ($this->checkView())
      {
        $this->loadUser();
        
        $this->aRouteUser = $this->getRoute()->getObject();
        
        if ($this->isCMSAdmin() 
            || ($this->aUserProfile->getId() === $this->aRouteUser->getId()))
        {
          //tiene permiso para editar el perfil
          $formValues =  $request->getParameter('locate');
          
          $hash = trim($formValues['hash']);
          $locationObj = Doctrine::getTable('arGmapsLocate')
                  ->getByHash($hash);
         
          if ($locationObj && is_object($locationObj))
          {
            $this->form = new arGmapsLocateForm($locationObj, array('profileRelated' => $this->aRouteUser));
          }
          else
          {
            $this->form = new arGmapsLocateForm(null,array('profileRelated' => $this->aRouteUser));  
          }
          
          $this->form->bind($request->getParameter($this->form->getName())); 
          
          if($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
          {
            $locationObj = $this->form->save();
            
            if (sfConfig::get('app_arquematics_encrypt', false))
            {
                 $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array(
                        'id' => $locationObj->getId(),
                        'hash' => $locationObj->getHash()
                    )); 
            }
            else
            {
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => $locationObj->getFormatedAddress(),
                    "values" => array(
                        'id' => $locationObj->getId(),
                        'hash' => $locationObj->getHash()
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
          
        }
      }
      
       //devuelve el contenido en Json de $this->data
       $this->returnJson();
  }
  
}