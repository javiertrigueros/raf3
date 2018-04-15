<?php

/**
 * arBlogItemExcerpt form.
 *
 */
class arBlogItemExcerpt extends BaseFormDoctrine
{
  public function configure()
  {
    $this->setWidget('id',new sfWidgetFormInputHidden());
    
    $this->setValidator('id', new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => true)));
      
    $this->setWidget('excerpt',new sfWidgetFormTextarea(array(), 
                array('class' => 'no-borders ui-control-text-input span12',
                      'autocomplete'=>'off',
                      'style'=> 'float:left;')));
    
     if (sfConfig::get('app_arquematics_encrypt'))
     {
          $this->validatorSchema['excerpt'] = new sfValidatorEncryptContent(array('required' => true));    
     }
     else
     {
          $this->validatorSchema['excerpt'] = new sfValidatorString(array('min_length' => 4,'required' => true));  
     }
     
     $this->widgetSchema->setNameFormat('a_blog_new_post[%s]');
  }
  
  public function getModelName()
  {
    return 'aBlogItem';
  }
}
