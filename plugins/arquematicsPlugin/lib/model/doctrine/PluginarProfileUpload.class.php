<?php


/**
 * PluginarProfileUpload
 * 
 * modelo de datos de los messages
 * 
 * @package    Arquematics
 * @subpackage model
 * @author     Javier Trigueros Martinez de los Huertos javiertrigueros@arquematics.com
 * @version    0.1
 */
abstract class PluginarProfileUpload extends BasearProfileUpload
                                        implements iFileResource
{
    
   /**
   *
   * @param Doctrine_Connection $conn
   * @return <boolean>
   */
  public function save(Doctrine_Connection $conn = null)
  {
    $ret = false;
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        if ($this->getIsProfile())
        {
            Doctrine_Query::create($conn)
                ->update('arProfileUpload u')
                ->set('u.is_profile','?', false)
                ->where('user_id = ?', $this->getUserId())
                ->execute();
        }
         
        parent::save($conn); 

        
        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
    }
    
    return $ret;
  }
  
  
  
   public function getPathAndFile($size = 'original')
   {
         return sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arProfileUpload'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$this->getFileName();
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
   
}