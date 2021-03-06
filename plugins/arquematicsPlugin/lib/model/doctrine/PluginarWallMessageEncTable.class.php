<?php

/**
 * PluginarWallMessageEncTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginarWallMessageEncTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginarWallMessageEncTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginarWallMessageEnc');
    }
    
    /**
    * 
    * @param int $messageId:
    * @param int $userId:
    * 
    * @return arWallMessageEnc
    */
    public function retrieveContentById($messageId, $userId )
    {
        $query = Doctrine_Core::getTable('arWallMessageEnc')
            ->createQuery('u')
            ->andWhere('u.wall_message_id = ?', $messageId)
            ->andWhere('u.user_id = ?', $userId);

        return $query->fetchOne();
    }
}