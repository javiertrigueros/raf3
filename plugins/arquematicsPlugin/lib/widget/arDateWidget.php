<?php
/**
 * arDateTimeWidget
 *
 *
 * @package    arquematics
 * @subpackage widget
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
class arDateWidget extends sfWidgetFormInput
{

    public function getJavascripts()
    {
        return array(
            '/arquematicsPlugin/js/components/moment/moment.js',
            '/arquematicsPlugin/js/components/moment/lang/'.$this->getOption('culture').'.js',
            '/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-datepicker.js');
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
        
       
        $this->setAttribute('data-format', aDate::getMomentDateFormat($culture)); 
        
        parent::configure($options,$attributes);
    }
    
    
    public function render ($name, $value = null,$attributes = array(), $errors = array())
    {
      $dateValue = aDate::widgetDate($value,$this->getAttribute('data-format'));

      $this->setAttribute('data-now', $dateValue); 
      
      $nameId = $this->generateId($name);

      
      return parent::render($name, $dateValue,$attributes, $errors);
    }
    
    
}
