<?php


class arFormSignin extends BaseForm
{
    
 
  public function setup()
  {
     $this->widgetSchema['username'] = new sfWidgetFormInputText(array(),array('class' => 'form-control input login-input'));
     $this->validatorSchema['username']= new sfValidatorString(array('required' => true));
     
     $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('type' => 'password'), array('class' => 'form-control input login-input'));
     $this->validatorSchema['password']= new sfValidatorString(array('required' => true));
     
     $this->widgetSchema['remember'] = new sfWidgetFormInputCheckbox();
     $this->validatorSchema['remember']= new sfValidatorBoolean(array('required' => false));
    
     if (sfConfig::get('app_arquematics_encrypt'))
     {
         $this->widgetSchema['private_key']  = new sfWidgetFormTextarea(array(),array('class' => 'form-control input private-key-input'));
         $this->validatorSchema['private_key']= new sfValidatorString(array('required' => false));
         
         $this->widgetSchema['public_key']  = new sfWidgetFormInputHidden();
         $this->validatorSchema['public_key']= new sfValidatorString(array('required' => false));
         
         $this->widgetSchema['store_key']  = new sfWidgetFormInputHidden();
         $this->validatorSchema['store_key']= new sfValidatorString(array('min_length' => 25, 'required' => false));
         
         $this->validatorSchema->setPostValidator(new sfValidatorUserPublicKey());
     }
     else {
         $this->validatorSchema->setPostValidator(new sfGuardValidatorUser());
     }
     
     
     $this->widgetSchema->setNameFormat('signin[%s]');

  }
 
}
