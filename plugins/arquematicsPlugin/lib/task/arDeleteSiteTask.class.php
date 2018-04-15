<?php
/**
 * @package         arquematicsPlugin
 * @subpackage      task
 * @author          Javier Trigueros Martínez de los Huertos 
 * 
 * borra un subdominio del dominio principal de drupal
 * 
 * ej:
 * ./symfony arquematics:delete-site terra
 * 
 * Borra el subdominio terra.telvy.net si telvy.net fuera el dominio principal
 */
class arDeleteSiteTask extends sfBaseTask
{

 
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'Default Lang','es'),
      // add your own options here
    ));

    $this->addArgument('domainName', sfCommandArgument::REQUIRED, 'Domain Name');
    
    $this->namespace        = 'arquematics';
    $this->name             = 'delete-site';
    $this->briefDescription = 'Crea una nueva site Drupal';
    $this->detailedDescription = <<<EOF
La tarea [arquematics:delete-site <dominio>] Borra un subdominio Drupal.
    
[php symfony arquematics:delete-site <dominio>]
EOF;
  }
  
 protected function execute($arguments = array(), $options = array())
 {
    $context = sfContext::createInstance($this->configuration);
    // initialize the database connection
    // $databaseManager = new sfDatabaseManager($this->configuration);
    // $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
   
    $domainName = (isset($arguments['domainName'])?trim($arguments['domainName']):false);
    
    
    //se fuerza a crear el dominio drupal
    if ($domainName && preg_match('/^[a-zA-Z0-9]+$/i',$domainName))
    {
       
        $site = Doctrine_Core::getTable('SitesAvailable')->retrieveByDomainName($domainName,true);
     
        if ($site && is_object($site))
        {
            $arDrupalConfigurator = arDrupalConfigurator::getInstance();
    
            $arDrupalConfigurator->doDelete($site);
        
            $site->setIsActive(false);
            $site->save();
        }
        
    }
    
   
 }
 
}

