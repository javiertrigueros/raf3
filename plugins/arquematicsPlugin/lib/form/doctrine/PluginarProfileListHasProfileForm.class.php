<?php

/**
 * acepta o rechaza los usuarios
 */
abstract class PluginarProfileListHasProfileForm extends BasearProfileListHasProfileForm
{
    
  public function setup()
  {
      unset(
            $this['created_at'], 
            $this['updated_at']
      );
      
      $this->widgetSchema['profile_id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['profile_id']  = new sfValidatorDoctrineChoiceMultiplePlainString(array('multiple' => false, 'model' => 'sfGuardUserProfile', 'required' => true));
      
     
      $this->widgetSchema['is_accept']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['is_accept']  = new sfValidatorBoolean(array('required' => true));
      
      $userProfile = $this->getOption('aUserProfile');
        
      $options = array();
        
      if (isset($userProfile) && is_object($userProfile))
      {
            $options =  $userProfile->getAdminListChoices();
      }
        
      $this->widgetSchema['profile_list_id'] = new sfWidgetFormChoice(array(
                    'choices' => $options
            ),array('style' => 'display:none','multiple'=> 'multiple'));
        
       
      $this->validatorSchema['profile_list_id'] = new sfValidatorChoice(
                            array(
                                    'required' => false,
                                    'multiple' => true,
                                    'max' => sfConfig::get('app_arquematics_plugin_max_list_items', 6),
                                    'choices' => array_keys($options)
                            ),
                            array()); 
      
      
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkIsBlocked')))
      );
     
      $this->widgetSchema->setNameFormat('accept[%s]');
  }
  
  public function checkIsBlocked($validator, $values)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      $friendProfile = Doctrine::getTable('sfGuardUserProfile')
                            ->retrieveById($values['profile_id']);
      
      if ($userProfile && $friendProfile)
      {
         $isAccept = $values['is_accept'];
          
         if ($isAccept && (!$friendProfile->canAddUser($userProfile->getId())))
         {
            throw new sfValidatorError($validator, 'Mode arFriendForm::checkCallback isAccept= true');    
         }
         else if ((!$isAccept) && (!(($friendProfile->canRemoveRequest($userProfile->getId())) || ($friendProfile->canRemoveSuscriptor($userProfile->getId())))))
         {
            throw new sfValidatorError($validator, 'Mode arFriendForm::checkCallback isAccept= false canRemoveRequest');        
         }
      }
      else
      {
        throw new sfValidatorError($validator, 'Error arProfileListHasProfileForm::checkIsBlocked');   
      }
     
     
      return $values;
  }
  
}
