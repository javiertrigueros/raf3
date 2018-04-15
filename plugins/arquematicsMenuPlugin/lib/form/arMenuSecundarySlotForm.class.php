<?php    
class arMenuSecundarySlotForm extends BaseForm
{
  protected $id;
  public function __construct($id, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
    parent::__construct($defaults, $options, $CSRFSecret);
   
  }
  
  public function configure()
  {
     sfProjectConfiguration::getActive()->loadHelpers(array('I18N','a'));
    // ADD YOUR FIELDS HERE
    $this->widgetSchema['title'] = new sfWidgetFormInput(array(), array('size' => 20));
    $this->validatorSchema['title']    = new sfValidatorString(array('required' => true));
    	        
    $this->widgetSchema['showTitle'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['showTitle']    = new sfValidatorBoolean(array('required' => false));
    
    //$this->setDefault('showTitle', false);
    
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  
  
}
