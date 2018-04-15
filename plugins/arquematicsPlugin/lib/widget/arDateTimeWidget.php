<?php
/**
 * arDateWidget
 *
 *
 * @package    arquematics
 * @subpackage widget
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
class arDateTimeWidget extends sfWidgetFormInput
{

    public function getJavascripts()
    {
        return array(
            '/arquematicsPlugin/js/components/moment/moment.js',
            '/arquematicsPlugin/js/components/moment/lang/'.$this->getOption('culture').'.js',
            '/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-datetimepicker.js');
                                   
    }
   
    public function getStylesheets ()
    {
         return array('/arquematicsPlugin/css/arquematics/plugins/arquematics.datetimepicker.css' => 'screen');
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        
        $this->addOption('culture', 'en');
        
        $culture = ($options 
                && is_array($options) 
                && isset($options['culture']))?$options['culture']:$this->getOption('culture') ;
        
       
        $this->setAttribute('data-format', aDate::getMomentDateTimeFormat($culture)); 
        
        parent::configure($options,$attributes);
    }
    
    
    public function render ($name, $value = null,$attributes = array(), $errors = array())
    {
      $dateValue = aDate::widgetDate($value,$this->getAttribute('data-format'));

      $this->setAttribute('data-now', $dateValue); 
      
      
      $nameId = $this->generateId($name);
/*
      $javascript = sprintf('<script type="text/javascript">
        $(document).ready(function()
        {
            $("#%s").datetimepicker({
                autoclose: true,
                language: "%s",
                initialDate: "%s",
                pickerPosition: "bottom-left"
                });
        });
      </script>',$nameId, $this->getOption('culture'), $dateValue);
      */

      return parent::render($name, $dateValue,$attributes, $errors);
    }
    
    
}
