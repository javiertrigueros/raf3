<?php

class arMenuAdminActions extends BaseArActions
{
  
  public function executeMenuEdit(sfRequest $request)
  {
      $this->form = new arMenuEditForm();
      $values = $request->getParameter($this->form->getName());
      
      $this->form->bind($values);
      
      if ( $this->checkView() 
          && $this->isCMSAdmin()
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          $values = $this->form->getValues();
          
          $content = utf8_encode($values['dataJson']);
          
          $dataVal = json_decode($content, true);
          
         
          arMenu::saveMenu(
                  $values['root_id'],
                  $values['name'], 
                  arNestedHelp::toHierarchy($dataVal));
          
          $this->aPage = aPageTable::retrieveByIdWithSlots($values['page_id']);
          
          //$this->slot = $this->aPage->getSlot($values['slot_name'], $values['permid']);
          
         
          //$this->slot->setArrayValue(array('title' => $values['name']));
       
          //$this->slot->save();
          
          $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => $values,
                    "HTML" => '');
          
      }
      else
      {
          $this->data = array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => $values,
                    "HTML" => '');
      }
      
      
    //devuelve el contenido en Json de $this->data
    $this->returnJson();
      
  }

  public function executeMenuCreate(sfRequest $request)
  {
       // https://github.com/wp-plugins/admin-menu-editor
      
      $this->form = new arMenuForm();
      
      $values = $request->getParameter($this->form->getName());
   
      $this->form->bind($values);
      
      if ( $this->checkView() 
          && $this->isCMSAdmin()
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
      {
          $this->loadUser();
          
          $values = $this->form->getValues();
          
          $this->aPage = aPageTable::retrieveByIdWithSlots($values['page_id']);
                                         
          $this->treeMenuNodes = Doctrine::getTable('arMenu')->retrieveNodesByRootId($values['root_id']);
          
          $this->formMenuEditForm = new arMenuEditForm(null, array(
              'page_id' => $values['page_id'],
              'slot_name' => $values['slot_name'], 
              'permid' => $values['permid'],
              'treeMenuNodes' => $this->treeMenuNodes));
          
          $root = aPageTable::retrieveBySlug('/');
          $this->allPagesData = $root->getTreeInfo(false);
          
          $this->categories = Doctrine_Core::getTable('aCategory')
                    ->addCategoriesForUser($this->authUser, true)
                    ->execute();
          
        
          
      }
      else
      {
          $this->redirect('@homepage');
      }
      
      
       //$this->setLayout(sfContext::getInstance()->getConfiguration()->getTemplateDir('arWall', 'layoutWall.php') . '/layoutWall');
  }
  
}
  