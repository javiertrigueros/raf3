<?php


class arDropComponents extends BaseArComponents
{

    public function executeShowDropControl(sfWebRequest $request)
    {
        $this->loadUser();
    
        $this->formFile = new arDropFileForm();
    
        $this->formFileChunk = new arDropFileChunkForm ();
    
        $this->formFilePreview = new arDropFilePreviewForm();
    
        $this->formFileChunkPreview = new arDropFileChunkPreviewForm ();
  
        // tamaños de imagenes
        $this->imageSizes = '[';
        $count = count(sfConfig::get('app_arquematics_plugin_image_wall_filters'));
    
        foreach (sfConfig::get('app_arquematics_plugin_image_wall_filters') as $imageSize)
        {
            $data = explode(':',$imageSize);
            $filterName = $data[0];
            list($width, $height, $algorith) = explode(',',$data[1]);
        
            $this->imageSizes .= "{name:'". $filterName ."', width:".$width.", height:". $height."}";
            $count--;
            if ($count > 0)
            {
                $this->imageSizes .=',';
            }
        }
    
        $this->imageSizes .= ']';
        
        //extensiones permitidas
        
        $countExtensions = count(sfConfig::get('app_arquematics_plugin_extensions_allowed'));
        
        $this->extensionsAllowed = '[';
        foreach (sfConfig::get('app_arquematics_plugin_extensions_allowed') as $extension)
        {
            $this->extensionsAllowed .= "'".$extension."'";  
            $countExtensions--;
            if ($countExtensions > 0)
            {
                $this->extensionsAllowed .=',';
            }
        }
        $this->extensionsAllowed .= ']';
        
    
        $this->arDropFiles = array();
    
        if (Doctrine::getTable('arDropFile')
           ->countUnassociated($this->aUserProfile->getId()) > 0)
        {
            $this->arDropFiles =  Doctrine::getTable('arDropFile')
                                ->getUnassociated($this->aUserProfile->getId());
        }

        $this->hasSessionFiles = ($this->arDropFiles && (count($this->arDropFiles) > 0));
    }
    
    public function executeShowList(sfWebRequest $request)
    {
        $this->listAllFiles = $this->message->DropFiles;
        
        $this->listViewImages = [];
        $this->listImages = [];
        $this->listFiles = [];
        //documentos de los editores
        $this->documents = [];
        
        $countImages = 0;
        
        $this->countMoreImages = 0;
        
        if (($this->listAllFiles) && (count($this->listAllFiles) > 0))
        {
            foreach ($this->listAllFiles as $file)
            {
                if (($countImages <= 3) && $file->isImageType())
                {
                    $this->listViewImages[] = $file; 
                    $countImages++;
                }
                else if ($file->isImageType())
                {
                    $this->listImages[] = $file;
                    $this->countMoreImages++;
                }
                else
                {
                    $this->listFiles[] = $file;
                }
                
            }
        }
        //imagenes vectoriales de los editores
        // o documentos
        if ((in_array('arLaverna', $this->enabledModules))
            || (in_array('arVectorialEditor', $this->enabledModules)))
        {
          $this->documents = $this->message->LavernaDocs; 
         
        }

    }
}