<?php

class arTagSimpleForm extends TagForm
{

  
  public function configure()
  {
      
    unset(
            $this['id'],
            $this['is_triple'], 
            $this['triple_namespace'], 
            $this['triple_key'], 
            $this['triple_value']
        );

    
    $this->setWidget('name',new sfWidgetFormInputText(array(), 
                array('class' => 'no-borders ui-control-text-input span12',
                      'autocomplete'=>'off')));
    
   
    $this->setValidator('name', new sfValidatorAnd(array(
            new sfValidatorString(array('min_length' => 2, 'max_length' => 20)),
            new sfValidatorRegex(array('pattern' => '/^[a-z\d_\<\>\[\]\{\}\*\!\%\$]{2,20}$/i'),
                array('invalid' => 'Invalid tag.')),
           
     )));
    
    $this->widgetSchema['search_tag']  = new sfWidgetFormInputHidden(array(),array('value' => 0));
    $this->validatorSchema['search_tag']  = new sfValidatorBoolean(array('required' => true));
    

    $this->widgetSchema->setNameFormat('a_blog_tag[%s]');
  }

  /**
   * DOCUMENT ME
   * @param mixed $values
   */
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }
    // Slashes break routes in most server configs. Do NOT force case of tags.
    
    $values['name'] = str_replace('/', '-', isset($values['name']) ? $values['name'] : '');
    parent::updateObject($values);
  }
  
}