<?php

/**
 * arDiagram form.
 *
 * @package    asandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arDiagramImageForm extends PluginarDiagramForm
{
  public function configure()
  {
     sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
    
      unset(
        $this['created_at'],
        $this['updated_at'],
        $this['wall_message_id'],
        $this['json'],
        $this['slug'],
        $this['vector_image']
              );
        
      //evitar problemas con los <br> en campos help
        $decorator = new arFormSchemaFormatter($this->getWidgetSchema()); 
        $this->widgetSchema->addFormFormatter('custom',$decorator); 
        $this->widgetSchema->setFormFormatterName('custom'); 
        
        $this->widgetSchema['file_name'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();

        $this->widgetSchema['data_image'] = new sfWidgetFormInputHidden();
        
        $this->validatorSchema['data_image'] = new sfValidatorString(array('required' => true));
        $this->validatorSchema['data_image']
                 ->setMessage('required', __('Required', array(), 'arquematics'));
      
      $this->widgetSchema->setNameFormat('diagram[%s]');
  }
}
