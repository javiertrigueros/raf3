<?php

class arDrupalConfigurator
{
    static private $instancia = null;
    
    function  __construct() {}
    
    /**
     * implementacion patrón Singleton
     * @return <arDrupalConfigurator>
     */
    static public function getInstance()
    {
       if (self::$instancia == null) {
          self::$instancia = new arDrupalConfigurator();
       }
       return self::$instancia;
    }
    
    /**
     * crea un nuevo dominio de para un ususario
     * 
     * @param <site> nuevo objeto site
     * 
     * @return <boolean>: true si se ha creado el nuevo sitio
     */
    public function doCreation($site)
    {

        $pass = $site->getSitePass();
        $domain = $site->getName();
        $user = $site->getSfGuardUser();
        
        if (!$this->canCreateFiles($domain))
        {
            return false;
        }
        else if (!$this->createDirs($domain))
        {
            return false;
        }
        else if (!$this->createConfigFiles($domain))
        {
            return false;
        }
        else if (!$this->createTables($domain))
        {
            return false;
        }
        else if (!$this->changeDrupalUserPass($domain, $user, $pass))
        {
           return false; 
        }
        
        return true;
    }
    
  private function changeDrupalUserPass($domain, $user, $pass)
  {
        $ajs_db = sfContext::getInstance()
            ->getDatabaseManager()
            ->getDatabase('drupal_database');
        // 
        $params = array(
          $ajs_db->getParameter('dsn'), 
          $ajs_db->getParameter('username'),
          $ajs_db->getParameter('password'),
          $ajs_db->getParameter('encoding'),
        );
    
        $conn = Doctrine_Manager::getInstance()
            ->openConnection($params, 'database', false);

        $migrate = new aMigrate($conn->getDbh());
        
        $ret = $migrate->begin();
        
        if ($ret)
        {
            $drupal_sites_folder = sfConfig::get('app_drupal_config_sites_folder');
            $drupal_sites_folder_domain_folder = $drupal_sites_folder.DIRECTORY_SEPARATOR.$domain.'.'.sfConfig::get('app_drupal_config_domain').DIRECTORY_SEPARATOR.'files';
            
            $field_data = 's:'.strlen($drupal_sites_folder_domain_folder).':"'.$drupal_sites_folder_domain_folder.'";';
            
            $sql = "UPDATE ".$domain."_"."users SET mail = '".$user->getEmailAddress()."',pass = '".$pass."',init = '".$user->getEmailAddress()."',picture = '".$drupal_sites_folder_domain_folder."' WHERE uid =1;";
     
            $sqlItem = array($sql);
            
            $migrate->sql($sqlItem);
            
            $sql = "UPDATE ".$domain."_"."variable SET value = '".$field_data."' WHERE name = 'file_directory_path';";
            
            $sqlItem = array($sql);
            
            $migrate->sql($sqlItem);
            
            $ret = $migrate->end();
            
            if (!$ret)
            {
                $migrate->rollback(); 
            }
        }
       
        return $ret;
  }
    
  /**
  * 
  * mira si puede crear el nuevo dominio
  * 
  * @param <string $domainName>: nombre del dominio que queremos crear
  * 
  * @return <int>: 
  *              false error ya se ha instalado el site con este nombre no se pueden crear.
  *             -1 no existe el directorio drupal-document-root, drupal-default-site-folder
  *                 o el fichero default.settings.php
  *              
  *              true se pueden crear los ficheros
  * 
  */
 private function canCreateFiles($domainName)
 {
     $drupal_sites_folder = sfConfig::get('app_drupal_config_sites_folder');
     $drupal_document_root = sfConfig::get('app_drupal_config_document_root');
     
     $ret = false;
     
     $ret = (@is_dir($drupal_sites_folder) 
            && @is_dir($drupal_document_root)
            && @is_file($drupal_sites_folder.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'default.settings.php')
             )? true : -1;
     
     return $ret && (!is_dir($drupal_sites_folder.DIRECTORY_SEPARATOR.$domainName.'.'.sfConfig::get('app_drupal_config_domain')));
    
 }
 /** 
  * crea los directorios, con los permisos.
  * 
  * @return <boolean>: true si crea los directorios sin novedad.
  */
 private function createDirs($domain)
 {
     $drupal_sites_folder = sfConfig::get('app_drupal_config_sites_folder');
     $drupal_sites_folder_domain = $drupal_sites_folder.DIRECTORY_SEPARATOR.$domain.'.'.sfConfig::get('app_drupal_config_domain');
     

     $ret = mkdir($drupal_sites_folder_domain, 0770);
     $ret = $ret && mkdir($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'files', 0770);
     $ret = $ret && mkdir($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'tmp', 0770);
     
     $ret = $ret && symlink(
             $drupal_sites_folder.DIRECTORY_SEPARATOR.'all'.DIRECTORY_SEPARATOR. 'themes',
             $drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'themes');
     
     $ret = $ret &&  symlink(
             $drupal_sites_folder.DIRECTORY_SEPARATOR.'all'.DIRECTORY_SEPARATOR.'modules',
             $drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'modules');
     
     //permisos
     $ret = $ret & chmod($drupal_sites_folder_domain,0770);
     $ret = $ret & chmod($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'files',0770);
     $ret = $ret & chmod($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'tmp',0770);
     $ret = $ret & chmod($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'themes',0770);
     $ret = $ret & chmod($drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'modules',0770);
     
     
     return $ret && $this->chownRecursive(
             $drupal_sites_folder_domain,
             sfConfig::get('app_drupal_config_apache_user'),
             sfConfig::get('app_drupal_config_apache_group')
             );
     
 }
 /**
  * crea los archivos de configuracion para el dominio o
  * subdominio
  * 
  * @param type $domain 
  */
 private function createConfigFiles($domain)
 {
    $ret = false;
     
    $databaseConf = sfYaml::load(sfConfig::get('sf_config_dir').'/databases.yml'); 
    

    if (($databaseConf && is_array($databaseConf))
        && isset($databaseConf['all']['drupal_database']['param']['dsn'])          
        && isset($databaseConf['all']['drupal_database']['param']['username'])
        && isset($databaseConf['all']['drupal_database']['param']['password'])
            )
    {
        
        $dsn = $databaseConf['all']['drupal_database']['param']['dsn'];
        $dsnArray = explode(';',$dsn);
        
        $data =  explode('=',$dsnArray[0]);
        $serverName = $data[1];
        $serverType = explode(':',$data[0]);
        $serverType = $serverType[0];
        
        $data =  explode('=',$dsnArray[1]);
        $dbName = $data[1];
        
        $username = $databaseConf['all']['drupal_database']['param']['username'];
        $password = $databaseConf['all']['drupal_database']['param']['password'];
        
        
        $content = <<<EOF
<?php 
 /**
 *
 * Configuración generada automáticamente
 *
 */
 
 /**
 * PHP settings:
 *
 * To see what PHP settings are possible, including whether they can
 * be set at runtime (ie., when ini_set() occurs), read the PHP
 * documentation at http://www.php.net/manual/en/ini.php#ini.list
 * and take a look at the .htaccess file to see which non-runtime
 * settings are used there. Settings defined here should not be
 * duplicated there so as to avoid conflict issues.
 */
ini_set('arg_separator.output',     '&amp;');
ini_set('magic_quotes_runtime',     0);
ini_set('magic_quotes_sybase',      0);
ini_set('session.cache_expire',     200000);
ini_set('session.cache_limiter',    'none');
ini_set('session.cookie_lifetime',  2000000);
ini_set('session.gc_maxlifetime',   200000);
ini_set('session.save_handler',     'user');
ini_set('session.use_cookies',      1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid',    0);
ini_set('url_rewriter.tags',        '');
ini_set('max_execution_time',        '12000');
ini_set('memory_limit',        '512M');

EOF;
        $content .= sprintf('$db_url = \'%s://%s:%s@%s/%s\';'."\n"
                ,$serverType
                ,$username
                ,$password
                ,$serverName 
                ,$dbName);
        
        //app_main_create_domain
        
        
        $content .= sprintf('$db_prefix = \'%s_\';'."\n",$domain);
        $content .= '?>';
        
        $drupal_sites_folder = sfConfig::get('app_drupal_config_sites_folder');
        $drupal_sites_folder_domain = $drupal_sites_folder.DIRECTORY_SEPARATOR.$domain.'.'.sfConfig::get('app_drupal_config_domain');
        $file_name = $drupal_sites_folder_domain.DIRECTORY_SEPARATOR.'settings.php';
        
        $ret = file_put_contents($file_name,$content);
        
        $ret = ($ret && $this->chownRecursive(
                            $file_name,
                            sfConfig::get('app_drupal_config_apache_user'),
                            sfConfig::get('app_drupal_config_apache_group')
                        ));
        
        $ret = $ret && chmod($file_name,0770);
    }
    

     return $ret;
 }
 
 
 /**
  * cambia usuario y grupo de todos los ficheros en un directorio.
  * Tambien puede cambiar usuario y grupo a un solo fichero. 
  * 
  * Cuidado!! si hace algo mal no continua cambiando permisos
  * 
  * @param <string $path>:  path al fichero o directorio
  * @param <string $uid>:   user name
  * @param <string $gid>:   nombre del grupo
  * 
  * @return <boolean>:      true si todos los ficheros y directorios han cambiado de usuario y grupo sin problemas
  */
 private function chownRecursive ($path, $uid, $gid)
 { 
    $ret = true;
   
    if (is_dir($path)) 
    { 
      $dh = opendir($path);
      if ($dh) 
      {
         while (($file = readdir($dh)) !== false) 
         {
            if (($file!=".") && ($file!=".."))
            {
                if (!is_link($path.DIRECTORY_SEPARATOR.$file) 
                    && is_dir($path.DIRECTORY_SEPARATOR.$file)  )
                { 
                    $ret = $ret && $this->chownRecursive(
                                    $path .DIRECTORY_SEPARATOR. $file,
                                    $uid, 
                                    $gid
                                ); 
                }
                #si es un enlace o cualquier tipo de fichero
                else 
                {
                    $ret = $ret && chown($path .DIRECTORY_SEPARATOR.$file, $uid); 
                    $ret = $ret && chgrp($path .DIRECTORY_SEPARATOR.$file, $gid); 
                } 
            }
         }
         $ret = $ret && chown($path , $uid); 
         $ret = $ret && chgrp($path , $gid);
         
         closedir($dh); 
      } 
    }
    else if (is_file($path))
    {
       $ret = $ret && chown($path , $uid); 
       $ret = $ret && chgrp($path , $gid);
    }
   
    
    return $ret;
 }
 
 /**
  * crea las tablas de un dominio
  * 
  * @param <string: $domain>
  * 
  * @return booleam: true
  */
 private function createTables($domain)
 {
     $tables = sfConfig::get('app_drupal_config_tables_to_clone');
     
     $ajs_db = sfContext::getInstance()
            ->getDatabaseManager()
            ->getDatabase('drupal_database');
     // 
    $params = array(
          $ajs_db->getParameter('dsn'), 
          $ajs_db->getParameter('username'),
          $ajs_db->getParameter('password'),
          $ajs_db->getParameter('encoding'),
    );
    
    $conn = Doctrine_Manager::getInstance()
            ->openConnection($params, 'database', false);

    $migrate = new aMigrate($conn->getDbh());
    
    $ret = $migrate->begin();
    
    if ($ret)
    {
       
       foreach ($tables as $table)
       {
        
            if ($migrate->tableExists($table))
            {
                $item = array(
                "CREATE TABLE ".$domain."_".$table." LIKE $table;",
                "INSERT INTO ".$domain."_".$table." SELECT * FROM $table;"
                );
                $migrate->sql($item);
            }
        }
        
        $ret = $ret && $migrate->end();
        
        if (!$ret)
        {
           $migrate->rollback(); 
        }
    }
    
    return $ret;

 }
 /**
  * borra un dominio y sus tablas en Drupal
  * 
  * @param <SitesAvailable $site> nuevo objeto site
  * @return <boolean>: true si se ha borrado el dominio
  */
 public function doDelete($site)
 {
    $domain = $site->getName();
    
    if (!$this->deleteDirs($domain) )
    {
      return false;
    }
    else if (!$this->deleteTables($domain))
    {
      return false;
    }
    
    return true;
 }
 
 /** 
  * borra los directorios y ficheros de un dominio
  * 
  * @return <boolean>: true si borra los directorios sin novedad.
  */
 private function deleteDirs($domain)
 {
    $drupal_sites_folder = sfConfig::get('app_drupal_config_sites_folder');
    $drupal_sites_folder_domain = $drupal_sites_folder.DIRECTORY_SEPARATOR.$domain.'.'.sfConfig::get('app_drupal_config_domain');
     
    
    return $this->SureRemoveDir($drupal_sites_folder_domain);
 }
 
 
 
 
/**
 * borra un directorio de forma recursiva
 * 
 * @param <string $dir>
 * @return <boolean> true si borra el directorio con exito
 */
function sureRemoveDir($dir) {
    if(!$dh = @opendir($dir)) return;
    while (($obj = readdir($dh))) {
        if($obj=='.' || $obj=='..') continue;
        if (!@unlink($dir.'/'.$obj)) $this->sureRemoveDir($dir.'/'.$obj);
    }
    closedir($dh);
    return @rmdir($dir);
}
 
 /**
  * borra todas las tablas de un subdominio de drupal
  * 
  * @param <string $domain> nombre del subdominio a borrar
  * @return <boolean> true si borra las tablas
  */
 private function deleteTables($domain)
 {
     $tables = sfConfig::get('app_drupal_config_tables_to_clone');
     
     $ajs_db = sfContext::getInstance()
            ->getDatabaseManager()
            ->getDatabase('drupal_database');
     // 
    $params = array(
          $ajs_db->getParameter('dsn'), 
          $ajs_db->getParameter('username'),
          $ajs_db->getParameter('password'),
          $ajs_db->getParameter('encoding'),
    );
    
     $conn = Doctrine_Manager::getInstance()
            ->openConnection($params, 'database', false);

     $migrate = new aMigrate($conn->getDbh());
    
     $ret = $migrate->begin();
     
     if ($ret)
     {
       foreach ($tables as $table)
       {
          $item = array("DROP TABLE $domain"."_".$table.";");
          $migrate->sql($item);
       }
       $ret = $migrate->end();
       
       if (!$ret)
       {
           $migrate->rollback();
       }
     }
     
     return $ret;
    
 }

    
}