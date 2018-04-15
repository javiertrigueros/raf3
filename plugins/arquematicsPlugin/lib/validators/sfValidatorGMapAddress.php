<?php
/**
 *
 * Valida geolocalizaciones de Google Maps
 * según sus caracteristicas
 *
 * Arquematics 2011
 *
 *
 * @author Javier Trigueros Martínez de los Huertos
 */
class sfValidatorGMapAddress extends sfValidatorBase
{
     // Lista de opciones validas
     // por defecto estan habilidadas todas las
     // opciones validas posibles
     static protected $all = array(
         'street_address',
         'route',
         'intersection',
         'political',
         'country',
         'administrative_area_level_1',
         'administrative_area_level_2',
         'administrative_area_level_3',
         'colloquial_area',
         'locality',
         'sublocality',
         'neighborhood',
         'premise',
         'subpremise',
         'postal_code',
         'natural_feature',
         'airport',
         'park',
         'point_of_interest',
         'post_box',
         'street_number',
         'floor',
         'room'
        );

  public function configure($options = array(), $messages = array())
  {
     
     $this->addOption('google_locations', sfValidatorGMapAddress::$all);
    
  }

  /**
   *
   * Validacion del objeto puede lanzar sfValidatorError
   *
   * @param <string> $value
   *
   * @throw sfValidatorError
   * 
   * @return <boolean>
   */
  protected function doClean($value)
  {

    //objeto para hacer las busquedas
    $request = GoogleGeocode::getInstance()->getGeocode($value);

    
    if (sfValidatorGMapAddress::validateResult($request,$this->getOption('google_locations')))
    {
      return $value;
    }
    else{
       throw new sfValidatorError($this, 'invalid');
    }
   
    return $value;
  }

    /**
     * valida los resultados de la peticion JSON
     * y comprueba que los resultados JSON son validos y tienen
     * las caracteristicas adecuadas
     *
     *
     * @param <$result array>: array con la respuesta JSON de Google
     * @param <$google_locations array>: array con los posibles tipos de
     *                                   localizaciones validas
     *
     * @return <boolean>: true si cumple las condiciones
     */
    public static function  validateResult($jsonResponse,
                    $google_locations = array(
                            'street_address','route','intersection',
                             'political','country','administrative_area_level_1','administrative_area_level_2','administrative_area_level_3','colloquial_area','locality','sublocality','neighborhood','premise','subpremise','postal_code','natural_feature','airport','park','point_of_interest','post_box','street_number','floor','room'))
    {
        $ret = false;

        

        if ($jsonResponse && is_array($jsonResponse) && (count($jsonResponse) > 0))
        {
          $i = 0;
          $result = $jsonResponse['results'][0];

          if ($result['address_components']
                && (is_array($result['address_components']))
                && (count($result['address_components']) > 0))
          {
                $item = $result['address_components'][$i];

                $ret = (isset($item['types'][0])) && (in_array($item['types'][0],$google_locations));

           }
        }

        return $ret;
    }

  /**
   * esta funcion es muy importante para hacer que el widget a medida
   * se valide siempre de lo contrario no se validará
   *
   * @param <type> $value
   * @return <type>
   */
  public function isEmpty($value)
  {
        return false;
  }
}


?>