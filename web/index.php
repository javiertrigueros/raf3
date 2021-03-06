<?php

//phpinfo();
//exit();

//header('Access-Control-Allow-Origin: *'); 
ini_set('memory_limit', '40000M');
ini_set('max_execution_time', 30000);

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

// DEVELOPERS: Internally, we have a separate index.php controller for each environment and 
// we rsync exclude it to avoid overwriting each environment's controller. 

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
//produccion con debug
//$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
//produccion sin debug
//$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
