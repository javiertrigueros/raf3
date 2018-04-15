<?php

/**
 * PluginsfGuardUserProfileTable
 * 
 * @package    arquematicsPlugin
 * @subpackage models
 * @author     Javier Trigueros Martínez de los Huertos javiertrigueros@gmail.com
 * @version    0.1 2010-03-29 
 */
class PluginsfGuardUserProfileTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginsfGuardUserProfileTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginsfGuardUserProfile');
    }
    

    /**
     * total de paginas de usuario por una cadena de busqueda
     * @paran <int $profileId> 
     * @return <int>
     */
    public function totalPagesByString($profileId, $search)
    {
        $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
        $count = Doctrine_Core::getTable('sfGuardUserProfile')->countByString($profileId, $search);
       
       return ceil($count / $maxResults);
    }
    
    /**
     * total de paginas de usuarios aceptados o ignorados
     * @paran <int $profileId>
     * @paran <boolean $isAccepted>
     * 
     * @return <int>
     */
    public function totalPagesFriends($profileId, $isAccepted)
    {
       $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
       $count = Doctrine_Core::getTable('sfGuardUserProfile')
                ->countUsersAccepted($profileId,  $isAccepted);
       
       return ceil($count / $maxResults);
    }
    
    public function totalPagesFriendsByString($profileId, $text, $isAccepted)
    {
       $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
       
       $count = Doctrine_Core::getTable('sfGuardUserProfile')
                ->countFriendsAcceptedByString($profileId, $text,  $isAccepted);
       return ceil($count / $maxResults);
    }
    
    public function getQueryByString($profileId, $search)
    {
        //TODO: Mirar esto otra vez :(
        $searchText = "%".trim($search)."%";
        $conn = Doctrine_Manager::connection();
        $searchText = $conn->quote($searchText);
        
        $query = Doctrine::getTable('sfGuardUserProfile')
                ->getQuery($profileId)
                ->andWhere("(c.email_address LIKE $searchText OR c.username LIKE $searchText OR c.first_last LIKE $searchText OR c.description LIKE $searchText OR c.address LIKE $searchText)");
      
        return $query;
        
    }
    
    public function getQueryByStringSimple($search)
    {
        $searchText = "%".trim($search)."%";
        $conn = Doctrine_Manager::connection();
        $searchText = $conn->quote($searchText);
        
        $query = Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                ->where("(c.email_address LIKE $searchText OR c.username LIKE $searchText OR c.first_last LIKE $searchText OR c.description LIKE $searchText OR c.address LIKE $searchText)");
      
        return $query;
    }
    
    
     /**
     * Query con listado de perfiles de usuario
     * 
     * @param <string $order>
     * @return <query>
     */
    public static function getQuery($profileId)
    {
        $usersQ = Doctrine::getTable('sfGuardUserProfile')
                     ->queryUsersAccepted($profileId, false)
                     //no puede ver los
                     //usuarios ignorados por el mismo
                     //->andWhere('f.request_id = ?', $profileId)
                     ->select('c.id as id');
             
        $usersList = $usersQ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
        //echo $usersQ->getSqlQuery();

        //print_r($usersList);
        //exit();
        if ($usersList && is_array($usersList) && (count($usersList) > 0))
        {
             $q = Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                ->where('c.id <> ? AND c.id NOT IN (' . implode(',', $usersList) . ')', $profileId)    
                ->orderBy('c.first_last ASC, c.created_at DESC');
        }
        else if ($usersList  && $usersList > 0)
        {
             $q = Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                ->where("c.id <> ? AND c.id NOT IN ($usersList)", $profileId)    
                ->orderBy('c.first_last ASC, c.created_at DESC');
        }
        else
        {
            $q = Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                ->where('c.id <> ?', $profileId)    
                ->orderBy('c.first_last ASC, c.created_at DESC');
        }
       
        //echo $q->getSqlQuery();
        //exit();
        return $q;
    }
    
    public static function getUsersListByStringLimited($profileId, $search)
    {
       
        $q = Doctrine::getTable('sfGuardUserProfile')
                      ->getQueryByString($profileId,$search)
                      ->select('c.first_last, c.id')
                      ->orderBy('c.first_last ASC, c.created_at DESC')
                       ->limit((int)sfConfig::get('app_arquematics_pluginprofiles_autocomplete_limit', 10));
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
    
    
    public static function getFriendsListByStringLimited($profileId, $search, $isAccept)
    {
         $query = Doctrine::getTable('sfGuardUserProfile')
                        ->getQueryFriendsByString($profileId, $search, $isAccept)
                        ->limit((int)sfConfig::get('app_arquematics_pluginprofiles_autocomplete_limit', 10));
        
         //echo $query->getSqlQuery();
         
        return $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public static function getQueryFriendsByString($profileId, $search, $isAccept)
    {
        $queryS = Doctrine::getTable('sfGuardUserProfile')
                        ->getQueryByStringSimple($search);
                
        return Doctrine::getTable('sfGuardUserProfile')
                            ->queryUsersAccepted($profileId, $isAccept, $queryS)
                            ->select('c.first_last, c.id')
                            ->orderBy('c.first_last ASC, c.created_at DESC');
    }
    
    /**
     * numero de usuarios encontrados con una cadena
     * 
     * @param <string $search>
     * @return <int>:               número de usuarios
     */
    public function countByString($profileId, $search)
    {
        $q = Doctrine::getTable('sfGuardUserProfile')
                ->getQueryByString($profileId, $search)
                ->select('COUNT(c.id)');

        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public static function getUsersListByString($profileId, $search, $page = 0,$order = 'DESC')
    {
        $ret = array();
        
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->countByString($profileId,$search);
        
        if ($count > 0)
        {
          $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
          $totalPages = ceil($count / $maxResults);
          if ($page <= $totalPages)
          {
              $query = Doctrine::getTable('sfGuardUserProfile')
                      ->getQueryByString($profileId,$search,$order);
              
              $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
              $pager->setQuery($query);
              //inicia el paginador en la pagina que se ha pedido
              $pager->setPage($page);
              $pager->init();
         
              $ret = $pager->getResults();
          }
        }
        
        return $ret;
    }
    
    
    /**
     * usuarios aceptados y que tienen una clave activa para enviarles 
     * mensajes
     * 
     * @param <integer $profileId>
     * @return <array> : con las id y las claves publicas
     */
    public static function getUsersAcceptedPublicKeys($profileId)
    {
         $q = Doctrine::getTable('sfGuardUserProfile')
                        ->queryUsersAccepted($profileId, true)
                        ->andWhere('key_saved = true')
            ->select('c.id, c.public_key');
         
         return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    /**
     * listado de usuarios aceptados o no
     * 
     * @param <int $profileId>
     * @param <int $page>
     * @param [boolean $isAccept]: true los aceptados, false los no aceptados
     * @param [string $order]
     * @return  <array of sfGuardUserProfile>
     */
    public static function getUsersAccepted($profileId, $page = 0,$isAccept = true, $order = 'DESC')
    {
        $ret = array();
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->countUsersAccepted($profileId, $isAccept);
        
        if ($count > 0)
        {
            $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
            $totalPages = ceil($count / $maxResults);
            if ($page <= $totalPages)
            { 
                $q = Doctrine::getTable('sfGuardUserProfile')
                        ->queryUsersAccepted($profileId, $isAccept);
                
                //echo $q->getSqlQuery();
                
                 $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
                 $pager->setQuery($q);
                 //inicia el paginador en la pagina que se ha pedido
                 $pager->setPage($page);
                 $pager->init();
         
                 $ret = $pager->getResults();
            }
        }
        
        return $ret;
    }
    
    /**
     * listado de usuarios aceptados o no por una cadena
     * 
     * @param <int $profileId>
     * @param <string $text>
     * @param <int $page>
     * @param [boolean $isAccept]: true los aceptados, false los no aceptados
     * @param [string $order}
     * @return  <array of sfGuardUserProfile>
     */
    public static function getUsersAcceptedByString($profileId, $text, $page = 0,$isAccept = true, $order = 'DESC')
    {
        $ret = array();
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->countFriendsAcceptedByString($profileId, $text, $isAccept);
        
        if ($count > 0)
        {
            $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
            $totalPages = ceil($count / $maxResults);
            if ($page <= $totalPages)
            { 
                $query = Doctrine::getTable('sfGuardUserProfile')
                            ->getQueryFriendsByString($profileId, $text, $isAccept);
                
                 $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
                 $pager->setQuery($query);
                 //inicia el paginador en la pagina que se ha pedido
                 $pager->setPage($page);
                 $pager->init();
         
                 $ret = $pager->getResults();
            }
        }
        
        return $ret;
    }
    /**
     * cuenta usuarios aceptados o no 
     * 
     * @param <int $profileId>
     * @param [boolean $isAccept]: true los aceptados, false los no aceptados
     * @return <int>
     */
    public static function countUsersAccepted($profileId, $isAccept = true)
    {

        $q = Doctrine::getTable('sfGuardUserProfile')
                ->queryUsersAccepted($profileId, $isAccept)
                 ->select('count(c.id)');
        
        //echo $q->getSqlQuery();
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    /**
     * query con el listado de peticiones de amistad
     * 
     * @param <int $profileId>
     * 
     * @return DoctrineQuery
     */
    public static function  queryFriendRequest($profileId)
    {
          return Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                 ->leftJoin('c.Friends f ON ((f.profile_id = c.id) OR (f.friend_id = c.id))')
                  ->andWhere('((f.profile_id = ?) OR (f.friend_id = ?))', array( $profileId, $profileId))
                  ->andWhere('c.id <> ?', $profileId)
                  ->andWhere('f.request_id <> ?', $profileId)
                  ->andWhere('f.is_accept = false')
                  ->andWhere('f.is_ignore = false');
    }
    
    public static function  countFriendRequest($profileId)
    {
        $q = Doctrine::getTable('sfGuardUserProfile')
                ->queryFriendRequest($profileId)
                ->select('count(c.id) as count');
                
         return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public static function  friendRequest($profileId)
    {
         return Doctrine::getTable('sfGuardUserProfile')
                ->queryFriendRequest($profileId)
                ->execute();
    }

    public static function queryUsersAcceptedArray($profileId)
    {
       $q = Doctrine::getTable('sfGuardUserProfile')
                     ->queryUsersAccepted($profileId, true)
                     ->select('c.id as id');
             
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR); 
    }

    
    
    public static function  queryUsersAccepted($profileId, $isAccept = true, $q = false)
    {
        
        if (!$q)
        {
              $q = Doctrine::getTable('sfGuardUserProfile')
                ->createQuery('c')
                 ->leftJoin('c.Friends f ON ((f.profile_id = c.id) OR (f.friend_id = c.id))')
                  ->andWhere('((f.profile_id = ?) OR (f.friend_id = ?))', array( $profileId, $profileId))
                  ->andWhere('c.id <> ?', $profileId)
                  ->andWhere('f.is_accept = ?', $isAccept )
                  ->andWhere('f.is_ignore = ?', !$isAccept );
              
        }
        else
        {
            $q->leftJoin('c.Friends f ON ((f.profile_id = c.id) OR (f.friend_id = c.id))')
                  ->andWhere('((f.profile_id = ?) OR (f.friend_id = ?))', array( $profileId, $profileId))
                  ->andWhere('c.id <> ?', $profileId)
                  ->andWhere('f.is_accept = ?', $isAccept )
                  ->andWhere('f.is_ignore = ?', !$isAccept );
        }
        
        //echo $q->getSqlQuery();
        
       return $q;
    }
    
    public static function countFriendsAcceptedByString($profileId, $text, $isAccept = true)
    {  
        $q = Doctrine::getTable('sfGuardUserProfile')
                ->getQueryFriendsByString($profileId, $text, $isAccept)
                ->select('count(c.id)');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    /**
     * mira si esta bloqueada la operacion
     * de aceptar o ignorar para un perfil
     * 
     * @param <int $profileId> : profile del usuario actual
     * @param <int $toProfileId> : perfil que queremos aceptar o ignorar
     * @return <boolean> : true si la operacion no se puede hacer
     */
    public static function isUserBlock($profileId, $toProfileId)
    {
       
        
          $q = Doctrine::getTable('arFriend')->createQuery('f')
            ->where('((f.profile_id = ?) OR (f.friend_id = ?)) 
                AND ((f.profile_id = ?) OR (f.friend_id = ?))',array($profileId, $profileId, $toProfileId, $toProfileId))     
            //1 .- el mismo usuario que hace la petición 
            //no puede aceptarse o ignorarse
            //2.- si el usuario ya ha sido aceptado siempre
            //podra cambiar de estado e ignorar $toProfileId
            ->andWhere('(f.is_accept=true AND f.is_ignore=false) 
                OR (f.is_accept=false AND f.is_ignore=false AND f.request_id <> ?) 
                OR f.request_id = ?',array($profileId,$profileId))
            ->select('count(*) as count'); 
           
        /* 
        echo $q->getSqlQuery();
        echo $profileId;
        echo 'to';
        echo $toProfileId;*/
        
        return !($q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0);
        
    }
    
    
    public static function getListQuery($listId)
    {
       return Doctrine::getTable('sfGuardUserProfile')->createQuery('c')
                 ->leftJoin( 'c.arProfileListHasProfile as hp')
                 ->where('hp.profile_list_id = ?',  $listId)
                 ->orderBy('c.first_last ASC, c.created_at DESC');

                //getListQuery

                //queryUsersAccepted($profileId, true, $q = false)
    }
    
    public static function getUsersByList($listId, $page = 0,$order = 'DESC')
    {
        $ret = array();
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->getUsersByListCount($listId);
        
        if ($count > 0)
        {
          $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
          $totalPages = ceil($count / $maxResults);
          if ($page <= $totalPages)
          {   
              $query = Doctrine_Core::getTable('sfGuardUserProfile')
                        ->getListQuery($listId);

              $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
              $pager->setQuery($query);
              //inicia el paginador en la pagina que se ha pedido
              $pager->setPage($page);
              $pager->init();
         
              $ret = $pager->getResults();
          }
        }
        
        return $ret;
         
    }
    
    public static function getUsersByListCount($listId)
    {
        $q = Doctrine_Core::getTable('sfGuardUserProfile')
                ->getListQuery($listId)
                 ->select('COUNT(c.id)');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }


    
  /**
  * devuelve un registro por la id
  * 
  * @param <$id>
  * @return <sfGuardUserProfile>
  */
  public function retrieveById($id)
  {
    $query = Doctrine_Core::getTable('sfGuardUserProfile')->createQuery('u')
      ->where('u.id = ?', $id);

    return $query->fetchOne();
  }
  
    public static function mutualFriendRequestExt($profileIdA,$profileIdB, $page, $maxResults)
    {
        $ret = array(
            'count' => 0,
            'pager' => null,
            'results' => array()
        );
       
        
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->queryUsersAcceptedForCount($profileIdA,$profileIdB);
        if ($count > 0)
        {
            
          
          $totalPages = ceil($count / $maxResults);
          if ($page <= $totalPages)
          {   
              $query = Doctrine::getTable('sfGuardUserProfile')
                        ->queryUsersAcceptedFor($profileIdA,$profileIdB);
              
              
              $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
              $pager->setQuery($query);
              //inicia el paginador en la pagina que se ha pedido
              $pager->setPage($page);
              $pager->init();
         
               $ret = array(
                    'count' => $count,
                    'pager' => $pager,
                    'results' => $pager->getResults()
                );
          }
        }
        
        return $ret;
        
    }
    public static function mutualFriendRequest($profileIdA,$profileIdB, $page)
    {
       $ret = Doctrine::getTable('sfGuardUserProfile')
               ->mutualFriendRequestExt($profileIdA,$profileIdB, $page);

        return $ret['results'];
    }
    
    public static function queryUsersAcceptedForCount($profileIdA,$profileIdB)
    {
        $q = Doctrine::getTable('sfGuardUserProfile')
                 ->queryUsersAcceptedFor($profileIdA,$profileIdB)
                 ->select('COUNT(c.id)');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public static function queryUsersAcceptedFor($profileIdA,$profileIdB)
    {
            if ($profileIdA === $profileIdB)
            {
               $q = Doctrine::getTable('sfGuardUserProfile')
                       ->queryUsersAccepted($profileIdA, true);
            }
            else 
            {
               $usersQ = Doctrine::getTable('sfGuardUserProfile')
                     ->queryUsersAccepted($profileIdA)
                     ->select('c.id as id');
             
               $usersList = $usersQ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
             
              
               if ($usersList && is_array($usersList) && (count($usersList) > 0))
               {
                  $q = Doctrine::getTable('sfGuardUserProfile')
                     ->createQuery('c')
                     ->where('c.id = ? OR c.id IN (' . implode(',', $usersList) . ')',$profileIdA)
                     ->leftJoin('c.Friends f ON ((f.profile_id = c.id) OR (f.friend_id = c.id))')
                     //->leftJoin('c.Messages m ON m.user_id = c.id')
                     ->andWhere('(f.profile_id = ?) OR (f.friend_id = ?)', array( $profileIdB, $profileIdB))
                     ->andWhere('c.id <> ?', $profileIdB)
                     ->andWhere('f.is_accept = true' )
                     ->andWhere('f.is_ignore = false');
                     //->select('count(m.id) as Messages.countMessages');   
               }
               else 
               {
                   //:TODO mirar cuando solo tenga uno
                   // 
                   $q = Doctrine::getTable('sfGuardUserProfile')
                     ->createQuery('c')
                     ->where('c.id = ?',$profileIdA)
                     ->leftJoin('c.Friends f ON ((f.profile_id = c.id) OR (f.friend_id = c.id))')
                     //->leftJoin('c.Messages m ON m.user_id = c.id')
                     ->andWhere('(f.profile_id = ?) OR (f.friend_id = ?)', array( $profileIdB, $profileIdB))
                     ->andWhere('c.id <> ?', $profileIdB)
                     ->andWhere('f.is_accept = true' )
                     ->andWhere('f.is_ignore = false');
                     //->select('count(m.id) as Messages.countMessages'); 
               }
               
            }
       
        //echo $q->getSqlQuery();
        
       return $q;
    }
   
    
    public static function getCount($profileId)
    {
       $q = Doctrine::getTable('sfGuardUserProfile')
               ->getQuery($profileId)
                ->select('COUNT(c.id)');
       
      return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);  
    }
    
    /**
     * listado de todos perfiles de usuario que no
     * han sido rechazados
     * 
     * @param <int $profileId>: usuario actual
     * @param <int $page>: pagina a visualizar
     * @param <string $order>: orden de los perfiles
     * 
     * @return array 
     */
    public static function getUsersList($profileId, $page = 0)
    {
        $ret = array();
        $count = Doctrine_Core::getTable('sfGuardUserProfile')
                    ->getCount($profileId);
        if ($count > 0)
        {
          $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
          $totalPages = ceil($count / $maxResults);
          if ($page <= $totalPages)
          {
              $query = Doctrine::getTable('sfGuardUserProfile')
                        ->getQuery($profileId);
              
              
              $pager = new sfDoctrinePager('sfGuardUserProfile', $maxResults);
              $pager->setQuery($query);
              //inicia el paginador en la pagina que se ha pedido
              $pager->setPage($page);
              $pager->init();
         
              $ret = $pager->getResults();
              
          }
        }
        
        return $ret;
    }
    
    /**
     * total de paginas de perfiles
     * 
     * @param <int $profileId>: usuario actual
     * @return <int>
     */
    public function totalPages($profileId)
    {
        $maxResults = sfConfig::get('app_arquematics_plugin_profiles_perpage', 10);  
        $count = Doctrine_Core::getTable('sfGuardUserProfile')->getCount($profileId);
       
       return ceil($count / $maxResults);
    }
    
  public function countByEmail($email)
  {
    $query = Doctrine_Core::getTable('sfGuardUserProfile')->createQuery('u')
      ->where('u.email_address LIKE ?', $email)
      ->select('COUNT(u.id)');
        
    return $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }
  
  public function countByUserName($username)
  {
    $query = Doctrine_Core::getTable('sfGuardUserProfile')->createQuery('u')
      ->where('u.username LIKE ?', $username)
      ->select('COUNT(u.id)');
        
    return $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }
        
}