<?php
class arVectorialEditorActions extends BaseArActions
{
    
  /**
   * Guarda la id del documento en la sesion 
   * y activa la herramienta documentos
   * 
   * @param arDiagram $arObject
   */
  private function saveSessionData($arObject)
  {
      if (isset($arObject) && is_object($arObject))
      {
        // guarda los datos en la session
        $documents = $this->getUser()->getAttribute('arLavernaDoc',array(),'wall');
        
        if (!in_array($arObject->getId(),$documents))
        {
           $documents[] = $arObject->getId();

           $this->getUser()->setAttribute('arLavernaDoc', $documents, 'wall');  
        }
        $this->getUser()->setAttribute('activeTool', 'arLavernaDoc' , 'wall'); 
      }
  }
  
  private function removeSessionData($object)
  {
    // borra la nota de la sesion siempre
    $documents = $this->getUser()->getAttribute('arLavernaDoc',array(),'wall');
        
    if (in_array($object->getId(),$documents))
    {
        $documents = array_diff($documents, array($object->getId()));
                    
        $this->getUser()->setAttribute('arLavernaDoc', $documents, 'wall');  
    }
        
    $this->getUser()->setAttribute('activeTool', 'arLavernaDoc' , 'wall');
  }
 
  
  
  public function executeDelete(sfWebRequest $request)
  {
    if ($this->checkView() 
        && $request->isMethod(sfRequest::DELETE))
    {
        $this->loadUser();
        try {
          $vectorialImage = $this->getRoute()->getObject();
          
          if ($vectorialImage->isSelfUser($this->userid) || $vectorialImage->hasShareDoc($this->userid))
          {
             $this->removeSessionData($vectorialImage);

             //si no se especifica nada
              //borra no solo de la sesion, tambien de la base de datos
              $params = $request->getParameterHolder()->getAll();
          
              $fromSession = isset($params['fromSession'])?$params['fromSession']: false;
              
              //queremos borrar de la sesion y el documento
              //en si mismo si procede
              if (!$fromSession)
              {
                $vectorialImage->conditionalDelete($this->userid);  
              }
              
              $this->data =   array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => '',
                                    "values" => array());
              
              $this->returnJson();
          }
          else
          {
             $this->getResponse()->setStatusCode(404,'Not Found' );
             return sfView::HEADER_ONLY;   
          }
        }
        catch (Exception $e)
        {
           if (!$vectorialImage)
           {
             $this->getResponse()->setStatusCode(404,'Not Found' );
             return sfView::HEADER_ONLY;  
           }
           else
           {
             $this->getResponse()->setStatusCode(500,'Internal Error' );
             return sfView::HEADER_ONLY;    
           } 
        }    
    }
    else
    {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
      
  }
  
  
  public function executeUpdate(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
        
        try{
            $this->aDiagram = $this->getRoute()->getObject();
            
            $this->form = new arLavernaDocForm($this->aDiagram, array('setUser' => false, 'aUserProfile' => $this->aUserProfile));
        
            $this->form->bind($request->getParameter($this->form->getName()));
            
            if ($request->isMethod(sfRequest::PUT)
              && ($this->aDiagram->isSelfUser($this->userid) || $this->aDiagram->hasShareDoc($this->userid))
              && $this->form->isValid())
            {
                $this->aDiagram = $this->form->save();
                //solo si se esta compartiendo
                if ($this->form->getValue('share') == 1)
                {
                    $this->saveSessionData($this->aDiagram); 
                }
                
                $this->data = $this->aDiagram->loadDocInfo($this->userid);
                
                $this->returnJson();
            }
            else
            {
                
                $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => "",
                    "values" => array());
                 
                $this->returnJson();
            }
        } catch (Exception $e) {
            $this->getResponse()->setStatusCode(501,'Server Error' );
            return sfView::HEADER_ONLY;
        }
    }
    else
    {
        $this->redirect('@homepage');
    }
      
  }
  
  private function svgList(sfWebRequest $request, $diagramName)
  {    
      $page = (int)$request->getParameter('page');
      $trash = (int)$request->getParameter('trash');
      $isFavorite = (int)$request->hasParameter('isFavorite');
        
      $max_per_page = (int)sfConfig::get('app_arquematics_document_docs_perpage', 10);
      /*
      echo $diagramName;
      exit();
       * 
       */
      $query = Doctrine_Core::getTable('arDiagram')
                ->getQuerySimpleByUserId($this->aUserProfile->getId(),
                                         $diagramName,
                                         $trash,
                                         $isFavorite);
      
     
      $this->pager = new sfDoctrinePager(null, $max_per_page);
      $this->pager->setQuery($query);
      //inicia el paginador en la pagina que se ha pedido
      $this->pager->setPage($page);
      $this->pager->init();
      
      $totalDocsCount = Doctrine_Core::getTable('arDiagram')
                                ->countByUserId($this->aUserProfile->getId(),
                                        $diagramName,
                                        $trash,
                                        $isFavorite);
      
      $this->data = array(
          /*'pages' => array("prev" => "",
                           "next" => url_for("@diagram_create?name=$diagramName&isFavorite=$isFavorite&trash=$trash"),
                           "first" =>  "",
                           "last" => ""),*/
          'current_page' => (int)$page,
          'total_pages' => ceil($totalDocsCount / $max_per_page),
          'total_count' => (int)$totalDocsCount,
          'items' => $this->pager->getResults(Doctrine::HYDRATE_ARRAY));
       
      return true;
  }
  
  private function saveDiagram(sfWebRequest $request)
  {
      
        $this->form = new arLavernaDocForm(null,array('setUser' => false, 'aUserProfile' => $this->aUserProfile));

        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST) 
                && ($this->form->isValid()))
        {
            $arDiagram = $this->form->save();
            
            if ($arDiagram && is_object($arDiagram))
            {
                $this->saveSessionData($arDiagram);
                
                $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
            }
            else
            {
                 $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
            }
        }
        else
        {
             $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => "",
                    "values" => array());
        }
  }
  
  
  public function executeView(sfWebRequest $request)
  {
      
      if ($this->checkView())
      {
          $this->loadUser();
          
          //try{
              $this->arDiagram = $this->getRoute()->getObject(); 
              
              
               if ($this->arDiagram 
                    && $request->isMethod(sfRequest::GET)
                    &&  $request->isXmlHttpRequest())
               {
                   $this->data = $this->arDiagram->loadDocInfo($this->aUserProfile->getId());
                   //$this->data = $this->arDiagram->getDocInfo($this->aUserProfile->getId());
                   $this->returnJson(); 
               }
               else if ($this->arDiagram
                       && $request->isMethod(sfRequest::GET))
               {
                    $this->form = new arLavernaDocForm($this->arDiagram);
                    
                    $this->documentType = $this->arDiagram->getInfo();
                    $this->arDiagram->loadDocInfo($this->aUserProfile->getId());
                    
                    $this->setTemplate($this->documentType['template']);
                    
                    $this->removeCss();
                    if (arLavernaDoc::isOryxName($this->arDiagram->getType()))
                    {
                      $this->setLayout('layoutSimple');
                    }
                    else if (arLavernaDoc::isRawChart($this->arDiagram->getType()))
                    {
                      $this->setLayout('layoutArAngular');
                    }
               }
               else
               {
                    $this->getResponse()->setStatusCode(401,'Unauthorized' );
                    return sfView::HEADER_ONLY;
               }
          /*} catch (Exception $e) {
            $this->getResponse()->setStatusCode(501,'Server Error' );
            return sfView::HEADER_ONLY;
          }*/
      }
      else if ($request->isMethod(sfRequest::GET) 
                 &&  $request->isXmlHttpRequest())
      {
        $this->getResponse()->setStatusCode(401,'Unauthorized' );
        return sfView::HEADER_ONLY;
      }
      else
      {
        $this->redirect('@homepage');
      }
      
  }
  
  private function removeCss()
  {
      //sfContext::getInstance()->getResponse()->removeStylesheet('/apostrophePlugin/css/ui-apostrophe/jquery-ui.css');
  }
  
  public function executeIndex(sfWebRequest $request)
  {
      
      if ($this->checkView())
      {
        $this->loadUser();
        
        $diagramName = $request->getParameter('name');
        if (arLavernaDoc::isNameType($diagramName))
        {
           
            if ($request->isMethod(sfRequest::GET) 
                 &&  $request->isXmlHttpRequest())
            {
                $this->svgList($request, $diagramName);
                
                $this->returnJson(); 
            }
            else if ($request->isMethod(sfRequest::POST) 
                 &&  $request->isXmlHttpRequest())
            {
                $this->saveDiagram($request);
                
                $this->returnJson(); 
            }
            else if ($request->isMethod(sfRequest::GET)
                    && ($diagramName == 'note'))
            {
                $this->loadUser();
                
                $this->documentType = arLavernaDoc::getTypeByName($diagramName);
                $this->setTemplate($this->documentType['template']); 
                 
                $this->formNote = new arLavernaDocForm();
                $this->formFile = new arLavernaFileForm();
                $this->formDiagram = new arDiagramForm();
                
                $this->setLayout('layoutMain');
            }
            else if ($request->isMethod(sfRequest::GET)
                    && arLavernaDoc::isOryxName($diagramName))
            {
                $this->documentType = arLavernaDoc::getTypeByName($diagramName);
                
                $this->form = new arLavernaDocForm();
                
                $this->setTemplate($this->documentType['template']); 
                
                $this->removeCss();

                $this->setLayout('layoutSimple');

            }
            else if ($request->isMethod(sfRequest::GET)
                    && arLavernaDoc::isRawChart($diagramName))
            {
                $this->documentType = arLavernaDoc::getTypeByName($diagramName);
                
                $this->form = new arLavernaDocForm();
                
                $this->setTemplate($this->documentType['template']); 

                $this->setLayout('layoutArAngular');
            }
            else if ($request->isMethod(sfRequest::GET))
            { 
                $this->documentType = arLavernaDoc::getTypeByName($diagramName);
                
                $this->form = new arLavernaDocForm();
                
                $this->setTemplate($this->documentType['template']); 
                
                $this->removeCss();
            }
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
   * muestra el editor
   */
  /*
  public function executeEdit(sfWebRequest $request)
  {
      $this->forwardIfNoEdit();
      $this->loadUser();
     
      $this->aDiagram = $this->getRoute()->getObject();
      $this->aDiagramJson = $this->aDiagram->getJson();
      
      $this->diagramType = $this->aDiagram->getType();
     
      if ($this->diagramType == arDiagram::SIMPLE_IMAGE)
      {
        $this->form =  new arDiagramImageForm();
        
        $this->setTemplate('simpleImage'); 
      }
      else if ($this->diagramType == arDiagram::WIREFRAME)
      {
          $this->form =  new arDiagramImageForm();
          
          $this->setTemplate('wireframe'); 
      }
      else if ($this->diagramType == arDiagram::MINDMAPS)
      {
         //$this->form = new arMindMapForm();
         $this->form = new arDiagramEditorForm();
         $this->setTemplate('mindmaps');  
      }
      else
      {
        $this->form = new arDiagramEditorForm();
        $this->stencilset = 'stencilsets'.Doctrine_Core::getTable('arDiagram')->getTypeName($this->aDiagram->getType());
        
        $this->setTemplate('diagrams'); 
      }

     $this->setLayout(false);  
     
  }*/
  
  
  /**
   * carga un archivo de imagen en el editor
   * 
   * @param sfWebRequest $request 
   */
  /*
  public function executePainterImageLoad(sfWebRequest $request)
  {
     $this->data =  array("status" => 500,
                    "errors" => "",
                    "HTML" => "",
                    "values" => array());
     
     if ($this->checkView())
     {
        $this->loadUser();
       
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array("jpeg","bmp","jpg","png",'gif');
         // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;
        $originalPath = sfConfig::get('app_aToolkit_writable_tmp_dir');

        $uploader = new FileXhrUpload($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($originalPath);
      
        if (($result && is_array($result)) && $result['success'])
        {
            
           $file = $result['file'];
             
           if (EditorImageUpload::saveImageDiagram($file))
           {
       
               $diagram = new arDiagram();
               $diagram->setFileName($file);
               $diagram->setUserId($this->userid);
               $diagram->save();
               
               $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => "",
                    "values" => array(
                        'id' => $diagram->getId(),
                        'url' => url_for("@user_wall_diagram?slug=".$diagram->getSlug()."&size=big"),
                        'user_id' => $this->userid
                        ));
               
           } 
        }
     }
   
     $this->returnJson();
  }*/
  public function executeSendMindMap(sfWebRequest $request)
  {
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
      
       if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arMindMapForm();
         
          $param = $request->getParameter($this->form->getName());
     
          $param['user_id'] = $this->userid;
          $param['file_name'] = isset($param['data_image'])?$param['data_image']:'';
          
          $this->form->bind($param);
          
          if ($request->isMethod(sfRequest::POST) 
                && ($this->form->isValid()))
          {
             
             $this->saveSessionData($this->form->save());
             
          }
          else
          {
               $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
          }
          
      }
      
      $this->returnJson();
  }
  
  public function executeSendImageEditor(sfWebRequest $request)
  {
     $this->loadUser();
     
     $this->form = new arDiagramImageForm();
     
     $this->form->bind(
            $request->getParameter($this->form->getName()),
            $request->getFiles($this->form->getName())
        );
     
     $hasError = true;
     
      $data = $request->getParameter('diagram');
          
         // print_r($data);
     
     if ($this->form->isValid())
     {
         
          if ($data && is_array($data) && isset($data['data_image']))
          {
             
              $file = EditorImageUpload::savePNG(
                  $data['data_image'],
                  sfConfig::get('app_aToolkit_writable_tmp_dir'));
          
              if ($file && EditorImageUpload::saveImageDiagram($file))
              {
                         
                $arDiagram = new arDiagram();
                $arDiagram->setFileName($file);
                $arDiagram->setUserId($this->userid);
               
                $arDiagram->save();
              
                //hasArDiagram
                $hasError = false;
                // guarda los datos en la sesion
                $diagrams = $this->getUser()->getAttribute('arDiagram',array(),'wall');
                $diagrams[] = $arDiagram->getId();
                $this->getUser()->setAttribute('arDiagram', $diagrams, 'wall');
                
                $this->getUser()->setAttribute('activeTool', 'arDiagram' , 'wall');
              }
         }
          
         
     }
    
     if ($hasError)
     {
       //red color 
       //$this->getUser()->setFlash('error', __('Error saving image.',array(),'diagram-editor'));
       //$this->redirect(url_for("@homepage"));
         
       $this->setLayout(false);  
     }
     else
     {
        
        $this->redirect(url_for("@wall")); 
     }
  }
  
  
  public function executeSendOryxImageEditor(sfWebRequest $request)
  {
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
      
      if ($this->checkView())
      {
          $this->loadUser();
      
          $this->form = new arDiagramEditorForm();
         
          $param = $request->getParameter($this->form->getName());
     
          $param['user_id'] = $this->userid;
          $param['file_name'] = isset($param['data_image'])?$param['data_image']:'';
          
          $this->form->bind($param);
          
          if ($request->isMethod(sfRequest::POST) 
                && ($this->form->isValid()))
          {
             $this->saveSessionData($this->form->save());
          }
          else
          {
               $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
          }
          
      }
      else
      {
           $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
      }
              
      $this->returnJson();
  }
}