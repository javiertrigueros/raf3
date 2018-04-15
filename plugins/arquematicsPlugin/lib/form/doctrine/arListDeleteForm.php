<?php

class arListDeleteForm extends BasearProfileListForm
{
  public function setup()
  {
     
      $this->widgetSchema['id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['id'] = new sfValidatorDoctrineChoice(array('model' => 'arProfileList', 'column' => 'id', 'required' => true));
      
 
      $this->widgetSchema['profile_id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['profile_id']  = new sfValidatorDoctrineChoiceMultiplePlainString(array('multiple' => false, 'model' => 'sfGuardUserProfile', 'required' => true));
      
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
      
      $this->widgetSchema->setNameFormat('delete_list[%s]');
      
  }
  
  public function checkCallback($validator, $values)
  {
      $this->checkList($validator, $values);
      $this->checkProfile($validator, $values);
      
      return $values;
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
      
  }
  /**
   * mira si se ha intentado borrar el propietario de la lista
   * o un usuario que no esta el las lista de peticiones
   * de amistad
   * 
   * @param validator $validator
   * @param array $values
   * @throws sfValidatorError
   */
  public function checkProfile($validator, $values)
  {
   
    $userProfile = $this->getOption('aUserProfile');
      
    if ($userProfile->getId() == $values['profile_id'])
    {
      throw new sfValidatorError($validator, 'Error you delete ownerid to list');
    }
    else if (!Doctrine_Core::getTable('arProfileList')
                ->hasProfileId($values['id'], $values['profile_id']))
    {
      throw new sfValidatorError($validator, 'Error no profileId at list');  
    }
  }
  
  
}
