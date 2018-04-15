<?php

/**
 * arMenuAdmin Components.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arPageAdminComponents extends BaseArComponents
{
    
  public function executeShowFormById(sfWebRequest $request)
  {
    
        $this->page = aPageTable::retrieveByIdWithSlots($this->pageId);
      
        $this->parent = $this->page->getParent(true);
  }
    
  public function executeShowForm(sfWebRequest $request)
  {
       $this->form = new aPageSettingsForm($this->page, $this->parent);

       //$event = new sfEvent($this->page, 'a.filterPageSettingsForm', array('parent' => $this->parent));
       //$this->dispatcher->filter($event, $this->form);
       //$this->form = $event->getReturnValue();
       
       $this->create = $this->page->isNew();
            
       $this->slugStem = preg_replace('/\/$/', '', $this->parent->slug);
  }
  
  
  
}