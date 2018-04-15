<?php
/**
 * sfWidgetGroupChoice 
 *
 *
 * @package    arquematics
 * @subpackage widget
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
class sfWidgetGroupChoice extends sfWidgetFormChoice
{
  

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = $value === null ? 'null' : $value;
 
    $options = array();
    foreach ($this->getOption('choices') as $key => $option)
    {
      $attributes = array('value' => self::escapeOnce($key));
      /*
      if ($key == $value)
      {
        $attributes['selected'] = 'selected';
      }*/
    
      $option = ucfirst($option);
      
      $attributes['value'] = '['. $attributes['value'].',0,0]';
      $options[] = $this->renderContentTag(
        'option',
        self::escapeOnce($option),
        $attributes
      );
    }
    
    return $this->renderContentTag(
      'select',
      "\n".implode("\n", $options)."\n",
      array_merge(array('name' => $name.'[]'), array()
    ));
    //return parent::render($name, $data['formated_address'],$attributes, $errors).$javascript;
  }

  
}