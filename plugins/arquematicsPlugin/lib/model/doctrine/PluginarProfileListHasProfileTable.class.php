<?php

/**
 * PluginarProfileListHasProfileTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarProfileListHasProfileTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarProfileListHasProfileTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarProfileListHasProfile');
    }
    
    public static function getByIdAndListId($profileId, $listId, $conn = false)
    {
        if (!$conn)
        {
             $q = Doctrine::getTable('arProfileListHasProfile')->createQuery('hp')
                    ->where("hp.profile_list_id = ?", $listId)
                    ->andWhere('hp.profile_id = ?', $profileId);
        }
        else {
           $q = Doctrine_Query::create($conn)
            ->from( 'arProfileListHasProfile as hp')
            ->where("hp.profile_list_id = ?", $listId)
            ->andWhere('hp.profile_id = ?', $profileId);
        }
        
         
       return $q->fetchOne();
    }
    
      
}