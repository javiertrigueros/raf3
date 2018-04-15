<?php

define("CSORT_ASC",     1);
define("CSORT_DESC",     -1); 

class DataAlgorithms
{
  static $csort_cmp; 
     
  public static function cmp(&$a, &$b)
  {
    
    if ($a[DataAlgorithms::$csort_cmp['key']] > $b[DataAlgorithms::$csort_cmp['key']])
        return DataAlgorithms::$csort_cmp['direction'];

    if ($a[DataAlgorithms::$csort_cmp['key']] < $b[DataAlgorithms::$csort_cmp['key']])
        return -1 * DataAlgorithms::$csort_cmp['direction'];

    return 0;
  }

  public static function sortArray(& $dataArray , $key, $ascOrder = CSORT_ASC)
  {
    
    DataAlgorithms::$csort_cmp = array(
        'key'           => $key,
        'direction'     => $ascOrder
    );

    usort($dataArray, array("DataAlgorithms", "cmp"));
    
    return $dataArray;
  }
  /**
   * busca en un array of array (2D) por campo
   * 
   * @param <mixed $needle> : valor que buscamos
   * @param <array $haystack> : array en el que buscamos
   * @param <string $field> : campo del array en el que se busca
   * @return <mixed index> false si no encuenta el valor o una cadena con la clave
   */
  public static function getArrayKey($needle, $haystack, $field) {
    foreach ($haystack as $index => $innerArray) {
        if (isset($innerArray[$field]) && $innerArray[$field] == $needle) {
            return $index;
        }
    }
    return false;
  }
  
}