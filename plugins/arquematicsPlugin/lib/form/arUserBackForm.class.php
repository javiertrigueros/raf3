<?php

class arUserBackForm extends BaseForm
{
  public function setup()
  {
      
     $this->widgetSchema['username'] = new sfWidgetFormInputHidden();
     $this->validatorSchema['username']= new sfValidatorString(array('required' => true));

     
     $this->widgetSchema->setNameFormat('user_key[%s]');
  }
  
}
