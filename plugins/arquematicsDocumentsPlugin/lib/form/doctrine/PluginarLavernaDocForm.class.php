<?php

/**
 * PluginarLavernaDoc form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarLavernaDocForm extends BasearLavernaDocForm
{
  public function setup()
  {
      parent::setup();
      
      unset(
       // $this['guid'],
        $this['user_id'],
        $this['messages_list'],
        $this['created_at'],
        $this['updated_at']
      );
      
      $this->widgetSchema['id']      = new sfWidgetFormInputHidden();
      $this->validatorSchema['id']   = new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false));
      
      $this->widgetSchema['title']     = new sfWidgetFormInputText();
      $this->validatorSchema['title']  = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['content']   = new sfWidgetFormInputHidden();
      $this->validatorSchema['content']  = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['data_image']   = new sfWidgetFormInputHidden();
      $this->validatorSchema['data_image']  = new sfValidatorString(array('required' => true));

      $this->widgetSchema['task_all']      = new sfWidgetFormInputHidden();
      $this->validatorSchema['task_all']      = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['task_complete'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['task_complete'] = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['is_favorite']   = new sfWidgetFormInputHidden();
      $this->validatorSchema['is_favorite']   = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['type']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['type']        = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['share']       = new sfWidgetFormInputHidden();
      $this->validatorSchema['share']    = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['trash']         = new sfWidgetFormInputHidden();
      $this->validatorSchema['trash']         = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['guid']          = new sfWidgetFormInputHidden();
      $this->validatorSchema['guid']          = new sfValidatorString(array('required' => false));
      
      if (sfConfig::get('app_arquematics_encrypt'))
      {
          $this->widgetSchema['pass']           = new sfWidgetFormInputHidden();
          $this->validatorSchema['pass']        = new sfValidatorEncryptContent(array('required' => true));    
      }
      
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
      
      $this->widgetSchema->setNameFormat('note[%s]');
     
  }
  
  public static function v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }
  
  public function checkCallback($validator, $values)
  {
      //$object = $this->getObject();
        
      if (!arLavernaDoc::isNameType($values['type']))
      {
         throw new sfValidatorError($validator, 'Error arLavernaDoc::checkDoc');    
      }
      /*
      if (isset($values['guid']))
      {
        $isNew = $object->isNew();

        if ($isNew && Doctrine::getTable('arLavernaDoc')
                    ->isValidGui($values['guid']))
        {
          // ya existe la guid
          throw new sfValidatorError($validator, 'Error arLavernaDoc::checkCallback isValidGui');    
        }
      }*/
     
     
      return $values;
  }
  
  public function callBack($validator, $values)
  {
     $values = $this->checkList($validator, $values);
     
     return $values;
  }
  
  /**
   * enlaza los ficheros con el documento
   * si el documento no se ha guardado 
   * 
   * @param type $object
   * @param type $con
   */
  protected function doSaveDocumentSessionFiles($object, $isNew, $con)
   {
            //ya si, muy mal lo de sfContext
            $user = sfContext::getInstance()->getUser();
            $sessionFiles = $user->getAttribute('arLavernaFile',array(),'wall');

            //si tiene documentos en la sesion esperando 
            //se guardan
            /*echo $object->getGuid();
            print_r($sessionFiles);
            exit();*/
            if (is_object($user) 
                && $user->isAuthenticated()
                && in_array($object->getGuid(), array_keys($sessionFiles)))
                {
                    $noteFiles = $sessionFiles[$object->getGuid()];
                    //borra la referencia al documento en la session
                    unset($sessionFiles[$object->getGuid()]);
                    $user->setAttribute('arLavernaFile', $sessionFiles, 'wall');
                    //agrega los ficheros al documento
                    if ($noteFiles && (count($noteFiles) > 0))
                    {
                       foreach ($noteFiles as $noteFile)
                       {
                         $noteFilesId =  $noteFile['id'];
                       }
                       
                       $lavernaFiles = Doctrine_Core::getTable('arLavernaFile')
                                            ->getByIds($noteFilesId);
                       
                       foreach ($lavernaFiles as $lavernaFile)
                       {
                          $lavernaFile->setLavernaId($object->getId());
                          $lavernaFile->save($con);
                       }
                      
                    }
            }
            else if ($isNew
                    && is_object($user) 
                    && $user->isAuthenticated()
                    && (count($sessionFiles) >  0))
            {
             

            }
   }
   
   protected function doSaveFavorite($object, $con)
   {
       $isFavorite = ((int)$this->values['is_favorite'] > 0);
       $aUserProfile =  $userProfile = $this->getOption('aUserProfile');
       //el documento esta en la papelera
       $isSetFavoriteTable = Doctrine_Core::getTable('arLavernaDocFavorite')
                            ->hasDocProfile($object->getId(), $aUserProfile->getId(),$con);
       
       if ($isFavorite && !$isSetFavoriteTable)
       {
         $arLavernaDocFavorite = new arLavernaDocFavorite();
         $arLavernaDocFavorite->setUserId($aUserProfile->getId());
         $arLavernaDocFavorite->setLavernaId($object->getId());
         $arLavernaDocFavorite->save($con);
       }
       //lo quita favorito
       else if (!$isFavorite && $isSetFavoriteTable)
       {
           $arLavernaDocFavorite = Doctrine_Core::getTable('arLavernaDocFavorite')
                                    ->getByDocProfile($object->getId(), $aUserProfile->getId(),$con);
           
           $arLavernaDocFavorite->delete($con);
       }
   }
   
   protected function doSaveTrash($object, $con)
   {
       $trash = (int)$this->values['trash'];
       $aUserProfile =  $userProfile = $this->getOption('aUserProfile');
       //el documento esta en la papelera
       $isAtTrash = Doctrine_Core::getTable('arLavernaDocTrash')
                        ->hasDocProfile($object->getId(), $aUserProfile->getId(),$con);
       
       if ($trash && !$isAtTrash)
       {
         $arLavernaDocTrash = new arLavernaDocTrash();
         $arLavernaDocTrash->setUserId($aUserProfile->getId());
         $arLavernaDocTrash->setLavernaId($object->getId());
         $arLavernaDocTrash->save($con);
       }
       //lo quita de la papelera
       else if (($trash === 0) && $isAtTrash)
       {
           $arLavernaDocTrash = Doctrine_Core::getTable('arLavernaDocTrash')
                                ->getByDocProfile($object->getId(), $aUserProfile->getId(),$con);
           $arLavernaDocTrash->delete($con);
       }
   }
   
   protected function doSave($con = null)
   {
       $aUserProfile =  $this->getOption('aUserProfile');
       
       $object = $this->getObject();
        
       $isNew = $object->isNew();
       
       //si el objeto es nuevo se genera una guid si no
       //se ha enviado
       //de lo contrario nunca se puede cambiar
       if ($isNew)
       {
         $object->setUserId($aUserProfile->getId());
         //guarda el nuevo guid
         $guid = $object->getGuid() || $this->values['guid'];

         if (strlen(trim($guid)) <= 0)
         {
            $this->values['guid'] = arLavernaDocForm::v4();
         }
       }
       else
       {
          $this->values['guid'] = $object->getGuid(); 
       }
       
       $object->setGuid($this->values['guid']);
       
       $ret = parent::doSave($con);
       
       if ($object->isNoteType())
       {
         $this->doSaveDocumentSessionFiles($object,$isNew, $con);  
       }
       
       $this->doSaveTrash($object, $con); 
       $this->doSaveFavorite($object, $con);
       
       if (sfConfig::get('app_arquematics_encrypt'))
       {
           try
           { 
            $commentsEnc = $this->values['pass'];
            
            $contentEncryptArr = json_decode($commentsEnc, true);
            
            if ($contentEncryptArr && (count($contentEncryptArr) > 0))
            {
                $encodedIds = ($object && is_object($object))?$object->getUserEncodedIds():array();
                
                if ($encodedIds && is_numeric($encodedIds)) 
                {
                  $encodedIds = array($encodedIds); 
                }

                foreach ($contentEncryptArr as $key => $data)
                {
                    if (!in_array($key, $encodedIds))
                    {
                       $encContent = new arLavernaDocEnc();
            
                       $encContent->setUserId($key);
                       $encContent->setLavernaId($object->getId());
                       $encContent->setContent($data);
            
                       $encContent->save($con);
                    }
                    
                     //contenido encriptado para el usuario activo
                    if (isset($encContent) && ($aUserProfile->getId() == $key))
                    {
                        $object->EncContent = $encContent;
                    }  
                }
                
                if (!$object->EncContent)
                {
                   $object->loadPass();
                }
             }
           }
           catch (Exception $e){return null;}
     }
    
     return $ret;
 }
}