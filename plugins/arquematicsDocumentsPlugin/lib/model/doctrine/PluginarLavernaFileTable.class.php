<?php

/**
 * PluginarLavernaFileTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarLavernaFileTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarLavernaFileTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarLavernaFile');
    }
    
    
    public static function getQueryByDocIdAndGui($docId, $gui)
    {
         $q = Doctrine::getTable('arLavernaFile')
             ->createQuery('f')
             ->leftJoin('f.LavernaDoc d')
             ->where('(d.id = ?) AND (f.guid like ?)', array($docId, $gui));
        return $q;
    }
    
    public static function getQueryByGui($gui)
    {
         $q = Doctrine::getTable('arLavernaFile')
             ->createQuery('f')
             ->where('f.guid like ?', array($gui));
        return $q;
    }
    
    public static function hasFileGui($gui)
    {
     $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByGui($gui)
             ->select('COUNT(f.id)');

     return ($q->execute(array(),  Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0);
    }
    
    public static function hasDocFile($docId, $gui)
    {
     $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocIdAndGui($docId, $gui)
             ->select('COUNT(f.id)');

     return ($q->execute(array(),  Doctrine_Core::HYDRATE_SINGLE_SCALAR) > 0);
    }
    
    public static function getDocFileObj($docId, $gui)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocIdAndGui($docId, $gui);

        return $q->fetchOne();
    }

    public static function getDocFile($docId, $gui)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocIdAndGui($docId, $gui)
             ->select('f.guid as id,
                    f.src as src,
                    f.w as w,
                    f.h as h,
                    f.type as type,
                    f.created_at as created
                    f.updated_at as updated');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }


    public static function getQueryByDocId($docId)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->createQuery('f')
             ->leftJoin('f.LavernaDoc d')
            ->where('d.id = ?', $docId)
                
            ->orderBy('d.created_at DESC');
        
        return $q;
    }
    
    public static function countGuidByDocId($docId)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocId($docId)
             ->select('COUNT(d.id)');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR); 
    }
    
    public static function getGuidByDocId($docId)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocId($docId)
                ->select('f.guid as id');
                
         
        return $q->execute(array(),  Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public static function getSimpleByDocId($docId)
    {
        $q = Doctrine::getTable('arLavernaFile')
             ->getQueryByDocId($docId)
                ->select('f.guid as id,
                    f.src as src,
                    f.w as w,
                    f.h as h,
                    f.type as type,
                    f.created_at as created
                    f.updated_at as updated');
                
          //->select('d.guid as guid, UNIX_TIMESTAMP(d.updated_at) as modified');
        
        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
    /**
     * devuelve los recursos por array de ids
     *
     * @param <array $ids> 
     * 
     * @return <array of arLavernaFile>
     */
    public static function getByIds($ids)
    {
        $q = Doctrine_Query::create()
            ->from('arLavernaFile f')
            ->whereIn('f.id',$ids);

        return $q->execute();
    }
    
}