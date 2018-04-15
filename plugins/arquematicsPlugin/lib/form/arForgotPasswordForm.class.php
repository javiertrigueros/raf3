<?php

class arForgotPasswordForm extends BaseForm
{
    
  public function setup()
  {
    sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
    
    $this->widgetSchema['email_address'] = new sfWidgetFormInputText();
    $this->validatorSchema['email_address'] = new sfGuardValidatorUsernameOrEmail(
      array('trim' => true),
      array('required' => __('Your username or e-mail address is required.', null, 'arquematics'), 
            'invalid' => __('Username or email address not found please try again.', null, 'arquematics'))
    );

    $this->widgetSchema->setNameFormat('forgot_password[%s]');
  }
  
}