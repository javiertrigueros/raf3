<?php
/**
 * devuelve una cadena de texto con el tiempo 
 * 
 * @param <string $session_time> 
 * @return <string>
 */
function time_stamp($session_time) 
{ 
    $session_time = strtotime($session_time);

    $time_difference = time() - $session_time ; 
    $seconds = $time_difference ; 
    $minutes = round($time_difference / 60 );
    $hours = round($time_difference / 3600 ); 
    $days = round($time_difference / 86400 ); 
    $weeks = round($time_difference / 604800 ); 
    $months = round($time_difference / 2419200 ); 
    $years = round($time_difference / 29030400 ); 

    if($seconds <= 60)
    {
        echo __('%seconds% seconds ago', array('%seconds%' => $seconds), 'wall');
    }
    else if($minutes <=60)
    {
        if($minutes==1)
        {
            echo __('one minute ago', null, 'wall');
        }
        else
        {
            echo __('%minutes% minutes ago', array('%minutes%' => $minutes), 'wall');
        }
    }
    else if($hours <=24)
    {
        if($hours==1)
        {
            echo __('one hour ago', null, 'wall');
        }
        else
        {
            echo __('%hours% hours ago', array('%hours%' => $hours), 'wall');
        }
    }
    else if($days <=7)
    {
        if($days==1)
        {
            echo __('one day ago', null, 'wall');
        }
        else
        {
            echo __('%days% days ago', array('%days%' => $days), 'wall');
        }
    }
    else if($weeks <=4)
    {
        if($weeks==1)
        {
            echo __('one week ago', null, 'wall');
        }
        else
        {
            echo __('%weeks% weeks ago', array('%weeks%' => $weeks), 'wall');
        }
    }
    else if($months <=12)
    {
        if($months==1)
        {
            echo __('one month ago', null, 'wall');
        }
        else
        {
             echo __('%months% months ago', array('%months%' => $months), 'wall');
        }   
    }
    else
    {
        if($years==1)
        {
            echo __('one year ago', null, 'wall');
        }
        else
        {
            echo __('%years% years ago', array('%years%' => $years), 'wall');
        }
    }
} 
/**
 * devuelve los enlaces del texto
 * 
 * @param <string $text>
 * @return <string>
 */
function textlink($text){
   $text = html_entity_decode($text);
   $text = " ".$text;
   $ret = false;
   
   if(preg_match_all('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',$text,$match))
   {
      $ret = $match[0];
   }
   
   return $ret;
}

function getGoogleMapControl($itemId, $jsonData)
{

    $ret = "<div id='map-".$itemId."' class='wall-link-locate'>";
    $ret .= "<form id='mapform-".$itemId."' name='mapform-".$itemId."' action='#' method='POST'>";
    
    if (isset($jsonData['formated_address']))
    {
      $ret .= "<input type='hidden' id='locate_formated_address-".$itemId."' name='locate[formated_address]' value='".$jsonData['formated_address']."' />";  
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_formated_address-".$itemId."' name='locate[formated_address]' value='' />";    
    }
    
    if (isset($jsonData['address']))
    {
      $ret .= "<input type='hidden' id='locate_address-".$itemId."' name='locate[address]' value='".$jsonData['address']."' />";   
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_address-".$itemId."' name='locate[address]' value='' />";   
    }
    
    if (isset($jsonData['locality']))
    {
      $ret .= "<input type='hidden' id='locate_locality-".$itemId."' name='locate[locality]' value='".$jsonData['locality']."' />";  
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_locality-".$itemId."' name='locate[locality]' value='' />";   
    }
    
    if (isset($jsonData['country']))
    {
      $ret .= "<input type='hidden' id='locate_country-".$itemId."' name='locate[country]' value='".$jsonData['country']."' />";  
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_country-".$itemId."' name='locate[country]' value='' />";    
    }
    
    if (isset($jsonData['lat']))
    {
      $ret .= "<input type='hidden' id='locate_latitude-".$itemId."' name='locate[latitude]' value='".$jsonData['lat']."' />";   
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_latitude-".$itemId."' name='locate[latitude]' value='' />";     
    }
    
    if (isset($jsonData['lng']))
    {
       $ret .= "<input type='hidden' id='locate_longitude-$itemId' name='locate[longitude]' value='".$jsonData['lng']."' />"; 
    }
    else
    {
       $ret .= "<input type='hidden' id='locate_longitude-$itemId' name='locate[longitude]' value='' />"; 
    }
    
    if (isset($jsonData['south_west_lat']))
    {
      $ret .= "<input type='hidden' id='locate_south_west_lat-".$itemId."' name='locate[south_west_lat]' value='".$jsonData['south_west_lat']."' />";  
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_south_west_lat-".$itemId."' name='locate[south_west_lat]' value='' />";    
    }
    
    if (isset($jsonData['south_west_lng']))
    {
      $ret .= "<input type='hidden' id='locate_south_west_lng-".$itemId."' name='locate[south_west_lng]' value='".$jsonData['south_west_lng']."' />";
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_south_west_lng-".$itemId."' name='locate[south_west_lng]' value='' />";  
    }
    
    if (isset($jsonData['north_east_lat']))
    {
      $ret .= "<input type='hidden' id='locate_north_east_lat-".$itemId."' name='locate[north_east_lat]' value='".$jsonData['north_east_lat']."' />";   
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_north_east_lat-".$itemId."' name='locate[north_east_lat]' value='' />";     
    }
    
    if (isset($jsonData['north_east_lng']))
    {
      $ret .= "<input type='hidden' id='locate_north_east_lng-".$itemId."' name='locate[north_east_lng]' value='".$jsonData['north_east_lng']."' />";
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_north_east_lng-".$itemId."' name='locate[north_east_lng]' value='' />";
    }
    
    if (isset($jsonData['zoom']))
    {
      $ret .= "<input type='hidden' id='locate_zoom-".$itemId."' name='locate[zoom]' value='".$jsonData['zoom']."' />";  
    }
    else
    {
      $ret .= "<input type='hidden' id='locate_zoom-".$itemId."' name='locate[zoom]' value='' />";    
    }
    
    $ret .= "</form>";
    $ret .= "</div>";
    
    return $ret;
}

function getGoogleStaticImage($locate)
{
    $filters = sfConfig::get('app_arquematics_plugin_image_maps_filters');
 
    foreach ($filters as $filter)
    {
        list($name,$data) = explode(':',$filter);
        if ($name == 'small')
        {
            list($smallW, $smallH)  = explode(',',$data);
            break;
        }
    }
    
    $zoom = $locate->getZoom();
    
    if (isset($zoom) && (strlen(trim($zoom)) > 0))
    {
     return "<img src='http://maps.google.com/maps/api/staticmap?center=".$locate->getLatitude().",".$locate->getLongitude()."&zoom=".$zoom."&size=".$smallW.'x'.$smallH."&maptype=roadmap' />";   
    }
    else
    {
       return "<img src='http://maps.google.com/maps/api/staticmap?center=".$locate->getLatitude().",".$locate->getLongitude()."&zoom=".getGoogleZoom(
                    $locate->getSouthWestLat(),
                    $locate->getNorthEastLat(),
                    $locate->getSouthWestLng(),
                    $locate->getNorthEastLng())."&size=".$smallW.'x'.$smallH."&maptype=roadmap' />"; 
    }
     
}

/**
 * Zoom the google a partir de su bounds
 *
 * @param <int $minLnW>
 * @param <int $maxLnE>
 * @param <int $minLatS>
 * @param <int $maxLatN>
 * @return <int> 
 */
function getGoogleZoom($minLnW, $maxLnE, $minLatS, $maxLatN) 
{
    /*
    $str = '<script type="text/javascript">';
    $str .= 'var GLOBE_WIDTH = 256;';
    $str .= 'var west = '.$jsonData['north_east_lng'].';';
    $str .= 'var east = '.$jsonData['south_west_lng'].';';
    $str .= 'var angle = east - west;';
    $str .= 'if (angle < 0) {angle += 360;}';
    $str .= 'var d = Math.round(Math.log(960 * 360 / angle / GLOBE_WIDTH) / Math.LN2);';
    $str .= 'alert(d);';
    $str .= '</script>';
    
    */
    //constante e google
    $GLOBE_WIDTH = 256; 
    
    $angle = $maxLnE - $minLnW;
  
    $angle = ($angle < 0)?$angle + 360:$angle;
    
    /*
    $angle2 = $maxLatN - $minLatS;
    
    $angle = ($angle2 > $angle)?$angle2:$angle;
    */
    $zoom = ceil(log(960 * 360 / $angle / $GLOBE_WIDTH) / log(2));
    $zoom -=10;
    
    return (($zoom >= 0) && ($zoom <= 21))?$zoom:abs($zoom);
}
/**
 * trata los datos el embed.ly
 * 
 * @param <string $oembedHtml>
 * @return <string> devuelve algo como <iframe width='$width' height='$height' src='http://www.youtube.com/embed/$videoId?wmode=opaque&autoplay=1'></iframe>
 */
function videoOembed($oembedHtml)
{
     $ret = $oembedHtml.'</iframe>';
     $matches = array();
     
     if (preg_match("/http:\/\/www\.youtube\.com\/embed\/(.*?)\?/i", $oembedHtml, $matches))
     {
         if (preg_match("/\/embed\/(.*?)\?/i", $oembedHtml, $matches))
         {
             $videoId = $matches[1];
             if (preg_match('/width="(\d+)"/', $oembedHtml, $matches))
             {
               $width = $matches[1];
               if (preg_match('/height="(\d+)"/', $oembedHtml, $matches))
               {
                  $height = $matches[1];
                  $ret = "<iframe width='$width' height='$height' src='http://www.youtube.com/embed/$videoId?wmode=opaque&autoplay=1'></iframe>";
               }
             }
             
         }
     }
     
     return $ret;
}
/**
 *
 * genera una id aleatoria
 * 
 * @return <string>
 */
function generateWallId()
{
  return substr(time().rand(11111, 99999), 0, 20 );  
}

?>
