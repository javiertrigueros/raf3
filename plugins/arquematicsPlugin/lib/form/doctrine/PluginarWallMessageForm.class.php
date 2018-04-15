<?php

/**
 * Formulario mensaje del muro
 *
 * @package    Arquematics
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    0.1
 */
abstract class PluginarWallMessageForm extends BasearWallMessageForm
{
     public function setup()
     {
       
        unset(
            $this['published_at'], 
            $this['created_at'], 
            $this['updated_at'],
            $this['user_id'],
            $this['id']
        );
        
        sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
        
        $this->widgetSchema['message'] = new sfWidgetFormTextarea(array(), 
                array('label' => __('Share what is new...', array(), 'wall'),
                      'placeholder' => __('Share what is new...', array(), 'wall'),
                      'autocomplete'=>'off'));
        
        
        
        $this->widgetSchema->setLabel('message', __("What's up?", array(), 'arquematics'));
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
          $this->validatorSchema['message'] = new sfValidatorEncryptContent(array('required' => true));    
        
          $this->widgetSchema['pass']  = new sfWidgetFormInputHidden();
          $this->validatorSchema['pass']  = new sfValidatorEncryptContent(array('required' => true));
        }
        else
        {
          $this->validatorSchema['message'] = new sfValidatorString(array('required' => true));  
        }
        
        $this->validatorSchema['message']->setMessage('required', __('Required', array(), 'arquematics'));
        
        $userProfile = $this->getOption('aUserProfile');
       
        $options = array();
        
        if (isset($userProfile) && is_object($userProfile))
        {
            $options =  $userProfile->getAdminListChoices();
        }

        $this->widgetSchema['groups'] = new sfWidgetFormChoice(
                array('choices' => $options),
                array('multiple'=> 'multiple'));
        
         $this->validatorSchema['groups'] = new sfValidatorChoice(
                 array('multiple' => true, 
                       'choices' => array_keys($options),
                       'required' => false));

        
        
        $this->widgetSchema['is_publish']  = new sfWidgetFormInputHidden(array(), array('value' => true));
        $this->validatorSchema['is_publish']  = new sfValidatorBoolean(array('required' => true));
        
       
        $this->widgetSchema->setNameFormat('wallMessage[%s]');
        
     }
     
     protected function doSave($conn = null)
     {
        
        $sysUser = $this->getOption('sysUser');
        $userProfile = $this->getOption('aUserProfile');

        $object = $this->getObject();
        
        $object->setUserId( $userProfile->getId());
        $object->setPublishedAt(date('Y-m-d H:i:s'));
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
            $object->setMessage('');
            $contentEncrypt = $this->values['message'];
            $this->values['message'] = '';
            
            parent::doSave($conn);
            
            //$contentEncryptPass = $this->values['pass'];
            
            $contentEncryptArr = json_decode($contentEncrypt, true);
            //$contentEncryptPassArr = json_decode($contentEncryptPass, true);
            
            if ($contentEncryptArr && count($contentEncryptArr))
            {
               foreach ($contentEncryptArr as $keyId => $data)
               {
                  $encContent = new arWallMessageEnc();
                  $encContent->setUserId($keyId);
                  $encContent->setWallMessageId($object->getId());
                  $encContent->setContent($data);
                 // $encContent->setPass($contentEncryptPassArr[$keyId]);
                  $encContent->save($conn);
                  
                  if ($userProfile->getId() == $keyId)
                  {
                    $object->EncContent = $encContent; 
                  }
                   
               }
            }
        }
        else
        {
            parent::doSave($conn);
        }
        
        $this->saveRelated($object, $sysUser, $conn);
        $this->saveGroupLists($object, $conn);
        
        $this->Links = Doctrine::getTable('arWallLink')
                  ->getMessageLinks($object->getId(), $userProfile->getId(),$conn);

        $this->Gmaps = Doctrine::getTable('arGmapsLocate')
                  ->getMessageLocations($object->getId(), $userProfile->getId(),$conn);
        

        $this->Diagrams = Doctrine::getTable('arDiagram')
                ->getByMenssage($object->getId(), $userProfile->getId(), $conn);
        
        $this->LavernaDocs = Doctrine::getTable('arLavernaDoc')
                                ->getByMenssage($object->getId(), $userProfile->getId(), $conn);
        
        
        return $object;
     }
     
     /**
   * guarda los registros de objetos relacionados con el mensaje
   * 
   * @param type $user 
   */
  private function saveRelated($messageObj, $user, $conn)
  {
     
     $this->saveRelatedTableNull($messageObj,$user,'arDropFile', $conn);
      
     $this->saveRelatedTable($messageObj,$user,'arWallLink', $conn);
     //$this->saveRelatedTable($messageObj,$user,'arWallUpload', $conn);
     //$this->saveRelatedTable($messageObj,$user,'arWallImageUploads', $conn);
     $this->saveRelatedTableNM($messageObj,$user,'arTag', 'setTagId', $conn);
     $this->saveRelatedTableNM($messageObj,$user,'arDiagram','setDiagramId', $conn);
     $this->saveRelatedTableNM($messageObj,$user,'arDoc','setDocId', $conn);
     $this->saveRelatedTableNM($messageObj,$user,'arGmapsLocate','setLocateId', $conn);
     $this->saveRelatedTableNM($messageObj,$user,'arLavernaDoc','setLavernaId', $conn);
  }
  
  private function saveGroupLists($messageObj, Doctrine_Connection $conn)
  {
      $groupLists = $this->values['groups'];
      if ($groupLists && is_array($groupLists) && (count($groupLists) > 0))
      {
          foreach ($groupLists as $list)
          {
              $arWallMessageHasProfileList = new arWallMessageHasProfileList();
              $arWallMessageHasProfileList->setWallMessageId($messageObj->getId());
              $arWallMessageHasProfileList->setProfileListId($list);
              $arWallMessageHasProfileList->save($conn);
          }
      }
  }
  
  private function saveRelatedTableNull($messageObj, $user, $table, $conn)
  {
      $items =  Doctrine_Core::getTable($table)->getUnassociatedAll($user->getGuardUser()->getId(), $conn);
     
      if ($items && (count($items) > 0))
      {
                   
        foreach ($items as $item)
        {
            //si esta listo lo salva 
            if ($item->getReady() > 0)
            {
              $item->setWallMessageId($messageObj->getId());
              $item->save($conn);  
            }
            else
            {
               $item->delete($conn);   
            }
         }
      }
      
  }
  
  private function saveRelatedTableNM($messageObj, $user, $table, $field, $conn)
  {
      $related = $user->getAttribute($table, array(),'wall');
      
       if ((isset($related)) 
                 && (is_array($related)) 
                 && (count($related) > 0))
         {
               $relatedItems =  Doctrine_Core::getTable($table)->getByIds($related);
               
               if ($relatedItems
                       && (count($relatedItems) > 0))
               {
                   
                    foreach ($relatedItems as $relatedItem)
                    {
                      //si el item es valido y no tiene
                      //problemas para ser enlazado
                      //if ($relatedItem->hasValid())
                      //{
                        $itemClass =$table.'HasArWallMessage';
                        $item = new $itemClass();
                        $item->$field($relatedItem->getId());
                        $item->setUserId($user->getGuardUser()->getId());
                        $item->setWallMessageId($messageObj->getId());
                        $item->save($conn);
                      //}
                      //else 
                      //{
                      //   $relatedItem->delete($conn); 
                      //}
                      
                    }
               }
                 
         } 
         // pone a cero el array
         $user->setAttribute($table, array(), 'wall');
      
  }
  
  private function saveRelatedTable($messageObj, $user, $table, $conn)
  {
         // items
         $uploads = $user->getAttribute($table, array(),'wall');
         if ((isset($uploads)) 
                 && (is_array($uploads)) 
                 && (count($uploads) > 0))
         {
               $uploadsItems =  Doctrine_Core::getTable($table)->getByIds($uploads);
              
               if ($uploadsItems
                       && (count($uploadsItems) > 0))
               {
                   
                    foreach ($uploadsItems as $upload)
                    {
                      $upload->setWallMessageId($messageObj->getId());
                      $upload->save($conn);
                    }
               }
                 
         } 
         // pone a cero el array
         $user->setAttribute($table, array(), 'wall');
    }
    
  }