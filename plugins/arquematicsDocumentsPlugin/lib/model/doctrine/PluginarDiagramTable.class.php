<?php

/**
 * PluginarDiagramTable
 * 
 * Arquematics 2012
 *
 * @author Javier Trigueros Martínez de los Huertos
 */
class PluginarDiagramTable extends Doctrine_Table
                                
{
    
    static protected $types = array(
         'paint' => array( 'id' => 0,
                           'extra' => false),
         'mindmaps' => 'mindmaps',
         'wireframe' => 'wireframe',
         'svg' => 'svg',
         'bpmn' => '/bpmn1.1/bpmn1.1.json',
         'epc' => '/epc/epc.json',
         'uml' =>'/uml2.2/uml2.2.json',
         'umlsequence' => '/umlsequence/umlsequence.json',
         'umlusecase' => '/umlusecase/umlusecase.json',
         'petrinet' => '/petrinets/petrinet.json',
         'interactionpetrinets' => '/coloredpetrinets/coloredpetrinet.json',
         'timeline' => '/umlactivity/umlactivity.json',
         'umlstate' => '/umlstate/umlstate.json',
         'umlusecase' => '/umlusecase/umlusecase.json',
         'letsdance' => '/letsdance/letsdance.json',
         'treeGraph' => '/treeGraph/treeGraph.json',
         'kmnets' => '/kmnets/kmnets.json',
         'aress' => '/aress/aress.json',
         'b3mn' => '/b3mn/b3mn.json',
         'fmcblockdiagram' => '/fmcblockdiagram/fmcblockdiagram.json',
         'ibpmn' => '/ibpmn/ibpmn.json',
         'trackerworkflow' => '/trackerworkflow/trackerworkflow.json'
        );
    
    /**
     * devuelve los tipos de diagramas validos
     * 
     * @return <array>
     */
    public function getTypes()
    {
        return self::$types;
    }
    
    /**
     * devuelve el nombre del stencilset del diagrama
     * 
     * @param <$id>: id del diagrama
     * 
     * @return <string>
     */
    public function getTypeName($id)      
    {
        $values = array_values(self::$types);
        return $values[$id];
    }
    /**
     * devuelve la id por el nombre
     * 
     * @param type $name
     * 
     * @return <int>
     */
    public function getTypeId($name)
    {
       $values = array_keys(self::$types);
       $ret = array_search($name, $values);
       return ($ret === false)? null: $ret;
    }
    
    /**
     * cuenta los documentos no borrados
     * 
     * @param type $profileId
     * @param type $trash
     * @param type $isFavorite
     * @return int
     */
    public static function countByUserId($profileId, $diagramName, $trash, $isFavorite)
    {
        $q =Doctrine::getTable('arDiagram')
             ->getQueryByUserId($profileId, $diagramName)
             ->select('COUNT(DISTINCT(d.id))');
        
        if ($trash)
        {
          $q->andWhere('dt.user_id = ?',$profileId); 
        }
        else {
          $q->andWhere('dt.user_id IS NULL'); 
        }
        
        if ($isFavorite)
        {
            $q->leftJoin('d.DiagramFavorite df')
              ->andWhere('df.user_id = ?',$profileId); 
        }
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    /**
     * documentos compartidos para un usuario
     * 
     * @param int $profileId
     * @return query
     */
    public static function getQueryByUserId($profileId, $diagramName, $conn = false)
    {
        $q = Doctrine::getTable('arDiagram')
             ->createQuery('d')
             ->leftJoin('d.Messages m')
             ->leftJoin('d.DiagramDeleted ld')
             ->leftJoin('d.DiagramTrash dt')
             ->leftJoin('m.User u')
             ->leftJoin('u.Friends f ON ((f.profile_id = u.id) OR (f.friend_id = u.id))')
             ->leftJoin('m.arWallMessageHasProfileList mhpl')
             ->leftJoin('mhpl.List l')
             ->leftJoin('l.arProfileListHasProfile plhp')
             ->where('(d.user_id = ?)  
                    OR ((l.profile_id = ?) AND (plhp.profile_id = ?)) 
                    OR (l.profile_id is NULL 
                                AND f.is_accept = true 
                                AND f.is_ignore = false  
                                AND ((f.profile_id = ?) OR (f.friend_id = ?)))', array($profileId,$profileId, $profileId,$profileId, $profileId))
            ->andWhere('(ld.user_id <> ?) OR (ld.user_id IS NULL)',$profileId)
            ->andWhere('d.type like ?', $diagramName)
            ->orderBy('d.created_at DESC');
        
        if ($profileId && sfConfig::get('app_arquematics_encrypt', false))
        {
           $q->leftJoin('d.EncContent ec')
              ->andWhere('ec.user_id = ?',$profileId);
        }
        
        
        if ($conn)
        {
           $q->setConnection($conn);  
        }
        /*
        echo $q->getSqlQuery();
        exit();*/
        
        return $q;
    }
    
    /**
     * listado de documentos no borrados
     * sin contenido , solo la id y la fecha de modificacion
     * 
     * @param $profileId
     * @return array 
     */
    public static function getQuerySimpleByUserId($profileId, $diagramName, $trash, $isFavorite)
    {
        $q = Doctrine::getTable('arDiagram')
             ->getQueryByUserId($profileId, $diagramName)
                ->select('d.guid as id,
                    d.title as title,
                    d.json as json,
                    d.type as diagramType,
                    d.data_image as dataImage,
                    d.created_at as created,
                    d.updated_at as updated,
                    IF(dt.id IS NULL,0,1) as trash,
                    IF(df.id IS NULL,0,1) as isFavorite,
                    ec.content as pass');
        
        if ($trash)
        {
          $q->andWhere('dt.user_id = ?',$profileId); 
        }
        else 
        {
          $q->andWhere('dt.user_id IS NULL'); 
        }
        
        if ($isFavorite)
        {
            $q->leftJoin('d.DiagramFavorite df')
              ->andWhere('df.user_id = ?',$profileId);
        }
        else
        {
           $q->leftJoin('d.DiagramFavorite df ON (d.id = df.diagram_id AND df.user_id = ?)',$profileId);
        }
        
        return $q;
         /*       ->select('d.guid as id,
                    d.title as title,
                    d.content as content,
                    lf.guid as images,
                    d.task_all as taskAll
                    d.task_complete as taskCompleted,
                    d.created_at as created,
                    d.updated_at as updated,
                    d.is_favorite as isFavorite,
                    d.trash as trash,
                    d.EncContent.content as pass');*/
                
             //->select('d.guid as guid, UNIX_TIMESTAMP(d.updated_at) as modified');
    }
    
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarDiagramTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarDiagram');
    }
    /**
     * devuelve los diagramas por ids
     *
     * @param <array $ids>
     * @param [int $profileId] : opcional. $profileId no se usa al guardar
     * 
     * @return <array>
     */
    public static function getByIds($ids, $profileId = false)
    {

        $q = Doctrine_Query::create()
            ->from('arDiagram d')
            ->whereIn('d.id',$ids)
            ->orderBy('d.created_at DESC');
        
        if ($profileId && sfConfig::get('app_arquematics_encrypt', false))
        {
           $q->leftJoin('d.EncContent ec ON (d.id = ec.diagram_id)')
              ->andWhere('ec.user_id = ?',$profileId);
        }
        return $q->execute();
    }
    
    public static function getByMenssage($menssageId, $profileId = false, $conn = false)
    {

        $q = Doctrine::getTable('arDiagram')
             ->createQuery('d')
            ->leftJoin('d.Messages m')
            ->where('m.id = ?',$menssageId)
                ->orderBy('d.created_at DESC');
        
        if ($conn)
        {
           $q->setConnection($conn);  
        }
        
        if ($profileId && sfConfig::get('app_arquematics_encrypt', false))
        {
           $q->leftJoin('d.EncContent ec')
              ->andWhere('ec.user_id = ?',$profileId);
        }
        return $q->execute();
    }
   
  public function retrieveByName($name)
  {
    $query = Doctrine_Core::getTable('arDiagram')
            ->createQuery('u')
            ->where('u.file_name = ?', $name);

    return $query->fetchOne();
  }
  
  /**
      * 
      * documento compartido en algun mensaje
      * 
      * @param <int $profileId>
      * @param <int $docId>
      * @param <$conn $conn>
      * 
      * @return boolean
      */
     public static function hasShareDoc($profileId, $docId,  $conn = false)
     {
        $q = Doctrine::getTable('arDiagram')
             ->getShareQueryByUserId($profileId, $conn)
              ->andWhere('d.id = ?',$docId)
                ->select('COUNT(d.id)');
        
        return ($q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0); 
     }
     
     /**
     * documentos compartidos para un usuario
     * 
     * @param int $profileId
     * @return query
     */
    public static function getShareQueryByUserId($profileId, $conn = false)
    {
        $q = Doctrine::getTable('arDiagram')
             ->createQuery('d')
             ->leftJoin('d.Messages m')
             ->leftJoin('m.User u')
             ->leftJoin('u.Friends f ON ((f.profile_id = u.id) OR (f.friend_id = u.id))')
             ->leftJoin('m.arWallMessageHasProfileList mhpl')
             ->leftJoin('mhpl.List l')
             ->leftJoin('l.arProfileListHasProfile plhp')  
             ->where('((l.profile_id = ?) AND (plhp.profile_id = ?)) 
                      OR (l.profile_id is NULL 
                                AND f.is_accept = true 
                                AND f.is_ignore = false  
                                AND ((f.profile_id = ?) OR (f.friend_id = ?)))', array($profileId, $profileId,$profileId, $profileId))
            
            //->groupBy('d.id')
            ->orderBy('d.created_at DESC');
        
        if ($profileId && sfConfig::get('app_arquematics_encrypt', false))
        {
           $q->leftJoin('d.EncContent ec')
              ->andWhere('ec.user_id = ?',$profileId);
        }
        
        if ($conn)
        {
           $q->setConnection($conn);  
        }
        
        return $q;
    }
    
    
}