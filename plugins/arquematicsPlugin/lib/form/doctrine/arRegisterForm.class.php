<?php


class arRegisterForm extends ArquematicsUserAdminForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
      parent::setup();
           
      sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
    
      unset(
        $this['id'],
        $this['password_again'],
        $this['groups_list'],     
        $this['permissions_list'],   
        $this['categories_list'],   
        $this['blog_editor_items_list'],
        $this['is_active'],
        $this['is_super_admin']);
        
        //evitar problemas con los <br> en campos help
        $decorator = new arFormSchemaFormatter($this->getWidgetSchema()); 
        $this->widgetSchema->addFormFormatter('custom',$decorator); 
        $this->widgetSchema->setFormFormatterName('custom'); 
      
        $this->widgetSchema['first_name'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off'));
        $this->widgetSchema->setLabel('first_name', __('First name', array(), 'arquematics'));
        $this->validatorSchema['first_name']
                 ->setOption('required', true)
                 ->setMessage('required', __('Required', array(), 'arquematics'));
        
        $this->widgetSchema['last_name'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off'));
        $this->widgetSchema->setLabel('last_name', __('Last name', array(), 'arquematics'));
        $this->validatorSchema['last_name']
                 ->setOption('required', true)
                ->setMessage('required', __('Required', array(), 'arquematics'));
        
        $this->widgetSchema['email_address'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off'));
        //mirar esto para validacion de mail
        $this->widgetSchema->setLabel('email_address', __('Email address', array(), 'arquematics'));
        $this->validatorSchema['email_address'] = new sfValidatorAnd(array(
            new sfValidatorEmail(array(), array(
                'invalid' => __('Email address is invalid',array(),'arquematics')
            )),
            new sfValidatorDoctrineUniqueAjax(array(
                'model' => 'sfGuardUser',
                'column' => 'email_address'    
            ), array(
                'invalid' => __('Sorry! A user with that email address already exists.',array(),'arquematics')    
            ))
        ),
            array('halt_on_error' => true)     
        );
        $this->validatorSchema['email_address']->setMessage('required', __('Required', array(), 'arquematics'));
        
        $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(),array('autocomplete'=>'off'));
        // [A-Za-z0-9_\-\.].
        $this->widgetSchema->setLabel('password', __('Password', array(), 'arquematics'));
        $this->validatorSchema['password']
                 ->setOption('required', true)
                 ->setMessage('required', __('Required', array(), 'arquematics'));
        
        
        $this->widgetSchema['terms'] = new sfWidgetFormInputCheckbox();
        $this->validatorSchema['terms'] = new sfValidatorBoolean(array('required' => true));
        
        $this->validatorSchema['terms']->setMessage('required', __('Required', array(), 'arquematics'));
        
        
        $this->widgetSchema['username'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['username'] = new sfValidatorString(array('required' => false, 'max_length' => 128));
        
        /*
        $this->validatorSchema->setPostValidator(
            new sfValidatorAnd(array(
                new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
                new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
            ))
        );*/
        
  
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'callBack')))
        );
        
        
  	$this->widgetSchema->getFormFormatter()->setTranslationCatalogue('arquematics');
  }
  
  public function generateUserName($emailAddress)
  {
      $parts = explode("@", $emailAddress);
      $username = $parts[0];
      
      if (Doctrine_Core::getTable('sfGuardUserProfile')
                ->countByUserName($username) > 0)
      {
        $username .=  sprintf('%04x%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff));
      }
      
      return  $username;   

  }
  
  public function callBack($validator, $values)
  {

     $values['username'] = (isset($values['username']) 
                            &&  (strlen(trim($values['username'])) > 0))?
                                $values['username']: $this->generateUserName($values['email_address']);
  
     
     if  (Doctrine_Core::getTable('sfGuardUserProfile')
             ->countByEmail($values['email_address']) > 0)
     {
         throw new sfValidatorError($validator, 'Email_address not unique');  
     }
     else if (Doctrine_Core::getTable('sfGuardUserProfile')
                ->countByUserName($values['username']) > 0)
     {
        throw new sfValidatorError($validator, 'username not unique');   
     }
     
     return $values;
  }
  
  protected function doSave($con = null)
  {
    $object = $this->getObject();
      
    parent::doSave($con); 
    
    //agrega el grupo por defecto
    //del usuario
    $object->addGroupByName(sfConfig::get('app_arquematics_plugin_default_user_group', 'basic'), $con);
    
    $aUserProfile = $object->getProfile();
    
    //copia los datoa l profile
    $aUserProfile->setEmailAddress($object->getEmailAddress());
    $aUserProfile->setUsername($object->getUsername());
    $aUserProfile->setFirstLast($object->getFirstName().' '.$object->getLastName());
    $aUserProfile->setPassword($object->getPassword());
    
    $aUserProfile->save($con);
    
    return $object;
  }
}