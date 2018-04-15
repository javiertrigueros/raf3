<?php

class arListAddForm extends BasearProfileListForm
{
  protected $arFriend = false;
  
  public function setup()
  {
     
      $this->widgetSchema['id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['id'] = new sfValidatorDoctrineChoice(array('model' => 'arProfileList', 'column' => 'id', 'required' => true));
      
      $this->widgetSchema['profile_id']  = new sfWidgetFormInputHidden();
      //$this->validatorSchema['profile_id']  = new sfValidatorDoctrineChoiceMultiplePlainString(array('multiple' => false, 'model' => 'sfGuardUserProfile', 'required' => true));
      $this->validatorSchema['profile_id']  = new sfValidatorDoctrineChoice(array('model' => 'sfGuardUserProfile', 'column' => 'id', 'required' => true));
     
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
      
      $this->widgetSchema->setNameFormat('list_add[%s]');
      
      
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
   * mira si se ha intentado añadir el propietario de la lista
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
      throw new sfValidatorError($validator, 'Error you add ownerid to list');
    }
    else if (Doctrine_Core::getTable('arProfileList')
                ->hasProfileId($values['id'], $values['profile_id']))
    {
      throw new sfValidatorError($validator, 'Error list already has profileid');
    }
    else 
    {
      $this->arFriend = Doctrine::getTable('arFriend')
                    ->getStatus($userProfile->getId(), $values['profile_id']);  
      
      if ($this->arFriend && (!$this->arFriend->getIsAccept()
                        && $this->arFriend->getIsIgnore()
                        && ($this->arFriend->getRequestId() != $userProfile->getId())))
      {
        throw new sfValidatorError($validator, 'Error user is ignore');  
      }
    }
  }
  
  protected function doSave($conn = NULL)
  {
     $userProfile = $this->getOption('aUserProfile');
     
     $listId = $this->values['id'];
     $friendId = $this->values['profile_id'];
             
     $userProfile->addFriendRequest($friendId, $listId, $conn);
     
     //si es el propio usuario el que estaba ignorando
     //ahora acepta la invitacion
  
     if ($this->arFriend && (!$this->arFriend->getIsAccept()
                        && $this->arFriend->getIsIgnore()
                        && ($this->arFriend->getRequestId() == $userProfile->getId())))
     {
         $this->arFriend->setIsAccept(true);
         $this->arFriend->setIsIgnore(false);
         $this->arFriend->save($conn);
     }
  }
}
