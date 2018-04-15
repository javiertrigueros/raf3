<?php

/*
 * Arquematics 2012
 *
 * @author Javier Trigueros Martínez de los Huertos <javiertrigueros@arquematics.com>
 * @version 0.1
 * 
 * gestiona los envios de la webcam
 *
 */
class WallCameraUpload
{
    /**
     * genera un nombre de fichero unico 
     * con la funcion time, ojo mirar horas de los servidores
     * 
     * @return <string> 
     */
    public static function generateFilename()
    {
        return substr(time().rand(11111, 99999), 0, 20 ).'.jpg';
    }
    /**
     * crea un fichero jpg a partir de los datos enviados
     * por la webcam
     * 
     * @param <string $type>: pixel | data
     * @param <string $imageData> datos enviados
     * @param <string $pathFile> path y nombre del archivo
     * @return <string|boolean> : nombre del archivo | false si no guarda bien los datos 
     */
    public static function make($type, $imageData, $path)
    {
        $invalid = "iVBORw0KGgoAAAANSUhEUgAAAUAAAADwCAYAAABxLb1rAAAG+UlEQVR4Xu3UgREAIAgDMdl/aPFc48MGTbnOfXccAQIEggJjAIOti0yAwBcwgB6BAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToDAAoCVvV4Lh4uLAAAAAElFTkSuQmCC";

        $ret = false;
        
        if ($type == "pixel") 
        {
            $filter_image = str_replace("data:image/png;base64,", "", $imageData);
            // input is in format 1,2,3...|1,2,3...|...
            if($filter_image != $invalid)
            {
              try {
                    ob_start(); /*don't send the output to the browser since we'll need to manipulate it*/  
                    $im = imagecreatetruecolor(320, 240);
                    foreach (explode("|", $imageData) as $y => $csv) {
                            foreach (explode(";", $csv) as $x => $color) {
				imagesetpixel($im, $x, $y, $color);
                            }
                    }
                
                    $file = WallCameraUpload::generateFilename();
                    $pathFile = $path.DIRECTORY_SEPARATOR.$file;
                    
                    Imagejpeg($im, $pathFile);
      
                    ob_clean();
                    
                    imagedestroy($im);
                
                    $ret = WallCameraUpload::setFilePermissions($pathFile);
                    
                  } catch (Exception $e) {$ret = false;}
                  
            }
        } else {
            // input is in format: data:image/png;base64,...
            $filter_image = str_replace("data:image/png;base64,", "", $imageData);
            if($filter_image != $invalid)
            {
                try {
                    ob_start();
                    $im = imagecreatefrompng($imageData);
                    $file = WallCameraUpload::generateFilename();
                    $pathFile = $path.DIRECTORY_SEPARATOR.$file;
                    
                    Imagejpeg($im, $pathFile);
                    
                    imagedestroy($im);
                    ob_clean();

                    $ret = WallCameraUpload::setFilePermissions($pathFile);
                } catch (Exception $e) {$ret = false;}
                
            }
		
        }
       
       return ($ret)?$file:false;
    }
    /**
     *
     * @param type $file
     * @param type $fileMode
     * @param type $create
     * @param type $dirMode
     * @return type 
     */
    private static function setFilePermissions($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
    $ret = false;
    // get our directory path from the destination filename
    $directory = dirname($file);

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

    // chmod our file
    return chmod($file, $fileMode);
  }
    
}