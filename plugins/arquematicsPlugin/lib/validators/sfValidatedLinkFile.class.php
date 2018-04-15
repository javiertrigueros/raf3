<?php

class sfValidatedLinkFile extends sfValidatedResource
{
  protected
    $url = '',
    $path         = null;
   /**
   * Constructor.
   *
   * @param <string url>    url del recurso       
   * @param <string $path>  Path donde guardar el archivo 
   */
  public function __construct($modelRelated, $url,  $path )
  {
    $this->url = $url;
    $this->path = $path;
    $this->modelRelated = $modelRelated;
  }
  
  /**
   * Genera un nombre aleatorio de archivo
   *
   * @return <string> 
   */
    public  function generateFilename()
    {
        return substr(time().rand(11111, 99999), 0, 20 );
    }
    
    public function getTempName()
    {
       return sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR.$this->fileName;
    }
    
  
  
    /**
     * crea imagenes con las versiones y lo guarda en 
     * Amazon s3 si esta activo
     *
     * @param <string $file> filename or null
     * @param <integer $fileMode> (is ignored)
     * @param <boolean $create >should we create the directory if it dosent exists
     * @param <integer $dirMode> (is ignored)
     * @return <string> file name saved
     * @throws Exception
     */
    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
        
            $file = $this->generateFilename();
            $this->fileName = $file;
            try
            {
              $this->saveUrl($this->url, 
                      sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR,
                      $file);
              
              $this->type = FileUtils::mimeContentType(sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR.$file);
              
              $this->isImageType = FileUtils::isImageContentType($this->getType());
              
            }
            catch (Exception $e)
            {
               throw new RuntimeException('Error saving URL resource.');
            }
            
            $ext = FileUtils::mimeToExt($this->getType());
            
            $retFileName = $file.'.'.$ext;
            
            $this->savedFilePath = sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR.$retFileName;
            if (!@rename($this->getTempName(), $this->savedFilePath))
            {
                throw new Exception(sprintf('File "%s" copy path "%s" error.',$file,sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR));
            }
            
            $this->path = (substr($this->path, -1) == DIRECTORY_SEPARATOR)?
                            $this->path: $this->path.DIRECTORY_SEPARATOR;
            
            $desS3FilePath = $this->path.'original'.DIRECTORY_SEPARATOR.$retFileName;
       
            if (!@copy($this->savedFilePath, $desS3FilePath))
            {
                throw new Exception(sprintf('Amazon S3 error file saving original "%s" .', $desS3FilePath)); 
            }
            
            // chmod our file
            if (!@chmod($desS3FilePath, $fileMode) )
            {
                throw new Exception(sprintf('chmod file "%s" error.', $file));
            }
            
            if ($this->isImageType && (!EditorImageUpload::saveImageVersions(
                                    $this->modelRelated,
                                    $retFileName, 
                                    $this->path)))
            {
                throw new Exception(sprintf('Error saving URL resource. %s', $file)); 
            }
            
            return $retFileName;
          
        /*

        $pathTempFile = sfConfig::get('app_aToolkit_writable_tmp_dir').DIRECTORY_SEPARATOR.$file.$this->getExtension();

        $directory = $this->path;

        if (!is_readable($directory))
        {
            if ($create && !@mkdir($directory, $dirMode, true))
            {
                // failed to create the directory
                throw new Exception(sprintf('Failed to create file upload directory "%s".', $directory));
            }

            // chmod the directory since it doesn't seem to work on recursive paths
            chmod($directory, $dirMode);
        }

        if (!is_dir($directory))
        {
            // the directory path exists but it's not a directory
            throw new Exception(sprintf('File upload path "%s" exists, but is not a directory.', $directory));
        }

        if (!is_writable($directory))
        {
            // the directory isn't writable
            throw new Exception(sprintf('File upload path "%s" is not writable.', $directory));
        }
         // copy the temp file to the destination file
        if (!@copy($this->getTempName(), $pathTempFile))
        {
            throw new Exception(sprintf('File "%s" copy path "%s" error.',$file, $directory));
        }
       
        // chmod our file
        if (!@chmod($pathTempFile, $fileMode) )
        {
            throw new Exception(sprintf('chmod file "%s" error.', $file));
        }
        

        $retFileName = $file.$this->getExtension();
        
        $filters = sfConfig::get('app_arquematics_plugin_image_link_filters');

        $originalPath = sfConfig::get('app_aToolkit_writable_tmp_dir');
        
        if (!EditorImageUpload::saveImages($originalPath, $this->path, $retFileName, $filters))
        {
             throw new Exception(sprintf('Error creating resized files.', $retFileName));
        }
        */
        
    }
    /**
     * guarda un recuso de una url en un directorio.
     * 
     * @param <URL $url>
     * @param <String $path>
     * @param <String $fileName>
     */
    private function saveUrl($url, $path, $fileName)
    {
         $pathFileName = $path.$fileName;  
         $ch = curl_init($url);
         $fp = fopen($pathFileName, "wb");

         // set URL en fp
         $options = array(CURLOPT_FILE => $fp,
                                 CURLOPT_HEADER => 0,
                                 CURLOPT_FOLLOWLOCATION => 1,
                                 CURLOPT_TIMEOUT => 60); // 1 minute timeout 

          curl_setopt_array($ch, $options);

          curl_exec($ch);
          curl_close($ch);
          fclose($fp);
    }
}