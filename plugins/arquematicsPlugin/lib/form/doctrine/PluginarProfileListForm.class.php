<?php

/**
 * Formulario lista de usuarios
 *
 * @package    Arquematics
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
abstract class PluginarProfileListForm extends BasearProfileListForm
{
    public function setup()
    {  
        parent::setup();
        unset(
            $this['is_all'],
            $this['profile_id'],
            $this['created_at'], 
            $this['updated_at']
        );
        
        $this->widgetSchema['id']  = new sfWidgetFormInputHidden();
        $this->validatorSchema['id'] = new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false));
        
        $this->widgetSchema['name'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off'));
        $this->validatorSchema['name'] = new sfValidatorString(array('max_length' => 15, 'min_length' => 3, 'required' => true), 
                                                               array('invalid' => 'Same name lists'));
        
        $this->validatorSchema['name']
                ->setMessage('invalid', __("Same name lists",array(),'arquematics'))
                ->setMessage('required', __("Required field",array(),'arquematics'))
                ->setMessage('max_length', __("Too long, please keep it short (max 15 chars)",array(),'profile'))
                ->setMessage('min_length', __("Too sort, please keep it long (min 3 chars)",array(),'profile')); 
        
        $this->widgetSchema['users_list']  = new sfWidgetFormInputHidden(array(),array('value' => ''));
        $this->validatorSchema['users_list']  = new sfValidatorDoctrineChoiceMultiplePlainString(array('multiple' => true, 'model' => 'sfGuardUserProfile', 'required' => false));
        
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkProfileList')))
        );
        
        $this->widgetSchema->setNameFormat('list[%s]');
    }
    
    public function checkProfileList($validator, $values)
    {
      $userProfile = $this->getOption('aUserProfile');
      
      if (isset($values['users_list']) 
         && is_array($values['users_list']))
      {
         for ($i = 0; ($i < count($values['users_list'])); $i++)
         {
             if ($values['users_list'][$i] == $userProfile->getId())
             {
               throw new sfValidatorError($validator, 'Error you add ownerid to list');  
             }
         }
      }
      else if (isset($values['users_list']) 
              && is_numeric($values['users_list'])
              && $values['users_list'] == $userProfile->getId())
      {
         throw new sfValidatorError($validator, 'Error you add ownerid to list');   
      }
      
      if (Doctrine::getTable('arProfileList')
                    ->hasProfileIdName($userProfile->getId(), $values['name']))
      {              
         throw new sfValidatorError($validator, 'invalid', array('value' => $values['name']));  
      }
      
      return $values;
    }
}
