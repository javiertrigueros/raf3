<?php


/**
 * 
 * arAuth actions.
 * @package    arquematics
 * @subpackage arAuth
 * @author     Javier Trigueros Martínez de los huertos
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */

class arAuthComponents extends BaseArAuthComponents
{
    
  public function executeShowHomeLogin(sfWebRequest $request)
  {
      $this->aUser = $this->getUser();
     
      $this->showContent =  !(is_object($this->aUser) 
                            && $this->aUser->isAuthenticated())
                            && $this->page->isEqualTo($this->root);
      
      
  
       $this->userBackForm = new arUserBackForm();
       $this->form = new arFormSignin();
      
  }
    
}
