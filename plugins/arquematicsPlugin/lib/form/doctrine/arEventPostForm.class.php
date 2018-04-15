<?php

// For your overriding convenience

class arEventPostForm extends PluginaNewEventForm
{
    
  public function configure()
  {
    parent::configure();
   
    $this->setWidget('title', new sfWidgetFormInputText(array(), 
                array('class' => 'span12 no-borders',
                      'label' => __('Event title', array(), 'wall'),
                      'placeholder' => __('Event title', array(), 'wall'),
                      'autocomplete'=>'off')));
    $this->setValidator('title', new sfValidatorString(array('min_length' => 2, 'required' => true)));
    
    $this->widgetSchema['message'] = new sfWidgetFormTextarea(array(), 
                array('class' => 'span12 no-borders',
                      'label' => __('Abstract', array(), 'wall'),
                      'placeholder' => __('Abstract', array(), 'wall'),
                      'autocomplete'=>'off'));
        
        
    $this->validatorSchema['message'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
    $this->validatorSchema['message']
                ->setMessage('required', __('Required', array(), 'arquematics'));
    
    
    $userProfile = $this->getOption('aUserProfile');
       
    $options = array();
        
    if (isset($userProfile) && is_object($userProfile))
    {
        $options =  $userProfile->getAdminListChoices();
    }
        
    $this->widgetSchema['groups'] = new sfWidgetGroupChoice(array(
                    'choices' => $options
            ),array('style' => 'display:none','multiple'=> 'multiple'));
        
       
    $this->validatorSchema['groups'] = new sfValidatorChoiceGroupMultiple(
                            array(
                                    'required' => false,
                                    'max' => sfConfig::get('app_arquematics_plugin_max_list_items', 6),
                                    'choices' => array_keys($options)
                            ),
                            array());
        
        
    $this->widgetSchema['is_publish']  = new sfWidgetFormInputHidden(array(), array('value' => true));
    $this->validatorSchema['is_publish']  = new sfValidatorBoolean(array('required' => true));
   
    $this->widgetSchema->setNameFormat('a_new_event[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
    
  }
}
