<?php

/**
 * arComment actions.
 * 
 * @package    aBlogPlugin
 * @subpackage arComment
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class arCommentActions extends BaseArActions
{
    
  public function executeCreate(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->form = new arCommentForm(null,array('aUserProfile' => $this->aUserProfile));
      }
      else
      {
         $this->form = new arCommentForm();  
      }
      
      $values = $request->getParameter($this->form->getName());
       
      //$values['ip'] = $request->getHttpHeader ('addr','remote');
      $values['ip'] = $request->getRemoteAddress();
      $values['comment_agent'] = $request->getHttpHeader('User-Agent');
      
      $this->form->bind($values);
        
     if ($request->isMethod(sfRequest::POST)
        && $this->form->isValid())
     {
         $this->form->save();
         
         $comment = $this->form->getObject();
         
         
         $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'parent' => $comment->getParent(),
                        'id' =>  $comment->getId()),
                    "HTML" => get_partial('arComment/comment', array(
                        'viewComment' => false,
                        'comment' => $comment)));
     }
    else {
         $this->data =   array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" =>  ""); 
     }
      
      
      $this->returnJson();
  }
  
  public function executeShow(sfWebRequest $request)
  {
        
  }
    
}
