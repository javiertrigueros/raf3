<?php
/**
 * @package         arquematicsPlugin
 * @subpackage      task
 * @author          Javier Trigueros Martínez de los Huertos 
 * 
 * Crea los directorios necesarios para que la aplicación
 * functione correctamente
 *
 * ej:
 * ./symfony arquematics:create-system-dirs 
 * 
 * Borra el subdominio terra.telvy.net si telvy.net fuera el dominio principal
 */
class arCreateS3DirsTask extends sfBaseTask
{

 
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'Default Lang','es'),
      // add your own options here
    ));

    //$this->addArgument('domainName', sfCommandArgument::REQUIRED, 'Domain Name');
    
    $this->namespace        = 'arquematics';
    $this->name             = 'create-s3-system-dirs';
    $this->briefDescription = 'Create S3 System Directorys';
    $this->detailedDescription = <<<EOF
The task [arquematics:create-s3-system-dirs] creates s3 system directorys for aplicacion.
    
[php symfony arquematics:create-s3-system-dirs]
EOF;
  }

 
 protected function execute($arguments = array(), $options = array())
 {
    $context = sfContext::createInstance($this->configuration);
    

    $this->logSection('create-s3-system-dirs', 'System dirs s3 creation inits!');

    $privateBucket = sfConfig::get('app_aToolkit_writable_dir');
    $publicBucket = sfConfig::get('app_a_static_dir');
   
    
    if (!@mkdir($privateBucket))
    {
       $this->logSection('create-s3-system-dirs', "Error creating private s3 filesystem: $privateBucket"); 
    }
    
    if (!@mkdir($publicBucket))
    {
       $this->logSection('create-s3-system-dirs', "Error creating public s3 filesystem: $publicBucket"); 
    }
      
   
    $this->logSection('create-s3-system-dirs', 'System s3 dirs ends successfully!');
 }
 
}

