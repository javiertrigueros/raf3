<?php

use \CloudConvert\Api;

class arDropActions extends BaseArActions
{
  
  public function executeDeleteFile(sfWebRequest $request)
  {
    if ($this->checkView() 
        && $request->isMethod(sfRequest::DELETE))
    {
        $this->loadUser();
     
        try {
          $file = $this->getRoute()->getObject();
         
          //if ($file->isSelfUser($this->userid) || $file->hasShareDoc($this->userid))
          if ($file->isSelfUser($this->userid))
          {
              $file->delete();
              $this->getResponse()->setStatusCode(200,'OK');
              return sfView::HEADER_ONLY; 
          }
          else
          {
             $this->getResponse()->setStatusCode(404,'Not Found' );
             return sfView::HEADER_ONLY;   
          }
          
        } catch (Exception $e) {
            
           if (!$file)
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
    
  public function executeCreateProcess(sfRequest $request)
  {
    $inputformat = trim($this->getRequestParameter('inputformat'));
    $outputformat = trim($this->getRequestParameter('outputformat'));
        
    if($this->checkView()
       && arDropFile::isValidInputConversionExt($inputformat)
       && arDropFile::isValidOutputConversionExt($outputformat))
    {
        $api = new Api(sfConfig::get('app_arquematics_plugin_cloudconvertAPI'));
        
        $process = $api->createProcess([
            'inputformat' => $inputformat,
            'outputformat' => $outputformat,
        ]);

        $this->data = $process;
        
    }
    
    $this->returnJson();
  }
  
  public function executeUpdateFile(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        //solamente el propio usuario puede cambiar el
        //estatus del fichero a ready
        if ($arDropFile && $arDropFile->isSelfUser($this->userid))
        {
            $arDropFile->setReady(true); 
        
            $arDropFile->save();
        
            $chunks = $arDropFile->countChunks();
        
            $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFile->getId(),
                                      //si tiene mas partes el contenido es nada
                                     'content' => ($chunks > 0)?'': $arDropFile->getSrc(),
                                     'chunks' => array(),
                                     'parts' => $arDropFile->countChunks()  
                                    ));
            
            $this->returnJson();
        }
        else
        {
            $this->getResponse()->setStatusCode(401,'Unauthorized' );
            return sfView::HEADER_ONLY;
        }
    }
    else
    {
       $this->getResponse()->setStatusCode(401,'Unauthorized' );
       return sfView::HEADER_ONLY;  
    }
  }
  
  public function executeViewFile(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        if ($arDropFile && 
                ($arDropFile->isSelfUser($this->userid) 
                || $arDropFile->isShareForUser($this->userid)))
        {
            $chunks = $arDropFile->countChunks();
        
            $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFile->getId(),
                                      //si tiene mas partes el contenido es nada
                                     'content' => ($chunks > 0)?'': $arDropFile->getSrc(),
                                     'chunks' => array(),
                                     'parts' => $arDropFile->countChunks()  
                                    ));
            $this->returnJson();
        }
        else
        {
            $this->getResponse()->setStatusCode(401,'Unauthorized' );
            return sfView::HEADER_ONLY;  
        } 
    }
    else
    {
        $this->getResponse()->setStatusCode(401,'Unauthorized' );
        return sfView::HEADER_ONLY;   
    }
  }
  
  public function executeViewFilePreview(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        $style = trim($this->getRequestParameter('style'));
        
        if ($arDropFile && $arDropFile->isValidStyle($style)
             && ($arDropFile->isSelfUser($this->userid) 
                || $arDropFile->isShareForUser($this->userid)))
        {
            
            $arDropFilePreview = $arDropFile->getPreview($style);
        
            if ($arDropFilePreview && is_object($arDropFilePreview))
            {
                $chunksCount = $arDropFilePreview->countChunks();
                
                $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFilePreview->getId(),
                                     //si tiene mas partes el contenido es nada
                                     'content' => ($chunksCount > 0)?'': $arDropFilePreview->getSrc(),
                                     'chunks' => ($chunksCount <= 3)?$arDropFilePreview->getChunks(): array(),
                                       // 'chunks' => array(),
                                     'parts' => ($chunksCount <= 3)?0:$chunksCount
                                      //  'parts' => $chunksCount 
                                    ));
                
                $this->returnJson();
            }
            else
            {
                $this->getResponse()->setStatusCode(401,'Unauthorized' );
                return sfView::HEADER_ONLY;  
            } 
        }
        else
        {
                $this->getResponse()->setStatusCode(401,'Unauthorized' );
                return sfView::HEADER_ONLY;  
        } 
    }
    else
    {
        $this->getResponse()->setStatusCode(401,'Unauthorized' );
        return sfView::HEADER_ONLY;  
    } 
  }
  
  
  public function executeViewFileChunk(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        if ($arDropFile && 
                ($arDropFile->isSelfUser($this->userid) 
                || $arDropFile->isShareForUser($this->userid)))
        {
            $pos = $this->getRequestParameter('pos');
        
            $arDropFileChunk = $arDropFile->getChunk($pos);
        
            if ($arDropFileChunk && is_object($arDropFileChunk))
            {
                $this->data =   array( 'pos' =>  $arDropFileChunk->getPos(),
                                       'chunk' => $arDropFileChunk->getChunkData()  
                                  );        
        
                $this->returnJson();
            }
            else
            {
                $this->getResponse()->setStatusCode(500,'Internal Error' );
                return sfView::HEADER_ONLY;   
            } 
        }
        else
        {
           $this->getResponse()->setStatusCode(401,'Unauthorized' );
           return sfView::HEADER_ONLY;  
        }
    }
    else
    {
        $this->getResponse()->setStatusCode(401,'Unauthorized' );
        return sfView::HEADER_ONLY;  
    } 
    
    
  }
  
  public function executeViewFileChunkPreview(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        $pos = $this->getRequestParameter('pos');
        $style = trim($this->getRequestParameter('style'));
        
        if ($arDropFile && is_object($arDropFile) 
                && ($arDropFile->isValidStyle($style))
                && ($arDropFile->isSelfUser($this->userid) 
                    || $arDropFile->isShareForUser($this->userid)))
        {
           $arDropFileChunk = $arDropFile->getPreviewChuck($style, $pos);
           
           if ($arDropFileChunk && is_object($arDropFileChunk))
           {
        
              $this->data = array('pos' =>  $arDropFileChunk->getPos(),
                                   'chunk' => $arDropFileChunk->getChunkData()  
                                  ); 
           
              $this->returnJson();
           }
           else
           {
              $this->getResponse()->setStatusCode(500,'Internal Error' );
              return sfView::HEADER_ONLY; 
           }      
        }
        else
        {
           $this->getResponse()->setStatusCode(401,'Unauthorized' );
           return sfView::HEADER_ONLY;   
        }
    }
    else
    {
        $this->getResponse()->setStatusCode(401,'Unauthorized' );
        return sfView::HEADER_ONLY;  
    } 
  }
  
  
  public function executeSendChunkPreview(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFilePreview = $this->getRoute()->getObject();
        
        $this->form = new arDropFileChunkPreviewForm(array(), array(
                        'arDropFilePreview' => $arDropFilePreview,                              
                        'aUserProfile' => $this->aUserProfile));
   
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
             $arDropFileChunk = $this->form->save();
             
             
             
             $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFileChunk->getId()
                                    ));
        }
        else 
        {
           $this->data = array("status" => 500,
                                    "errors" => $this->form->getErrors(),
                                    "HTML" => "",
                                    "values" => array());   
        }     
    }
    
    $this->returnJson();
  }
  
  public function executeSendChunk(sfRequest $request)
  {
      
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        $this->form = new arDropFileChunkForm(array(), array(
                        'arDropFile' => $arDropFile,                              
                        'aUserProfile' => $this->aUserProfile));
   
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
             $arDropFileChunk = $this->form->save();
             
             
             
             $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFileChunk->getId()
                                    ));
        }
        else 
        {
           $this->data = array("status" => 500,
                                    "errors" => $this->form->getErrors(),
                                    "HTML" => "",
                                    "values" => array());   
        }     
        
        
    }
    
    $this->returnJson();
  }
  
  public function executeSendPreview(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
        $arDropFile = $this->getRoute()->getObject();
        
        $this->form = new arDropFilePreviewForm(array(), 
                        array('arDropFile' => $arDropFile,                              
                              'aUserProfile' => $this->aUserProfile));
   
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
            $arDropFilePreview = $this->form->save();
           
            $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'id' =>  $arDropFilePreview->getId()
                                    ));
        }
        else 
        {
           $this->data = array("status" => 500,
                                    "errors" => $this->form->getErrors(),
                                    "HTML" => "",
                                    "values" => array());  
        }  
        
    }
    else 
    {
       $this->data = array("status" => 500,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array());   
    }
    
    $this->returnJson();
      
  }
  
  public function executeSendFile(sfRequest $request)
  {
    
    if($this->checkView())
    {
        $this->loadUser();
        
        $this->form = new arDropFileForm(array(), array('aUserProfile' => $this->aUserProfile));
   
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
            $arDropFile = $this->form->save();
           
            $this->data = array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array(
                                     'url' => url_for('@drop_file_view?slug='.$arDropFile->getSlug()),
                                     'id' =>  $arDropFile->getId()
                                    ));
        
        }
        else 
        {
           $this->data = array("status" => 500,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array());   
        }        
    }
        
    $this->returnJson();
  }

  
}