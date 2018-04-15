<?php
/**
 *
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martinez de los Huertos, Arquematics
 * @version    0.1
 */
class arProfileDescriptionForm extends BasesfGuardUserProfileForm
{
  public function setup()
  {    
      
      parent::setup();
       
       unset(
      $this['email_address'],
      $this['username'],
      $this['user_id'],
      $this['first_last'],
      $this['password'],
      $this['address'],
      $this['key_saved'],
      $this['public_key'],
      $this['store_key'],
      $this['phone'],
      $this['facebook_uid'],
      $this['profile_data'],
      $this['created_at'],
      $this['updated_at'],
      $this['gmaps_locate_list']);
       
      sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
       
      $this->widgetSchema['description'] = new sfWidgetFormTextarea();
        
      $this->validatorSchema['description'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        
      $this->validatorSchema['description']
                ->setMessage('required', __('Required', array(), 'arquematics'));
   
      
      $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['id'] = new sfValidatorDoctrineUnique(array('required' => true,'model' => 'sfGuardUserProfile', 'column' => array('id')));
        
      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
         
      $this->widgetSchema->setNameFormat('profile[%s]');
        
  }
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['description']))
    {
        
        $this->setDefault('description',  $this->getObject()->getDescription());
    }
   
  }
  
  
  
    
}
