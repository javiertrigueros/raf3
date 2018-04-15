<?php

class arSearchFriend extends BaseForm
{
  public function setup()
  {
     
      $this->widgetSchema['search'] = new sfWidgetFormInput(array(), array('class' => 'span4','autocomplete'=>'off'));
      $this->validatorSchema['search']= new sfValidatorString(array('required' => false));
      
      $this->widgetSchema['page'] = new sfWidgetFormInputHidden(array('default' => '0'));
      $this->validatorSchema['page'] = new sfValidatorInteger(array('required' => true));
      
      $this->widgetSchema['is_subscriber']  = new sfWidgetFormInputHidden(array('default' => true));
      $this->validatorSchema['is_subscriber']  = new sfValidatorBoolean(array('required' => true));
       
      $this->widgetSchema->setNameFormat('search[%s]');
  }
  
}
