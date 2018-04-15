<?php
/**
 * PluginarFriendForm form.
 *
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martinez de los Huertos, Arquematics
 * @version    0.1
 */
abstract class PluginarFriendForm extends BasearFriendForm
{

  const ADD_USER = 0;
  const REMOVE_REQUEST = 1;
  const REMOVE_SUSCRIPTOR = 2;
  
  protected $node = -1;


  public function setup()
  {
      parent::setup();
       
       unset($this['id'],
             $this['profile_id' ], 
             $this['request_id'],
             $this['is_accept'], 
             $this['is_ignore'], 
             //$this['is_block'],   
             $this['created_at'], 
             $this['updated_at']);
      /*
      $this->widgetSchema['mode'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['mode']=  new sfValidatorInteger(array('required' => true));
     */
      $this->widgetSchema['friend_id']  = new sfWidgetFormInputHidden();
      $this->validatorSchema['friend_id']  = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserF'), 'required' => false));
              
     
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
      
      $this->widgetSchema->setNameFormat('add_friend[%s]');
      
  }
  public function checkCallback($validator, $values)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      $friendProfile = Doctrine::getTable('sfGuardUserProfile')
                            ->retrieveById($values['friend_id']);
      
      
      if ($userProfile && $friendProfile)
      {
         if ($friendProfile->canAddUser($userProfile->getId()))
         {
            $this->node = arFriendForm::ADD_USER;
         }
         else if ($friendProfile->canRemoveRequest())
         {
            $this->node = arFriendForm::REMOVE_REQUEST;
         }
         else if ($friendProfile->canRemoveSuscriptor())
         {
            $this->node = arFriendForm::REMOVE_SUSCRIPTOR; 
         }
         else {
            throw new sfValidatorError($validator, 'Mode arFriendForm::checkCallback');    
         }
      }
      else
      {
        throw new sfValidatorError($validator, 'Mode arFriendForm::checkCallback');   
      }
      
      return $values;
  }
  
  protected function doSave($conn = NULL)
  {
      $userProfile = $this->getOption('aUserProfile');
      
      if ($this->node === arFriendForm::ADD_USER)
      {
       
        $arFriend = Doctrine::getTable('arFriend')
                        ->getStatus($userProfile->getId(), $this->values['friend_id'], $conn);
        
        if ($arFriend)
        {
          $userProfile->confirmRequest($this->values['friend_id'], array(), true, $conn);    
        }
        else
        {
          arFriend::addRequest($userProfile->getId(), $this->values['friend_id'], $conn);  
        }
      }
      else if ($this->node === arFriendForm::REMOVE_REQUEST)
      {
          $userProfile->removeRequest($this->values['friend_id'],  $conn);      
      }
      else if ($this->node === arFriendForm::REMOVE_SUSCRIPTOR)
      {
          $userProfile->confirmRequest($this->values['friend_id'], array(), false, $conn);
      }
  }
}
