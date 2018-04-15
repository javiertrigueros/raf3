<?php


class arVectorialEditorComponents extends BaseArComponents
{
  
  public function executeShowEditorsEnable(sfWebRequest $request)
  {
     
     $editors = sfConfig::get( 'app_arquematics_editor_enabled');
   
     $this->editors = array();
     foreach ($editors as $editor)
     {
         $editorId = Doctrine_Core::getTable('arDiagram')->getTypeId($editor);
        
         if ($editorId >= 0)
         {
           
            $item = array('name' => $editor, 'id' => $editorId);
            $this->editors[] = $item;
         }
     }
  }
  
  public function executeShowControl(sfWebRequest $request)
  {
      
      $this->loadUser();
     
      //$this->documentTypeEnable = array_merge(arDiagram::getEnabled(), array('doc'));
      $this->documentsTypeEnabled = arLavernaDoc::getEnabled();
      /*
      // ficheros en la session
      $this->diagrams = $this->getUser()->getAttribute('arDiagram',array(),'wall');
      $this->hasContent = (count($this->diagrams) > 0);
      
      $activeTool = $this->getUser()->getAttribute('activeTool', false, 'wall');
      $this->showTool = ($activeTool == 'arDiagram');
      
      if ($this->hasContent)
      {
        $this->diagrams =  Doctrine_Core::getTable('arDiagram')
                            ->getByIds($this->diagrams, $this->aUserProfile->getId());
      }*/
  }
  
}