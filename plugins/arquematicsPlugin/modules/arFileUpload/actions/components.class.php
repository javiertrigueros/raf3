<?php


class arFileUploadComponents extends sfComponents
{
  public function preExecute()
  {
   sfProjectConfiguration::getActive()->loadHelpers(array('I18N','Partial'));

   parent::preExecute();
  }
  
  public function executeShowFileList(sfWebRequest $request)
  {
      $this->files = $this->message->getMessageUploads();
      
      $this->hasFiles = (count($this->files) > 0);
      
      $this->imageFiles = array();
      
      $this->d3Files = array();
     
      $this->resourceFiles = array();
      
      if ($this->hasFiles)
      {
          foreach($this->files as $file)
          {
              if ($file->isImageFile())
              {
                 $this->imageFiles[] = $file;
              }
              else if ($file->is3dFile())
              {
                 $this->d3Files[] = $file; 
              }
              else
              {
                 $this->resourceFiles[] = $file; 
              }
          }

      }
  }
  
  public function executeShowFileControl(sfWebRequest $request)
  {
    $this->form = new arWallUploadForm();
    
    $this->aUser = $this->getUser();
    $this->authUser = $this->aUser->getGuardUser();
    $this->userid = $this->authUser->getId();
    
    //ficheros subidos     
    $this->uploads = $this->getUser()->getAttribute('arWallUpload',array(),'wall');
    $this->hasContent = (count($this->uploads) > 0);
    
    $activeTool = $this->getUser()->getAttribute('activeTool', false, 'wall');
    $this->showTool = ($activeTool == 'arWallUpload');
  
    if ($this->hasContent)
    {
         $uploads = Doctrine_Core::getTable('arWallUpload')->getByIds($this->uploads);
         $this->uploads = array();
         $this->uploadsGuiIds = array();
            
         foreach ($uploads as $upload) 
         {
              $this->uploads[] = array("name" => $upload->getName(),
                    "id" => $upload->getId(),
                    "is_image" => $upload->getIsImage(),
                    "thumbnail_url" => url_for("@user_resource?type=arWallUpload&name=".$upload->getBaseName()."&format=".$upload->getExtension()."&size=mini"),
                    "url" => url_for("@user_resource?type=arWallUpload&name=".$upload->getBaseName()."&format=".$upload->getExtension()."&size=original"),
                    "size" => $upload->getSize(),
                    "gui_id" => $upload->getGuiId(),
                    "delete_url" => url_for("@wall_file_delete?id=".$upload->getId()),
                    "type" => $upload->getMimeContentType());
              
              $this->uploadsGuiIds[] = $upload->getGuiId();

         }
         
    }
  }
  
 
}