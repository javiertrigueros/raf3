<?php
/**
 * @package         arquematicsPlugin
 * @subpackage      task
 * @author          Javier Trigueros Martínez de los Huertos 
 * 
 * actualiza los subdominios de sistema dejandolos en blanco
 * 
 * ej:
 * 
 * ./symfony arquematics:clear-subs
 * 
 * vacia de datos los subdominios del array en app.yml -> drupal_config_system_subdomains
 */
class arClearSystemSubdomainsTask extends sfBaseTask
{

  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'Default Lang','es'),
      // add your own options here
    ));
   
    
    $this->namespace        = 'arquematics';
    $this->name             = 'clear-subs';
    $this->briefDescription = 'Borra los datos de los subdominios de systema';
    $this->detailedDescription = <<<EOF
La tarea [arquematics:update-system-subs] deja los subdominios de systema en blanco
borrando sus bases de datos.
    
[php symfony arquematics:clear-subs]
EOF;
  }
  
 protected function execute($arguments = array(), $options = array())
 {
    $context = sfContext::createInstance($this->configuration);
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
   
    $subdomains = sfConfig::get('app_drupal_config_system_subdomains');
    
    if ($subdomains && is_array($subdomains))
    {
        foreach ($subdomains as $domain)
        {

           $task = new arDeleteSiteTask($this->dispatcher, new sfFormatter());
           $task->run(array('argument_name' => $domain));
           
           $task = new arCreateSiteTask($this->dispatcher, new sfFormatter());
           $task->run(array('argument_name' => $domain));
           
        }
    }
    
   
 }
}