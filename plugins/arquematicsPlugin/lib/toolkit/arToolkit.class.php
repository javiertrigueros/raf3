<?php
class arToolkit extends BaseaTools 
{
  static public $jsCalls = array();
  //partials  
  static public $jsPartialCalls = array();
  /**
   * Lanza un excepción. error 500
   * 
   * @param <string $message>
   */
  public static function throwException($message) 
  {
      sfContext::getInstance()->set('error_msg', $message);
      throw new sfException($message);
  }
}