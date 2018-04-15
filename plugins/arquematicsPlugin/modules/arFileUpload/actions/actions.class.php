<?php


class arFileUploadActions extends BaseArActions
{
  public function executeDeleteSessionFile(sfWebRequest $request)
  {
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
       try 
       {
             $arWallUpload = $this->getRoute()->getObject();
             
             if (is_object($arWallUpload) 
              && ($this->checkView())){
                 
                $uploads = $this->getUser()->getAttribute('arWallUpload',array(),'wall');
      
                $itemId = $arWallUpload->getId();
          
                //borra el elemento del array de archivos
                if (in_array($itemId, $uploads))
                {
                    $arWallUpload->delete();
              
                    $indexVal = array_search($itemId, $uploads);
                    unset($uploads[$indexVal]);
             
                    $this->getUser()->setAttribute('arWallUpload', $uploads, 'wall');
             
                    $this->data =  array(
                        "status" => 200,
                        "errors" => array(),
                        "HTML" => '',
                        "values" => array());
                }
            }
       }
       catch (Exception $e)
       {
            $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'Exception error',
                    "values" => array());
       }

     
      
      //devuelve el contenido en Json de $this->data
      $this->returnJson();
  }
  
  public function executeDeleteFile(sfWebRequest $request)
  {
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
      
      $arWallUpload = $this->getRoute()->getObject();
      
      $uploads = $this->getUser()->getAttribute('arWallUpload',array(),'wall');
      
      
      if (is_object($arWallUpload) 
              && ($this->checkView()))
      {
          $itemId = $arWallUpload->getId();
          
          //borra el elemento del array de archivos
          if (in_array($itemId, $uploads))
          {
             $arWallUpload->delete();
              
             $indexVal = array_search($itemId, $uploads);
             unset($uploads[$indexVal]);
             
             $this->getUser()->setAttribute('arWallUpload', $uploads, 'wall');
             
             $this->data =  array(
                    "status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
          }
      }
      
      //devuelve el contenido en Json de $this->data
      $this->returnJson();
  }
  
  public function executeGetSessionFiles(sfWebRequest $request)
  {
    $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
    
    if ($this->checkView())
    {
        //$this->loadUser();
        
        $sessionUploads = $this->getUser()->getAttribute('arWallUpload',array(),'wall');
        
        $hasUploads = (count($sessionUploads) > 0);
        
        $uploadFiles = array();
        if ($hasUploads)
        {
           $uploads = Doctrine_Core::getTable('arWallUpload')->getByIds($sessionUploads);
           foreach ($uploads as $upload) 
           {
               $uploadFiles[] = array("name" => $upload->getName(),
                                  "id" => $upload->getId(),
                                  "is_image" => $upload->getIsImage(),
                                  "thumbnail_url" => url_for("@user_resource?id=".$upload->getId()."&type=arWallUpload&size=mini"),
                                  "url" => url_for("@user_resource?id=".$upload->getId()."&type=arWallUpload&size=original"),
                                  "size" => $upload->getSize(),
                                  "delete_url" => url_for("@wall_file_delete?id=".$upload->getId()),
                                  "type" => $upload->getMimeContentType(),
                                  "gui_id" => $upload->getGuiId(),
                                  "date" => $upload->getCreatedAt());
           }
        }
                
        $this->data =  array("status" => 200,
                             "errors" => array(),
                             "HTML" => array(),
                             "values" => $uploadFiles);
        
    }
    
    //devuelve el contenido en Json de $this->data
    $this->returnJson();
    
  }
  
  private function cancelHandler()
  {
     
      $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
      
     //devuelve el contenido en Json de $this->data
     $this->returnJson();
  }
  
  public function executeSendFile(sfWebRequest $request)
  {
    $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => 'no go',
                    "values" => array());
    
    
    if ($this->checkView())
    {
        $this->loadUser();
        
        $this->form = new arWallUploadForm();
        $formValues = $request->getParameter($this->form->getName());
        
        $files = $request->getFiles();
        //$formValues['file_name'] = $files['files'][0];
        $fileArray = array('file_name' => $files['files'][0]);
        
        $this->form->bind($formValues, $fileArray);
        
         
        if ($this->can_edit && $this->form->isValid())
        {
           // try
           // {
                $file = $this->form->getValue('file_name');
                $fileName = $file->save();
                $isImage = $file->getIsImageType();
                $size = $file->getSize();
                
                $arWallUpload = new arWallUpload();
                $arWallUpload->setName($file->getOriginalName());
                $arWallUpload->setFileName($fileName);
                $arWallUpload->setIsImage($isImage);
                $arWallUpload->setSize($size);
                
                $arWallUpload->setUserId($this->userid);
                $arWallUpload->setGuiId($this->form->getValue('gui_id'));
                $arWallUpload->setMimeContentType($file->getType());
                $arWallUpload->save();
                
                 // guarda los datos en la session
                $uploads = $this->getUser()->getAttribute('arWallUpload',array(),'wall');
                $uploads[] = $arWallUpload->getId();
                $this->getUser()->setAttribute('arWallUpload', $uploads, 'wall');
                
                $this->getUser()->setAttribute('activeTool', 'arWallUpload', 'wall');
                
                $this->data =  array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => array(),
                                    "values" => array("file" => 
                                                array("name" => $file->getOriginalName(),
                                                    'id' => $arWallUpload->getId(),
                                                    "is_image" => $isImage,
                                                    "thumbnail_url" => url_for("@user_resource?type=arWallUpload&name=".$arWallUpload->getBaseName()."&format=".$arWallUpload->getExtension()."&size=mini"),
                                                    "url" => url_for("@user_resource?type=arWallUpload&name=".$arWallUpload->getBaseName()."&format=".$arWallUpload->getExtension()."&size=original"),
                                                    "size" => $size,
                                                    "delete_url" => url_for("@wall_file_delete?id=".$arWallUpload->getId()),
                                                    "gui_id" => $arWallUpload->getGuiId(),
                                                    "type" => $file->getType())));
                
           // }
                /*
            catch (Exception $e)
            {
                $this->data['errors'] =  $e->getTraceAsString();
                $this->data['HTML'] = 'excep';
                $this->data['values'] = $fileArray;
            }*/
            
            
        }
        else 
        {
            $this->data['errors'] = $this->form->getErrors();
            $this->data['HTML'] = 'go ja';
            $this->data['values'] = $formValues;
        }
        
    }
    
     //devuelve el contenido en Json de $this->data
     $this->returnJson();
    
  }
  
  public function executeSendFileStatus(sfWebRequest $request)
  {
     if($request->getMethod() == sfWebRequest::HEAD) 
     {
       sfConfig::set('sf_cache', false);
       $this->getResponse()->setHttpHeader('Pragma: no-cache');
       $this->getResponse()->setHttpHeader('Cache-Control: no-store, no-cache, must-revalidate');
       $this->getResponse()->setHttpHeader('Content-Disposition: inline; filename="files.json"');
       $this->getResponse()->setHttpHeader('X-Content-Type-Options: nosniff');
       
       
       $this->options = array(
            'script_url' => '/',
            'upload_dir' => '/files/',
            'upload_url' => '/files/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition',
                'Content-Description'
            ),
            // Enable to provide file downloads via GET requests to the PHP script:
            'download_via_php' => false,
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to true to rotate images based on EXIF meta data, if available:
            'orient_image' => false,
            'image_versions' => array(
                // Uncomment the following version to restrict the size of
                // uploaded images:
                /*
                '' => array(
                    'max_width' => 1920,
                    'max_height' => 1200,
                    'jpeg_quality' => 95
                ),
                */
                // Uncomment the following to create medium sized images:
                /*
                'medium' => array(
                    'max_width' => 800,
                    'max_height' => 600,
                    'jpeg_quality' => 80
                ),
                */
     
                'thumbnail' => array(
                    'max_width' => 80,
                    'max_height' => 80
                )
            )
        );
       
       
       
       $this->getResponse()->setHttpHeader('Vary: Accept');
       $this->returnJson();
            
     }
    
    
  }
  
  
  
    
}