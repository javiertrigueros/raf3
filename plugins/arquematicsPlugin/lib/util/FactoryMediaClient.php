<?php

/**
 * uso de patron factory, para economizar
 * se implementa todo con las clases de symfony. El 
 * metodo create seria retrieveById ;)
 *
 * @author javier
 */
class FactoryMediaClient {
    
    private static $wallMediaType = 
            array('arWallUpload', 
                'arProfileUpload',
                'arDiagram',
                'arWallLink');
    
   
    static public function byId($name, $id)  
    {  
        if (!in_array($name, self::$wallMediaType)) {  
           throw new Exception('wallMediaType undefined');  
        }
        
        $ret = null;
        switch ($name) 
        {  
            case 'arWallUpload': 
                $ret = Doctrine_Core::getTable('arWallUpload')->retrieveById($id); 
            break;
            case 'arProfileUpload':
                $ret = Doctrine_Core::getTable('arProfileUpload')->retrieveById($id); 
            break;
            case 'arDiagram':
                $ret = Doctrine_Core::getTable('arDiagram')->retrieveById($id); 
            break;
            case 'arWallLink':
                $ret = Doctrine_Core::getTable('arWallLink')->retrieveById($id); 
            break;
        }
        
        return $ret;
    }  
    
    static public function byName($type, $name)  
    {  
        if (!in_array($type, self::$wallMediaType)) {  
           throw new Exception('wallMediaType undefined');  
        }
        
          
        $ret = null;
        switch ($type) 
        {  
            case 'arWallUpload': 
                $ret = Doctrine_Core::getTable('arWallUpload')->retrieveByName($name); 
            break;
            case 'arProfileUpload':
                $ret = Doctrine_Core::getTable('arProfileUpload')->retrieveByName($name);
            break;
            case 'arDiagram':
                $ret = Doctrine_Core::getTable('arDiagram')->retrieveByName($name); 
            break;
            case 'arWallLink':
                $ret = Doctrine_Core::getTable('arWallLink')->retrieveByName($name); 
            break;
        }
        
        return $ret;
    }  
    
}

?>
