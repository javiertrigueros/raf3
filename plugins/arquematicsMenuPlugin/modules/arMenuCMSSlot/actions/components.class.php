<?php
class arMenuCMSSlotComponents extends aSlotComponents
{
    
  

  public function setup()
  {
    parent::setup();
    
    $this->options['class'] = $this->getOption('class', '');
    
    $this->values = $this->slot->getArrayValue();
    
    $this->title = false;
    if ((isset($this->values['title'])) 
        && (strlen(trim($this->values['title'])) > 0))
    {
      $this->title = $this->values['title'];    
    }
    
    $this->showTitle = false;
    
    if (isset($this->values['showTitle'])) 
    {
      $this->showTitle = true;   
    }
    
   
    $this->form = new arMenuSlotForm($this->id, $this->slot->getArrayValue());
    
    $this->treeMenuNodes = Doctrine::getTable('arMenu')->retrieveNodesByRootId($this->id);
    
     if (count($this->treeMenuNodes) > 0)
     {
        $this->rootNode = $this->treeMenuNodes->getFirst(); 
        $this->formMenu = new arMenuForm(null, array(
            'slot_name' => $this->name, 
            'permid' => $this->permid,
            'name' => $this->rootNode->getName(),
            'page_id' => $this->page->getId(),
            'root_id' => $this->id));
        unset($this->treeMenuNodes[0]);
     }
     else 
     {
        $this->rootNode = false;
        
        $this->treeMenuNodes = false;
        
         $this->formMenu = new arMenuForm(null, array(
            'slot_name' => $this->name, 
            'permid' => $this->permid,
            'name' => 'root', // el nombre por defecto es root
            'page_id' => $this->page->getId(),
            'root_id' => $this->id));
     }
  }
  
  

  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
  }
  

  public function executeNormalView()
  {
    $this->setup();
    
  }
}
