<?php

/**
 * PluginarDoc form.
 *
 * @package    aDoc
 * @subpackage form
 * @author     Javier Trigueros Martínez de los Huertos, Arquematics 2011 
 * @version    0.1
 */
abstract class PluginarDocForm extends BasearDocForm
{
  public function setup()
  {
      parent::setup();
      
      sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
      
       unset(
           $this['image_name'],
           $this['mime_content_type'],
           $this['pad_write'],
           $this['pad_read'],
           $this['pass'],
           $this['user_id'],
           $this['doc_type_id'],
           $this['slug'],
           $this['created_at'], 
           $this['updated_at']
        );
       
       $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['id']= new sfValidatorNumber(array('min' => 1,'required' => true));

       //$this->widgetSchema['title'] = new sfWidgetFormInputText(array('default', __('Untitled Document',null,'doc')),array('autocomplete'=>'off','style' => 'width: 500px;','maxlength' => 255));
       $this->widgetSchema['title'] = new sfWidgetFormInputText(array(),array('autocomplete'=>'off','style' => 'width: 500px;','maxlength' => 255));
       $this->validatorSchema['title']= new sfValidatorString(array('max_length' => 255, 'required' => true));
      
       $this->widgetSchema->setNameFormat('doc[%s]');
       
       
  }
    
}
