<?php

/**
 * Formulario editar lista
 *
 * @package    Arquematics
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
class arProfileListEditForm extends BasearProfileListForm
{
    public function setup()
    {        
        unset(
            $this['users_list'],
            $this['description'],
            $this['created_at'], 
            $this['updated_at']
        );
        
        $this->widgetSchema['id']  = new sfWidgetFormInputHidden();
        $this->validatorSchema['id'] = new sfValidatorDoctrineChoice(array('model' => 'arProfileList', 'column' => 'id', 'required' => true));
        
        $this->widgetSchema['name'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off'));
        $this->validatorSchema['name'] = new sfValidatorString(array('max_length' => 15,'min_length' => 3, 'required' => true));
        
        $this->validatorSchema['name']
                 ->setMessage('invalid', __("Same name lists",array(),'arquematics'))
                ->setMessage('required', __("Required field",array(),'arquematics'))
                ->setMessage('max_length', __("Too long, please keep it short (max 15 chars)",array(),'profile'))
                ->setMessage('min_length', __("Too sort, please keep it long (min 3 chars)",array(),'profile')); 

        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkList')))
        );
        
        $this->widgetSchema->setNameFormat('list_edit[%s]');
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
      else if (isset($values['name'])
              && Doctrine::getTable('arProfileList')
                    ->hasProfileIdName($userProfile->getId(), $values['name']))
      {
         throw new sfValidatorError($validator, 'invalid', array('value' => $values['name']));  
      }
      
      return $values;
      
  }
}
