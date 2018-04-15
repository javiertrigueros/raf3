<?php

class arGravatarApi
{
  private static $instance = null;
  
  protected $domain, 
            $rating, 
            $defaultImage,
            $defaultImageSmall,
            $imageSizeSmall,
            $imageSize;

  public function __construct()
  {
    $this->domain = sfConfig::get('app_arquematics_domain', 'localhost');
    
    $this->rating = sfConfig::get('app_arquematics_gravatar_rating', 'G');
    
    $this->defaultImage = sfConfig::get('app_arquematics_gravatar_image_url', '/arGravatarPlugin/images/default.jpg');
    
    $this->defaultImageSmall = sfConfig::get('app_arquematics_gravatar_image_small', '/arGravatarPlugin/images/defaultmini.jpg');

    $this->imageSize = sfConfig::get('app_arquematics_gravatar_size', 67);

    $this->imageSizeSmall = sfConfig::get('app_arquematics_gravatar_size_small', 16);
    
  }
  
  public static function getInstance() 
  {
	if (arGravatarApi::$instance == null) 
        {
            arGravatarApi::$instance = new arGravatarApi;  
	}
	return arGravatarApi::$instance;
                
  }
  
  public function getGravatarQuery($email, $size = false)
  {
      $md5Email = md5(strtolower(trim($email)));
      
      if (!$size)
      {
          return 'https://www.gravatar.com/avatar.php?gravatar_id='.$md5Email.
                           '&size='.$this->imageSize.
                           '&rating='.$this->rating.
                           '&default='.$this->getDefaultQuery();
          
      }
      else {
           return 'https://www.gravatar.com/avatar.php?gravatar_id='.$md5Email.
                           '&size='.$size.
                           '&rating='.$this->rating.
                           '&default='.$this->getDefaultQuery();
      }
      
  }
  /**
   * si no tiene parametros o ($imageSizeType == false), 
   * devuelve la imagen por defecto del avatar
   * @param <string||bool $imageSizeType>
   * @return <string>
   */
  public function getDefaultQuery($imageSizeType = 'avatar')
  {
      if (!$imageSizeType || ($imageSizeType === 'avatar'))
      {
        return 'http://'.$this->domain.$this->defaultImage;  
      }
      else if ($imageSizeType === 'small') {
        return 'http://'.$this->domain.$this->imageSizeSmall;
      }
  }
  
  public function isUser($email)
  {
      $md5Email = md5( strtolower(trim($email)));
      
      $url = 'http://www.gravatar.com/'.$md5Email.'.json';
      
      
  }


  
}