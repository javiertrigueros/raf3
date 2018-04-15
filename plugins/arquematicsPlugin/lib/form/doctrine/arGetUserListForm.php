<?php

class arGetUserListForm extends BasearProfileListForm
{
  public function setup()
  {
     
      $this->widgetSchema['id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['id'] = new sfValidatorDoctrineChoice(array('model' => 'arProfileList', 'column' => 'id', 'required' => true));
      
     
      $this->widgetSchema['page']  = new sfWidgetFormInputHidden(array('default' => '0'));
      $this->validatorSchema['page']  = new sfValidatorInteger(array('required' => true));
      
       $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkList')))
      );
      
      $this->widgetSchema->setNameFormat('get_list[%s]');
      
  }
  
   /**
   * mira si es propietario de la lista
   * 
   * @param array $validator
   * @param array $values
   * @throws sfValidatorError
   */
  public function checkList($validator, $values)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      if (!Doctrine_Core::getTable('arProfileList')
              ->isAdminProfile(
                      $values['id'],
                      $userProfile->getId()))
      {
          throw new sfValidatorError($validator, 'Not list owner');
      }
      
      return $values;
  }
  
  
}
