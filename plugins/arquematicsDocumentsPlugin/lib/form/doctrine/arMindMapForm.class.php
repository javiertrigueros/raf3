<?php

/**
 * 
 *
 * @package    diagramEditor
 * 
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    S0.1
 */
class arMindMapForm extends BasearDiagramForm
{
  public function configure()
  {
           
      sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
    
      unset(
        $this['id'],       
        $this['wall_message_id'],
        $this['created_at'],
        $this['slug'],
        $this['updated_at']
        );
      /*
       'id'              => new sfWidgetFormInputHidden(),
      'json'            => new sfWidgetFormTextarea(),
      'vector_image'    => new sfWidgetFormTextarea(),
      'file_name'       => new sfWidgetFormInputText(),
      'wall_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
        */
      
      $this->widgetSchema['type']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['type']        = new sfValidatorInteger(array('required' => true));
      
      $this->widgetSchema['json']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['json']        = new sfValidatorString(array('required' => true));
      
      
      $this->widgetSchema['data_image']    = new sfWidgetFormInputHidden();
      $this->validatorSchema['data_image'] = new sfValidatorString(array('required' => true));
      
     
      $this->widgetSchema['user_id']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['user_id']        = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => true));
      
      $this->widgetSchema['file_name'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['file_name'] = new sfValidatorImageString(array(
          'required' => true,
          'path' => sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arDiagram'));
      
      
      $this->widgetSchema->setNameFormat('diagram[%s]');
  }
  
}
