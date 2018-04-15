<?php

/**
 * PluginarWallUpload form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarWallUploadForm extends BasearWallUploadForm
{
    public function setup()
    {
        parent::setup();
       
        unset(
            $this['created_at'], 
            $this['updated_at'],
            $this['slug'],
            $this['id']
        );
        
        $this->widgetSchema['file_name'] = new sfWidgetFormInputHidden();
        /*
        $this->validatorSchema['file_name'] = new sfValidatorFile(
                array(
                    'required'   => true,
                    'path' => sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arWallUpload',
                    'validated_file_class' => 'sfValidatedUpload')
                );*/
        
         $this->validatorSchema['file_name'] = new sfValidatorFileArquematics(
                array(
                    'required'   => true,
                    'related_model' => 'arWallUpload',
                    'path' => sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arWallUpload',
                    'validated_file_class' => 'sfValidatedUpload')
                );
        
        $this->widgetSchema['name']  = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['mime_content_type'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['size'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['is_image'] = new sfWidgetFormInputHidden();
       
        $this->widgetSchema['wall_message_id'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['gui_id'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['gui_id'] = new sfValidatorNumber(array('required' => true));
        
        $this->widgetSchema->setNameFormat('ar_wall_uploads[%s]');
    }
    
   
}
