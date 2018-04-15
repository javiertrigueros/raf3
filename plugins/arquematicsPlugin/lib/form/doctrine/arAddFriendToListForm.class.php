<?php

/**
 * acepta o rechaza los usuarios
 */
class arAddFriendToListForm extends BaseFormDoctrine
{
 
 public function getModelName()
 {
    return 'arFriend';
 }
    
  public function setup()
  {
      unset(
            $this['created_at'], 
            $this['updated_at']
      );
      
      $this->widgetSchema['profile_id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['profile_id']  = new sfValidatorDoctrineChoice(array('multiple' => false, 'model' => 'sfGuardUserProfile', 'required' => true));
      
      
      $userProfile = $this->getOption('aUserProfile');
        
      $options = array();
        
      if (isset($userProfile) && is_object($userProfile))
      {
            $options =  $userProfile->getAdminListChoices();
      }
        
      $this->widgetSchema['profile_list_id'] = new sfWidgetFormChoice(array(
                    'choices' => $options
            ),array('style' => 'display:none','multiple'=> 'multiple'));
        
       
      $this->validatorSchema['profile_list_id'] = new sfValidatorDoctrineChoiceMultiplePlainString(
                            array(
                                    'model' => 'sfGuardUserProfile',
                                    'required' => false,
                                    'max' => sfConfig::get('app_arquematics_plugin_max_list_items', 6)
                            ),
                            array()); 
      
      $this->widgetSchema['is_accept']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['is_accept']  = new sfValidatorBoolean(array('required' => true));
      
      
       
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'callBack')))
      );
     
      $this->widgetSchema->setNameFormat('accept[%s]');
  }
  
  protected function doSave($conn = null)
  {
     $userProfile = $this->getOption('aUserProfile');
     
     $profileList = $this->values['profile_list_id'];
     $friendId = $this->values['profile_id'];
     $isAccept = $this->values['is_accept'];
     
     $userProfile->confirmRequest($friendId, $profileList, $isAccept, $conn);
     
     return $this->arFriendStatus;
  }
  
  public function callBack($validator, $values)
  {
     $values = $this->checkList($validator, $values);
     
     return $this->checkProfile($validator, $values);
  }
  
  public function checkList($validator, $values)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      if ($values && isset($values['profile_list_id']))
      {
          $values['profile_list_id'] = explode(' ',$values['profile_list_id']);
          $userListIds =  $userProfile->getListIds();

          if ($values['profile_list_id'] && (count($values['profile_list_id']) > 0)
              && (count($userListIds) > 0))
          {
              foreach ($values['profile_list_id'] as $listId)
              {
  
                  if (!(in_array($listId, $userListIds)))
                  {

                    throw new sfValidatorError($validator, 'Mode arAddFriendToListForm::checkList not in array');  
                  }
              }
          }
          else
          {
             throw new sfValidatorError($validator, 'Mode arAddFriendToListForm::checkList ');         
          }
          
      }
      
      return $values;
  }
  
  public function checkProfile($validator, $values)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      $friendProfile = Doctrine::getTable('sfGuardUserProfile')
                            ->retrieveById($values['profile_id']);
     
      if ($userProfile && $friendProfile)
      {
          
         $this->arFriendStatus = Doctrine::getTable('arFriend')
              ->getStatus($friendProfile->getId(), $userProfile->getId());  
         
         if (!$this->arFriendStatus)
         {
           throw new sfValidatorError($validator, 'Mode arAddFriendToListForm::checkProfile');      
         }
         else if (!(!$this->arFriendStatus->getIsAccept() 
                       && !$this->arFriendStatus->getIsIgnore()
                       && ($this->arFriendStatus->getRequestId() !== $userProfile->getId())))
         {
            throw new sfValidatorError($validator, 'Mode arAddFriendToListForm::checkProfile and $arFriendStatus true');        
         }
      }
      else
      {
        throw new sfValidatorError($validator, 'Error arAddFriendToListForm::checkProfile');   
      }
     
      return $values;
  }
  
}
