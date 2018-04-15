<?php

class sfValidatedResource extends sfValidatedFile
{
   protected $isImageType      = false;
    /**
   * Constructor.
   *
   * @param string $originalName  The original file name
   * @param string $type          The file content type
   * @param string $tempName      The absolute temporary path to the file
   * @param int    $size          The file size (in bytes)
   * @param string $path          The path to save the original file.
   */
  public function __construct($originalName, $type, $tempName, $size, $path)
  {
    $this->originalName = $originalName;
    $this->tempName = $tempName;
    $this->type = $type;
    $this->size = $size;
    $this->path = $path;
  }
  
  public function getIsImageType()
  {
      return $this->isImageType;
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

        if (is_null($file)) {
            $file = $this->generateFilename();
        }

        
        $pathTemp = rtrim(sfConfig::get('app_aToolkit_writable_tmp_dir'), '/') . DIRECTORY_SEPARATOR;
        
        if (!preg_match('/(\/)(([a-zA-Z0-9.]+)(\/+)*)/', $pathTemp)) {
            
            throw new Exception("File path is not a valid path. $pathTemp");
        }
        
        
        
        
        if (!@is_writeable($pathTemp) && $create && !@mkdir($pathTemp,$dirMode)) {
            throw new Exception(sprintf('File upload path "%s" is not writable.', $pathTemp));
        }
        
        //solamente el nombre del fichero de verdad
        $fileArray = explode(DIRECTORY_SEPARATOR,$file);
        if ($fileArray && is_array($fileArray))
        {
          $file =   $fileArray[count($fileArray) -1];
        }
            
        file_put_contents($pathTemp . $file, file_get_contents($this->getTempName()));
        
        $this->savedFile = $pathTemp . $file;
        
        //es más seguro que saber el tipo de fichero con las extensiones
        $this->type = FileUtils::mimeContentType($this->savedFile);
       
        
        if (!FileUtils::setFilePermissions($this->savedFile, $fileMode))
        {
           throw new Exception(sprintf('Can\'t update "%s" permissions.', $this->savedFile)); 
        }
        
        $this->path = rtrim($this->path, '/') . DIRECTORY_SEPARATOR;
        
        if (!preg_match('/(\/)(([a-zA-Z0-9.]+)(\/+)*)/', $this->path)) {
            
            throw new Exception("File path is not a valid path. $this->path");
        }
        
        if (!@is_writeable($this->path) && $create && !@mkdir($this->path, $dirMode)) {
            throw new Exception(sprintf('File upload path "%s" is not writable.', $this->path));
        }
        

        $desS3FilePath = $this->path.'original'.DIRECTORY_SEPARATOR.$file;
       
        if (!@copy($this->savedFile, $desS3FilePath) && (!FileUtils::setFilePermissions($desS3FilePath,$fileMode)))
        {
           throw new Exception(sprintf('Amazon S3 error file saving original "%s" .', $desS3FilePath)); 
        }
        
        return $file;
    }
}