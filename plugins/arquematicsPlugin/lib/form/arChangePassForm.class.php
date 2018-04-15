<?php
/**
 *
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martinez de los Huertos, Arquematics
 * @version    0.1
 */
class arProfilePassForm extends BaseForm
{
     
  public function setup()
  { 
     $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
     $this->validatorSchema['password'] = new sfValidatorString(array('min_length' => sfConfig::get('app_arquematics_pass_chars', 8), 'max_length' => 32, 'required' => true));
      
     $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
     $this->validatorSchema['password_again'] = new sfValidatorString(array('min_length' => sfConfig::get('app_arquematics_pass_chars', 8), 'max_length' => 32, 'required' => true));
    
      $this->validatorSchema->setPostValidator(
           new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again', array(), array('invalid' => 'The two passwords must be the same.'))
      );
     
     $this->widgetSchema->setNameFormat('profile[%s]');
  }
  
 
}
