<?php

/**
 * PluginarDiagramEncTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarDiagramEncTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarDiagramEncTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarDiagramEnc');
    }
    
    /**
    * devuelve un registro por la id
    * 
    * @param <int $diagramId>
    * @param <int $profileId>
    * @return <arDiagramEnc arDiagramEnc> 
    */
    public function retrieveByProfileId($diagramId, $profileId)
    {
        $query = Doctrine_Core::getTable('arDiagramEnc')
            ->createQuery('u')
            ->where('u.diagram_id = ?', $diagramId)
            ->andWhere('u.user_id = ?',$profileId);
            

        return $query->fetchOne();
    }
    
    /**
     * array de usuarios para los que se ha codificado un diagrama
     * 
     * @param <int $diagramId>
     * @return <array>
     */
    public function retrieveUserById($diagramId)
    {
        $q = Doctrine_Core::getTable('arDiagramEnc')
            ->createQuery('u')
            ->andWhere('u.diagram_id = ?', $diagramId)
            ->select('user_id as id');
        
        return $q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
}