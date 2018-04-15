<?php

/**
 * aMedia actions.
 *
 * @package    arquematicsPlugin
 * @subpackage aMedia
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */

class arMediaActions extends BaseArActions
{
  //mirar https://github.com/mondalaci/dxf2svg2kicad sobre todo
  //  https://github.com/duckinator/dxf2ponoko
  // https://github.com/alexmuro/dwgtoweb
  // https://github.com/SketchUp/sketchup-stl
    
  //  https://github.com/fuzziness/kabeja
    
    // pasar a colada file y luego los carga
   // https://github.com/mrdoob/three.js/wiki/Using-SketchUp-Models
  
    
   // https://github.com/OnyxGroup/EverBoard
    
   // https://github.com/bartaz/impress.js me encantaria meter lo de impress para presentaciones
   /**
   * muestra una imagen del perfil
   *
   * @param sfRequest $request A request object
   */
  public function executeShowProfile(sfWebRequest $request)
  {
      $this->image = $this->getRoute()->getObject();
      $logUser = $this->getUser();
      $this->is_auth_user = (is_object($logUser) &&  $logUser->isAuthenticated());
      
      //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->image && is_object($this->image)) );
      
      $size = $request->getParameter('size');

      $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR;
      $this->pathAndFile = $path.DIRECTORY_SEPARATOR.$this->image->getFileName();
       
      if (!file_exists($this->pathAndFile))
      {
        // Make an "original" in the other format (conversion but no scaling)
        //aImageConverter::convertFormat($item->getOriginalPath(),
        //    $item->getOriginalPath($format));
      }
      
      $this->setLayout(false);
      $this->setTemplate('show');
       
      header("Content-length: " . filesize($this->pathAndFile));
      header("Content-type: " . $this->image->getMimeContentType());
      
      //cierro rapido sin más
      //exit(0);
  }
  
  public function executeShowLink(sfWebRequest $request)
  {
      $this->image = $this->getRoute()->getObject();
      $logUser = $this->getUser();
      $this->is_auth_user = (is_object($logUser) &&  $logUser->isAuthenticated());
      
       //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->image && is_object($this->image)) );
      
      $size = $request->getParameter('size');
      
      $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR;
      
      $this->pathAndFile = $path.DIRECTORY_SEPARATOR.$this->image->getFileName();
      $this->setLayout(false);
      $this->setTemplate('show');
       
      header("Content-length: " . filesize($this->pathAndFile));
      header("Content-type: " . $this->image->getMimeContentType());
      
  }
  
  public function executeShowDiagram(sfWebRequest $request)
  {
      $this->image = $this->getRoute()->getObject();
      $logUser = $this->getUser();
      $this->is_auth_user = (is_object($logUser) &&  $logUser->isAuthenticated());
      
       //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->image && is_object($this->image)) );
      
      $size = $request->getParameter('size');
      
      $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arDiagram'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR;
      
      $this->pathAndFile = $path.DIRECTORY_SEPARATOR.$this->image->getFileName();
      $this->setLayout(false);
      $this->setTemplate('show');
       
      header("Content-length: " . filesize($this->pathAndFile));
      header("Content-type: image/png");
        
  }
  
  
  
  
  public function executeShowWallImage(sfWebRequest $request)
  {
      $this->image = $this->getRoute()->getObject();
      $logUser = $this->getUser();
      $this->is_auth_user = (is_object($logUser) &&  $logUser->isAuthenticated());
      
       //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->image && is_object($this->image)) );
      
      $size = $request->getParameter('size');
      
      $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'wall'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR;
      
      $this->pathAndFile = $path.DIRECTORY_SEPARATOR.$this->image->getFileName();
      $this->setLayout(false);
      $this->setTemplate('show');
       
      header("Content-length: " . filesize($this->pathAndFile));
      header("Content-type: " . $this->image->getMimeContentType());
      
  }
  /**
   * muestra o descarga un recurso si se tienen los permisos necesarios
   * 
   * @param sfWebRequest $request
   */
  public function executeShowMediaFile(sfWebRequest $request)
  {
      try{
          $nameType = $request->getParameter('type');
          $size = $request->getParameter('size');
          $baseName = $request->getParameter('name');
          $format = $request->getParameter('format');
          
         
          /*
           * mini
           * small
           * big
           * original
           */
         
          $mediaObj = FactoryMediaClient::byName($nameType, $baseName.'.'.$format);
           
          $this->pathAndFile = $mediaObj->getPathAndFile($size);
          
          $this->setTemplate('show');
          
          if (!is_file($this->pathAndFile))
          {
             $this->error = new Exception("No file found. $this->pathAndFile");
          
          }
          else 
          {
            $this->setLayout(false);
       
            header("Content-length: " . filesize($this->pathAndFile));
            header("Content-type: " . $mediaObj->getMimeContentType());
            
            
            if ($size === 'original')
            {
             header('Content-Disposition: attachment; filename="'.$mediaObj->getName().'"');   
            }
            
          }
      }
      catch (Exception $e)
      {
          $this->error = $e;
      }
  }
  
  public function executeShow3dObj(sfWebRequest $request)
  {
      
      try{
          $baseName = $request->getParameter('name');
          $format = $request->getParameter('format');

          $this->mediaObj = FactoryMediaClient::byName('arWallUpload', $baseName.'.'.$format);
          
          //$this->setLayout(false);
          
          
          $this->aPage = aPageTable::retrieveBySlug(url_for('@wall'));
          
          $this->setLayout(sfContext::getInstance()->getConfiguration()->getTemplateDir('arWall', 'layoutWall.php') . '/layoutWall');
          
      }
      catch (Exception $e)
      {
          $this->error = $e;
      }
      
  }
  
   public function executeShowFile(sfWebRequest $request)
  {
      $this->image = $this->getRoute()->getObject();
      $logUser = $this->getUser();
      $this->is_auth_user = (is_object($logUser) &&  $logUser->isAuthenticated());
      
       //si no tiene permisos para ver la pagina o no existe el usuario
      // error 404
      $this->forward404Unless(($this->image && is_object($this->image)) );
      
      $size = $request->getParameter('size');
      
      $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'wall'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR;
      
      $this->pathAndFile = $path.DIRECTORY_SEPARATOR.$this->image->getFileName();
      $this->setLayout(false);
      $this->setTemplate('show');
       
      header("Content-length: " . filesize($this->pathAndFile));
      header("Content-type: " . $this->image->getMimeContentType());
      
  }
  
  
  
 
}
