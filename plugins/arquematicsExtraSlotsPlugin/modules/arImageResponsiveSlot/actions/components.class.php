<?php

class arImageResponsiveSlotComponents extends aSlotComponents
{
    /**
   * DOCUMENT ME
   */
  public function executeEditView()
  {
    
    $this->setup();
  }
  /*
  private function getImageEmbed($slotWidth, $slotHeight)
  {
      $imageItem = $this->item;
      
      $dimensions = aDimensions::constrain(
        $imageItem->width, 
        $imageItem->height,
        $imageItem->format, 
        array(
          "width" =>  $slotWidth,
          //"width" => ($imageItem->width > $slotWidth)? $slotWidth: false,
          "height" => ($imageItem->height > $slotHeight)?  $slotHeight: false,
          "resizeType" => 'c'));
      
      return $this->item->getImgSrcUrl($dimensions['width'], $dimensions['height'], 'c', $dimensions['format'], false); 
      //return $this->item->getScaledUrl( $dimensions);
      //return $this->item->getEmbedCode($dimensions['width'], $dimensions['height'], $dimensions['resizeType'], $dimensions['format'], false);
  }*/
  /**
   * DOCUMENT ME
   */
  public function executeNormalView()
  {
    $this->setup();
    $this->setupOptions();
    
    // Behave well if it's not set yet!
    if (!count($this->slot->MediaItems))
    {
      $this->item = false;
      $this->itemId = false;
    }
    else
    {
      $this->item = $this->slot->MediaItems[0];
      $this->itemId = $this->item->id;
     
    }
  }

  /**
   * DOCUMENT ME
   */
  protected function setupOptions()
  {
    $this->options['width'] = $this->getOption('width', 440);
    $this->options['height'] = $this->getOption('height', false);
    $this->options['resizeType'] = $this->getOption('resizeType', 's');
    $this->options['flexHeight'] = $this->getOption('flexHeight');
    $this->options['maxHeight'] = $this->getOption('maxHeight', false);
    $this->options['title'] = $this->getOption('title');
    $this->options['description'] = $this->getOption('description');
    $this->options['credit'] = $this->getOption('credit');
    $this->options['link'] = $this->getOption('link');
    $this->options['defaultImage'] = $this->getOption('defaultImage');
    
    // We automatically set up the aspect ratio if the resizeType is set to 'c'
    $constraints = $this->getOption('constraints', array());
    if (($this->getOption('resizeType', 's') === 'c') && isset($constraints['minimum-width']) && isset($constraints['minimum-height']) && (!isset($constraints['aspect-width'])))
    {
      $constraints['aspect-width'] = $constraints['minimum-width'];
      $constraints['aspect-height'] = $constraints['minimum-height'];
    }
    $this->options['constraints'] = $constraints;
  }
}