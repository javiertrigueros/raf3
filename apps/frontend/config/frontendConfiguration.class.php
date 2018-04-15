<?php
/*
$dir = dirname(__FILE__);
if (file_exists("$dir/require-core.php"))
{
  // Look for a custom Symfony require directive in require-core.php
  require_once 'require-core.php';
}
else
{
 
  // Use copy checked out via svn:externals
  require_once '/var/www/vhosts/alcoor.com/httpdocs/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
}

sfCoreAutoload::register();*/

// We can't autoload this because we don't have an autoloader yet
//require dirname(__FILE__) . '/../plugins/apostrophePlugin/lib/toolkit/aProjectConfiguration.class.php';

class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
      set_include_path(sfConfig::get('sf_lib_dir') .'/vendor' . PATH_SEPARATOR . get_include_path());
      
   
    // ORDER IS SIGNIFICANT. sfDoctrinePlugin logically comes first followed by sfDoctrineGuardPlugin.
    // apostrophePlugin must precede apostropheBlogPlugin. 
    
  }
  
  static protected $first = true;
  
  public function configureDoctrineConnectionDoctrine($conn)
  {
    if (!self::$first)
    {
      return;
    }
    self::$first = false;
    $wrapper = new aS3StreamWrapper();
    $wrapper->register(array('protocol' => 's3private', 'key' => sfConfig::get('app_s3_key'), 'secretKey' => sfConfig::get('app_s3_secret_key'), 'acl' => AmazonS3::ACL_PRIVATE, 'cache' => aCacheTools::get('s3privateCache'), 'region' => sfConfig::get('app_s3_region')));
    $wrapper->register(array('protocol' => 's3public', 'key' => sfConfig::get('app_s3_key'), 'secretKey' => sfConfig::get('app_s3_secret_key'), 'acl' => AmazonS3::ACL_PUBLIC, 'cache' => aCacheTools::get('s3publicCache'), 'region' => sfConfig::get('app_s3_region')));
  }
}
