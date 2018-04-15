<?php

/**
 * PluginarMenuItem form.
 *
 */
class arCreateMenuForm extends BaseForm
{
    public function configure()
    {
       
       $this->widgetSchema['name']          = new sfWidgetFormInputHidden();
     
       $this->widgetSchema['data']          = new sfWidgetFormInputHidden();
       $this->validatorSchema['data']       = new sfValidatorString(array( 'required' => true));
      
       $this->widgetSchema->setNameFormat('arMenu[%s]');
    
    }
    
}
