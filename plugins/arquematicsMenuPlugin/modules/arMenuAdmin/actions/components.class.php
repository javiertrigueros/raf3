<?php

/**
 * arMenuAdmin Components.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arMenuAdminComponents extends BaseArComponents
{
    
  public function executeShowMainMenu(sfWebRequest $request)
  {
      $this->isCMSAdmin =  $this->isCMSAdmin();
  }
  
  public function executeShowAppsMenu(sfWebRequest $request)
  {
      $this->isCMSAdmin =  $this->isCMSAdmin();
      $this->documentsTypeEnabled = arLavernaDoc::getEnabled();
  }
  
  public function executeShowExplorerMenu(sfWebRequest $request)
  {
      $this->isCMSAdmin =  $this->isCMSAdmin();
      $this->documentsTypeEnabled = arLavernaDoc::getEnabled();
  }
  
  public function executeShowBackButton(sfWebRequest $request)
  {
       
      if ($this->pageBack !== false)
      {
         if ($this->pageBack && is_object($this->pageBack))
         {
             
             if ($this->pageBack->getSlug() === 'global')
             {
               //:TODO javier
               //esto es un arreglo momentaneo haste que lo mire
               //mejor lo del global
               $this->urlBack = '/';  
             }
             else 
             {
                 $slug = $this->pageBack->getSlug();
                 
                 if (preg_match('/^@.*/i', $slug))
                 {
                   $this->urlBack = url_for($slug);    
                 }
                 else
                 {
                    $this->urlBack = $slug;  
                 }
                 
              
             }
             $this->textBack = __('Back',array(),'adminMenu');
         }
         else
         {
            $infoURL = arMenuInfo::get($this->pageBack);
          
            $this->urlBack = $infoURL['url'];
            $this->textBack = $infoURL['text'];
         }
         
      }
      
  }
  
}