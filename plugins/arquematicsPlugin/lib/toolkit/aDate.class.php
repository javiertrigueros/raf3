<?php
/**
 * @package    apostrophePlugin
 * @subpackage    toolkit
 * @author     P'unk Avenue <apostrophe@punkave.com>
 *
 * See BaseaDate for the exciting stuff. Override aDate in your project level lib/ to
 * override methods of BaseaDate
 */

class aDate extends BaseaDate
{
    
    static public $mommentJStoPHP = array(
                                    'DD/MM/YYYY' => 'd/m/Y',
                                    'YYYY-MM-DD' => 'Y-m-d',
                                    'DD/MM/YYYY H:mm' => 'd/m/Y G:i',
                                    'DD/MM/YYYY HH:mm' => 'd/m/Y H:i',
                                    'YYYY-MM-DD H:mm' => 'Y-m-d G:i',
                                    'YYYY-MM-DD HH:mm' => 'Y-m-d H:i');
     
     /**
   * formato de fecha y hora PHP
   * 
   * @param <string $culture>
   * @return <string>
   */
  static public function getDateTimeFormatPHP($culture)
  {
                                                             
      $dateTimeFormats = sfConfig::get('app_arquematics_normalDateTimeFormat');
      
      if ($dateTimeFormats && is_array($dateTimeFormats) && isset($dateTimeFormats[$culture]))
      {
         
         return $dateTimeFormats[$culture];
      }
      else 
      {
         return 'Y-m-d H:i'; 
      }
  }
  /**
   * formato de fecha
   * 
   * @param <string $culture>
   * @return <string>
   */
  static public function getDateFormatPHP($culture)
  {
                                                             
      $dateTimeFormats = sfConfig::get('app_arquematics_normalDateFormat');
      
      if ($dateTimeFormats && is_array($dateTimeFormats) && isset($dateTimeFormats[$culture]))
      {
         
         return $dateTimeFormats[$culture];
      }
      else 
      {
         return 'Y-m-d'; 
      }
  }
  
  static public function getMomentDateFormat($culture)
  {
       $dateTimeFormats = sfConfig::get('app_arquematics_momentNormalDateFormat');
       
       if ($dateTimeFormats && is_array($dateTimeFormats) && isset($dateTimeFormats[$culture]))
       {
            return $dateTimeFormats[$culture]; 
       }
       else {
           return 'YYYY-MM-DD';
       }
  }
  
  static public function getMomentDateTimeFormat($culture)
  {
       $dateTimeFormats = sfConfig::get('app_arquematics_momentNormalDateTimeFormat');
       
       if ($dateTimeFormats && is_array($dateTimeFormats) && isset($dateTimeFormats[$culture]))
       {
            return $dateTimeFormats[$culture]; 
       }
       else {
           return 'YYYY-MM-DD H:mm';
       }
  }
   
  
  static public function getMomentLongDateFormat($culture)
  {
      $dateTimeFormats = sfConfig::get('app_arquematics_momentLongDateFormat');
      
      if ($dateTimeFormats && is_array($dateTimeFormats) && isset($dateTimeFormats[$culture]))
      {
         return $dateTimeFormats[$culture];
      }
      else 
      {
         return 'MMMM D YYYY'; 
      }
  }
  
  static public function dataToHuman($year, $month = false, $day = false)
  {
    if ($year && $month && $day)
    {
      return aDate::dayMonthYear($year.'-'. $month.'-'. $day);
    }
    else if ($year && $month )
    {
       $date = $year.'-'. $month.'-01';
       return aDate::monthYear($date);
    }
    elseif ($year)
    {
       $date = $year.'-01-01';
       return aDate::year($date);
    }
  }
  
  
  static public function widgetDate($when, $dateFormat)
  {
    $when = aDate::normalize($when);
  
    return date( self::$mommentJStoPHP[$dateFormat], $when);
  }
  
  static public function monthYear($date)
  {
    $date = aDate::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Long date format is the closest I18N format to our proprietary version
      return format_date($date, 'Y');
    }
    return date('M, Y', $date);
  }
  
  static public function year($date)
  {
    $date = aDate::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Long date format is the closest I18N format to our proprietary version
      return format_date($date, 'yyyy');
    }
    return date('Y', $date);
  }
  
}

