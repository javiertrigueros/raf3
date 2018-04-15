<?php

/**
 * PluginarDoc form.
 *
 * @package    aDoc
 * @subpackage form
 * @author     Javier Trigueros Martínez de los Huertos, Arquematics 2011 
 * @version    0.1
 */
class arDocUpdateForm extends BaseFormDoctrine
{
  public function setup()
  {
      
      sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
      
       unset(
           $this['title'],
           $this['image_name'],
           $this['mime_content_type'],
           $this['pad_write'],
           $this['pad_read'],
           $this['pass'],
           $this['user_id'],
           $this['doc_type_id'],
           $this['slug'],
           $this['title'],
           $this['created_at'], 
           $this['updated_at']
        );

       $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['id']= new sfValidatorNumber(array('min' => 1,'required' => true));
       
       $this->widgetSchema['revision'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['revision']= new sfValidatorNumber(array('min' => 0,'required' => true));
      
       
       $this->validatorSchema->setPostValidator(
            new sfValidatorDoctrineUnique(array('model' => 'arDoc', 'column' => array('id')))
        );
       
       $this->widgetSchema->setNameFormat('update_doc[%s]');
       
       $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

       $this->setupInheritance();
    
       parent::setup();
       
  }
  
  public function getModelName()
  {
    return 'arDoc';
  }
    
}
