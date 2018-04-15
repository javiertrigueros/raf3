<?php

/**
 * PluginarDiagramEditor form.
 *
 * @package    oryxEditor
 * 
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    S0.1
 */
abstract class PluginarDiagramForm extends BasearDiagramForm
{
  public function setup()
  {
      parent::setup();
      
      unset(
        $this['user_id'],
        $this['guid'],
        $this['messages_list'],
        $this['file_name'],
        $this['wall_message_id'],
        $this['slug'],
        $this['created_at'],
        $this['updated_at']
      );
    
      $this->widgetSchema['id']      = new sfWidgetFormInputHidden();
      $this->validatorSchema['id']   = new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false));
      
      $this->widgetSchema['title']           = new sfWidgetFormInputText();
      $this->validatorSchema['title']        = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['type']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['type']        = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['json']           = new sfWidgetFormInputHidden();
      $this->validatorSchema['json']        = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['data_image']    = new sfWidgetFormInputHidden();
      $this->validatorSchema['data_image'] = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['share']       = new sfWidgetFormInputHidden();
      $this->validatorSchema['share']    = new sfValidatorInteger(array('required' => true));
      
      $this->widgetSchema['is_favorite']   = new sfWidgetFormInputHidden();
      $this->validatorSchema['is_favorite']   = new sfValidatorInteger(array('required' => false));
      
      $this->widgetSchema['trash']         = new sfWidgetFormInputHidden();
      $this->validatorSchema['trash']         = new sfValidatorInteger(array('required' => false));
      
      //$this->widgetSchema['user_id']           = new sfWidgetFormInputHidden();
      //$this->validatorSchema['user_id']        = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => true));
      
      if (sfConfig::get('app_arquematics_encrypt'))
      {
          $this->widgetSchema['pass']           = new sfWidgetFormInputHidden();
          $this->validatorSchema['pass'] = new sfValidatorEncryptContent(array('required' => true));    
      }
    
      /*
      $this->widgetSchema['file_name'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['file_name'] = new sfValidatorDiagram(array(
          'required' => true,
          'path' => sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arDiagram'));
      */
      $this->widgetSchema->setNameFormat('diagram[%s]');
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
  
  protected function doSaveFavorite($object, $con)
  {
       $isFavorite = ((int)$this->values['is_favorite'] > 0);
       $aUserProfile =  $userProfile = $this->getOption('aUserProfile');
       //el documento esta en favoritos
       $isSetFavoriteTable = Doctrine_Core::getTable('arDiagramFavorite')
                            ->hasDocProfile($object->getId(), $aUserProfile->getId(),$con);
       
       if ($isFavorite && !$isSetFavoriteTable)
       {
         $arLavernaDocFavorite = new arDiagramFavorite();
         $arLavernaDocFavorite->setUserId($aUserProfile->getId());
         $arLavernaDocFavorite->setDiagramId($object->getId());
         $arLavernaDocFavorite->save($con);
       }
       //lo quita favorito
       else if (!$isFavorite && $isSetFavoriteTable)
       {
           $arLavernaDocFavorite = Doctrine_Core::getTable('arDiagramFavorite')
                                    ->getByDocProfile($object->getId(), $aUserProfile->getId(),$con);
           
           $arLavernaDocFavorite->delete($con);
       }
   }
   
   protected function doSaveTrash($object, $con)
   {
       $trash = (int)$this->values['trash'];
       $aUserProfile =  $userProfile = $this->getOption('aUserProfile');
       //el documento esta en la papelera
       $isAtTrash = Doctrine_Core::getTable('arDiagramTrash')
                    ->hasDocProfile($object->getId(), $aUserProfile->getId(),$con);
       
       if ($trash && !$isAtTrash)
       {
         $arLavernaDocTrash = new arDiagramTrash();
         $arLavernaDocTrash->setUserId($aUserProfile->getId());
         $arLavernaDocTrash->setDiagramId($object->getId());
         $arLavernaDocTrash->save($con);
       }
       //lo quita de la papelera
       else if (($trash === 0) && $isAtTrash)
       {
           $arLavernaDocTrash = Doctrine_Core::getTable('arDiagramTrash')
                                ->getByDocProfile($object->getId(), $aUserProfile->getId(),$con);
           $arLavernaDocTrash->delete($con);
       }
   }
  
  protected function doSave($con = null)
  {
       $aUserProfile =  $userProfile = $this->getOption('aUserProfile');
       $object = $this->getObject();
       
       //introduce el usuario solo
       //al crear el documento
       if ($this->getOption('setUser'))
       {
         $object->setUserId($aUserProfile->getId());  
       }
       
       if ($object->isNew())
       {
         //guarda el nuevo guid
         $object->setGuid(arDiagramForm::v4());  
       }
       
       $ret = parent::doSave($con);
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
                       $arDiagramEnc = new arDiagramEnc();
            
                       $arDiagramEnc->setUserId($key);                 
                       $arDiagramEnc->setDiagramId($object->getId());
                       $arDiagramEnc->setContent($data);
            
                       $arDiagramEnc->save($con);  
                    }
                }
            }
            
             return $ret;
            
           }
           catch (Exception $e){return null;}
    }
    else 
    {
      return $ret; 
    }
    
  }

}
