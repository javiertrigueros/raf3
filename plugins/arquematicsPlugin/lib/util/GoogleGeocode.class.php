<?php
/**
 * Arquematics 2010
 *
 * @author Javier Trigueros Martínez de los Huertos <javiertrigueros@arquematics.com>
 * @version 0.1
 * 
 * Geolocalizacion con Google 
 *
 */
class GoogleGeocode
{
    static private $instancia = null;

    const GEOCODE_REVERSE = 'http://maps.google.com/maps/api/geocode/json?latlng=';
    const GEOCODE = 'http://maps.google.com/maps/api/geocode/json?address=';

    function  __construct() {}

    /**
     * implementacion patrón Singleton
     * @return <GoogleGeocode>
     */
    static public function getInstance()
    {
       if (self::$instancia == null) {
          self::$instancia = new GoogleGeocode();
       }
       return self::$instancia;
    }

    /**
     * geolocaliza una direccion
     *
     * @param <string> $address: direccion que queremos encontrar
     * @param <string> $bounds: marco del tipo 34.172684,-118.604794|34.236144,-118.500938
     *                          en el que se encuadra la busqueda.
     *                          Por defecto no hay.
     *
     * @return <array> : array vacio cuando no encuentra nada
     */
    public function getGeocode($address, $bounds = false)
    {
        if (!$bounds)
        {
           return $this->getRequest($address,self::GEOCODE);
        }
        else
        {
           return $this->getRequest($address,self::GEOCODE,"&bounds=".$bounds);
        }
    }
    /**
     * geolocalización inversa con una coordenadas
     *
     * @param <string> $latlong: coordenadas del tipo latlng=40.714224,-73.961452
     * @return <array> array vacio cuando no encuentra nada
     */
    public function getGeocodeReverse($latlong)
    {
        return $this->getRequest($latlong,self::GEOCODE);
    }
  
    /**
    *
    * Intenta geolocalizar un texto. El texto puede tener la forma: 
    *		ÜT: lat, lot ó Las Mercedes 8, Getxo 
    *
    * @param <string $query>
    * @return <array|null>: null si no encuentra nada
    */
    public function getGeocodeRequest($query)
    {
      $find = preg_match('/^ÜT:/',$query ) > 0;
      if ($find)
      {
    	$twitterLocate = trim(preg_replace('/^ÜT:/', '', $query));
      	$twitterLocate = $this->getGeocodeReverse($query);
      }
      else
      {
        $twitterLocate = trim($query);
	$twitterLocate = $this->getGeocode($query);
      }

      return $twitterLocate;
    }
    /**
     * encuentra una imformacion en la respuesta
     * 
     * @param type $result
     * @param type $type
     * @return type 
     */
    private function findInfo($result, $type) 
    {
        if ($result && is_array($result) && isset($result['address_components']))
        {
            
            for ($i = 0; $i < count($result['address_components']); $i++) 
            {
                if (isset($result['address_components'][$i]))
                {
                    $component = $result['address_components'][$i];
                    if ($component['types'][0] == $type) 
                    {
                        return $component['long_name'];
                    }
                    
                }
            } 
        }
        
        return false;
    }
    
    /**
     * intenta que devuelva la informacion basica de una petición
     * de geolocalización
     * 
     * @param <string $query>
     * @return <array|null>: null si no encuentra nada
     */
    public function getGeoCodeBasicInfo($query)
    {
      $ret = array();
      
      $rawData =  $this->getGeocodeRequest($query);
      
      
      if ($rawData && is_array($rawData) 
              && isset($rawData['status']) 
              && ($rawData['status'] == 'OK'))
      {
          $resultData = $rawData['results'];
          if ($resultData && is_array($resultData) && isset($resultData[0]['geometry']['location']['lat']))
          {
            $formatted_address = $resultData[0]['formatted_address'];
            $lat = $resultData[0]['geometry']['location']['lat'];
            $lng = $resultData[0]['geometry']['location']['lng'];

            $ret['formated_address'] = $formatted_address;
            $ret['lat'] = $lat;
            $ret['lng'] = $lng;

            if (isset($resultData[0]['geometry']['bounds']['southwest']['lat']))
            {
              $southwestlat = $resultData[0]['geometry']['bounds']['southwest']['lat'];
              $southwestlng = $resultData[0]['geometry']['bounds']['southwest']['lng'];
              $northeastlat = $resultData[0]['geometry']['bounds']['northeast']['lat'];
              $northeastlng = $resultData[0]['geometry']['bounds']['northeast']['lng'];

              $ret['south_west_lat'] = $southwestlat;
              $ret['south_west_lng'] = $southwestlng;
              $ret['north_east_lat'] = $northeastlat;
              $ret['north_east_lng'] = $northeastlng;
            }
            
            $ret['locality'] = $this->findInfo($resultData[0], 'locality');
            $ret['country'] = $this->findInfo($resultData[0], 'country');

        }  
          
      }
      
      return $ret;

     
    }


    
   /**
   * devuelve un array con los datos de la geolocalización
   * de un objeto. Si no tiene una respuesta correcta
   * devuelve un array vacio
   *
   * @param <string> $data
   * @param <string> $url
   * @return <array>
   */
  private static function getRequest($data, $url, $extraParams = "" )
  {

       $ret = array();
       $data = urlencode(trim($data));
       $url = $url.$data.$extraParams;
   
       try 
       {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $ret = json_decode(curl_exec($ch), true);
            
      } catch (Exception $e){}
      
      return $ret;
  }


}
