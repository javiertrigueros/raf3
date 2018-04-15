<?php

/**
 * 
 * @package    arquematicsChat
 * @author     Javier Trigeuros Martinez de los Huertos javiertrigueros@gmail.com
 * @version    0.1
 */
abstract class PluginarChatToken extends BasearChatToken
{
  /**
   * genera un token aleatorio
   * 
   * @return <string> 
   */
  private function generateRandomKey()
  {
    return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
  }
  
   /**
   *
   * Guarda los datos del objeto en la base de datos y en los
   * indices de busqueda Lucene
   * 
   * @param Doctrine_Connection $conn
   * @return <type>
   */
  public function save(Doctrine_Connection $conn = null)
  {

    if ($this->isNew())
    {
        $this->setIsActive(true);
        $this->setToken($this->generateRandomKey());
         
        $now = $this->getCreatedAt() ? $this->getDateTimeObject('created_at')->format('U') : time();
        $this->setExpiresAt(date('Y-m-d H:i:s', $now + 60 * sfConfig::get('app_archat_token_active_min')));
    }

    //añade la id del usuario que crea la entrada
    if ($this->isNew() && sfContext::hasInstance())
    {
        
        $sessionUser = sfContext::getInstance()->getUser();
        if (is_object($sessionUser) && $sessionUser->isAuthenticated())
        {

         $sessionHistoryId  = $sessionUser->getAttribute('session_history_id');
            
         $this->setSessionHistoryId($sessionHistoryId);
        }
    }

    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        $ret = parent::save($conn);

        $conn->commit();

        return $ret;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
  }

}