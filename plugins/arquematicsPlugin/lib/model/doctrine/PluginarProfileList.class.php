<?php

/**
 * PluginarProfileList
 * 
 * 
 * @package    arquematics
 * @subpackage model
 * @author     Javier Trigueros Martinez de los Huertos javiertrigueros@arquematics.com
 * @version    0.1
 */
abstract class PluginarProfileList extends BasearProfileList
{
    protected $ownAdminUserID = null;
    protected $userIDS = null;
    
    public function setOwnAdminUser($ownAdminUserID)
    {
        $this->ownAdminUserID = $ownAdminUserID;
    }
    
    public function setUserIDS($userIDS)
    {
        $this->userIDS = $userIDS;
    }
    /**
     * cuenta el numero de usuarios en la lista. 
     * 
     * @return <int>
     */
    public function count()
    {
       return Doctrine_Core::getTable('sfGuardUserProfile')
                    ->getUsersByListCount($this->getId());

    }
    
    /**
     * total de paginas de usuarios de una lista
     * 
     * @return <int>
     */
    public function totalPages()
    {
        $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
        $count = $this->count();
       
       return ceil($count / $maxResults);
    }
   
    
    public static function createList($listName, $profileId,  $conn)
    {
      //crea la lista
      $list = new arProfileList();
      $list->setName($listName);
      $list->setProfileId($profileId);
      //$list->setIsAll($isMainList);
      $list->save($conn);
      return $list;
    }
    
    public function addFriend($profileId, $conn)
    {
        $arProfileListHasProfile = new arProfileListHasProfile();
        $arProfileListHasProfile->setProfileId($profileId);
        $arProfileListHasProfile->setProfileListId($this->getId());
        $arProfileListHasProfile->save($conn);
    }
    /*
    public function addFriendRequest($profileId, $conn)
    {
        $arProfileListHasProfile = new arProfileListHasProfile();
        $arProfileListHasProfile->setProfileId($profileId);
        $arProfileListHasProfile->setProfileListId($this->getId());
        $arProfileListHasProfile->setIsAdmin(false);
        $arProfileListHasProfile->setIsAccept(false);
        $arProfileListHasProfile->setIsIgnore(false);
        $arProfileListHasProfile->setIsBlock(false);
        $arProfileListHasProfile->save($conn);
    }*/
    
    /**
     * devuelve el administrador de la lista
     * 
     * @return <sfGuardUserProfile>
     */
    public function getAdminProfile()
    {
         $q = Doctrine_Core::getTable('sfGuardUserProfile')
            ->createQuery('s')
            ->leftJoin( 's.arProfileListHasProfile as hp')
             ->where('hp.profile_list_id = ?',  $this->getId())
            ->andWhere('hp.is_admin = true');
         
         return $q->fetchOne();
    }
    /**
     * listado de perfiles de usuario en la lista
     * @return <array sfGuardUserProfile>
     */
    public function getList()
    {
         $q = Doctrine_Core::getTable('sfGuardUserProfile')
            ->createQuery('s')
            ->leftJoin( 's.arProfileListHasProfile as hp')
            ->where('hp.profile_list_id = ?',  $this->getId())
            ->select('s.id as id');
         
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    /**
     * mira si esta un perfil en la lista
     * @return <boolean>. true si esta el perfil en la lista
     */
    public function hasProfileId($profileId, $conn = false)
    {
        return Doctrine_Core::getTable('arProfileList')
                ->hasProfileId($this->getId(), $profileId, $conn);
    }
    
    /**
     * mira si esta el perfil esta en la lista y ha sido aceptado o 
     * ignorado
     * 
     * @return <boolean>. true si esta el perfil en la lista
     */
    /*
    public function isProfileIdResolve($profileId, $conn = false)
    {
        if (!$conn)
        {
           $q = Doctrine_Core::getTable('sfGuardUserProfile')
            ->createQuery('s')
            ->leftJoin( 's.arProfileListHasProfile as hp')
            ->where('hp.profile_list_id = ?',  $this->getId())
            ->andWhere('hp.profile_id = ?',  $profileId)
            ->andWhere('(hp.is_accept = true) OR (hp.is_ignore = true)')
            ->select('COUNT(s.id)');  
        }
        else {
            $q = Doctrine_Query::create($conn)
             ->from('sfGuardUserProfile s')
             ->leftJoin( 's.arProfileListHasProfile as hp')
             ->where('hp.profile_list_id = ?',  $this->getId())
             ->andWhere('hp.profile_id = ?',  $profileId)
             ->andWhere('(hp.is_accept = true) OR (hp.is_ignore = true)')
             ->select('COUNT(s.id)');  
        }
        
         
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0;
    }*/
    
    /**
     * clona un item de la lista actual a la lista especificada
     * 
     * @param <int $profileId>
     * @param <int $toListId>: lista en la que se copia el item
     * @param <conn $conn>
     */
    /*
    public function cloneProfileId($profileId, $toListId, $conn)
    {
         $sourceItem = Doctrine_Core::getTable('arProfileListHasProfile')->getByIdAndListId($profileId, $this->getId(), $conn);
                
         $arProfileListHasProfile = new arProfileListHasProfile();
         $arProfileListHasProfile->setProfileId($sourceItem->getProfileId());
         $arProfileListHasProfile->setProfileListId($toListId);
         $arProfileListHasProfile->setIsAdmin($sourceItem->getIsAdmin());
         $arProfileListHasProfile->setIsAccept($sourceItem->getIsAccept());
         $arProfileListHasProfile->setIsIgnore($sourceItem->getIsIgnore());
         $arProfileListHasProfile->setIsBlock($sourceItem->getIsBlock());
         $arProfileListHasProfile->save($conn);
    }*/
    
    /*
    public function cloneList($listTarget)
    {
        
        if (!($listTarget && is_object($listTarget)))
        {
            //crea una nueva si no existe
             $listTarget = new arProfileList();
             $listTarget->setName('All');
             $listTarget->setIsAll(true);
             $listTarget->save();
        }
        
        $listItems = $this->getArProfileListHasProfile();
        
        if (count($listItems) > 0)
        {
           foreach ($listItems as $item)
           {
               if (!$listTarget->hasProfileId($item->getProfileId()))
               {
                $arProfileListHasProfile = new arProfileListHasProfile();
                $arProfileListHasProfile->setProfileId($item->getProfileId());
                $arProfileListHasProfile->setProfileListId($listTarget->getId());
                $arProfileListHasProfile->setIsAdmin($item->getIsAdmin());
                $arProfileListHasProfile->setIsAccept($item->getIsAccept());
                $arProfileListHasProfile->setIsIgnore($item->getIsIgnore());
                $arProfileListHasProfile->save();
               } 
           } 
        }
        
    }*/
    
    
    
    public function getListData()
    {
        $dataIds = '[';
        if (count($listItems = $this->getList()) > 0 )
        {
            if (is_array($listItems))
            {
                foreach ($listItems as $id)
                {
                    $dataIds .= $id.',';  
                }
                $dataIds = preg_replace('/\,$/', '', trim($dataIds));
            }
            else {
                $dataIds .=  $listItems;
            }
        }
        $dataIds .=  ']';
        
        return $dataIds;
    }
    
    /*
    public function saveRelated($conn)
    {
       
            foreach ($this->userIDS as $userID)
            {
                //si no tiene el perfil ya en la lista
                if (!$this->hasProfileId($userID))
                {
                    $arProfileListHasProfile = new arProfileListHasProfile();
                    $arProfileListHasProfile->setProfileId($userID);
           
                    $arProfileListHasProfile->setProfileListId($this->getId());
                
                    if ($userID == $this->ownAdminUserID)
                    {
                        $arProfileListHasProfile->setIsAdmin(true);
                        $arProfileListHasProfile->setIsAccept(true);
                        $arProfileListHasProfile->setIsIgnore(false); 
                    }
                    else 
                    {
                        $arProfileListHasProfile->setIsAdmin(false);
                        $arProfileListHasProfile->setIsAccept(false);
                        $arProfileListHasProfile->setIsIgnore(false);
                    }
                
                    $arProfileListHasProfile->save($conn);
                }
               
            }
        
        
    }*/
    
    public function deleteProfile($deleteProfileId, $conn)
    {
         Doctrine_Query::create($conn)
                        ->delete()
                        ->from('arProfileListHasProfile')
                        ->where('profile_id = ?',$deleteProfileId)
                        ->andWhere('profile_list_id = ?', $this->getId())
                        ->execute();
    }
    
   
   
  /*  
   public function deleteAll($profileId, $allList, Doctrine_Connection $conn = null)
   {
      $conn = $conn ? $conn : $this->getTable()->getConnection();
      $conn->beginTransaction();
      
      try
      { 
          parent::delete($conn);
          
           $q = Doctrine_Query::create($conn)
                  ->from('arProfileListHasProfile hp')
                   ->where('hp.profile_list_id = ?', $allList->getId())
                   ->andWhere('hp.is_admin = false')
                   ->select('hp.profile_id as profile_id');
           
           $userProfiles = $q->fetchArray();
           
           if ($userProfiles && is_array($userProfiles) && (count($userProfiles) > 0))
           {
               foreach ($userProfiles as $userProfile)
               {
                     $q = Doctrine_Query::create($conn)
                        ->from('arProfileList c')
                        ->leftJoin('c.arProfileListHasProfile as hp')
                        ->where('c.id IN (SELECT lp.profile_list_id FROM arProfileListHasProfile lp WHERE lp.profile_id = ? AND lp.is_admin = true)', $profileId)
                       // ->andWhere("c.is_all = false")
                        ->andWhere('hp.profile_id = ?', $userProfile['profile_id'])
                        ->select('COUNT(c.id)');
                     
                      if ($q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR) == 0)
                      {
                           Doctrine_Query::create($conn)
                            ->delete()
                            ->from('arProfileListHasProfile')
                            ->where('profile_id = ?',$userProfile['profile_id'])
                            ->andWhere('profile_list_id = ?', $allList->getId())
                            ->execute();
                      }
               }
                
           }
          
        $conn->commit();

        return true;
      }
      catch (Exception $e)
      {
        $conn->rollBack();
        throw $e;
      }
   } */
    
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
        if (isset($this->userIDS) && is_array($this->userIDS))
        {
           $this->saveRelated($conn);   
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
    

}