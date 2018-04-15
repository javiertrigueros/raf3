<?php


/**
 * sfValidatorChoiceGroupMultiple 
 *
 * @package    arquematics
 * @subpackage validator
 * @author     javier Trigueros Martínez de los Huertos javiertrigueros@gmail.com 
 * @version    0.1
 */
class sfValidatorChoiceGroupMultiple extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * choices:  An array of expected values (required)
   *  * max:      The maximum number of values that need to be selected (this option is only active if multiple is true)
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('choices');
    $this->addOption('max');

    $this->addMessage('max', 'At most %max% values must be selected (%count% values selected).');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $choices = $this->getChoices();
    
    return $this->cleanMultiple($value, $choices);
   
  }

  public function getChoices()
  {
    $choices = $this->getOption('choices');
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }

    return $choices;
  }

  /**
   * Cleans a value 
   *
   * @param  mixed $value The submitted value
   *
   * @return array The cleaned value
   */
  protected function cleanMultiple($value, $choices)
  {
    if (!is_array($value))
    {
      $value = array($value);
    }
    
    foreach ($value as $v)
    {
      if (!self::inChoices($v, $choices))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $v));
      }
      
    }

    $count = count($value);

    if ($this->hasOption('max') && $count > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('count' => $count, 'max' => $this->getOption('max')));
    }

    return $value;
  }

  /**
   * Checks if a value is part of given choices (see bug #4212)
   *
   * @param  mixed $value   The value to check
   * @param  array $choices The array of available choices
   *
   * @return Boolean
   */
  static protected function inChoices($value, array $choices = array())
  {
      try {
          if ($value)
          {
             $value = json_decode($value);
             
             if ($value && is_array($value) && isset($value[0]) && isset($value[1]) && isset($value[2]))
             {
                 
                 foreach ($choices as $choice)
                 {
                    if ((string) $choice == (string) $value[0])
                    {
                        $ret = ($value[1] == 0) || ($value[1] == 1) &&
                        ($value[2] == 0) || ($value[2] == 1);
                       
                        return ($ret)? $value[0]:false;
                    }
                 }
             }
             
          }
          
      }
      catch (Expection $e){}
    
    return false;
  }
}
