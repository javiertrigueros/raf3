<?php

/**
 * arComment Components.
 * 
 * @package    aBlogPlugin
 * @subpackage arComment
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    SVN: $Id: Components.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class arCommentComponents extends BaseArComponents
{
    
  public function executeShowFormComment(sfWebRequest $request)
  {
      
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arCommentForm(null,array(
                            'aBlogItem'  => $this->aBlogItem, 
                            'aUserProfile' => $this->aUserProfile));
      }
      else 
      {
          $this->form = new arCommentForm(null, array('aBlogItem'  => $this->aBlogItem));
      }
     
  }
    
    
}
