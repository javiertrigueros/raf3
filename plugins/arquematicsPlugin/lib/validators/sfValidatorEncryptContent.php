<?php

/**
 * sfValidatorEncryptContent: Validar contenido encriptado.
 * Mira si los usuarios a los que se envia el contenido existen
 * y tienen una clave publica ya en la base de datos.
 *
 *
 * @package    arquematics
 * @subpackage validators
 * @author     Javier Trigueros Martínez de los Huertos <javiertrigueros@arquematics.com>
 * @version    0.1  
 */

class sfValidatorEncryptContent extends sfValidatorBase
{
 
  protected function doClean($value)
  {
      $require = $this->getOption('require');
      
      $value = trim($value);
      
      if ($require || (strlen($value) > 0))
      {
          try {
                $encryptValues = $this->fixJsonArray($value);
          
                if ($encryptValues && (count($encryptValues) > 0))
                {
                    $query = Doctrine_Core::getTable('sfGuardUserProfile')->createQuery();
           
                    $data = array();
                    foreach ($encryptValues as $key => $value)
                    {
                        $data[] = $key;
                    }
                    $query->andWhere('sfGuardUserProfile.key_saved = true');
            
                    $query->andWhereIn('sfGuardUserProfile.id', $data);
            
                    $count = $query->count();
            
                    if (!$count || (count($data) <> $count))
                    {
                        throw new sfValidatorError($this, 'invalid');
                    }
                }
          
                return json_encode($encryptValues);
            }
            catch (Exception $e)
            {
                throw new sfValidatorError($this, 'invalid ', array('value' => $e));
            } 
      }
      
      return $value;
      
  }
  
  /**
   * arregla el contenido que se envia y 
   * 
   * array(array('id' => userId1, 'data' => 'content1')
   *       array('id' => userId3, 'data' => 'content2'))
   * 
   * pasa a ser:
   * 
   * array('userId1' => 'content1', 'userId2' => 'content2')
   * 
   * si no tenemos contenido devuelve un array()
   * 
   * @param <string $message>
   * @return <array>
   */
  private function fixJsonArray($json)
  {
      //$json = str_replace(array("\\"),"",$json);
      //$json = preg_replace('/\s+/', '', $json);
      
      $contentArray = json_decode($json,true);
      $errorCode = json_last_error();

      if ($errorCode > 0)
      {
          switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                throw new Exception(' - Maximum stack depth exceeded');
            break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception(' - Underflow or the modes mismatch');
            break;
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception(' - Unexpected control character found');
            break;
            case JSON_ERROR_SYNTAX:
                throw new Exception(' - Syntax error, malformed JSON');
            break;
            case JSON_ERROR_UTF8:
                throw new Exception(' - Malformed UTF-8 characters, possibly incorrectly encoded');
            break;
         }
      }
       
      
      $ret = array();
    
      if ($contentArray && is_array($contentArray) && (count($contentArray) > 0))
      {
          foreach ($contentArray as $dataEncrypt)
          {
             if (isset($dataEncrypt['id']) && (isset($dataEncrypt['data'])))
             {
               $id = $dataEncrypt['id'];
               $ret[$id] = $dataEncrypt['data'];  
             }
              
          }
      }
      
      return $ret;
  }
  
  private function json_last_error_msg() {
        static $errors = array(
            JSON_ERROR_NONE             => null,
            JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
            JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );
        $error = json_last_error();
        return array_key_exists($error, $errors) ? $errors[$error] : "Unknown error ({$error})";
    }
  
  
}