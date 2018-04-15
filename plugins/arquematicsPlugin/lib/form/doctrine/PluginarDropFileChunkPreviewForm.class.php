<?php

/**
 * PluginarDropFileChunkPreview form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarDropFileChunkPreviewForm extends BasearDropFileChunkPreviewForm
{
    public function setup()
    {
        parent::setup();
        
         unset(
            $this['id'],
            $this['drop_file_preview_id']
        );
        
        $this->setWidgets(array(
                            'chunkData'    => new sfWidgetFormInputHidden(),
                            'pos'          => new sfWidgetFormInputHidden(),
                        ));

        $this->setValidators(array(
                            'chunkData'    => new sfValidatorString(array('required' => true)),
                            'pos'          => new sfValidatorInteger(array('required' => true))
                        ));
        
       
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
        );
        
        $this->widgetSchema->setNameFormat('ar_drop_file_chunk_preview[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
     }
     
     public function checkCallback($validator, $values)
     {
      $this->checkDropFile($validator, $values);
  
      return $values;
     }
    
   /**
   * solo el propio usuario puede subir las partes del fichero 
   * 
   * @param validator $validator
   * @param array $values
   * @throws sfValidatorError
   */
  public function checkDropFile($validator, $values)
  {
    
    $userProfile = $this->getOption('aUserProfile');
    $arDropFile = $this->getOption('arDropFilePreview')
                            ->getDropFile();

    if ($userProfile->getId() != $arDropFile->getUserId())
    {
      throw new sfValidatorError($validator, 'Error you are not owner');
    }
    
  }
  
  protected function doSave($conn = null)
  {
        $arDropFilePreview = $this->getOption('arDropFilePreview');
        
        $object = $this->getObject();
        
        $object->setDropFilePreviewId( $arDropFilePreview->getId());

        parent::doSave($conn);
        
        return $object;
  }
}
