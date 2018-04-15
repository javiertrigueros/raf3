<?php

/**
 *
 */
class arPageDeleteForm extends BaseForm
{
    public function configure()
    {
       
       $this->widgetSchema['id']          = new sfWidgetFormInputHidden();
       $this->validatorSchema['id']       = new sfValidatorDoctrineChoice(array('model' => 'aPage', 'required' => true));
      
       $this->widgetSchema->setNameFormat('page_delete[%s]');
    
    }
    
}
