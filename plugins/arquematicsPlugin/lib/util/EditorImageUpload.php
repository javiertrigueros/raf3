<?php
/**
 * Arquematics 2010
 *
 * @author Javier Trigueros Martínez de los Huertos <javiertrigueros@arquematics.com>
 * @version 0.1
 * 
 * EditorImageUpload 
 *
 */
class EditorImageUpload
{
    public static $modelsRelated = array(
        'arProfileUpload' => 'app_arquematics_plugin_image_profile_filters',
        'arDiagram' => 'app_arquematics_plugin_image_wall_filters',
        'arGmapsLocate' => 'app_arquematics_plugin_image_maps_filters',
        'arWallLink' => 'app_arquematics_plugin_image_link_filters',
        'arWallUpload' => 'app_arquematics_plugin_image_wall_filters'
    );
    
    /*
    public static function saveImageDiagram( $file, $path)
    {
        $filters = sfConfig::get('app_arquematics_plugin_image_wall_filters');
        
        $originalPath = sfConfig::get('app_aToolkit_writable_tmp_dir');
        
       
        return EditorImageUpload::saveImages($originalPath, $path, $file, $filters);
    }*/
    
   
    /**
     * guarda varias versiones de un archivo de imagen
     * 
     * @param string $path: directorio base donde guardar las imagenes
     * @param string $file: nombre del archivo
     * @return boolean: true si ha guardado las versiones del archivo
     *                  con exito
     */
    public static function saveImageDiagram( $file, $path)
    {
        $filters = sfConfig::get('app_arquematics_plugin_image_wall_filters');
        
        $originalPath = sfConfig::get('app_aToolkit_writable_tmp_dir');
        
       
        return EditorImageUpload::saveImages($originalPath, $path, $file, $filters);
    }
    
    /**
     * guarda una imagen en varios tamaños
     * 
     * @param <string $modelRelated>
     * @param <string $file>: pathAndFile del archivo del que queremos varias versiones
     * @param <string $toPath>: path base donde guardar los archivos
     * @return boolean
     */ 
    public static function saveImageVersions($modelRelated, $file, $toPath)
    {
  
        if (isset(EditorImageUpload::$modelsRelated[$modelRelated]))
        {
            
            $filters = sfConfig::get(EditorImageUpload::$modelsRelated[$modelRelated]);
        
           
            $sourcePath = sfConfig::get('app_aToolkit_writable_tmp_dir');
        
            return EditorImageUpload::saveImages($sourcePath, $toPath, $file, $filters);
            
        }
        else
        {
            return false;
        }
    }
    
    public static function saveImages($originalPath, $basePath, $file, $filters)
    {
        
        $ret = false;
        

        
        if ($filters && is_array($filters))
        {
            
            $basePath = (substr($basePath, -1) == DIRECTORY_SEPARATOR)?
                            $basePath: $basePath.DIRECTORY_SEPARATOR;
           
            foreach ($filters as $filter)
            {
                $data = explode(':',$filter);
                $filterName = $data[0];
                list($width, $height, $algorith) = explode(',',$data[1]);
                $width = trim($width);
                $height = trim($height);
                $algorith = trim($algorith);
                
                $path = $basePath.$filterName;
                
                $ret = EditorImageUpload::saveImage($originalPath, $path, $file,$width,$height, $algorith);
                if (!$ret)
                {
                   break; 
                }
                
            }
        }
        
        return $ret;
    }
    
    /**
     * posible algoritmos 
     * 
     * cropOriginal
     * scaleToFit
     * scaleToNarrowerAxis
     */
    public static function saveImage($originalPath, $path, $file, $width, $height, $algorith ='scaleToFit',$pathDest = false)
    {
        FileUtils::setPathPermissions($path);
        aImageConverter::$algorith(
                   $originalPath.DIRECTORY_SEPARATOR.$file,
                   $path.DIRECTORY_SEPARATOR.$file,
                   $width,
                   $height);
        
        //copia el fichero en su sitio en s3
        if (sfConfig::get('app_s3_enabled'))
        {
           $basePath = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'a_writable';
           $desPath = str_replace($basePath,'',$path);
           $desPath = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.$desPath;
           FileUtils::setPathPermissions($desPath);
           return @copy($path.DIRECTORY_SEPARATOR.$file,$desPath.DIRECTORY_SEPARATOR.$file); 
        }
        
        return FileUtils::setFilePermissions($path.DIRECTORY_SEPARATOR.$file);
    }
    
    public static function saveSVG($dataString, $uploadDirectory)
    {
        $file = FileUtils::generateFilename();
        $pathFile = $uploadDirectory.DIRECTORY_SEPARATOR.$file.".svg";

	$ret = false;
        if (file_put_contents($pathFile, $dataString))
        {
           $ret = FileUtils::setFilePermissions($pathFile);
           
           $outFile = $uploadDirectory.DIRECTORY_SEPARATOR.$file.".png";
	
           $shell = "inkscape --export-png=$outFile --export-background-opacity=0 --without-gui $pathFile";

           exec($shell, $stdout, $retval);

           $ret = FileUtils::setFilePermissions($outFile);
        }
        
        return ($ret)?$file.".png":false;
    }
   
    /**
     * guarga una imagen, creando a partir de la cadena de texto
     * que se le pasa
     * 
     * @param $uploadDirectory
     * @param $data
     * 
     * @return string|boolean:  false si no pudo guardar el archivo 
     *                          de lo contrario, devuelve el nombre del
     *                          archivo
     */
    public static function savePNG($dataString, $uploadDirectory)
    {
        
        try
        {
          //echo $dataString;
          $dataString = preg_replace('/data\:image\/png;base64\,/i','',$dataString);
          //echo $dataString;
          
          $img  = imagecreatefromstring(base64_decode($dataString));
          
          if ($img !== false) 
          {
            $file = FileUtils::generateFilename().".png";
            $pathFile = $uploadDirectory.DIRECTORY_SEPARATOR.$file;
            
            ob_start();
            imagepng($img,$pathFile);
            imagedestroy($img);
            ob_clean();
            
            $ret = FileUtils::setFilePermissions($pathFile);
            
            return ($ret)?$file:false;
          }
          else
          {
              return false;
          }

          
        } catch (Exception $e) 
        { 
            return false;
        }
        
        
    }
}
