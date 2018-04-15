<?php

class FileUtils
{
     // lista de tipos mime
     static  $mime_types = array(
                "pdf"=>"application/pdf",
                "exe"=>"application/octet-stream",
                "zip"=>"application/zip",
                "docx"=>"application/msword",
                "doc95"=>"application/vnd.ms-office",
                "doc"=>"application/msword",
                "xls"=>"application/vnd.ms-excel",
                "ppt"=>"application/vnd.ms-powerpoint",
                "gif"=>"image/gif",
                "png"=>"image/png",
                "jpeg"=>"image/jpeg",
                "jpg"=>"image/jpg",
                "mp3"=>"audio/mpeg",
                "wav"=>"audio/x-wav",
                "mpeg"=>"video/mpeg",
                "mpg"=>"video/mpeg",
                "mpe"=>"video/mpeg",
                "mov"=>"video/quicktime",
                "avi"=>"video/x-msvideo",
                "3gp"=>"video/3gpp",
                "css"=>"text/css",
                "jsc"=>"application/javascript",
                "js"=>"application/javascript",
                "php"=>"text/html",
                "htm"=>"text/html",
                "html"=>"text/html",
                "txt" => "text/html"
        );
     
    //lista de tipos mime con imagenes
    static  $image_mime_types = array(
                "gif"=>"image/gif",
                "png"=>"image/png",
                "jpeg"=>"image/jpeg",
                "jpg"=>"image/jpg"
        );
    
    //lista de tipos mime con imagenes
    static  $types_mime_to_ext = array(
                'image/gif' => 'gif',
                'image/png' => 'png',
                'image/jpeg' => 'jpg',
                'image/jpg' =>  'jpg'
        );
    /**
     * genera un nombre de fichero unico 
     * con la funcion time, ojo mirar horas de los servidores
     * 
     * @return <string> 
     */
    public static function generateFilename()
    {
        return substr(time().rand(11111, 99999), 0, 20 );
    }
    /**
     * tipo mime a extension de archivo
     * 
     * @param <string $mime>
     * @return <string>
     */
    public static function mimeToExt($mime)
    {
        if (isset(FileUtils::$types_mime_to_ext[$mime]))
        {
            return FileUtils::$types_mime_to_ext[$mime];
        }
        else return '';
    }
    
    
    
    public static function saveLinkImage($file)
    {

        $pathDest = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'wall'.DIRECTORY_SEPARATOR.'original';
        
        return FileUtils::saveImage($pathDest, $file);
    }
    
    public static function saveProfileImage($file)
    {
        $pathDest = sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.'original';
        
        return FileUtils::saveImage($pathDest, $file);
    }
    
    /**
     * 
     * @param string $pathDest
     * @param string $file
     * @return string | false: 
     *                          si el archivo no se ha podido guardar el
     *                          archivo o el nombre del archivo si
     *                          ha guardado el archivo
     * 
     */
    public static function saveImage($pathDest, $file)
    {
        $ret = false;
        $tempPath = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'a_writable'.DIRECTORY_SEPARATOR.'tmp';
        
        $mimeType = FileUtils::mimeContentType(
                    $tempPath.DIRECTORY_SEPARATOR.$file);
         if (FileUtils::isImageContentType($mimeType))
         {
                $ext = FileUtils::mimeToExt($mimeType);
                $pathFileOrig = $tempPath.DIRECTORY_SEPARATOR.$file;
                $pathFileDest = $pathDest.DIRECTORY_SEPARATOR.$file.'.'.$ext;
              
                $ret = FileUtils::setPathPermissions($pathDest,0660);
                if ($ret && (@rename($pathFileOrig, $pathFileDest))
                    && (FileUtils::setFilePermissions($pathFileDest,0660)))
                {
                    $ret = $file.'.'.$ext;
                }
         }
         
         return  $ret;
    }
    /**
     * guarda un archivo de una url
     * 
     * @param type $url
     * @return string|boolean: false si no ha podido guardar el archivo
     */
    public static function saveFileCURL($url)
    {
        $ret = false;
        try
        {
            $originalPath =  sfConfig::get('app_aToolkit_writable_tmp_dir');
            
            if (FileUtils::setPathPermissions($originalPath))
            {
                $fileName = FileUtils::generateFilename();
                $pathFileName = $originalPath.DIRECTORY_SEPARATOR.$fileName;
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
                
                if (FileUtils::setFilePermissions($pathFileName))
                {
                  $ret = $fileName;  
                }
            }
            
        }
        catch (Exception $e)
        {
            
        }
        
        return $ret;
       
}
    
    /**
     * indica si es un tipo mime de imagen
     * 
     * @param type $typeMime
     * @return boolean: true si es un tipo mime de imagen
     */
    public static function isImageContentType($typeMime)
    {
        return in_array($typeMime,FileUtils::$image_mime_types);
    }
    /**
     * devuelve el tipo mime del objeto
     * 
     * @param pathAndFile $filename
     * @return string: tipo mime del objeto
     */
    public static function mimeContentType($filename)
    {
        $result = new finfo();

        if (is_object($result) === true)
        {
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }

        return false;
    }
    
    public static function recursiveRemove($path)
    {
        // Ensure trailing / so we can append
        if (!preg_match('/\/$/', $path))
        {
            $path .= '/';
        }
        $dir = opendir($path);
        if (!$dir)
        {
            return false;
        }
        while (($item = readdir($dir)) !== false)
        {
            if (($item === '.') || ($item === '..'))
            {
                continue;
            }
            $itemPath = $path . $item;
            if (is_dir($itemPath))
            {
               return FileUtils::recursiveRemove($itemPath);
            }
            else
            {
                if (substr($itemPath, 0, 2) !== 's3')
                {
                    return false;
                }
                if (!unlink($itemPath))
                {
                    return false;
                }
            }
        }
        closedir($dir);
        rmdir($path);
        return true;
    }
    
    
    /**
     * pone los permisos adecuados para manipular un directorio.
     * 
     * Si no existe crea el directorio, y le da los permisos
     * 
     * @param type $directory
     * @param type $fileMode
     * @param type $create
     * @param type $dirMode
     * 
     * @return boolean: true si hace bien la operacion
     */
    public static function setPathPermissions($directory = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
       $ret = @is_readable($directory);
       
       if (!$ret)
       {
            if (!($create && !@mkdir($directory, $dirMode, true)))
            {

               $ret =  @chmod($directory, $dirMode);
            }
        }
        
        return $ret;
    }
    
    /**
     *
     * @param type $file
     * @param type $fileMode
     * @param type $create
     * @param type $dirMode
     * 
     * @return boolean: false si no puede poner los permisos correctamente
     */
    public static function setFilePermissions($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
    $ret = false;
    // get our directory path from the destination filename
    $directory = dirname($file);

    if (!is_readable($directory))
    {
      if ($create && !@mkdir($directory, $dirMode, true))
      {
        // failed to create the directory
        //throw new Exception(sprintf('Failed to create file upload directory "%s".', $directory));
        return false;
      }
      // chmod the directory since it doesn't seem to work on recursive paths
      @chmod($directory, $dirMode);
    }

    if (!is_dir($directory))
    {
      // the directory path exists but it's not a directory
      //throw new Exception(sprintf('File upload path "%s" exists, but is not a directory.', $directory));
        return false;
    }

    if (!is_writable($directory))
    {
      // the directory isn't writable
      //throw new Exception(sprintf('File upload path "%s" is not writable.', $directory));
      return false;
    }

    // chmod our file
    return @chmod($file, $fileMode);
  }
}