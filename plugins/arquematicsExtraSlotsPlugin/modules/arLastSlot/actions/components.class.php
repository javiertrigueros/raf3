<?php
class arLastSlotComponents extends aSlotComponents
{

  public function setup()
  {
    parent::setup();
  }
  
  public function getQuery()
  {

      $q = Doctrine::getTable('aBlogItem')->createQuery()
      ->leftJoin('aBlogItem.Author a')
      ->leftJoin('aBlogItem.Categories c')
      ->select('aBlogItem.*, a.*, c.*');
     
    Doctrine::getTable('aBlogItem')->addPublished($q);
    if (!isset($this->values['count']))
    {
        $this->values['count'] = 3;
    }
    $q->limit($this->values['count']);
    $q->orderBy('published_at desc');
    return $q;
    
  }
    
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();

    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new arLastSlotForm($this->id, $this->slot->getArrayValue());
    }

   
  }
  

  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = $this->getQuery();
    
    $this->title_head = false;
    if ((isset($this->values['title_head'])) 
        && (strlen(trim($this->values['title_head'])) > 0))
    {
      $this->title_head = $this->values['title_head'];
    }
   
    //echo $q->getSqlQuery();
    $this->aBlogPosts = $q->execute();
    $this->aBlogPostsMedia = array();
    aBlogItemTable::populatePages($this->aBlogPosts);
    
    
  }
}
