<?php

class arTagComponents extends BaseArComponents
{
  private function loadTagListAndActivate()
  {
    $hasSetActive = false;
    $this->userTags = $this->aUserProfile->getUserTags();
    $this->activeTag = false;
    
    if ($this->userTags && (count($this->userTags) > 0))
    {
       $tagList = $this->aUser->getAttribute('arWall.sort', false, 'wall');
        
       if ($tagList && (count($tagList) > 0))
       {
          $hasSetActive = true;
          for ($i = 0, $countTags = count($this->userTags);
               ($i < $countTags) ; $i++)   
            {
                $this->userTags[$i]['active'] = in_array($this->userTags[$i]['hash'],$tagList);        
            
                if ($this->userTags[$i]['active'])
                {
                   $this->activeTag = $this->userTags[$i]['hash']; 
                }
            }
       }
    }
    
    if (!$hasSetActive)
    {
        for ($i = 0; $i < count($this->userTags); $i++) 
        {
          $this->userTags[$i]['active'] = false;        
        }
    }
  }
 
  public function executeShowTagControl(sfWebRequest $request)
  {
    $this->form = new arTagForm();
    
    $this->loadUser();
    $this->loadTagListAndActivate(); 
  }
}