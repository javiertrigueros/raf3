<?php

/**
 * PluginarMenu
 * 
 */
abstract class PluginarMenu extends BasearMenu
{
  static public function saveHierarchy($parent, $rootId, $hierarchyArr,  $conn)
    {
       if (is_array($hierarchyArr) 
            && (count($hierarchyArr) > 0))
       {
           for ($i = count($hierarchyArr) - 1; ($i >= 0); $i--)
           {
               $dataNode = $hierarchyArr[$i];
               $arMenu = new arMenu();
               $arMenu->setName($dataNode['name']);
               $arMenu->setRootId($rootId);
               if (isset($dataNode['menu_type']))
               {
                 $arMenu->setMenuType($dataNode['menu_type']);  
               
                 if (($dataNode['menu_type'] == 'page')
                    && isset($dataNode['id']) 
                    && is_numeric($dataNode['id']))
                 {
                    $arMenu->setPageId($dataNode['id']);   
                 }
                 else if ((($dataNode['menu_type'] == 'event')
                          || ($dataNode['menu_type'] == 'blog'))
                    && isset($dataNode['id']) 
                    && is_numeric($dataNode['id']))
                 {
                    $arMenu->setCategoryId($dataNode['id']); 
                 }
                
               }
               
               
               
               $arMenu->setUrl($dataNode['url']);
              
               $parent->getNode()->addChild($arMenu, $conn);
               
               arMenu::saveHierarchy($arMenu, $rootId, $dataNode['children'],  $conn);
           }
           
       }
    }
  static public function saveMenu($rootId, $rootName, $dataArray)
  {
      $conn = Doctrine_Manager::connection();
      $conn->beginTransaction();
      try
      {
           
            Doctrine::getTable('arMenu')
                    ->queryNodesByRootId($rootId, $conn)
                    ->delete()
                    ->execute();
            
            $rootObj = arMenu::createRootNode($rootId, $rootName, $conn);
            
            arMenu::saveHierarchy($rootObj, $rootId, $dataArray, $conn);
            
            
            $conn->commit();

            return true;
        
       }
       catch (Exception $e)
       {
        $conn->rollBack();
        throw $e;
        
        return false;
       }
      
  }
  /*
  static public function saveMenu($rootId, $dataArray)
  {
      
      $conn = Doctrine_Manager::connection();
      $conn->beginTransaction();
      try
      {
           
            Doctrine::getTable('arMenu')
                    ->queryNodesByRootId($rootId, $conn)
                    ->delete()
                    ->execute();
            
            $rootObj = arMenu::createRootNode($rootId, $conn);
            
            $saveNode = array();
            
            foreach ($dataArray as $data)
            {
                if ($data['parent'] == 0)
                {
                     $arMenu = new arMenu();
                     $arMenu->setName($data['name']);
                     $arMenu->setRootId($rootId);
                     $arMenu->setPageId(isset($data['pageid'])?$data['pageid']:null);
                     $arMenu->setUrl($data['url']);
                     $arMenu->getNode()->insertAsLastChildOf($rootObj, $conn);
                     
                }
                else {

                     $arMenu = new arMenu();
                     $arMenu->setName($data['name']);
                     $arMenu->setRootId($rootId);
                     $arMenu->setPageId(isset($data['pageid'])?$data['pageid']:null);
                     $arMenu->setUrl($data['url']);
                     
                     $parentNode = $saveNode[$data['parent'] -1];
                     $arMenu->getNode()->insertAsLastChildOf($parentNode, $conn);
                           
                }
                
                $saveNode[] = $arMenu;
            }
            
            
            $conn->commit();

            return true;
        
       }
       catch (Exception $e)
       {
        $conn->rollBack();
        throw $e;
        
        return false;
       }
      
  }*/
    
  /**
   * Borra un menu cuando se borra el slot con el que esta
   * relacionado
   * 
   * @param <sfEvent $event>
   */
  static public function deleteSlot(sfEvent $event)
  {
    $params = $event->getParameters();
    
    if (is_array($params) 
      && isset($params['name'])
      && isset( $params['slot'])
      && isset($params['pageid']))
    {
       
       $conn = Doctrine_Manager::connection();
       $conn->beginTransaction();
       try
       {
            $rootId = $params['pageid'].'-'.$params['name'].'-'.$params['slot'];
      
            Doctrine::getTable('arMenu')
                    ->queryNodesByRootId($rootId, $conn)
                    ->delete()
                    ->execute();
            
            $conn->commit();

            return true;
        
       }
       catch (Exception $e)
       {
        $conn->rollBack();
        throw $e;
        
        return false;
       }
    }
  }
  
  /**
   * agrega un slot, tambien se ejecuta cuando se edita el slot
   * 
   * @param <sfEvent $event>
   */
  static public function addslot(sfEvent $event)
  {
    $params = $event->getParameters(); 
    $ret = false;
   
    if (is_array($params) 
      && isset($params['type']) 
      && isset($params['name'])
      && isset($params['pageid'])
      && (($params['type'] === 'arMenuCMS')
          || ($params['type'] === 'arMenuSecundary'))
      )
    {
       $rootId = $params['pageid'].'-'.$params['name'].'-'.$params['slot'];
       $treeNodes = Doctrine::getTable('arMenu')->retrieveNodesByRootId($rootId);
        
       $conn = Doctrine_Manager::connection();
       $conn->beginTransaction();
       try
       {
           $dataVal = false;
           $rootName = "root";
           
           if (isset($params['values']))
           {
              $dataVal = unserialize($params['values']);
              if (isset($dataVal['title']))
              {
                 $rootName = $dataVal['title'];
              }
           }
           
           if (count($treeNodes) > 0)
           {
               $rootNode = $treeNodes[0];
               $rootNode->setName($rootName);
               $rootNode->save($conn);
           }
           else
           {
              $ret =  arMenu::createRootNode($rootId, $rootName, $conn);   
           }
           
           $conn->commit();
           
       }
       catch (Exception $e)
       {
        $conn->rollBack();
        throw $e;
        
        return $ret;
       }
    }
    
    return $ret;
  }
  
  /**
   * crea un nodo raiz
   * 
   * @param <string $rootNameId>
   * @param <string $rootName>
   * @return <arMenu>| <booleam> false si ha habido algun error al crear el nodo root
   */
  static public function createRootNode($rootNameId, $rootName, $conn)
  {
      $rootNode = false;
      $treeObject = Doctrine_Core::getTable('arMenu')->getTree();
      if ($treeObject && is_object($treeObject))
      {
         $rootPage = aPageTable::retrieveBySlug('/');
          
         $rootNode = new arMenu();
         $rootNode->setName($rootName);
         $rootNode->setRootId($rootNameId);
         $rootNode->setPageId( $rootPage->getId());
         $rootNode->setUrl('/');
         $rootNode->save($conn);
         
         $treeObject->createRoot($rootNode);
      }
      
      return $rootNode;
  }

}