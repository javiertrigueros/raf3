<?php
/**
 * @package         arquematicsPlugin
 * @subpackage      task
 * @author          Javier Trigueros Martínez de los Huertos 
 * 
 * crea un nuevo sitio subdominio en drupal, si se le pasa un
 * parametro fuera a crear un nuevo site
 * 
 * ej:
 * ./symfony arquematics:create-site terra
 * 
 * Crea el subdominio terra.telvy.net si telvy.net fuera el dominio principal
 */
class arCreateSiteTask extends sfBaseTask
{

  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'Default Lang','es'),
      // add your own options here
    ));

    $this->addArgument('domainName', sfCommandArgument::OPTIONAL, 'Nombre de dominio');
   
    
    $this->namespace        = 'arquematics';
    $this->name             = 'create-site';
    $this->briefDescription = 'Crea una nueva site Drupal';
    $this->detailedDescription = <<<EOF
La tarea [arquematics:create-site <dominio> <user>] Crea un nuevo subdominio con Drupal y inicializandolo con
los valores por defecto.
    
[php symfony arquematics:create-site <dominio> <admin pass>]
EOF;
  }
  
 protected function execute($arguments = array(), $options = array())
 {
    $context = sfContext::createInstance($this->configuration);
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
    //$connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
   
    $domainName = (isset($arguments['domainName'])?trim($arguments['domainName']):false);
    //sino hay parametro domainName
    if(!$domainName)
    {
      //ultimo sitio creado no activo
      $site = Doctrine_Core::getTable('SitesAvailable')->getLastSite();  
      //siempre que el sitio no este entre los
      //sitios de sistema
      $subdomains = sfConfig::get('app_drupal_config_system_subdomains');
    
      if ($subdomains && is_array($subdomains))
      {
          $site = (in_array($site->getName(),$subdomains))?$site:false;
      }
      
    }
    //se fuerza a crear el dominio drupal
    else if ($domainName && preg_match('/^[a-zA-Z0-9]+$/i',$domainName))
    {
          $site = Doctrine_Core::getTable('SitesAvailable')->retrieveByDomainName($domainName,false);
     
    }
    
   
    if ($site && is_object($site))
    {
        $arDrupalConfigurator = arDrupalConfigurator::getInstance();
    
        $arDrupalConfigurator->doCreation($site); 
        
        //marca el sitio como activo
        $site->setIsActive(true);
        $site->save();
    }
   
 }
}