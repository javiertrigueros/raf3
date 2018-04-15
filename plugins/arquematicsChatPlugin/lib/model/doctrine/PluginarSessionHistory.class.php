<?php

/**
 * PluginarSessionHistory
 * 
 * 
 * @package    arquematicsPlugin
 * 
 * @author     Javier Trigueros Martínez de los Huertos javiertrigueros@gmail.com
 * @version    0.1
 */
abstract class PluginarSessionHistory extends BasearSessionHistory
{

    
  /**
   * es el listener de user.change_authentication
   * 
   * (se ejecuta cuando se ha producido un evento
   * de cambio de autentificacion)
   * 
   * @param <sfEvent $event>
   * 
   */  
  static public function doAuthChange(sfEvent $event)
  {
    $sessionUser = $event->getSubject();
    $params = $event->getParameters();
    
    
    if(true === $params['authenticated'])
    {
      $userId = $sessionUser->getGuardUser()->getId();
      $sessionUser->setAttribute('user_id', $userId, 'arquematicsPlugin');
      //state = true = login
      $arSessionHistory = self::createHistoryEntry(true, $userId);
      
      $sessionUser->setAttribute('session_history_id', $arSessionHistory->getId());
    }
    else
    {
      $userId = $sessionUser->getAttributeHolder()->remove('user_id', null, 'arquematicsPlugin');
      $sessionUser->getAttributeHolder()->remove('session_history_id', null);
      //state = false = logout
      $arSessionHistory = self::createHistoryEntry(false, $userId);
    }
  }

  /**
   * crea una nueva entrada de login
   * 
   * @param <boolean $state> true para login| false logout
   * @param <integer $userId> id del usuario
   * 
   * @return <arSessionHistory> : nueva entrada
   */
  protected static function createHistoryEntry($state, $userId)
  {
    $history = new arSessionHistory();
    $history->state = $state;
    $history->user_id = $userId;
    $history->session_id = session_id();
    $history->ip = getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
    $history->save();
    
    return $history;
  }
}