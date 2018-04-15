<?php
class arLavernaActions extends BaseArActions
{
 
  /**
   * Guarda la id del documento en la sesion 
   * y activa la herramienta documentos
   * 
   * @param arLavernaDoc $arDocument
   */
  private function saveSessionData($arDocument)
  {
     
      if (isset($arDocument) && is_object($arDocument))
      {
          
        // guarda los datos en la session
        $documents = $this->getUser()->getAttribute('arLavernaDoc',array(),'wall');
        
        if (!in_array($arDocument->getId(),$documents))
        {
           $documents[] = $arDocument->getId();

           $this->getUser()->setAttribute('arLavernaDoc', $documents, 'wall');  
        }
        $this->getUser()->setAttribute('activeTool', 'arLavernaDoc' , 'wall');
              
      }
  }
  
  private function saveSessionFileData($arFile, $docGui)
  {
      $docGui = filter_var($docGui, FILTER_SANITIZE_MAGIC_QUOTES);
      
      if (isset($arFile) && is_object($arFile))
      {
        // guarda los datos en la session
        $sessionFiles = $this->getUser()->getAttribute('arLavernaFile',array(),'wall');
        if (isset($sessionFiles[$docGui]))
        {
            array_push($sessionFiles[$docGui],array($arFile->getId(), $arFile->getGuid()));
        }
        else {
            $sessionFiles[$docGui] = array(array('id' => $arFile->getId(), 'guid' => $arFile->getGuid()));
        }
           
        $this->getUser()->setAttribute('arLavernaFile', $sessionFiles, 'wall');   
      }
      
      //print_r($sessionFiles);
      //$sessionFiles[$docGui] = array(array($arFile->getId(), $arFile->getGuid()));
      //$this->getUser()->setAttribute('arLavernaFile', array(), 'wall');   
  }
  
  public function executeUserInfo(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
         
        if (sfConfig::get('app_arquematics_encrypt'))
        {
            
            $isAdmin = $this->isCMSAdmin();
            $wallInfo = arMenuInfo::get(arMenuInfo::WALL);
            
            $this->data =   array("status" => 200,
                    'HTML' => get_partial('arMenuAdmin/mainMenu', array('isCMSAdmin' => $isAdmin, 'aUserProfile' => $this->aUserProfile)),
                    'HTML_DOC' => get_partial('arMenuAdmin/sidrDocumentsContent'),
                    'HTML_EXTRA' => ($isAdmin)? get_partial('arMenuAdmin/sidrAdminMenuContent'):'',
                    'user' => $this->aUserProfile->getUserInfo(),
                    'lang' => $this->culture,
                    'wall_url'     => $wallInfo['url'],
                    'store_key' => $this->aUserProfile->getStoreKey (),               
                    'public_keys' => $this->aUserProfile->getEncryptKeys(),
                    'cms_admin' => $isAdmin?'true':'false',
                    'encrypt' => true);
        }
        else 
        {
           $this->data =   array("status" => 200,
                    'user' => array('id' => $this->aUserProfile->getId()),
                    'encrypt' => true); 
        }
         
        $this->returnJson();
    }
    else {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
  }
  
  public function executeNotesAuth(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => 'NotesAuth',
                    "values" => array());
         
        $this->returnJson();
    }
    else {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
  }
  
  public function executeNotesDelete(sfWebRequest $request)
  {
    if ($this->checkView() 
        && $request->isMethod(sfRequest::DELETE))
    {
        $this->loadUser();
     
        try {
          $note = $this->getRoute()->getObject();
         
          if ($note->isSelfUser($this->userid) || $note->hasShareDoc($this->userid))
          {

              $this->data = $note->loadDocInfo($this->aUserProfile->getId(), $this->getUser()->getAttribute('arLavernaDoc', array(), 'wall'));
              
              // borra la nota de la sesion siempre
              $documents = $this->getUser()->getAttribute('arLavernaDoc',array(),'wall');
        
              if (in_array($note->getId(),$documents))
              {
                    $documents = array_diff($documents, array($note->getId()));
                    
                    $this->getUser()->setAttribute('arLavernaDoc', $documents, 'wall');  
              }
              $this->getUser()->setAttribute('activeTool', 'arLavernaDoc' , 'wall');
              
              //si no se especifica nada
              //borra no solo de la sesion, tambien de la base de datos
              $params = $request->getParameterHolder()->getAll();
          
              $fromSession = isset($params['fromSession'])?$params['fromSession']: false;
          
              //queremos borrar de la sesion y el documento
              //en si mismo
              if (!$fromSession)
              {
                  //$this->userid
                $note->conditionalDelete($this->userid);  
              }

              $this->returnJson();
          }
          else
          {
             $this->getResponse()->setStatusCode(404,'Not Found' );
             return sfView::HEADER_ONLY;   
          }
        } catch (Exception $e) {
            
           if (!$note)
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
    else {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
    
    
  }
  
  public function executeFileView(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
        
        try {
            $note = $this->getRoute()->getObject();
            
            $fileGuiId = $request->getParameter('fileguid');
		
                if ($note 
                    && $request->isMethod(sfRequest::GET)
                    && ($note->isSelfUser($this->userid) || $note->hasShareDoc($this->userid))
                    && $note->hasFile($fileGuiId))
                {
                   $this->data = $note->getFile($fileGuiId);
                   //pequeño arreglo 
                   $this->data = $this->data[0];
                   $this->returnJson(); 
                }
                else {
			
                    $this->returnJson(); 
                    $this->getResponse()->setStatusCode(404,'Not Found' );
                    return sfView::HEADER_ONLY;
                }
            }
            catch (Exception $e)
            {
                $note = false;
                
                if (!$note)
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
    else {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
    
    
  }
  
  public function executeNotesView(sfWebRequest $request)
  {
    if ($this->checkView())
    {
           $this->loadUser();
           $this->note = false;
           
           try {
            
                $this->note = $this->getRoute()->getObject();
                
                if ($this->note 
                    && $request->isMethod(sfRequest::GET)
                    && ($this->note->isSelfUser($this->userid) || $this->note->hasShareDoc($this->userid)))
                {
                    $this->data = $this->note->loadDocInfo($this->aUserProfile->getId(), $this->getUser()->getAttribute('arLavernaDoc', array(), 'wall'));
        
                    $this->returnJson();
                }
                else
                {
                    $this->getResponse()->setStatusCode(401,'Unauthorized' );
                    return sfView::HEADER_ONLY;
                }
            }
            catch (Exception $e)
            {
                if (!$this->note)
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
    else {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
    
    
  }
  
  public function executeNotesUpdate(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
       
        try {
            $this->note = $this->getRoute()->getObject();
            $this->form = new arLavernaDocForm($this->note, array(
                            //'setUser' => false, 
                            'aUserProfile' => $this->aUserProfile));
        
            $this->form->bind($request->getParameter($this->form->getName()));
      
            if ($request->isMethod(sfRequest::PUT)
              && ($this->note->isSelfUser($this->userid) || $this->note->hasShareDoc($this->userid))
              && $this->form->isValid())
            {
                $arLavernaDoc = $this->form->save();
                //solo si se esta compartiendo
                if ($this->form->getValue('share') == 1)
                {
                    $this->saveSessionData($arLavernaDoc); 
                }
           
                $this->data = $arLavernaDoc->loadDocInfo($this->aUserProfile->getId(), $this->getUser()->getAttribute('arLavernaDoc', array(), 'wall'));
           
                $this->returnJson();
            }
            else
            {
                $this->getResponse()->setStatusCode(401,'Unauthorized' );
                return sfView::HEADER_ONLY;
            }
        } catch (Exception $e) {
            $this->getResponse()->setStatusCode(501,'Server Error' );
            return sfView::HEADER_ONLY;
        }
    }
  }
  
  /**
   * crea un pad y valida la entrada
   * 
   * @param sfWebRequest $request
   * @return <boolean>: true ha creado el pad
   */
  private function notesCreate(sfWebRequest $request)
  {
      $this->form = new arLavernaDocForm(null, array(
                           //'setUser' => true, 
                           'aUserProfile' => $this->aUserProfile));
      //si false no ha validado bien el dato
      $ret = false;
      
      $this->form->bind($request->getParameter($this->form->getName()));
      
      if ($request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          
           $arLavernaDoc = $this->form->save();
           
           $this->saveSessionData($arLavernaDoc);
           
           $this->data = $arLavernaDoc->loadDocInfo($this->aUserProfile->getId(), $this->getUser()->getAttribute('arLavernaDoc', array(), 'wall'));
           
           $ret = true;
      }
      
      else
      {
          $this->data =   array("status" => 500,
                              "errors" => $this->form->getErrors(),
                              'contents' => array(),
                              "HTML" => 'notesCreate',
                              "values" => array());
      }
      
      return $ret;
     
  }
  
  private function notesList(sfWebRequest $request)
  {
      $page = (int)$request->getParameter('page');
      $trash = (int)$request->getParameter('trash');
      $isFavorite = (int)$request->hasParameter('isFavorite');
      
      if ($request->hasParameter('docType') 
              && ($trash === 0)
              && ($isFavorite === 0))
      {
        $docType = $request->getParameter('docType');  
      }
      else if (($trash === 0)
              && ($isFavorite === 0))
      {
        $docType = 'note';   
      }
      else {
        //todos los tipos de documentos
        $docType = false;     
      }
     

      $max_per_page = (int)sfConfig::get('app_arquematics_document_docs_perpage', 10);
      
      $query = Doctrine_Core::getTable('arLavernaDoc')
                ->getQuerySimpleByUserId($this->aUserProfile->getId(),
                                         $docType,
                                         $trash,
                                         $isFavorite);
      
      $this->pager = new sfDoctrinePager(null, $max_per_page);
      $this->pager->setQuery($query);
      //inicia el paginador en la pagina que se ha pedido
      $this->pager->setPage($page);
      $this->pager->init();
      
      $totalDocsCount = Doctrine_Core::getTable('arLavernaDoc')
                                ->countByUserId($this->aUserProfile->getId(),
                                        $docType,
                                        $trash,
                                        $isFavorite);
      
      
      $this->data = array(
          'total_pages' => ceil($totalDocsCount / $max_per_page),
          'total_count' => (int)$totalDocsCount,
          'items' => $this->pager->getResults(Doctrine::HYDRATE_ARRAY));
       
      return true;
  }
  
  private function filesCreate(sfWebRequest $request)
  {

      $this->form = new arLavernaFileForm(null, array(
                           'aNote' => $this->note,
                           'aUserProfile' => $this->aUserProfile));
      //false si no ha validado bien el dato
      $ret = false;

      $this->form->bind($request->getParameter($this->form->getName()));
      
      if ($request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
           $this->arLavernaFile = $this->form->save();
           $this->data = $this->arLavernaFile->getInfo($this->aUserProfile->getId());
           $ret = true;
      }
      
      else
      {
          $this->data =   array("status" => 500,
                              "errors" => $this->form->getErrors(),
                              'contents' => array(),
                              "HTML" => 'notesCreate',
                              "values" => array());
      }
      
      return $ret;
  }

  public function executeFilesUpdate(sfWebRequest $request)
  {

    try {
       $this->note = $this->getRoute()->getObject();
    }
    catch (Exception $e)
    {
       $this->note = false;
    }

    if ($this->checkView() 
      && ($request->isMethod(sfRequest::PUT)) 
      && $this->note && is_object($this->note))
    {

      $this->loadUser();



      $arLavernaFile = $this->note->getFileObj($request->getParameter('fileguid'));


      if ($arLavernaFile)
      {

        $this->form = new arLavernaFileUpdateForm($arLavernaFile, array(
                           'aNote' => $this->note,
                           'aUserProfile' => $this->aUserProfile));

      
        $this->form->bind($request->getParameter($this->form->getName()));

        if ($request->isMethod(sfRequest::PUT)
          && $this->form->isValid())
        {
          $this->arLavernaFile = $this->form->save();
          $this->data = $this->arLavernaFile->getInfo();
          $this->returnJson(); 
        }
        else
        {
          $this->data = $this->form->getErrors();
          $this->returnJson(); 
           //ha tenido un error
          //$this->getResponse()->setStatusCode(501,'Unauthorized on Error' );
          //return sfView::HEADER_ONLY;
        }

      }
      else
      {
        //ha tenido un error
        $this->getResponse()->setStatusCode(501,'Unauthorized on Error' );
        return sfView::HEADER_ONLY;
      }
    }
    else
    {
        //ha tenido un error
        $this->getResponse()->setStatusCode(501,'Unauthorized on Error' );
        return sfView::HEADER_ONLY;
    }

  }

  public function executeFilesMain(sfWebRequest $request)
  {
    $ret = $this->checkView();
   
    try {
       $this->note = ($ret)? $this->getRoute()->getObject():false;
    }
    catch (Exception $e)
    {
       $this->note = false;
    }
    
    if ($ret && $this->note && is_object($this->note))
    {
        $this->loadUser();
       
        $ret = $this->note->isSelfUser($this->userid) || $this->note->hasShareDoc($this->userid);
       
        if ($ret && $request->isMethod(sfRequest::POST)) 
        {
          $ret = $this->filesCreate($request); 
        }
        else if ($ret && $request->isMethod(sfRequest::GET))
        {
          $this->data = $this->note->getFiles();
          $ret = true;   
        }
        
        if ($ret && $this->note)
        {
            $this->returnJson(); 
        }
        else
        {
            //ha tenido un error
            $this->getResponse()->setStatusCode(501,'Unauthorized on Error' );
            return sfView::HEADER_ONLY;   
        }
    }
    //esto solo pasa si
    //el documento no se ha guardado aun
    else if ($ret 
            && (!$this->note)
            && $request->isMethod(sfRequest::POST)) 
    {
       $this->loadUser();
       
       $ret = $this->filesCreate($request);
       //guarda datos en la sesión
       $this->saveSessionFileData($this->arLavernaFile,  $request->getParameter('guid'));
       
       $this->returnJson(); 
    }
    else
    {
      //ha tenido un error
       $this->getResponse()->setStatusCode(501,'Unauthorized on Error' );
       return sfView::HEADER_ONLY;  
    }
  }
  
  public function executeNotesMain(sfWebRequest $request)
  {
    $ret = $this->checkView();
    //echo $ret;
    //exit();
    if ($ret)
    {
        $this->loadUser();
        
        $ret = false;
        
        if ($request->isMethod(sfRequest::POST)) 
        {
           $ret = $this->notesCreate($request); 
        }
        else if (($request->isMethod(sfRequest::GET)) )
        {
          $ret = $this->notesList($request);   
        }
    }

    if ($ret)
    {
       $this->returnJson(); 
    }
    else
    {
       //ha tenido un error
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;
    }
   
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
        
        $this->formNote = new arLavernaDocForm();
        
        $this->formFile = new arLavernaFileForm();

        $this->formFileUpdate = new arLavernaFileUpdateForm();
        
        $this->formDiagram = new arDiagramForm();
    }
    else
    {
      $this->redirect('@homepage');
    }
  }
  
  public function executeView(sfWebRequest $request)
  {
    if ($this->checkView())
    {
        $this->loadUser();
        
    } 
  }
}
