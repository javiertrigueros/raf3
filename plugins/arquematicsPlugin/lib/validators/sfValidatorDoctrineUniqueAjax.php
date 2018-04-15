<?php

/**
 * sfValidatorDoctrineUniqueAjax validates that the uniqueness of a column.
 *
 *
 * @package    arquematics
 * @subpackage validators
 * @author     Javier Trigueros Martínez de lso Huertos <javiertrigueros@arquematics.com>
 * @version    0.1 sfValidatorDoctrineUniqueAjax.class.php  
 */
class sfValidatorDoctrineUniqueAjax extends sfValidatorSchema
{
  /**
   * Constructor.
   *
   * @param array  An array of options
   * @param array  An array of error messages
   *
   * @see sfValidatorSchema
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * model:              The model class (required)
   *  * column:             The unique column name in Doctrine field name format (required)
   *                        If the uniquess is for several columns, you can pass an array of field names
   * 
   *  * connection:         The Doctrine connection to use (null by default)
   *  * throw_global_error: Whether to throw a global error (false by default) or an error tied to the first field related to the column option array
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addRequiredOption('column');
    $this->addOption('required', false);
    $this->addOption('form_field_name', '');
    $this->addOption('connection', null);
    $this->addOption('throw_global_error', false);

    //$this->setMessage('invalid', 'An object with the same "%value%" already exist.');
  }

  /**
   * @param <array of values>
   * 
   */
  protected function doClean($values)
  {
    $originalValues = $values;
    $colName = $this->getOption('column');
    $form_field_name = $this->getOption('form_field_name');
    
    $schemaName = $colName;
    
    $table = Doctrine_Core::getTable($this->getOption('model'));
    
    if (is_array($values) && (array_key_exists($colName, $values) 
            || array_key_exists($form_field_name, $values)))
    {
        if (array_key_exists($colName, $values))
        {
          $value = $values[$colName]; 
          $schemaName = $colName;
        }
        else
        {
          $value = $values[$form_field_name];
          $schemaName = $form_field_name;
        }
                    
        $q = Doctrine_Core::getTable($this->getOption('model'))->createQuery('a');
        $colName = $this->getOption('column');
        $q->addWhere('a.' . $colName . ' = ?', $value);
        
        //echo $q->getSqlQuery();
        $object = $q->fetchOne();
        
        if (!$object)
        {
            return $originalValues;
        }
        
        $error = new sfValidatorError($this, 'invalid', array('value' => $value));
      
    }
    else if ($values)
    {
        
        $value = $values;
        $q = Doctrine_Core::getTable($this->getOption('model'))->createQuery('a');
        $colName = $this->getOption('column');
        $q->addWhere('a.' . $colName . ' = ?', $value);
        
        //echo $q->getSqlQuery();
        $object = $q->fetchOne();
        
        if (!$object)
        {
            return $originalValues;
        }
        
        $error = new sfValidatorError($this, 'invalid', array('value' => $value));
        
    }
    else if (!$this->getOption('required'))
    {
        return $originalValues;
    }
    else 
    {
        $error = new sfValidatorError($this, 'required');
    }
    
    if ($this->getOption('throw_global_error'))
    {
      throw $error;
    }
    
    throw new sfValidatorErrorSchema($this, array( $error));
  }

}