<?php

class sfValidatorImageString extends sfValidatorString
{
 
  /**
   * Configuracion
   *
   *
   * @param <array $options>    array de optiones
   * @param <array $messages>     array de messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(),
                                $messages = array()) {
      // The type of method to use to validate it.
      $this->addOption('path', isset($options['path'])?$options['path']:sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arDiagram');
      
      $this->addOption('dirMode', isset($options['dirMode'])?$options['dirMode']:0777);
      
      $this->addOption('create', isset($options['create'])?$options['create']:true);
      
      // Setup some basic error messages
      $msg = 'Error sfValidatorImageString.';
    
      $this->addMessage('filesystem', isset($messages['filesystem'])?$messages['filesystem']:$msg);
      $this->addMessage('invalid', isset($messages['invalid'])?$messages['invalid']:$msg);
  }
  
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value) 
  {
      $data = parent::doClean($value);

      return  $this->save($data);
  }
  
    /**
     * crea imagenes con las versiones a partir de los datos y lo guarda en 
     * Amazon s3 
     *
     * @param <string $data> cadena con imagen en formato svg
     * @return <string> file name saved
     * @throws Exception
     */
    public function save($data = null)
    {

        $dirMode = $this->getOption('dirMode');
        $create = $this->getOption('create');
        
        $pathTemp = rtrim(sfConfig::get('app_aToolkit_writable_tmp_dir'), '/') . DIRECTORY_SEPARATOR;
        
        if (!preg_match('/(\/)(([a-zA-Z0-9.]+)(\/+)*)/', $pathTemp)) 
        {
            $val = "File path is not a valid path. $pathTemp";
            $error = new sfValidatorError($this, 'filesystem',array('value' => $this->getMessage('filesystem').$val));
            throw new sfValidatorErrorSchema($this, array( $error));
        }
        
        if (!@is_writeable($pathTemp) && $create && !@mkdir($pathTemp,$dirMode))
        {
            $val =  sprintf('File upload path "%s" is not writable.', $pathTemp);
            $error = new sfValidatorError($this, 'filesystem',array('value' => $this->getMessage('filesystem').$val));
            throw new sfValidatorErrorSchema($this, array( $error));
        }
        
        
        $file = EditorImageUpload::savePNG(
                  $data,
                  $pathTemp);
        
        //guarda las versiones del archivo en su directorio
        $this->path = rtrim($this->getOption('path'), '/') . DIRECTORY_SEPARATOR;
        
        
        if (!preg_match('/(\/)(([a-zA-Z0-9.]+)(\/+)*)/', $this->path)) 
        {
            $val =  "File path is not a valid path. $this->path";
            $error = new sfValidatorError($this, 'filesystem',array('value' => $this->getMessage('filesystem').$val));
            throw new sfValidatorErrorSchema($this, array( $error));
        }
        
        if (!@is_writeable($this->path) && $create && !@mkdir($this->path, $dirMode)) 
        {
            $val = sprintf('File upload path "%s" is not writable.', $this->path);
            $error = new sfValidatorError($this, 'filesystem',array('value' => $this->getMessage('filesystem').$val));
            throw new sfValidatorErrorSchema($this, array( $error));
        }
        
        if ((!$file) || (!EditorImageUpload::saveImageDiagram($file, $this->path)))
        {
            //throw new Exception('Amazon S3 EditorImageUpload::saveSVG saveImageDiagram');
            
            $error = new sfValidatorError($this, 'filesystem',array('value' => $this->getMessage('filesystem')));
            throw new sfValidatorErrorSchema($this, array( $error));
        }
       
        return $file;
    }
    
   
    
}