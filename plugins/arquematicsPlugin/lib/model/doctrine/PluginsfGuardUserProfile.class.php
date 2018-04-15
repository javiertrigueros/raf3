<?php

/**
 * PluginsfGuardUserProfile
 * 
 * @package    arquematicsPlugin
 * @subpackage models
 * @author     Javier Trigueros Martínez de los Huertos javiertrigueros@gmail.com
 * @version    0.1 2010-03-29 
 */
abstract class PluginsfGuardUserProfile extends BasesfGuardUserProfile
{
   protected $userImage = null;
   
   protected $locate = null;
   
   protected $listItems = null;
   
   protected $arFriend = false;
   
   /**
    * Alias de getFirstLast
    * 
   * Returns the first and last name of the user concatenated together
   *
   * @return string $name
   */
   public function getName()
   {
       
    return trim($this->getFirstLast());
   }
   
   public function getUserInfo()
   {
       $profileImage = $this->getProfileImage();
       $urlIcon = ($profileImage)?
               url_for("@user_resource?type=arProfileUpload&name=".$profileImage->getBaseName()."&format=".$profileImage->getExtension()."&size=mini"):
               '/arquematicsPlugin/images/unknown.mini.jpg';
       
       $urlIconAvatar = ($profileImage)?
                url_for("@user_resource?type=arProfileUpload&name=".$profileImage->getBaseName()."&format=".$profileImage->getExtension()."&size=avatarwall"):
               '/arquematicsPlugin/images/unknown.avatarwall.jpg';
       
       $urlIconSmall = ($profileImage)?
                url_for("@user_resource?type=arProfileUpload&name=".$profileImage->getBaseName()."&format=".$profileImage->getExtension()."&size=small"):
               '/arquematicsPlugin/images/unknown.small.jpg';
       
       
       return array(
         'id' => $this->getId(),
         'firstlast' =>  $this->getFirstLast(),
         'username'   => $this->getUsername(),
         'iconMiniURL' => $urlIcon,
         'iconSmallURL' => $urlIconSmall,
         'iconAvatarURL' => $urlIconAvatar,
         'url' => url_for('@user_profile?username='.$this->getUsername()));
       
   }
   
   public function getUsersListByStringLimited($searchText)
   {
       $list = Doctrine_Core::getTable('sfGuardUserProfile')
                                    ->getUsersListByStringLimited($this->getId(), $searchText);
       $imageList = array();
                if ($list && count($list) > 0)
                {
                    foreach($list as $user)
                    {
                       $profile = Doctrine_Core::getTable('sfGuardUserProfile')
                               ->retrieveById($user['id']);
                       
                       $image = $profile->getProfileImage();

                       
                       $user['wall'] = url_for('@wall?userid='.$profile->getId());
                       $user['profile'] = url_for('user_profile',$profile);
                               
                       if ($image)
                       {
                           $user['image'] = url_for("@user_resource?type=arProfileUpload&name=".$image->getBaseName()."&format=".$image->getExtension()."&size=small");
                       }
                       else
                       {
                           $user['image'] = '/arquematicsPlugin/images/unknown.normal.jpg';
                       }
                       
                       $imageList[] = $user;
                    }
                
                }
                
         return $imageList;
   }
   
   
   /**
    * listado de las claves publicas de los usuarios
    * @return <JSON array string>
    */
   public function getEncryptKeys()
   {
       $arrayAccepted = Doctrine::getTable('sfGuardUserProfile')
               ->getUsersAcceptedPublicKeys($this->getId());
      
       $arrayAccepted[] = array('id' => $this->getId(), 'public_key' => $this->getPublicKey());
       
       return $arrayAccepted;
   }
   /**
   * devuelve la imagen del usuario
   * 
   * @param <$forceReload boolean>: true fuerza a cargar otra vez el objeto imagen
   * 
   * @return <arProfileUpload>
   */
  public function getProfileImage($forceReload = false)
  {
     if (($forceReload) || ($this->userImage == null))
     {
        $this->userImage = Doctrine_Core::getTable('arProfileUpload')->retrieveByUserId($this->getUserId());
     }
     return $this->userImage;
  }
  /**
   * listado de arProfileList de listas de las que es administrador
   * menos la principal
   * 
   * @return <array arProfileList>
   */
  public function getAdminListNoMain() 
  {
     if ($this->listItems == null)
     {
       $this->listItems =  Doctrine_Core::getTable('arProfileList')->getAdminListNoMain($this->getId());
     }
     return $this->listItems;
  }
  
  /**
   * ids de las listas de un usuario
   * 
   * @return array if int
   */
  public function getListIds()
  {
      $list = Doctrine_Core::getTable('arProfileList')
               ->getAdminList($this->getId());
      
      if ($list && is_array($list))
      {
         return $list;
      }
      else if ($list && is_int($list))
      {
         return array($list); 
      }
      else return array();
  }
  /**
   * devuelve los usuarios que quieren agregarle
   * 
   * @return <int>
   */
  public function countFriendRequest()
  {
      //return Doctrine_Core::getTable('arProfileList')->countFriendRequest($this->getId());
  
      return Doctrine_Core::getTable('sfGuardUserProfile')->countFriendRequest($this->getId());
  }
  
  public function getFriendRequest()
  {
      //return Doctrine_Core::getTable('arProfileList')->getFriendRequest($this->getId());
      return Doctrine_Core::getTable('sfGuardUserProfile')->friendRequest($this->getId());
  }
  
  
  
  /**
   * listas de usuario, preparadas para usar en un widget choice
   * @return <array> 
   */
  public function getAdminListChoices()
  {
      $count = $this->countAdminList();
      $userLists = $this->getAdminListNoMain();
      $ret = array();
      if ($count > 0)
      {
          foreach ($userLists as $list)
          {
              $ret[$list->getId()] = $list->getName();
          }
      }
      
      return $ret;
  }
  /**
   * cuenta el numero de listas de las que es administrador
   * @return <int> 
   */
  public function countAdminList()
  {
      return Doctrine_Core::getTable('arProfileList')->countAdminList($this->getId());
  }
  /**
   * devuelve la lista principal del perfil
   * @return <arProfileList>
   */
  public function getMainList($conn = false)
  {
    return Doctrine_Core::getTable('arProfileList')->getMainList($this->getId(), $conn);   
  }
  
  /**
   * listado de usuarios aceptados
   * 
   * @return <array of sfGuardUserProfile>
   */
  public function getUsersAccepted($page,  $isAccept = true)
  {
     return Doctrine_Core::getTable('sfGuardUserProfile')
             ->getUsersAccepted($this->getId(),$page, $isAccept);   
  }
 
  
  
  /*
  private function doCreateMainList($conn)
  {
      $listAll = new arProfileList();
      $listAll->setName('All');
      $listAll->setIsAll(true);
      $listAll->setProfileId($this->getId());
      $listAll->save($conn); 
      
      /*
      $arProfileListHasProfile = new arProfileListHasProfile();
      $arProfileListHasProfile->setProfileId($this->getId());
      $arProfileListHasProfile->setProfileListId($listAll->getId());
      $arProfileListHasProfile->setIsAdmin(true);
      $arProfileListHasProfile->setIsAccept(true);
      $arProfileListHasProfile->setIsIgnore(false);
      $arProfileListHasProfile->setIsBlock(false);
      $arProfileListHasProfile->save($conn);
      
      //return $listAll;
  }*/
  /*
  public function createMainList()
  {
    $conn = $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {   
       $listAll = $this->doCreateMainList($conn);
       
       $conn->commit();

       return $listAll;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
    
  }*/
  
  /**
   *
   * password de usuario
   * 
   * @param <string $password>
   * @return <string>
   */
  public function setPassword($password)
  {
     if (!$password && 0 == strlen($password))
    {
      return;
    }

    $algorithmAsStr = 'sha1';
    
    if (!is_callable($algorithmAsStr))
    {
      throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
    }

    $this->_set('password', call_user_func_array($algorithmAsStr, array($password)));
  }
  
  /**
   * crea una nueva lista, con varios usuarios si procede
   * 
   * @param <string $listName>
   * @param <array $profileList>
   * @throws Exception
   */
  public function createList($listName, $profileIds)
  {
       $conn = $this->getTable()->getConnection();
       $conn->beginTransaction();
       
       try
       {
         //crea la lista
         $list = arProfileList::createList($listName,$this->getId(),  $conn);
        
         
         if ($profileIds && is_array($profileIds))
         {
             foreach ($profileIds as $profileId)
             {
               
               arFriend::addRequest($this->getId(), $profileId, $conn);
               
               $list->addFriend($profileId, $conn);
               
             }
         }
         
         
         $conn->commit();
         
         return $list;
       }
       catch (Exception $e)
       {
        $conn->rollBack();
        throw $e;
       }
  }
 
  public function deleteList($listId)
  {
     $conn = $this->getTable()->getConnection();
     $conn->beginTransaction();
     
     try
     {

       $list = Doctrine_Core::getTable('arProfileList')->retrieveById($listId, $conn);
      
       $list->delete($conn);
       
       $conn->commit();
       
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    } 
     
  }
  
  public function deleteFriendRequest($listId, $profileId)
  {
    $conn = $this->getTable()->getConnection();
    $conn->beginTransaction();
    
    try
    {
       $list = Doctrine_Core::getTable('arProfileList')->retrieveById($listId, $conn);
       
       $list->deleteProfile($profileId, $conn);
     
       $conn->commit();
       
       return $list;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    } 
  }
  /**
   * agrega un amigo a una lista 
   * o simplemente lo agrega (sin aceptarlo aún)
   * 
   * @param int $listId
   * @param int $profileId
   */
  public function addFriendRequest( $profileId, $listId = false, $conn = false)
  {
    if ($conn)
    {
       if ($listId && ($listId > 0))
       {
            $list = Doctrine_Core::getTable('arProfileList')->retrieveById($listId, $conn);
       
            $list->addFriend($profileId, $conn); 
        }
       
        arFriend::addRequest($this->getId(), $profileId, $conn);
        
        return ($listId && ($listId > 0))?$list:false;
    }
    else 
    {
       $conn = $this->getTable()->getConnection();
       $conn->beginTransaction();
       try
       {
            if ($listId && ($listId > 0))
            {
                $list = Doctrine_Core::getTable('arProfileList')->retrieveById($listId, $conn);
       
                $list->addFriend($profileId, $conn); 
            }
       
            arFriend::addRequest($this->getId(), $profileId, $conn);
       
            $conn->commit();
       
            return ($listId && ($listId > 0))?$list:false;
        }
        catch (Exception $e)
        {
            $conn->rollBack();
            throw $e;
        } 
    }
  }
  
  public function removeRequest($toProfileId,  $conn = false)
  {
      arFriend::removeRequest($this->getId(), $toProfileId, $conn);
      
      $this->deleteFromAllUserList($toProfileId,$conn);
  }
  
  private function deleteFromAllUserList($toProfileId,$conn)
  {
      $profileList = $this->getAdminListNoMain();
      
      if ($profileList && (count($profileList) > 0))
      {
        foreach ($profileList as $list)
        {
            $list->deleteProfile($toProfileId, $conn);
        }
      } 
  }
  
  public function confirmRequest( $toProfileId, $profileListIds, $confimStatus, $conn = false)
  {
    if (!$conn)
    {
        $conn = $this->getTable()->getConnection();
        $conn->beginTransaction();
    
        try
        {
            arFriend::confirmRequest($this->getId(), $toProfileId, $confimStatus, $conn);
        
            if ($confimStatus)
            {
                if ($profileListIds && is_array($profileListIds))
                {
                    $profileList = Doctrine::getTable('arProfileList')
                                    ->getByIds($profileListIds);
            
                    if ($profileList && (count($profileList) > 0))
                    {
                        foreach ($profileList as $list)
                        {
                            if (!$list->hasProfileId($toProfileId, $conn))
                            {
                               $list->addFriend($toProfileId, $conn); 
                            }       
                        }
                    }
                }
            }
            else
            {
                $this->deleteFromAllUserList($toProfileId,$conn);
            }
            
            $conn->commit();
            
        }
        catch (Exception $e)
        {
            $conn->rollBack();
            throw $e;
        }
        
    }
    else {
        
        arFriend::confirmRequest($this->getId(), $toProfileId, $confimStatus, $conn);
        
        if ($confimStatus)
        {
            if ($profileListIds && is_array($profileListIds))
            {
                $profileList = Doctrine::getTable('arProfileList')->getByIds($profileListIds);
            
                if ($profileList && (count($profileList) > 0))
                {
                    foreach ($profileList as $list)
                    {
                        if (!$list->hasProfileId($toProfileId, $conn))
                        {
                            $list->addFriend($toProfileId, $conn);
                        }
                    }
                }
            }
        }
        else
        {
            $this->deleteFromAllUserList($toProfileId,$conn);
        }
        
    } 
  }
  
 
  /**
   * 
   * devuelve la localización actual del usuario
   * 
   * @param type $forceReload
   * @return type 
   */
  public function getCurrentLocate($forceReload = false)
  {
     if (($forceReload) || ($this->locate == null))
     {
        $this->locate = Doctrine_Core::getTable('arGmapsLocate')->retrieveLastByProfileId($this->getId());
     }
     return $this->locate;
  }
  /**
   * mira si el usuario esta bloqueado
   * es decir no puede cambiar de estado
   * 
   * @return <boolean>
   */
  public function isBlock()
  {
      //si esta aceptado siempre puede cambiar de estado
      //de lo contrario podrá cambiar si no es el mismo
      //el que hace la petición
      return  $this->Friends->getIsAccept()?
                    false:
                    $this->getId() == $this->Friends->getRequestId();
  }
  
  
   /**
   * es el listener de user.filter_register
   * 
   * (se ejecuta cuando se ha producido el evento
   * crear usuario)
   * 
   * @param <sfEvent $event>
   * 
   */  
  static public function doRegister(sfEvent $event)
  {
    $registerAction = $event->getSubject();
    $params = $event->getParameters();
    
    $params = $registerAction->form->getValues();
    /*
    $userClone = new ofUser();
    $userClone->setUsername($params['username']);
    $userClone->setEncryptedPassword($params['password']);
    $userClone->setEmail($params['email_address']);
    $userClone->setName($params['first_name']);
    
    $userClone->save(); 
    */
    return $registerAction->form;
  }
  /**
   * ATENCIÓN: Si la clave no se ha salvado en el sistema,
   * será valida, sólo si la cadena no esta en blanco y es mayor de 20.
   * 
   * Si se ha salvado la clave pública, es valida, si se envia una cadena en blanco.
   * 
   * TODO: Posiblemente, ese bien hacer alguna validación extra.
   * 
   * @param <string $publickey>
   * @return <boolean>: true si esta bien
   */
  public function checkPublicKey($publickey)
  {
      $ret = $this->getKeySaved();
      if ($ret)
      {
          $ret = (strlen(trim($publickey)) === 0); 
      }
      else
      {
         $ret = (strlen(trim($publickey)) > 20); 
      }
      
      return $ret;
  }
  /**
   * valida la clave privada enviada por el usuario
   * 
   * @param <string $privateKey>: by ref
   * 
   * @return <boolean>: true si esta bien
   */
  public function checkPrivateKey(&$privateKey)
  {
      $ret = true;
      
      if (sfConfig::get('app_arquematics_send_private_key',false))
      {
          
        $ret = ($this->getKeySaved()
               && (strlen(trim($privateKey)) === 0));
       
        $info = '';
       
        if (!($this->getKeySaved()) 
            &&  (strlen(trim($privateKey)) > 0))
        {
            try {
            
            $privateKeyArr = json_decode($privateKey);
            
            $ret = (count($privateKeyArr) > 0);
            
            if ($ret)
            {
                $privateMailKeyString = base64_decode($this->getPrivateMailKey());
                
                $privateMailKey = openssl_pkey_get_private($privateMailKeyString);
                
                foreach ($privateKeyArr as $data)
                {
                    $ret = $ret && base64_decode($data->data);
                    if ($ret)
                    {
                        $data64chars = base64_decode(base64_decode($data->data));
                        
                        $decryptedData64 = '';

                        if (openssl_private_decrypt ( $data64chars , $decryptedData64 , $privateMailKey)
                                && ctype_xdigit($decryptedData64))
                        {
                          $info .= $decryptedData64. "\n";
                        }
                        else {
                            throw new Exception('openssl_private_decrypt error');
                        }
                    }
                }
                
                openssl_pkey_free($privateMailKey);
                
                $privateKey = $info;
            }
        }
        catch (Exception $e)
        {
            $ret = false;
        } 
       }
      }
      
      return $ret;
  }
  
  /**
   * devuelve cadena aleatoria usando str_shuffle (reordenar array o cadena de forma aleatoria) 
   * 
   * @param [String $numAlpha] : número de carateres alphanuméricos
   * @param [String $numNonAlpha] :caracteres no alphanuméricos
   * @return String
   */
  function generatePasswd($numAlpha=20,$numNonAlpha=5)
  {
    $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';
    return str_shuffle(
      substr(str_shuffle($listAlpha),0,$numAlpha) .
      substr(str_shuffle($listNonAlpha),0,$numNonAlpha)
    );
  }
  
   /**
   * es el listener de arUser.publicKeyAdd
   * 
   * se ejecuta cuando se ha producido un evento
   * de  autentificacion
   * 
   * @param <sfEvent $event>
   * 
   */  
  static public function publicKeyAdd(sfEvent $event)
  {
    $profile = $event->getSubject();
    $params = $event->getParameters();
    
    if ($profile 
            && is_object($profile) 
            && isset($params['public_key'])
            && (!$profile->getKeySaved()))
    {
       
        $conn =  Doctrine::getTable('sfGuardUserProfile')->getConnection();
        $conn->beginTransaction();
     
        try
        {
       
            $profile->setKeySaved(true);
            $profile->setPublicKey(trim($params['public_key']));
            $profile->setStoreKey(trim($params['store_key']));
            
            $profile->save($conn);
        
            $conn->commit();
        }
        catch (Exception $e)
        {
            $conn->rollBack();
            throw $e;
        }
        
    }
  }
  
  static public function sendPrivateKey(sfEvent $event)
  {
    $profile = $event->getSubject();
    $params = $event->getParameters();
    
    if ($profile && is_object($profile) )
    {
       
        $conn =  Doctrine::getTable('sfGuardUserProfile')->getConnection();
        $conn->beginTransaction();
     
        try
        {
       
            
            $profile->save($conn);
        
            $conn->commit();
        }
        catch (Exception $e)
        {
            $conn->rollBack();
            throw $e;
        }
        
    }
  }
  
  
  /**
   * tags de usuario
   * 
   * @param <int $userId>
   * @return Doctrine_Collection
   */
  public function getUserTags()
  {
    return Doctrine_Core::getTable('arTag')
            ->getUserTags($this->getId());  
  }
  
  /**
   * tags de usuario
   * 
   * @param <int $userId>
   * @return Doctrine_Collection
   */
  public function getUserTagsCount()
  {
    return Doctrine_Core::getTable('arTag')
            ->getUserTagsCount($this->getId());  
  }
  /**
   * ultima localización 
   * 
   * @param <int $toProfileId> : $toProfileId se usa para dar el contenido 
   *                                adecuado para desencriptar
   * @return arGmapsLocate
   */
  public function getLastLocation($toProfileId)
  {
      return Doctrine::getTable('arGmapsLocate')
              ->retrieveLast($this->getId(), $toProfileId);
  }
  
   /**
   *
   * @param Doctrine_Connection $conn
   * @return <boolean>
   */
  public function save(Doctrine_Connection $conn = null)
  {
   
  
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {       
        parent::save($conn);

        if (!Doctrine::getTable('arFriend')
                ->hasStatus(
                        $this->getId(),
                        $this->getId(),
                        $conn))
        {
            $arFriend = new arFriend();
        
            $arFriend->setProfileId($this->getId());
            $arFriend->setFriendId($this->getId());
            $arFriend->setIsAccept(true);
            $arFriend->setIsIgnore(false);
            //$arFriend->setIsBlock(false);
        
            $arFriend->save($conn);
        }
        
        
        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
  }
  /**
   * cuenta el número de mensajes enviados por un usuario
   * @return <int>
   */
  public function countUserMessages()
  {
      return Doctrine::getTable('arWallMessage')
              ->countUserMessage($this->getId());
  }
  /**
   * usuarios aceptados mutuamente para el profile y $profileIdB
   * 
   * @param <int $profileIdB>
   * @return <int>
   */
  public function countMutualUsersAccepted($profileIdB)
  {
     return Doctrine::getTable('sfGuardUserProfile')
        ->queryUsersAcceptedForCount($this->getId(),$profileIdB);
  }
  
  public function getMutualUsersAccepted($profileIdB)
  {
     return Doctrine::getTable('sfGuardUserProfile')
        ->queryUsersAcceptedForCount($this->getId(),$profileIdB); 
  }
  
  public function isUserAccept($aUserProfileId)
  {
      $ret = false;
      
      if ($aUserProfileId)
      {
         $this->arFriend = Doctrine::getTable('arFriend')
              ->getStatus($this->getId(), $aUserProfileId);  
      
         if ($this->arFriend)
         {
            $ret = ($this->arFriend->getIsAccept()
               || ($this->arFriend->getRequestId() == $aUserProfileId));
         }
      }
      
      return $ret;
  }
  
   
  public function canAddUser($aUserProfileId = false)
  {
      if ($aUserProfileId)
      {
         $this->arFriend = Doctrine::getTable('arFriend')
              ->getStatus($this->getId(), $aUserProfileId);  
      }
     
      if ($this->arFriend)
      {
        return (!$this->arFriend->getIsAccept()
               && $this->arFriend->getIsIgnore()
               && ($this->arFriend->getRequestId() == $aUserProfileId));
      }
      else return true;
  }
  
  public function canRemoveRequest($aUserProfileId = false)
  {    
      if ($aUserProfileId)
      {
         $this->arFriend = Doctrine::getTable('arFriend')
              ->getStatus($this->getId(), $aUserProfileId);  
      }
      
      if ($this->arFriend)
      {
         return (!$this->arFriend->getIsAccept()
               && !$this->arFriend->getIsIgnore()); 
      }
      else return false;
  }
  
  public function canRemoveSuscriptor($aUserProfileId = false)
  {
      if ($aUserProfileId)
      {
         $this->arFriend = Doctrine::getTable('arFriend')
              ->getStatus($this->getId(), $aUserProfileId);  
      }
      
      if ($this->arFriend)
      {
        return ($this->arFriend->getIsAccept()
               && !$this->arFriend->getIsIgnore());  
      }
      else return false;
  }
  
  public function infoD()
  {
      return '|'.$this->getId().'|';
      //return $this->getId().'|'.$this->arFriend->getIsAccept().'|'.$this->arFriend->getIsIgnore().'|'.$this->arFriend->getRequestId(); 
  }
  /**
   * devuelve total de páginas de usuarios aceptados o por aceptar
   * 
   * @param boolean $isAccepted
   * @return int
   */
  public function totalPagesFriends($isAccepted)
  {
     return Doctrine_Core::getTable('sfGuardUserProfile')
                                ->totalPagesFriends($this->getId(), $isAccepted);   
  }
  
  private function generateMailKeys()
  {
    
      $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 1024,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
      );
   
    // Create the private and public key
    $res = openssl_pkey_new($config);

    $privKey = '';
    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    
    openssl_pkey_free($res);
    
    return array('public' => $pubKey["key"], 'private' => $privKey);
      
  }
  
  public function saveMailKeys($conn = null)
  {
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {       
        $keys = $this->generateMailKeys();
        
        $this->setPublicMailKey(base64_encode($keys['public'])); 
        $this->setPrivateMailKey(base64_encode($keys['private']));
        
        parent::save($conn);
    
        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
  }
}