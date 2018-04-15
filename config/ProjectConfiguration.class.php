<?php

// Where does Symfony live? 

$dir = dirname(__FILE__);
if (file_exists("$dir/require-core.php"))
{
  // Look for a custom Symfony require directive in require-core.php
  require_once 'require-core.php';
}
else
{
  // Use copy checked out via svn:externals
  require_once "$dir/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php";
}

require_once "$dir/../lib/vendor/autoload.php";

sfCoreAutoload::register();

// We can't autoload this because we don't have an autoloader yet
require dirname(__FILE__) . '/../plugins/apostrophePlugin/lib/toolkit/aProjectConfiguration.class.php';
//require sfConfig::get('sf_lib_dir') .'/vendor' . PATH_SEPARATOR . 'autoload_real.php';

class ProjectConfiguration extends aProjectConfiguration
{
  public function setup()
  {
    // We do this here because we chose to put Zend in lib/vendor/Zend.
    // If it is installed system-wide then this isn't necessary to
    // enable Zend Search
    set_include_path(
      sfConfig::get('sf_lib_dir') .
        '/vendor' . PATH_SEPARATOR . get_include_path());
    // ORDER IS SIGNIFICANT. sfDoctrinePlugin logically comes first followed by sfDoctrineGuardPlugin.
    // apostrophePlugin must precede apostropheBlogPlugin. 
    $this->enablePlugins(array(
      'sfDoctrinePlugin',
      'sfDoctrineGuardPlugin',
      'sfDoctrineActAsTaggablePlugin',
      'sfTaskExtraPlugin',
      'sfWebBrowserPlugin',
      'sfFeed2Plugin',
      'sfSyncContentPlugin',
      'apostrophePlugin',
      'apostropheBlogPlugin',
      'apostropheExtraSlotsPlugin',
      'apostropheFeedbackPlugin',
      'apostropheImportersPlugin',
      'apostropheMysqlSearchPlugin',
      'apostropheAwesomeLoginPlugin',
      'aS3StreamWrapperPlugin',
      'arGravatarPlugin',
       //arquematics
      'arquematicsMenuPlugin',
      'arquematicsExtraSlotsPlugin',
      'arquematicsPlugin',
      //'arquematicsChatPlugin',
      'arquematicsTelegramPlugin',
      'arquematicsDocumentsPlugin',
      'sfDoctrineOAuthPlugin',
      'sfMelodyPlugin'

      //'arquematicsWorkflowPlugin',
			));
  }
  
  // Known Symfony issue: this method is sometimes called more than once
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
