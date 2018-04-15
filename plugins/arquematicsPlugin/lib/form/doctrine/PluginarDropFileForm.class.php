<?php

/**
 * PluginarDropFile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarDropFileForm extends BasearDropFileForm
{
    public function setup()
    {
        parent::setup();
        
        unset(
            $this['created_at'], 
            $this['updated_at'],
            $this['user_id'],
            $this['message_id'],
            $this['ready'],
            $this['id']
        );
        
        $this->setWidgets(array(
            'src'        => new  sfWidgetFormInputHidden(),
            'type'       => new  sfWidgetFormInputHidden(),
            'guid'       => new  sfWidgetFormInputHidden(),
            'size'       => new  sfWidgetFormInputHidden(),
            'name'       => new  sfWidgetFormInputHidden()
        ));
        
        $this->setValidators(array(
            'name'       => new sfValidatorString(array('required' => true)),
            'src'        => new sfValidatorString(array('required' => false)),
            'size'       => new sfValidatorNumber(array('min' => 0,'required' => true)),
            'type'       => new sfValidatorString(array('max_length' => 255, 'required' => true)),
            'guid'       => new sfValidatorString(array('max_length' => 255, 'required' => true)),
         ));
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
          $this->widgetSchema['pass']  = new sfWidgetFormInputHidden();
          $this->validatorSchema['pass']  = new sfValidatorEncryptContent(array('required' => true));
        }
        
        $this->validatorSchema->setPostValidator(
            new sfValidatorDoctrineUnique(array('model' => 'arDropFile', 'column' => array('guid')))
        );

        $this->widgetSchema->setNameFormat('ar_drop_file[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
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
        $userProfile = $this->getOption('aUserProfile');

        $object = $this->getObject();
        
        $object->setUserId( $userProfile->getId());
         
        //el fichero no esta listo hasta que se terminan  de
        //mandar las partes y las vistas
        $object->setReady(false); 
        
        //segun si tiene o no contenido en src
        $leng = strlen(trim($this->values['src']));
        
        if (($leng > 0) && $this->isValidBase64($this->values['src']))
        {
            $object->setPersistence(arDropFile::PERSISTENCE_SMALL);
            parent::doSave($conn);
        }
        else if ($leng > 0)
        {
            $object->setPersistence(arDropFilePreview::PERSISTENCE_MED);
            $srcData = $this->values['src'];
            $this->values['src'] = ''; 
            $object->setSrc(''); //almacenado en partes
            parent::doSave($conn);
            
            $contentEncryptArr = json_decode($srcData, true);
            
            if ($contentEncryptArr && count($contentEncryptArr))
            {
                $i = 0;
               foreach ($contentEncryptArr as  $data)
               {
                  $arDropFileChunk = new arDropFileChunk();
                  
                  $arDropFileChunk->setChunkData($data['chunkData']);
                  $arDropFileChunk->setPos($i);
                  $arDropFileChunk->setDropFileId($object->getId());
                  $arDropFileChunk->save($conn);
                  
                  $i++;
               }
            }
        }
        else
        {
            $object->setPersistence(arDropFilePreview::PERSISTENCE_BIG);  
            $object->setSrc(''); //almacenado en partes
            parent::doSave($conn);
        }
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
            $contentEncryptPass = $this->values['pass'];
            
            $contentEncryptArr = json_decode($contentEncryptPass, true);
            
            if ($contentEncryptArr && count($contentEncryptArr))
            {
               foreach ($contentEncryptArr as $keyId => $data)
               {
                  $encContent = new arDropFileEnc();
                  $encContent->setUserId($keyId);
                  $encContent->setDropFileId($object->getId());
                  $encContent->setContent($data);
                  $encContent->save($conn);
                  
                  if ($userProfile->getId() == $keyId)
                  {
                    $this->EncContent = $encContent; 
                  }
               }
            } 
            
        }

        return $object;
     }
     
}
