<?php

/**
 * PluginarWallLink
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * 
 * @package    Arquematics
 * @subpackage model
 * @author     Javier Trigueros Martinez de los Huertos javiertrigueros@arquematics.com
 * @version    0.1
 */
abstract class PluginarWallLink extends BasearWallLink
                                    implements iFileResource
{
    /**
     * es un link valido
     * 
     * @return boolean
     */
    /*
    public function hasValid()
    {
        return true;   
    }*/
    
    
    public function getPathAndFile($size = 'original')
    {
         return sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arWallLink'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$this->getFileName();
    }
    
    public function getBaseName()
    {
        $filenameitems = explode(".", $this->getFileName());
        return $filenameitems[0];
    }
    
    public function getExtension()
    {
        $filenameitems = explode(".", $this->getFileName());
        return $filenameitems[count($filenameitems) - 1];
    }
    /*
    public function getOembed()
    {
      $oembed = $this->_get('oembed');
      return json_decode($oembed, true);
    }*/
    
}