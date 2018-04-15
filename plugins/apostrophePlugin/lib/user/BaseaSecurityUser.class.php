<?php
/**
 * @package    apostrophePlugin
 * @subpackage    user
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class BaseaSecurityUser extends sfMelodyUser
{

  /**
   * DOCUMENT ME
   */
  function clearCredentials()
  {
    parent::clearCredentials();
    $this->getAttributeHolder()->removeNamespace('apostrophe');
    $this->getAttributeHolder()->removeNamespace('apostrophe_media');
  }
}
