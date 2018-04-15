<?php

/**
 * PluginarFriend
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginarFriend extends BasearFriend
{
    
    public static function removeRequest($fromProfileId, $toProfileId, $conn)
    {
        $arFriend = Doctrine::getTable('arFriend')
                         ->getStatus( $fromProfileId, $toProfileId, $conn);
    
        if ($arFriend && is_object($arFriend))
        {
            $arFriend->delete($conn);
        }
    }
     
    public static function confirmRequest($fromProfileId, $toProfileId, $confimStatus, $conn)
    {
         $arFriend = Doctrine::getTable('arFriend')
                         ->getStatus( $fromProfileId, $toProfileId, $conn);
        
         if ($arFriend && is_object($arFriend))
         {
            $arFriend->setRequestId($fromProfileId);
            $arFriend->setIsAccept($confimStatus);
            $arFriend->setIsIgnore(!$confimStatus);
            //$arFriend->setIsBlock(false);
            
            $arFriend->save($conn);
         }
    }
    /**
     * hace una petición de amistad
     * 
     * @param <int $fromProfileId> : usuario logeado
     * @param <int $toProfileId>
     * @param <conn $conn>
     */
    public static function addRequest($fromProfileId, $toProfileId, $conn)
    {
         //para el usuario activo
         if (!Doctrine::getTable('arFriend')
                         ->hasStatus( $fromProfileId, $toProfileId, $conn))
         {
            $arFriend = new arFriend();

            $arFriend->setProfileId($fromProfileId);
            $arFriend->setRequestId($fromProfileId);
            $arFriend->setFriendId($toProfileId);
           
            $arFriend->setIsAccept(false);
            $arFriend->setIsIgnore(false);
            //$arFriend->setIsBlock(false);
            
            $arFriend->save($conn);
            
            return $arFriend;
          }
          else {
              return false;
          }
    }

}