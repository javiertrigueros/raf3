<?php
/**
 *  devuelve una imagen gravatar
 * 
 * @param <string $email>: email del usuario
 * @param <string $alt>: texto alternativo
 * @param <string $cssClass>: clase css de la iamgen
 * 
 * @return <string>
 */
function gravatarImage($email, $sizeType = false, $cssClass = 'avatar', $alt = 'Gravatar' )
{

  $gravatar = arGravatarApi::getInstance();
  
  $defaultQueryImg = $gravatar->getDefaultQuery($sizeType);
  
  $idHTML = uniqid('gravatar-');
  
  include_js_call('arGravatar/jsGravatar', array(
      'defaultQueryImg' => $defaultQueryImg,
      'id' => $idHTML));
   
  if (!$sizeType)
  {
    $avatarSize = sfConfig::get('app_arquematics_gravatar_size');   
  }
  else {
    $avatarSize = sfConfig::get('app_arquematics_gravatar_size_'.$sizeType); 
  }
  
  
  $ret = image_tag($gravatar->getGravatarQuery($email, $avatarSize),
                   array('alt' => $alt,
                         'id' => $idHTML,
                         'height' => $avatarSize,
                         'width' => $avatarSize,
                         'class' => $cssClass
                        )
                  );
  
  return $ret;
}

