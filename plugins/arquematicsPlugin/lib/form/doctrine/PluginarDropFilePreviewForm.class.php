<?php

/**
 * PluginarDropFilePreview form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarDropFilePreviewForm extends BasearDropFilePreviewForm
{
    public function setup()
    {
        parent::setup();
        
        unset(
            $this['created_at'], 
            $this['updated_at'],
            $this['user_id'],
            $this['drop_file_id'],
            $this['guid'],
            $this['slug'],
            $this['id']
        );
        
        $this->setWidgets(array(
            'src'          =>  new  sfWidgetFormInputHidden(),
            'type'         =>  new  sfWidgetFormInputHidden(),
            'guid'         =>  new  sfWidgetFormInputHidden(),
            'size'         =>  new  sfWidgetFormInputHidden(),
            'size_style'   =>  new  sfWidgetFormInputHidden()
        ));

        $this->setValidators(array(
            'src'          => new sfValidatorString(array('required' => false)),
            'guid'         => new sfValidatorString(array('max_length' => 255, 'required' => true)),
            'type'         => new sfValidatorString(array('max_length' => 255, 'required' => true)),
            'size'         => new sfValidatorNumber(array('min' => 0,'required' => true)),
            'size_style'   => new sfValidatorString(array('max_length' => 255, 'required' => true))
        ));
        
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
        );
    
        $this->widgetSchema->setNameFormat('ar_drop_file_preview[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
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
    $arDropFile = $this->getOption('arDropFile');

    if ($userProfile->getId() != $arDropFile->getUserId())
    {
      throw new sfValidatorError($validator, 'Error you are not owner');
    }
    
  }
     
     public function checkCallback($validator, $values)
     {
      $this->checkDropFile($validator, $values);
  
      return $values;
     }
     
     protected function isValidBase64($string){
        
        // Check if there is no invalid character in strin
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

        // Decode the string in strict mode and send the responce
        if(!base64_decode($string, true)) return false;

        $decoded = base64_decode($string, true);
        // Encode and compare it to origional one
        if(base64_encode($decoded) != $string) return false;

        return true;
    }
     
     protected function doSave($conn = null)
     {
        $object = $this->getObject();
        $arDropFile = $this->getOption('arDropFile');
        
        //segun si tiene o no contenido en src
        $leng = strlen(trim($this->values['src']));
        
        if (($leng > 0) && $this->isValidBase64($this->values['src']))
        {
            $object->setPersistence(arDropFilePreview::PERSISTENCE_SMALL);
            $object->setDropFileId($arDropFile->getId());
            
            parent::doSave($conn);
        }
        else if ($leng > 0)
        {
            $object->setPersistence(arDropFilePreview::PERSISTENCE_MED);
            $object->setDropFileId($arDropFile->getId());
            $srcData = $this->values['src'];
            $this->values['src'] = ''; 
            $object->setSrc(''); //almacenado en partes
            
            parent::doSave($conn);
            
            $contentEncryptArr = json_decode($srcData, true);
            
            if ($contentEncryptArr && count($contentEncryptArr))
            {
               $i = 0;
               
               foreach ($contentEncryptArr as $data)
               {
                   
                  $arDropFileChunkPreview = new arDropFileChunkPreview();
                  
                  $arDropFileChunkPreview->setChunkData($data['chunkData']);
                  $arDropFileChunkPreview->setPos($i);
                  $arDropFileChunkPreview->setDropFilePreviewId($object->getId());
                  $arDropFileChunkPreview->save($conn);
                  
                  $i++;
               }
            }
        }
        else
        {
            $object->setPersistence(arDropFilePreview::PERSISTENCE_BIG);  
            $object->setDropFileId($arDropFile->getId());
            $this->values['src'] = '';
            $object->setSrc(''); //almacenado en partes
            
            parent::doSave($conn);
            
        }
        
        return $object;
     }
}
