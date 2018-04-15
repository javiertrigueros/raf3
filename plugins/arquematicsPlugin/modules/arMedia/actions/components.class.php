<?php

/**
 * aMedia components.
 *
 * @package    arquematicsPlugin
 * @subpackage aMedia
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arMediaComponents extends sfComponents
{
  public function preExecute()
  {
   sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial'));

   parent::preExecute();
  }
  
  public function executeShowImage(sfWebRequest $request)
  {
     $this->embedImage = false;
     if ($this->mediaItem && is_object($this->mediaItem))
     {
       $this->embedImage = $this->mediaItem->getEmbedCode($this->width, $this->height, $this->resizeType, $this->mediaItem->getFormat());  
     }
  }
  
  public function executeShowPicture(sfWebRequest $request)
  {
     $this->mediaQueries = false;
     $this->hasMediaImages = false;
     if ($this->mediaItem && is_object($this->mediaItem))
     {
        $this->mediaQueries = $this->mediaItem->getMediaQueries();
      
        $this->hasMediaImages = count($this->mediaQueries > 0);
     }
  }
  
  public function executeShowBlogItemImage(sfWebRequest $request)
  {
      $this->hasToPopulate = isset($this->hasToPopulate)? $this->hasToPopulate: true;
      
      if ($this->hasToPopulate)
      {
         $this->aBlogItem->populatePage(); 
      }
      
      $this->width = isset($this->width)? $this->width:130;
      $this->height = isset($this->height)? $this->width:130; 
      
      
      $this->hasMedia = $this->aBlogItem->hasMedia('image');
  }
  
  public function executeShowBlogItemImageResponsive(sfWebRequest $request)
  {
      $this->hasToPopulate = isset($this->hasToPopulate)? $this->hasToPopulate: true;
      
      if ($this->hasToPopulate)
      {
         $this->aBlogItem->populatePage(); 
      }
      
      $this->hasMedia = $this->aBlogItem->hasMedia('image');
  }
  
  public function executeShowProfile(sfWebRequest $request)
  {
     
  }
}