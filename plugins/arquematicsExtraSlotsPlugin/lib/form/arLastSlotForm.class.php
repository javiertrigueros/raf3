<?php    
class arLastSlotForm extends BaseForm
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
    $this->widgetSchema['title_head'] = new sfWidgetFormInput(array(), array('size' => 20));
    $this->validatorSchema['title_head']    = new sfValidatorString(array('required' => false));
    
    $this->widgetSchema['count'] = new sfWidgetFormInput(array(), array('size' => 2));
    $this->validatorSchema['count'] = new sfValidatorNumber(array('min' => $min = sfConfig::get('app_aBlog_slot_minimum_posts', 1), 'max' => $max = sfConfig::get('app_aBlog_slot_maximum_posts', 20)));
    $this->widgetSchema->setHelp('count', a_('Set the number of posts to display (between %min% and %max%).',array('%min%' => $min,'%max%' => $max)));
    if(!$this->hasDefault('count'))
		{
      $this->setDefault('count', 3);
    }

           
   		        
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  
  /**
   * Support method for aBlogToolkit::addBlogItemsWidget
   */
  public function getBlogItemIds($model, $name)
  {
    return $this->getDefault('blog_posts');
  }
}
