<?php
/**
 * arTimeWidget
 *
 *
 * @package    arquematics
 * @subpackage widget
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
class arTimeWidget extends sfWidgetFormInput
{

    public function getJavascripts()
    {
        return array('/arquematicsPlugin/js/arquematics/plugins/arquematics.timepicker.js');
    }
   
    public function getStylesheets ()
    {
        return array('/arquematicsPlugin/css/arquematics/plugins/arquematics.datetimepicker.css' => 'screen');
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        $this->addOption('format', 'H:i');
        $this->addOption('culture', 'en');
        
        parent::configure($options,$attributes);
    }
    
    
    public function render ($name, $value = null,$attributes = array(), $errors = array())
    {   
       $format = true;
       $empty = empty($value);
       if ($empty)
       {
            $format = false;
       }
       if (is_array($value))
       {
            if ((!strlen($value['hour'])) || (!strlen($value['minute'])))
            {
                $format = false;
            }
            else
            {
                $value = $value['hour'] . ':' . $value['minute'];
                if (isset($value['second']))
                {
                    $value .= ':' . $value['second'];
                }
            }
        }
        
        if ($format && is_numeric($value))
        {
            $value = date($this->getOption('format'), $value);
        }
        else if ($format)
        {
            $timeArray = explode(":", $value); 
           
            $timestamp = mktime($timeArray[0], $timeArray[1] , isset($timeArray[2])?$timeArray[2]:0, 0 , 0, 0 );
            $value = date($this->getOption('format'), $timestamp);
        }
        
        $nameId = $this->generateId($name);
        
        $this->setAttribute('data-now', $value);
        $this->setAttribute('data-format', 'HH:mm'); 
       
        

      return parent::render($name, $value,$attributes, $errors);
    }
    
    
}
