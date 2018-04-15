<?php


class arWallActions extends BaseArActions
{
  protected function setTagSort($sort)
  {
    $this->getUser()->setAttribute('arWall.sort', $sort, 'wall');
  }
  
  protected function getTagSort()
  {
    return $this->getUser()->getAttribute('arWall.sort', null, 'wall');
  }
  
  protected function setGeoTagSort($sort)
  {
    $this->getUser()->setAttribute('arWall.geosort', $sort, 'wall');
  }
  
  protected function getGeoTagSort()
  {
    return $this->getUser()->getAttribute('arWall.geosort', null, 'wall');
  }
  
  /**
   * prepara la query para el listado, con filtros o si ellos
   * 
   * @param <sfWebRequest $request>
   * @param <boolean $filter>
   * @return <Query>
   */
  protected function buildQuery($request)
  {
      $q = Doctrine_Core::getTable('arWallMessage')
              ->getMessageQuery($this->userid);
      
      return $this->addTagQueryFilter($q, $request);
  }
  
  protected function addTagQueryFilter($query, $request)
  {
      $tag = $this->getRequestParameter('tag');
      $geotag = $this->getRequestParameter('geotag');
      $pag = (int)$this->getRequestParameter('pag');
      $userid = (int)$this->getRequestParameter('userid');
      
      if ($this->getRequestParameter('userid') 
          && $userid && ($userid > 0))
      {
         $query->andWhere('m.user_id = ?',$userid);

         $this->aUserProfileFilter = Doctrine_Core::getTable('sfGuardUserProfile')
                                    ->retrieveById($userid);
      
        
      }
      else
      {
         $this->aUserProfileFilter = false; 
      }
      
      if ($this->getRequestParameter('tag') && $tag)
          //&& Doctrine::getTable('arTag')->find($tag))
      {
         $query->leftJoin('m.arTagHasArWallMessage tm')
                 ->leftJoin('tm.Tag t')
                 ->andWhere('t.hash = ?',$tag);
         
         $this->setTagSort(array($tag));
      }
      else if ($this->getRequestParameter('geotag') && $geotag)
      {
         $query->andWhere('g.hash = ?',$geotag);
            
         $this->setGeoTagSort(array($geotag));
      }
      
      else if  ($this->getRequestParameter('pag') 
                && ($pag == 1))
      {
          $this->setTagSort(null);
          $this->setGeoTagSort(null);
      }
      else if (($tagArr = $this->getTagSort()) !== null)
      {
          $query->leftJoin('m.arTagHasArWallMessage tm')
                ->leftJoin('tm.Tag t')
                 ->andWhere('t.hash = ?',$tagArr[0]);
      }
      else if (($tagArr = $this->getGeoTagSort()) !== null)
      {
          $query->andWhere('g.hash = ?',$tagArr[0]);
      }
      
      
      return $query;
  }
  /*
  protected function isFriendUser()
  {
      $ret = false;
      
      if ($this->aUserProfileFilter)
      {
          $this->aUserProfileFilter->getId();
      }
      
      return $ret;        
  }*/
  
  public function executeIndex(sfWebRequest $request)
  {
    
    if ($this->checkView())
    {
        $this->loadUser();
        
        $this->isBlogAdmin = $this->hasBlogCredential();
        
        $query = $this->buildQuery($request, false);
        
        $this->countMessages = $query->count();

        $this->has_messages = ($this->countMessages > 0);
        
        
        $this->hasProfileFilterAccepted = false;
        
        if ($this->aUserProfileFilter)
        {
            $this->hasProfileFilterAccepted = $this->aUserProfile->isUserAccept($this->aUserProfileFilter->getId());
        } 
        
        $this->currentPage = (int)$request->getParameter('pag');
         
        $max_per_page = sfConfig::get('app_arquematics_plugin_wall_messages_perpage', 10);
       
        $this->pager = new sfDoctrinePager('arWallMessage', $max_per_page);
        $this->pager->setQuery($query);
        //inicia el paginador en la pagina que se ha pedido
        $this->pager->setPage($this->currentPage);
        $this->pager->init();
          
        if ($request->isXmlHttpRequest())
        {
              $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "extraHTML" => ($this->currentPage === 1)?
                                    get_component('arProfile', 'showProfileWall', 
                                            array('aUserProfile' => $this->aUserProfile,
                                                  'aUserProfileFilter' => $this->aUserProfileFilter,
                                                  'showScript' => false)):false,
                    "values" => array(
                        'counterid' => $this->currentPage,
                        'has_messages' => $this->has_messages,
                        'countMessages' => $this->countMessages,
                        'haveToPaginate' => $this->pager->haveToPaginate(),
                        'isLastPage' => $this->pager->isLastPage()),
                    "HTML" => get_partial('arWall/listMessages', 
                                array('pager' => $this->pager,
                                      'currentPage' => $this->currentPage,
                                      'iniPage' => false,
                                      'hasProfileFilterAccepted' => $this->hasProfileFilterAccepted,
                                      'aUserProfileFilter' => $this->aUserProfileFilter,
                                      'authUser' => $this->authUser,
                                      'has_messages' => $this->has_messages,
                                      'countMessages' => $this->countMessages)));
            
        
             $this->returnJson();
        }
        
       
    }
    else
    {
      $this->redirect('@homepage');
    }
    
  }
  
  public function executeDeleteComment(sfRequest $request)
  {
      if ($this->checkView())
      {
            $this->loadUser();
            
            $comment = $this->getRoute()->getObject();
            
            $message = ($comment && is_object($comment))? 
                    $comment->getMessages():
                    false;

            if ($comment 
                && is_object($comment)
                && is_object($message)
                && 
              (($message->getUserId() == $this->userid)
               || ($comment->getUserId() == $this->userid)))
             {
                    $comment->delete();
                    
                    $this->data =   array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array());
             }
             else
             {
                   $this->data =   array("status" => 404,
                    "errors" => array(),
                    "HTML" => "",
                    "values" => array());
                   
             }
      }
      
       //devuelve el contenido en Json de $this->data
       $this->returnJson();
  }
  
  public function executeDeleteMessage(sfRequest $request)
  {
      if ($this->checkView())
      {
            $this->loadUser();
            
            $message = $this->getRoute()->getObject();
            
            if ($message 
                && is_object($message)
                && ($message->getUser()->getId() == $this->userid))
             {
                    $message->delete();
                    
                    $this->data =   array("status" => 200,
                                    "errors" => array(),
                                    "HTML" => "",
                                    "values" => array());
             }
             else
             {
                   $this->data =   array("status" => 404,
                    "errors" => array(),
                    "HTML" => "",
                    "values" => array());
                   
             }
      }
      
       //devuelve el contenido en Json de $this->data
       $this->returnJson();
  }
  
  
  public function executeSendComment(sfRequest $request)
  {
    
    if($this->checkView())
    {
        $this->loadUser();
        
        $message = $this->getRoute()->getObject();
        if (($message) && is_object($message))
        {
             
             $this->form = new arWallCommentForm();
            
             $formValues = $request->getParameter($this->form->getName());
             
             $messageId = $message->getId();
             $formValues = array_merge( $formValues, 
                     array('user_id' => $this->userid,
                           'wall_message_id' => $messageId));
    
             $this->form->bind($formValues);
             if ($request->isMethod(sfRequest::POST)
                    && $this->form->isValid())
             {
                 $arWallComment = $this->form->save();
                 
                 if ($arWallComment && is_object($arWallComment))
                 {
                      $this->data = array(
                                    "status" => 200,
                                    "errors" => array(),
                                    "values" => array('arWallCommentId' => $arWallComment->getId()),
                                    "HTML" => get_partial('arWall/comment', 
                                                 array('comment' => $arWallComment,
                                                       'authUser' => $this->authUser)));
                     
                 }
                 else {
                      $this->data =   array("status" => 500,
                            "errors" => $this->form->getErrors(),
                            "HTML" => "Save Error",
                            "values" => $formValues);
                 }             
             }
             else
             {
                  $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => "",
                    "values" => $formValues);
             }
            
        }

    }
    
    $this->returnJson();
  }
  /**
   * 
   * @param sfRequest $request 
   */
  public function executeSendMessage(sfRequest $request)
  {
    if($this->checkView())
    {
        $this->loadUser();
        
       
        $this->form = new arWallMessageForm(null, array('sysUser' => $this->getUser() ,
                                                        'aUserProfile' => $this->aUserProfile));
    
        $this->form->bind(
            $request->getParameter($this->form->getName()),
            $request->getFiles($this->form->getName())
        );
        
        if ($request->isMethod(sfRequest::POST)
            && $this->form->isValid())
        {
    
         $arDropFiles = $this->getUser()->getAttribute('arDropFile',array(),'wall');
         
         $messageObj = $this->form->save();
         
         $this->getUser()->setAttribute('activeTool', false , 'wall');
         
         //no nuestra el mensaje directamente
         //lo hace despues de un efecto con javascript para mostrarlo
         $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'arDropFiles' => $arDropFiles
                    ),
                    "HTML" =>  get_partial('arWall/message', 
                                    array('message' => $messageObj,
                                          'authUser' => $this->aUserProfile,
                                          'display' => false)));
            
        }
        else
        {
             $this->data =   array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" =>  ""); 
        }
            
    }
   
    
    $this->returnJson();
  }
    
}