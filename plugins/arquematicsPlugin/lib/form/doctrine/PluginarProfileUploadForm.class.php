<?php

/**
 * Formulario para subir un fichero de usuario
 * 
 * Fichero de usuario subido, puede ser la imagen de usuario o cualquier 
 * otro fichero
 *
 * @package    Arquematics
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
abstract class PluginarProfileUploadForm extends BasearProfileUploadForm
{
    
    public function setup()
    {
       parent::setup();
       
        unset(
            $this['created_at'], 
            $this['updated_at'],
            $this['id'],
            $this['user_id'],
            $this['mime_content_type'],
            $this['is_profile'],
            $this['file_name']
        );
        
        sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
        
        $this->widgetSchema['name'] = new sfWidgetFormInputFile();
        //soporte para amazon s3
        
       $this->validatorSchema['name'] = new sfValidatorFileArquematics(
                array(
                    'required'   => true,
                    'related_model' => 'arProfileUpload',
                    'path' => sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arProfileUpload',
                    'validated_file_class' => 'sfValidatedImage')
                );
    
        
    
        $this->widgetSchema->setNameFormat('upload[%s]');
   }     
}
