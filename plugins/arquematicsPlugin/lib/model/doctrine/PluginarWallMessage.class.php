<?php

/**
 * PluginarWallMessage
 * 
 * modelo de datos de los messages
 * 
 * @package    arquematics
 * @subpackage model
 * @author     Javier Trigueros Martinez de los Huertos javiertrigueros@arquematics.com
 * @version    0.1
 */
abstract class PluginarWallMessage extends BasearWallMessage
{
    private $groupLists = array();
    
    /**
     * mira si puede borrar un objeto arWallMessage
     * 
     * @return <boolean>: true si puede borrar el message del wall 
     */
    public function canDelete()
    {

        if (sfConfig::get('app_arquematics_encrypt', false))
        {
          return ($this->User->getId() == $this->EncContent->getUserId());  
        }
        else
        {
          //yes bad ...
          $aUser = sfContext::getInstance()->getUser();
          
          return ($this->User->getId() == $aUser->getId());
        }
        
    }
  /*
  public function getTagIds()
  {
      
     $listItems = $this->Tags;
     $countItems = count($listItems);
    
     $dataIds = '[';
     
     if ($countItems > 0)
     {
       $i = 0;
       foreach ($listItems as $tag)
       {
          $dataIds .= $tag->getId();
          $i++;
          $dataIds .= ($i == $countItems)?'':',';
       }
     }
     $dataIds .=  ']';
     
     return $dataIds;
  }*/
   /**
   *
   * borra un mensaje y los comentarios
   * 
   * @param Doctrine_Connection $conn
   * @return <type>
   */
  public function delete(Doctrine_Connection $conn = null)
  {
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        
        $aBlogItems = Doctrine::getTable('aBlogItem')
                ->getMessage($this->id);
        
        if ($aBlogItems && (count($aBlogItems) > 0))
        {
            foreach ($aBlogItems as $aBlogItem)
            {
              $aBlogItem->delete($conn);
            }
        }

        $dropFiles = $this->getDropFiles();
        
        if ($dropFiles && (count($dropFiles) > 0))
        {
            foreach ($dropFiles as $dropFiles)
            { 
                $dropFiles->delete($conn);
            }
        }
        
        $ret = parent::delete($conn);

        $conn->commit();

        return $ret;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
        
        return false;
    }
  }
  /**
   * devuelve una cadena con el mensaje
   * que se ha encriptado para un usuario contreto
   * 
   * @param <authUser $authUser>
   * @return <String> 
   */
  public function getEncryptTxt($authUser)
  {
     $arWallMessageEnc = Doctrine_Core::getTable('arWallMessageEnc')
                        ->retrieveContentById($this->getId(), $authUser->getId());
     
     if ($arWallMessageEnc &&  is_object($arWallMessageEnc))
     {
         return $arWallMessageEnc->getContent();
     }
     else return '';
  }
  /**
   * devuelve las descargas del mensaje
   * 
   * @return <array>
   */
  public function getImages()
  {
      /*
      return Doctrine_Core::getTable('arWallUpload')
              ->getDocuments($this->getId());*/
      
      
    $images = array();
    $messageId = $this->getId();
    $con = Doctrine_Manager::getInstance()->connection();
    
    
    $sql = "select id, file_name, slug, type  FROM (
            (
                SELECT au.id as id, au.file_name as file_name, au.slug as slug, 'wall' as type
                FROM ar_wall_image_upload au
                WHERE au.wall_message_id = $messageId
            ) 
            UNION ALL
            (
                SELECT ad.id as id, ad.file_name as file_name, ad.slug as slug, 'diagram' as type   
                FROM ar_diagram ad
                WHERE ad.wall_message_id = $messageId
            )
        ) data ";
    
    //$sql = "SELECT id, file_name FROM ar_wall_image_uploads au WHERE au.wall_message_id = $messageId";

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $images = $q->fetchAssoc($sql);
    
    
    return $images;
    
   
  }
  /**
   * listado de documentos asociados con un mensaje
   * 
   * @return <array of arDoc>
   */
  public function getDocuments()
  {
      return Doctrine_Core::getTable('arDoc')
              ->getDocuments($this->getId());
      
  }
 
  
  /**
   * array de aBlogItems asociados 
   * 
   * @return <array of aBlogItem>
   */
  public function getBlogItems()
  {
      return Doctrine::getTable('aBlogItem')
              ->getMessage($this->getId());
  }
  
  /**
   * listado de direcciones asociadas a un mensaje
   * @return <array of arGmapsLocate>
   */
  public function getLocations($profileId)
  {
      
      return Doctrine_Core::getTable('arGmapsLocate')
              ->getMessageLocations($this->getId(), $profileId);
  }
  
  /**
   * devuelve la imagen el registro de la imagen del usuario
   * 
   * @return <arProfileUpload>
   */
  public function getImageUserProfile()
  {
     return Doctrine_Core::getTable('arProfileUpload')->retrieveByUserId($this->getUserId());
  }
  
   /**
   *
   * @param Doctrine_Connection $conn
   * @return <boolean>
   */
  /*
  public function save(Doctrine_Connection $conn = null)
  {
   
    //añade la id del usuario que crea la entrada
    if (sfContext::hasInstance())
    {
        $user = sfContext::getInstance()->getUser();
        if (is_object($user) && $user->isAuthenticated())
        {
         $this->setUserId($user->getGuardUser()->getId());
        }
    }

    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
           $contentEncrypt = $this->getMessage();
           
           $this->setMessage('');
           
           parent::save($conn);
           
           $contentEncryptArr = json_decode($contentEncrypt, true);
           
           if ($contentEncryptArr && count($contentEncryptArr))
           {
               foreach ($contentEncryptArr as $keyId => $data)
               {
                  $encContent = new arWallMessageEnc();
                  $encContent->setUserId($keyId);
                  $encContent->setWallMessageId($this->getId());
                  $encContent->setContent($data);
                  $encContent->save($conn);
                  
                  if ($this->getUserId() == $keyId)
                  {
                    $this->EncContent = $encContent; 
                  }
               }
           }
        }
        else
        {
          parent::save($conn);  
        }
        
        $this->saveRelated($user, $conn);
        $this->saveGroupLists($conn);
        
        //carga automática de objetos, relacionados con arWallMessage
        
        $this->Links = Doctrine::getTable('arWallLink')
                  ->getMessageLinks($this->getId(), $user->getGuardUser()->getId(),$conn);

        $this->Gmaps = Doctrine::getTable('arGmapsLocate')
                  ->getMessageLocations($this->getId(), $user->getGuardUser()->getId(),$conn);
        

        $this->Diagrams = Doctrine::getTable('arDiagram')
                ->getByMenssage($this->getId(), $user->getGuardUser()->getId(), $conn);
        
        //$this->Comments = null;
        $this->LavernaDocs = Doctrine::getTable('arLavernaDoc')
                                ->getByMenssage($this->getId(), $user->getGuardUser()->getId(), $conn);
        
        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
  }
   * 
   */
  
  
  public function getVectorialImages($userProfileId)
  {
      return Doctrine::getTable('arDiagram')->getByMenssage($this->getId(), $userProfileId);
  }
  
}