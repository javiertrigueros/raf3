<?php

/**
 * arBlogActions
 *
 * @package    arquematicsPlugin
 * @subpackage aDoc
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arBlogActions extends BaseArActions
{ 
  protected function getTagInfo($object)
  {//->getObject()
    // Retrieve the tags currently assigned to the blog post for the inlineTaggableWidget
    $this->existingTags = $object->getTags();
    // Retrieve the 10 most popular tags for the inlineTaggableWidget
    $this->popularTags = TagTable::getPopulars(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true), false, 10);
  }
  
  protected function postFormFor($object)
  {
    $form = new aBlogPostForm($object);
    $event = new sfEvent(null, 'a.filterBlogItemForm');
    $this->dispatcher->filter($event, $form);
    return $event->getReturnValue();
  }
  
  protected function eventFormFor($object)
  {
    $form = new aEventForm($object);
    $event = new sfEvent(null, 'a.filterBlogItemForm');
    $this->dispatcher->filter($event, $form);
    return $event->getReturnValue();
  }
  
  
  protected function setBlogPostForUser(sfWebRequest $request)
  {
    $this->a_blog_post = false;

    $this->a_blog_post = Doctrine::getTable('aBlogPost')
              ->findOneEditable(
                      $request->getParameter('id'),
                      $this->getUser()->getGuardUser()->getId());
  }
  
  protected function setBlogEventForUser(sfWebRequest $request)
  {
    $this->a_blog_post = false;
    $this->a_blog_post = Doctrine::getTable('aEvent')
              ->findOneEditable(
                      $request->getParameter('id'),
                      $this->getUser()->getGuardUser()->getId());
  }
  
  protected function isValidPageBack($pageBack)
  {
      $pageBack = (int) $pageBack;
      
      return is_numeric($pageBack) 
                && is_int($pageBack)
                && ($pageBack < count(arMenuInfo::$urlBackData) );
  }
  
  protected function canEditItem($request)
  {
     $this->a_blog_post = false;
   
     $this->a_blog_post = Doctrine::getTable('aBlogItem')
              ->findOneEditable(
                      $request->getParameter('id'),
                      $this->getUser()->getGuardUser()->getId());
     
     return ($this->a_blog_post && is_object($this->a_blog_post));
  }
  
  
  protected function canEditPost($request)
  {
              
      $this->setBlogPostForUser($request);
      
      $ret = ($this->a_blog_post && is_object($this->a_blog_post));
      
      $pageBack = (strlen(trim($request->getParameter('page_back'))) > 0)?
                        trim($request->getParameter('page_back')):false;
      
      if ($pageBack)
      {
           return $ret && $this->isValidPageBack($pageBack);
          
      }
      else
      {
         return $ret;   
      }
  }
  
  protected function canEditEvent($request)
  {
      $this->setBlogEventForUser($request);
      
      $ret = ($this->a_blog_post && is_object($this->a_blog_post));
      
      $pageBack = (strlen(trim($request->getParameter('page_back'))) > 0)?
                        trim($request->getParameter('page_back')):false;
      
      if ($pageBack)
      {
          return $ret && $this->isValidPageBack($pageBack);
      }
      else 
      {
        
        return $ret;  
      }
  }
  
  public function executeTagsByNameAutoComplete(sfWebRequest $request)
  {
     
      if ($this->checkView() && $this->hasBlogCredential())
      {
        $this->form = new arSearchTag();
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
              && $this->form->isValid())
        {
            $name = trim($this->form->getValue('name'));
            
           
            $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => Doctrine_Core::getTable('Tag')->getTagByStringLimited($name));
        }
        else
        {
             $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array());
        }
      }
      
      
      $this->returnJson();
  }
  
  
  public function executePreviewPost(sfWebRequest $request)
  {
      if ($this->checkView() && $this->canEditPost($request))
      {
        aBlogItemTable::populatePages(array($this->a_blog_post)); 
      }
      else
      {
        $this->redirect('@homepage');
      }
       
  }
  
  public function executeEditEvent(sfWebRequest $request)
  {
      
      if ($this->checkView() 
           && $this->hasBlogCredential()
           && $this->canEditEvent($request))
      {
        $this->loadUser();
        
        $this->getTagInfo($this->a_blog_post);

        aBlogItemTable::populatePages(array($this->a_blog_post));
        
        
        $this->pageBack = (int)trim($request->getParameter('page_back'));
        
        if ($this->pageBack && ($this->pageBack && arMenuInfo::PAGE))
        {
            $this->pageBack = $this->a_blog_post->Page;
        }

        $this->formCategory = new arBlogItemCategoryForm(null,array('aBlogItem' => $this->a_blog_post));
       
        $this->formTag = new arTagSimpleForm();

        $this->form = new arBlogEventForm($this->a_blog_post, array('aUser' => $this->aUser));
        
      }
      else
      {
        $this->redirect('@homepage');
      }
       
  }
  
  public function executeDeletePost(sfWebRequest $request)
  {
      if ($this->checkView()
          && $this->hasBlogCredential()
          && $this->canEditPost($request))
      {
           
          $messagesRelated  = Doctrine_Core::getTable('arWallMessage')
                        ->getMessagesByBlogId($this->a_blog_post->getId());
            
          if ($messagesRelated && (count($messagesRelated) > 0))
          {
              foreach ($messagesRelated as $messageRelated)
              {
                  $messageRelated->delete();
              }
          }
          else
          {
              //no tiene mensaje asociado y borra simplemente el post
              $this->a_blog_post->delete();
          }
          
          $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
          
        
          $this->redirect('@a_blog_admin');
      }
      else
      {
        $this->redirect('@homepage');
      }
       
  }
  
  public function executeDeleteEvent(sfWebRequest $request)
  {
      if ($this->checkView()
          && $this->hasBlogCredential()
          && $this->canEditEvent($request))
      {
           
          $messagesRelated  = Doctrine_Core::getTable('arWallMessage')
                        ->getMessagesByBlogId($this->a_blog_post->getId());
            
          if ($messagesRelated && (count($messagesRelated) > 0))
          {
              foreach ($messagesRelated as $messageRelated)
              {
                  $messageRelated->delete();
              }
          }
          else
          {
              //no tiene mensaje asociado y borra simplemente el post
              $this->a_blog_post->delete();
          }
          
          $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
          
        
          $this->redirect('@a_event_admin');
      }
      else
      {
        $this->redirect('@homepage');
      }
       
  }
  
  public function executeEditPost(sfWebRequest $request)
  {
      if ($this->checkView() 
           && $this->hasBlogCredential()
           && $this->canEditPost($request))
      {
        $this->loadUser();
                                   
        $this->getTagInfo($this->a_blog_post);

        aBlogItemTable::populatePages(array($this->a_blog_post));
        
        $this->pageBack = (int) trim($request->getParameter('page_back'));
        
        if ($this->pageBack && ($this->pageBack && arMenuInfo::PAGE))
        {
            $this->pageBack = $this->a_blog_post->Page;
        }
        
        $this->formCategory = new arBlogItemCategoryForm(null,array('aBlogItem' => $this->a_blog_post));
        
        $this->formTag = new arTagSimpleForm();

        $this->form = new arBlogPostForm($this->a_blog_post);
           
      }
      else
      {
        $this->redirect('@homepage');
      }
       
  }
  
  public function executeTagDelete(sfWebRequest $request)
  {
      if ($this->checkView() && $this->hasBlogCredential())
      {
          try {
              $tag = $this->getRoute()->getObject();
              $tagId = $tag->getId();
              $tag->delete();
              
              $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $tagId),
                    "HTML" =>  ""); 
              
          }
          catch (Exception $e)
          {
               $this->data =   array(
                    "status" => 500,
                    "errors" => array('error' => $e->getTraceAsString()),
                    "values" => array(),
                    "HTML" =>  ""); 
          }
          
      }
      else
      {
          $this->data =   array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" =>  "hasBlogCredential"); 
      }
      
      $this->returnJson(); 
  }
  
  public function executeCatDelete(sfWebRequest $request)
  {
      if ($this->checkView() && $this->hasBlogCredential())
      {
          try {
              $cat = $this->getRoute()->getObject();
              $catId = $cat->getId();
              $cat->delete();
              
              $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $catId),
                    "HTML" =>  ""); 
              
          }
          catch (Exception $e)
          {
               $this->data =   array(
                    "status" => 500,
                    "errors" => array('error' => $e->getTraceAsString()),
                    "values" => array(),
                    "HTML" =>  ""); 
          }
          
      }
      else
      {
          $this->data =   array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" =>  "hasBlogCredential"); 
      }
      
      $this->returnJson(); 
  }
  
 
  public function executeCatAddToBlogItem(sfWebRequest $request)
  {
      if ($this->checkView() && $this->canEditPost($request))
      {
        $this->form = new arBlogItemCategoryMultipleForm( null,array('aBlogItem' => $this->a_blog_post));
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
         
           $categoriesList = $this->form->getValue('categories_list');
           
           if (isset($categoriesList) && is_array($categoriesList))
           {
               foreach ($categoriesList as $category)
               {
                   /*
                   $aBlogItemToCategory = new aBlogItemToCategory();
                   $aBlogItemToCategory->setCategoryId();
                   $aBlogItemToCategory->setBlogItemId();
                   $aBlogItemToCategory->save();*/
               }
           }
           else
           {
              $categoriesList = array(); 
           }
          
           
            $this->data =   array(
                    "status" => 200,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" =>  ''); 
           
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
  
  public function executeCatCreate(sfWebRequest $request)
  {
      if ($this->checkView() && $this->isBlogAuthor())
      {
        
        $formValues = $request->getParameter('a_blog_categories');
        
        $catName = trim(
                (is_array($formValues) 
                && isset($formValues['name']))?$formValues['name']:'');
        
        $hasTosave = true;
        
        if (strlen($catName) > 0)
        {
          $this->arCategory = Doctrine_Core::getTable('aCategory')->findOneByName($catName); 
          
          if (($this->arCategory && is_object($this->arCategory)))
          {
             $this->form = new arBlogItemCategoryForm($this->arCategory);
          
             $hasTosave = false;
          }
          else {
              $this->form = new arBlogItemCategoryForm();
          }
        }
        else {
            $this->form = new aTagForm();
        }
          
        $this->form->bind($formValues);
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
           if ($hasTosave)
           {
             $this->form->save();
             $this->arCategory = $this->form->getObject();
           }
           
           $this->data =   array(
                    "status" => 200,
                    "errors" => $this->form->getErrors(),
                    "values" => array(
                        'id' => $this->arCategory->getId(),
                        'name' => $this->arCategory->getName()),
                    "HTML" =>  $this->arCategory->getName()); 
           
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
  
  private function doCreateOrFindTag($tagName, $request)
  {
        $hasTosave = true;
        
        if (strlen($tagName) > 0)
        {
          $this->aTag = Doctrine_Core::getTable('Tag')->findOneByName($tagName); 
          
          if (($this->aTag && is_object($this->aTag)))
          {  
             $this->form = new arTagSimpleForm($this->aTag);
             $hasTosave = false;
          }
          else {
              $this->form = new arTagSimpleForm();
          }
        }
        else {
            $this->form = new arTagSimpleForm();
        }
        
        $formValues = $request->getParameter('a_blog_tag');
        $this->form->bind($formValues);
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
           if ($hasTosave)
           {
             $this->form->save();
             $this->aTag = $this->form->getObject();
           }
    
           $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'id' => $this->aTag->getId(),
                        'name' => $this->aTag->getName()),
                    "HTML" =>  $this->aTag->getName()); 
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
  /**
   * crea o busca tags
   * 
   * @param sfWebRequest $request
   */
  public function executeCreateTagOrFind(sfWebRequest $request)
  {
      if ($this->checkView() && $this->isBlogAuthor())
      {
        $formValues = $request->getParameter('a_blog_tag');
        
        
        $searchTag = (is_array($formValues) 
                && isset($formValues['search_tag'])
                && ($formValues['search_tag'] === 'true'));
        
        $tagName = trim(
                (is_array($formValues) 
                && isset($formValues['name']))?$formValues['name']:'');
        
        
        if ($searchTag)
        {
            $this->form = new arTagSimpleForm();
            $this->form->bind($request->getParameter($this->form->getName()));
             
            if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
            {
                $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => Doctrine_Core::getTable('Tag')->getTagByStringLimited($tagName));
                 
            }
            else {
                
                 $this->data =   array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" =>  ""); 
     
            }
           
        }
        else {
           
            $this->doCreateOrFindTag($tagName, $request);
        }
        
      }
      
      $this->returnJson(); 
      
  }
  
  public function executeUpdateExcerpt(sfWebRequest $request)
  {
      
    if ($this->checkView()  
            && $this->hasBlogCredential()
            && $this->canEditItem($request))
    {
       
        $this->form = new arBlogItemExcerpt($this->a_blog_post);
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
            $this->form->updateObject();
            $this->form->save();
            
            $arBlogItem = $this->form->getObject();
            
            $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'id' => $arBlogItem->getId(),
                        'slug' => $arBlogItem->getSlug(),
                        'title' => $arBlogItem->getTitle(),
                        'excerpt' => $arBlogItem->getExcerpt()),
                    "HTML" =>  $arBlogItem->getExcerpt()); 
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
  
  public function executeUpdateTitle(sfWebRequest $request)
  {
    if ($this->checkView() 
            && $this->hasBlogCredential()
            && $this->canEditItem($request))
    {
       
        $this->form = new arBlogItemTitle($this->a_blog_post);
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
            $this->form->updateObject();
            $this->form->save();
            
            $arBlogItem = $this->form->getObject();
            
            $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'id' => $arBlogItem->getId(),
                        'slug' => $arBlogItem->getSlug(),
                        'title' => $arBlogItem->getTitle()),
                    "HTML" =>  $arBlogItem->getTitle()); 
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
  
  public function executeUpdateSlug(sfWebRequest $request)
  {
    if ($this->checkView() 
            && $this->hasBlogCredential()
            && $this->canEditItem($request))
    {
        $this->form = new arBlogItemSlug($this->a_blog_post);
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
            $this->form->updateObject();
            $arBlogItem = $this->form->getObject();
            $arBlogItem->save();
            
            $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(
                        'id' => $arBlogItem->getId(),
                        'slug' => $arBlogItem->getSlug(),
                        'title' => $arBlogItem->getTitle()),
                    "HTML" =>  $arBlogItem->getSlug()); 
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
  
  public function executeUpdateEvent(sfWebRequest $request)
  {
      
    if ($this->checkView()
       && $this->hasBlogCredential()
       && $this->canEditEvent($request))
    {
        $this->updateBlogItem = $this->getRoute()->getObject();
        
        $this->form = new arBlogEventForm($this->updateBlogItem);
        
        $postValues = $request->getParameter($this->form->getName());
                
        if (!isset($postValues['author_id']))
        {
            $postValues['author_id'] = $this->updateBlogItem->getAuthorId();
        }
        
        
        $this->form->bind($postValues);
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
          //el error esta en blog save
            $this->form->save();
            
             $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" =>  "");
             
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
  
  public function executeUpdateBlog(sfWebRequest $request)
  {
       
           
    if ($this->checkView()
            && $this->hasBlogCredential()
            && $this->canEditPost($request))
    {
        $this->updateBlogItem = $this->getRoute()->getObject();
        
        $this->form = new arBlogPostForm($this->updateBlogItem);
        
        $postValues = $request->getParameter($this->form->getName());
                
        if (!isset($postValues['author_id']))
        {
            $postValues['author_id'] = $this->updateBlogItem->getAuthorId();
        }
       
        $this->form->bind($postValues);
        if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
        {
            $this->form->save();
            
             $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" =>  "");
             
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
  
  public function executeCreatePost(sfWebRequest $request)
  {
       
       if ($this->checkView() && $this->hasBlogCredential())
       {
            $this->loadUser();
            
            $this->form = new arBlogNewPostForm(null, array(
                'authUser' => $this->authUser,
                'aUserProfile' => $this->aUserProfile));
            
            $this->form->bind($request->getParameter($this->form->getName()));
        
            if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
            {
                $this->form->save();
                
                $this->a_blog_post = $this->form->getObject();
                
                $event = new sfEvent($this->a_blog_post, 'a.postAdded', array());
                $this->dispatcher->notify($event);
                
                $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('url' => url_for('@ar_blog_post_edit?page_back='.arMenuInfo::WALL.'&id='.$this->a_blog_post->getId())),
                    "HTML" =>  ""); 
                 
            }
            else
            {
               
                $this->data =   array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => array(),
                    "HTML" =>  ""); 
                //devuelve el contenido en Json de $this->data
            }
       }

       $this->returnJson();  
       
  }
  
  public function executeCreateEvent(sfWebRequest $request)
  {
       
       if ($this->checkView() && $this->hasBlogCredential())
       {
            $this->loadUser();
            
            $this->form = new arBlogNewEventForm(null, array(
                'authUser' => $this->authUser,
                'aUserProfile' => $this->aUserProfile));
            $this->form->bind($request->getParameter($this->form->getName()));
        
            if ($request->isMethod(sfRequest::POST)
                && $this->form->isValid())
            {
                $this->form->save();
                
                $this->postEvent = $this->form->getObject();
                
                $event = new sfEvent($this->postEvent, 'ar.eventAdded', array());
                $this->dispatcher->notify($event);
                
                $this->data =   array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('url' => url_for('@ar_blog_event_edit?page_back='.arMenuInfo::WALL.'&id='.$this->postEvent->getId())),
                    "HTML" =>  ""); 
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
       //devuelve el contenido en Json de $this->data
       $this->returnJson();
  }
    
}
