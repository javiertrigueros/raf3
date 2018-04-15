<?php
/**
 * 
 * Valida la existencia real de un dominio y
 * devuelve un objeto sfValidatedFile si tiene sentido
 *
 * Arquematics 2011
 *
 * @author Javier Trigueros Martínez de los Huertos
 */
class sfValidatorDomainExtra extends sfValidatorDomain 
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

      parent::configure();
      //save class item
      $this->addOption('validated_file_class', false);
      //path donde se guarda la imagen  original
      //no temporal
      $originalPath = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'a_writable'.DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR.'original';
      $this->addOption('path', $originalPath);
      
      $this->addOption('related_model', false);
   
      $this->addMessage('related_model', 'Related model required or not valid');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value) {
     
      $value = parent::doClean($value);
      
      if (!$this->hasOption('related_model'))
      {
        throw new sfValidatorError($this, 'related_model');
      }

      $class = $this->getOption('validated_file_class');
      
      if ($class)
      {
          return new $class($this->getOption('related_model'), $value, $this->getOption('path'));
      }
              
      return $value;
      
    
  }
}
?>