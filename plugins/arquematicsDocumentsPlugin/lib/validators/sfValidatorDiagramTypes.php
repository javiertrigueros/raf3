<?php
/**
 *
 * Valida los tipos de editores posibles
 *
 * Arquematics 2012
 *
 * @author Javier Trigueros Martínez de los Huertos
 */
class sfValidatorDiagramTypes extends sfValidatorBase
{
     
     // Lista de opciones validas
     // por defecto estan habilidadas todas las
     // opciones validas posibles
     static protected $all = array(
         'simple.image',
         '/bpmn1.1/bpmn1.1.json',
         '/epc/epc.json',
         '/uml2.2/uml2.2.json',
        );

  public function configure($options = array(), $messages = array())
  {
     
     $this->addOption('diagram_names', sfValidatorDiagramTypes::$all);
    
  }

  /**
   *
   * Validacion del objeto puede lanzar sfValidatorError
   *
   * @param <string> $value
   *
   * @throw sfValidatorError
   * 
   * @return <boolean>
   */
  protected function doClean($value)
  {

    if ($value && in_array($value,$this->getOption('diagram_names')))
    {
      return $value;
    }
    else{
       throw new sfValidatorError($this, 'invalid');
    }
   
    return $value;
  }


  /**
   * esta funcion es muy importante para hacer que el widget a medida
   * se valide siempre de lo contrario no se validará
   *
   * @param <type> $value
   * @return <type>
   */
  public function isEmpty($value)
  {
        return false;
  }
}


?>