<?php

/**
 * PluginarLavernaDocDeleteTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarLavernaDocDeleteTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarLavernaDocDeleteTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarLavernaDocDelete');
    }
    
   /**
    * devuelve un registro 
    * 
    * @param <$documentId>
    * @param <$userId>
    * @return <arLavernaDocDelete>
    */
  public function hasUserAndDoc($userId, $documentId,  $conn = false)
  {
    $q = Doctrine_Core::getTable('arLavernaDocDelete')
            ->createQuery('d')
            ->where('d.laverna_id = ?', $documentId)
            ->andWhere('d.user_id = ?', $userId)
            ->select('count(d.id)');
    
    if ($conn)
    {
        $q->setConnection($conn);  
    }
    
    //echo $q->getSqlQuery();
    return ($q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0);
  }
}