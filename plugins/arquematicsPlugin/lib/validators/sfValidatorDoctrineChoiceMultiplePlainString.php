<?php

/**
 * sfValidatorDoctrineChoiceMultiplePlainString valida que los valores son unicos en la tabla. 
 * Los valores se pasan en una cadena separada por espacios. Util para valores numericos
 *
 * @package    arquematics
 * @subpackage doctrine
 * @author     Javier Trigueros Martinez javiertrigueros@gmail.com
 * @version    0.1
 */
class sfValidatorDoctrineChoiceMultiplePlainString extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * model:      The model class (required)
   *  * query:      A query to use when retrieving objects
   *  * column:     The column name (null by default which means we use the primary key)
   *                must be in field name format
   *  * min:        The minimum number of values that need to be selected 
   *  * max:        The maximum number of values that need to be selected 
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('query', null);
    $this->addOption('column', null);
    $this->addOption('multiple', false);
    $this->addOption('min');
    $this->addOption('max');

    $this->addMessage('min', 'At least %min% values must be selected (%count% values selected).');
    $this->addMessage('max', 'At most %max% values must be selected (%count% values selected).');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $query = $this->getOption('query');
    if ($query)
    {
      $query = clone $query;
    }
    else
    {
      $query = Doctrine_Core::getTable($this->getOption('model'))->createQuery();
    }

   
    $oldVal = trim($value);
   
    $value = explode(' ', $oldVal);
    
    $count = count($value);
    
    if (($count > 0) && $this->getOption('multiple'))
    {
     
      if ($this->hasOption('min') && $count < $this->getOption('min'))
      {
        throw new sfValidatorError($this, 'min', array('count' => $count, 'min' => $this->getOption('min')));
      }

      if ($this->hasOption('max') && $count > $this->getOption('max'))
      {
        throw new sfValidatorError($this, 'max', array('count' => $count, 'max' => $this->getOption('max')));
      }

      $query->andWhereIn(sprintf('%s.%s', $query->getRootAlias(), $this->getColumn()), $value);
    
      if ($query->count() != count($value))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }
    else
    {
      $query->andWhere(sprintf('%s.%s = ?', $query->getRootAlias(), $this->getColumn()), $oldVal);
      
      $value = $oldVal;
      
      if (!$query->count())
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $oldVal));
      }
      
    }

    return $value;
  }

  /**
   * Returns the column to use for comparison.
   *
   * The primary key is used by default.
   *
   * @return string The column name
   */
  protected function getColumn()
  {
    $table = Doctrine_Core::getTable($this->getOption('model'));
    if ($this->getOption('column'))
    {
      $columnName = $this->getOption('column');
    }
    else
    {
      $identifier = (array) $table->getIdentifier();
      $columnName = current($identifier);
    }

    return $table->getColumnName($columnName);
  }
}


