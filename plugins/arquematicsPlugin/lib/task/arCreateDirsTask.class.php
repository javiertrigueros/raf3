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
class arCreateDirsTask extends sfBaseTask
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
    $this->name             = 'create-system-dirs';
    $this->briefDescription = 'Create System Directorys';
    $this->detailedDescription = <<<EOF
The task [arquematics:system-dirs] creates system directorys for aplicacion.
    
[php symfony arquematics:system-dirs]
EOF;
  }

 /**
 * Crea los subdirectorios para almacenas las imagenes
 * en los diferentes tamaños
 * @param <string> $parent: 
 * @param <string> $configurationSubDirsKey: 
 *
 * @return <boolean>: true si hace bien la operacion
 */
 protected function createSubDirs($parent, $configurationSubDirsKey)
 {
    $path = sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.$parent;

    $subDirs = sfConfig::get($configurationSubDirsKey);

    $ret = true;

    if (count($subDirs) > 0)
    {
      for ($i = 0; (($i < count($subDirs)) && $ret); $i++){ 
        //saco el nombre de la configuracion
        $dirname = split(":", $subDirs[$i]);
        $dirname = $dirname[0];

        $ret = ($ret && FileUtils::setPathPermissions($path.DIRECTORY_SEPARATOR.$dirname));
      }
      //se crea el subdirectorio "original" para el
      //fichero sin modificaciones
      $ret = $ret && FileUtils::setPathPermissions($path.DIRECTORY_SEPARATOR.'original');
    }

    return $ret;
 }

 protected function createToolsSubDirs()
 {
  $sysInfo = arSystemInfo::getInstance();
  $enabledTools = $sysInfo->getEnabledTools(true);

  $ret = true;

  if (count($enabledTools) > 0)
  {
    for ($i = 0; (($i < count($enabledTools)) && $ret); $i++)
    {
        //si el tool tiene sistema de archivos 
        if ($enabledTools[$i]['hasFileSystemDir'])
        {
          $ret = ($ret && $this->createSubDirs($enabledTools[$i]['main-dir'], $enabledTools[$i]['subdirs']));
          //ha habido un error
          if (!$ret)
          {
            $this->logSection('create-system-dirs', 'Error creating dir '.$enabledTools[$i]['main-dir']);
          }
        }
    }
  }

  return $ret;
 }
  
 protected function execute($arguments = array(), $options = array())
 {

    $this->logSection('create-system-dirs', 'System dirs creation inits!');

    $path = sfConfig::get('app_aToolkit_writable_dir');
    
    if (sfConfig::get('app_s3_enabled'))
    {
       if (!FileUtils::setPathPermissions(sfConfig::get('app_aToolkit_writable_tmp_dir')))
       {
         $this->logSection('create-system-dirs', "Error Creating temporal system path ".sfConfig::get('app_aToolkit_writable_tmp_dir'));
       } 
    }
    else
    {
       if (!FileUtils::setPathPermissions($path))
       {
        $this->logSection('create-system-dirs', "Error System dirs ".$path);
       }
       else if (!FileUtils::setPathPermissions(sfConfig::get('app_aToolkit_writable_tmp_dir')))
       {
         $this->logSection('create-system-dirs', "Error Creating temporal system path ".sfConfig::get('app_aToolkit_writable_tmp_dir'));
       }
       else if (!FileUtils::setPathPermissions($path.DIRECTORY_SEPARATOR.'arProfileUpload'))
       {
        $this->logSection('create-system-dirs', "Error System dirs ".$path.DIRECTORY_SEPARATOR."arProfileUpload creation!");
       }
       else if (!$this->createSubDirs('arProfileUpload', 'app_arquematics_plugin_image_profile_filters'))
       {
        $this->logSection('create-system-dirs', "Error arProfileUpload subdirs creation");
       }
       else if (!$this->createToolsSubDirs())
       {
        $this->logSection('create-system-dirs', "Error creating tool filesystem");
       }  
    }

    $this->logSection('create-system-dirs', 'System dirs ends successfully!');
 }
 
}

