<?php

/**
 * PluginarLavernaDocEncTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarLavernaDocEncTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarLavernaDocEncTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarLavernaDocEnc');
    }
    
    /**
     * array de usuarios para los que se ha codificado un documento
     * 
     * @param <int $arLavernaDocId>
     * @return <array>
     */
    public function retrieveUserById($diagramId)
    {
        $q = Doctrine_Core::getTable('arLavernaDocEnc')
            ->createQuery('u')
            ->andWhere('u.laverna_id = ?', $diagramId)
            ->select('user_id as id');
        
        return $q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    /**
    * devuelve un registro por la id
    * 
    * @param <int $lavernaId>
    * @param <int $profileId>
    * @return <arDiagramEnc arDiagramEnc> 
    */
    public function retrieveByProfileId($lavernaId, $profileId)
    {
        $query = Doctrine_Core::getTable('arLavernaDocEnc')
            ->createQuery('u')
            ->where('u.laverna_id = ?', $lavernaId)
            ->andWhere('u.user_id = ?',$profileId);
            

        return $query->fetchOne();
    }
}