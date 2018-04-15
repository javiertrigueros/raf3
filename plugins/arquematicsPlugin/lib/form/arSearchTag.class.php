<?php

class arSearchTag extends BaseForm
{
    
  public function setup()
  {
     $this->widgetSchema['name']  = new sfWidgetFormInputHidden();
     
     $this->setValidator('name', new sfValidatorAnd(array(
            new sfValidatorString(array('min_length' => 1, 'max_length' => 20)),
            new sfValidatorRegex(array('pattern' => '/^[a-z\d_ \<\>\[\]\{\}\*\!\%\$]{3,20}$/i'),
                array('invalid' => 'Invalid tag.')),
           
     )));
        
      $this->widgetSchema->setNameFormat('search_tag[%s]');
  }
  
}