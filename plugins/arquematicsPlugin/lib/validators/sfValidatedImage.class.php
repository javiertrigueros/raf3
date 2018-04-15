<?php

class sfValidatedImage extends sfValidatedResource
{
    
    public function __construct($originalName, $type, $tempName, $size, $path, $modelRelated)
    {
        parent::__construct($originalName, $type, $tempName, $size, $path);
        
        $this->modelRelated = $modelRelated;
   }
 
    /**
     * Saves the file to Amazon Simple Storage Service (S3)
     *
     * @param string $file filename or null
     * @param integer $fileMode (is ignored)
     * @param boolean $create should we create the directory if it dosent exists
     * @param integer $dirMode (is ignored)
     * @return string
     * @throws Exception
     */
    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
        $file = parent::save($file, $fileMode, $create, $dirMode);
       
        //guarda las versiones del archivo de imagen
        $this->isImageType = FileUtils::isImageContentType($this->getType());
        
        //no se puede salvar no es un archivo tipo imagen
        if (!$this->isImageType)
        {
            throw new Exception(sprintf('Can\'t save "%s" versions. Not a image file', $file)); 
        }
        else if (!EditorImageUpload::saveImageVersions(
                                        $this->modelRelated,
                                        $file,
                                        $this->path))
        {
            throw new Exception(sprintf('Can\'t save "%s" versions.', $file)); 
        }
        
        return $file;
    }
}