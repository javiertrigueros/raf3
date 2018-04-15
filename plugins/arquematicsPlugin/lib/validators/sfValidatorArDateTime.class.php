<?php

/**
 * sfValidatorArDateTime validates a date and a time. It also converts the input value to a valid date.
 *
 * @package    arquematics
 * @subpackage validator
 * @author     Javier Trigueros Martínez de los Huertos javiertrigueros@gmail.com
 * @version    0.1
 */
class sfValidatorArDateTime extends sfValidatorDate
{
  /**
   * @see sfValidatorDate
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('with_time', true);
    
    $this->addOption('format', 'Y-m-d H:i');
  }
  
  protected function getTimeDate($value)
  {
        $valueArray = explode(" ", $value);
        
        $dateString = $valueArray[0];
        $dateArray = explode("/", $dateString);
        if (count($dateArray) <= 1)
        {
          $dateArray = explode("-", $dateString);  
        }
   
        $timeString = $valueArray[1];
        $timeArray = explode(":", $timeString); 
       
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        
  
       $format = $this->getOption('format');
    
       switch ($format) {
        case 'd/m/Y G:i':
            $date->setDate($dateArray[2], $dateArray[1], $dateArray[0]);
            $date->setTime($timeArray[0], $timeArray[1], 0);
            break;
        case 'd/m/Y H:i':
            $date->setDate($dateArray[2], $dateArray[1], $dateArray[0]);
            $date->setTime($timeArray[0], $timeArray[1], 0);
            break;
        case 'Y-m-d G:i':
            $date->setDate($dateArray[0], $dateArray[1], $dateArray[2]);
            $date->setTime($timeArray[0], $timeArray[1], 0);
             break;
        case 'Y-m-d H:i':
            $date->setDate($dateArray[0], $dateArray[1], $dateArray[2]);
            $date->setTime($timeArray[0], $timeArray[1], 0);
            break;
       }
       
       return $date;
      
  }
  
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    // check date format
    if (is_string($value) && $regex = $this->getOption('date_format'))
    {
      if (!preg_match($regex, $value, $match))
      {
        throw new sfValidatorError($this, 'bad_format', array('value' => $value, 'date_format' => $this->getOption('date_format_error') ? $this->getOption('date_format_error') : $this->getOption('date_format')));
      }

      $value = $match;
    }
    

    // convert array to date string
    if (is_array($value))
    {
      $value = $this->convertDateArrayToString($value);
    }

    // convert timestamp to date number format
    if (is_numeric($value))
    {
      $cleanTime = (integer) $value;
      $clean     = date($this->getOption('date_format'), $cleanTime);
    }
    // convert string to date number format
    else
    {
      try
      {
        $date = $this->getTimeDate($value);
        
        $clean = $date->format($this->getOption('format'));
        $cleanTime = $date->getTimestamp();
      }
      catch (Exception $e)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    if ($clean === $this->getEmptyValue())
    {
      return $cleanTime;
    }

    $format = $this->getOption('with_time') ? $this->getOption('datetime_output') : $this->getOption('date_output');

    return date($format, $cleanTime);
  }
  
}