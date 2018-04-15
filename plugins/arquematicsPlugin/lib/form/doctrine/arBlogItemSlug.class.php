<?php

/**
 * arBlogItemSlug form.
 *
 */
class arBlogItemSlug extends BaseFormDoctrine
{
  public function configure()
  {
    $this->setWidget('id',new sfWidgetFormInputHidden());
    
    $this->setValidator('id', new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => true)));
      
    $this->setWidget('slug',new sfWidgetFormInputText(array(), 
                array('class' => 'no-borders ui-control-text-input',
                      'autocomplete'=>'off',
                      'style'=> 'float:left;')));
    $this->setValidator('slug', new sfValidatorString(array('min_length' => 4, 'required' => true)));
    $this->widgetSchema->setNameFormat('a_blog_new_post[%s]');
  }
  
  public function getModelName()
  {
    return 'aBlogItem';
  }
}
