<?php


/**
 * arPageAdmin actions.
 *
 * @package    alcoor
 * @subpackage arPageAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arPageAdminActions extends BaseArActions
{
    
  public function executeIndex(sfWebRequest $request)
  {
      
      if ( $this->checkView() 
          && $this->isCMSAdmin())
      {
          $this->loadUser();
          
          $this->page = new aPage();
          $this->parent = aPageTable::retrieveBySlug('/');
          
          $this->form = new aPageSettingsForm($this->page, $this->parent);

          $this->formPageDelete = new arPageDeleteForm();
            
          $this->slugStem = preg_replace('/\/$/', '', $this->parent->slug);
  
          $this->allPagesTree = $this->parent->getTreeInfo(true);
          
      }
      else
      {
          $this->redirect('@homepage');
      }
      
  }
  /**
   * carga $this->page y $this->parent
   * 
   * @param sfWebRequest $request
   */
  private function loadPages(sfWebRequest $request)
  {
    $settings = $request->getParameter('settings');
    
    if (isset($settings['id']) && is_numeric($settings['id']))
    {
        $this->page = $this->retrievePageForEditingById($settings['id']);
        
        $this->parent = $this->page->getParent(true);
    }
    else
    {
        $this->page = new aPage();
     
        $this->parent = aPageTable::retrieveBySlug('/');
   
        $event = new sfEvent($this->parent, 'a.filterNewPage', array());
        $this->dispatcher->filter($event, $this->page);
        $this->page = $event->getReturnValue();
    }
  }
  
  
  private function processEngine(sfWebRequest $request)
  {       
      $settings = $request->getParameter('settings');
      $this->engine = $this->page->engine;
      
      if (isset($settings['joinedtemplate']))
      {
            list($this->engine, $this->template) = preg_split('/:/', $settings['joinedtemplate']);
      }
       
      return ($this->engine !== 'a');
      
  }
  
  
  private function processEngine2(sfWebRequest $request)
  {
      $ret = false;
       
      $engineFormClass = $this->engine . 'EngineForm';
            
      if (class_exists($engineFormClass))
      {
        // Used for the initial render. We also ajax re-render this bit when they pick a
        // different engine from the dropdown, see below
        $this->engineForm = new $engineFormClass($this->page);
        $this->engineSettingsPartial = $this->engine . '/settings';
      }
      
      
      if ($request->hasParameter('settings') 
          && isset($this->engineForm))
      {
         
        // If it's a new page we need the page id so we can save the engine's setting
        $request->setParameter("settings[pageid]", $this->page->id);
        $this->engineForm->bind($request->getParameter("settings"));
                
        $ret = ($request->isMethod(sfRequest::POST) 
                        && $this->engineForm->isValid());
        
        /*
        echo print_r($this->engineForm->getErrors()); 
        echo $ret; 
        exit();*/
        
        
        if ($ret)
        {
            // Avoid multiple updates of search index for performance
            $this->form->getObject()->blockSearchUpdates();
            // Yes, this does save the same object twice in some cases, but
            // Symfony 1.4 embedded forms are an unreliable alternative with
            // many issues and no proper documentation

            $this->form->save();

            if ($this->isNewPage)
            {
                // If the page was new, we won't be able to save the
                // engine form if it's a conventional subclass of aPageForm;
                // they don't like being saved consecutively for the
                // same new object. Make a new form and bind it to exactly
                // the same data
                $this->engineForm = new $engineFormClass($this->page);
                $this->engineForm->bind($request->getParameter("settings"));
                        
                $ret = $this->engineForm->isValid();
            }

            $this->engineForm->save();
            $this->form->getObject()->flushSearchUpdates();
        }
     }
       
     return $ret;
  }
  /*
  public function hasPageEngine(sfWebRequest $request)
  {
      $settings = $request->getParameter('settings');
      $this->engine = $this->page->engine;
      
       if (isset($settings['joinedtemplate']))
       {
            list($this->engine, $this->template) = preg_split('/:/', $settings['joinedtemplate']);
       }
       
       return ($this->engine === 'a');
  }*/
  
  
  public function hasChangePageEngine(sfWebRequest $request)
  {
      $settings = $request->getParameter('settings');
      $oldEngine = $this->page->engine;
      //$oldTemplate = $this->page->template;
      
      $ret = !$this->page->isNew();
      
      if (!$ret)
      {
        if (isset($settings['joinedtemplate']))
        {
           list($this->engine, $this->template) = preg_split('/:/', $settings['joinedtemplate']);   
        }
        
        return false;  
      }
      else if (isset($settings['joinedtemplate']))
      {
        list($this->engine, $this->template) = preg_split('/:/', $settings['joinedtemplate']);
       
        $ret = ($oldEngine !== $this->engine);  
      }
     
      echo $oldEngine;
      echo '|'.$this->engine;
      echo $ret;
      exit();
      return $ret;
  }
  
  
  
  public function executeUpdate(sfWebRequest $request)
  {
   
    $this->lockTree();
    
    $this->loadPages($request);
    
    $this->isNewPage = ($this->page && is_object($this->page))? $this->page->isNew(): false; 
    $this->stem = $this->isNewPage ? 'a-create-page' : 'a-page-settings';
    
    $this->form = new aPageSettingsForm($this->page, $this->parent);

    //$settings = $request->getParameter('settings');
    
    $this->form->bind($request->getParameter('settings'));
    
    if ($this->checkView() 
        && $this->isCMSAdmin()
        && $request->isMethod(sfRequest::POST)
        && $this->form->isValid())
    {
       
       if ($this->page->isNew())
       {
            $settings = $request->getParameter('settings');
            if (isset($settings['joinedtemplate']))
            {
                list($this->engine, $this->template) = preg_split('/:/', $settings['joinedtemplate']);
            }
      
            // Avoid multiple updates of search index for performance
            $this->form->getObject()->blockSearchUpdates();
            $this->page = $this->form->save();
            $this->form->getObject()->flushSearchUpdates();
                                                 
            $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $this->page->getId(),
                                      'slug' => $this->page->getSlug(),
                                      'title' => $this->page->getTitle()),
                    "HTML" =>  get_component('arPageAdmin','showForm', 
                                                array('page' => $this->page,
                                                'parent' => $this->parent)) );
           
       }
       //javier hack
       // solo permito cambiar el nombre y los usuarios que
       // pueden ver la pagina si no es nueva
       else 
       {
           $values = $this->form->getValues();
          
           if (isset($values['joinedtemplate']))
           {
               list($this->engine, $this->template) = preg_split('/:/', $values['joinedtemplate']);
           }
           // The slugifier needs to see pre-encoding text
           $this->page->updateLastSlugComponent($values['realtitle']);
           $title = htmlentities($values['realtitle'], ENT_COMPAT, 'UTF-8');
           $this->page->setTitle($title);
           $this->page->save();
           
            $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $this->page->getId(),
                                      'slug' => $this->page->getSlug(),
                                      'title' => $this->page->getTitle()),
                    "HTML" =>  get_component('arPageAdmin','showForm', 
                                                array('page' => $this->page,
                                                'parent' => $this->parent)) );
       }
       // :TODO
       // arquematics hack   
      /* else if ($this->processEngine($request))
       {
            $this->form->getObject()->blockSearchUpdates();
            $this->page = $this->form->save();
            $this->form->getObject()->flushSearchUpdates();
           
            $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => $settings,
                    "HTML" =>  get_component('arPageAdmin','showForm', 
                                                array('page' => $this->engineForm->getObject(),
                                                'parent' => $this->parent)) );
       }*/
       /*
       else
       {
            //aBlog
            //aEvent
            
            $this->engine = $this->page->engine;
            $this->template = $this->page->template;
            
            $this->form->getObject()->blockSearchUpdates();
            $this->page = $this->form->save();
            $this->form->getObject()->flushSearchUpdates();
                                                 
            $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $this->page->getId(),
                                      'slug' => $this->page->getSlug(),
                                      'title' => $this->page->getTitle()),
                    "HTML" =>  get_component('arPageAdmin','showForm', 
                                                array('page' => $this->page,
                                                'parent' => $this->parent)) );
           
           /*
            $this->data = array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => '',
                    "HTML" => '');
       }*/
    }
    else
    {
        $this->data = array(
                    "status" => 500,
                    "errors" => $this->form->getErrors(),
                    "values" => '',
                    "HTML" => '');
    }
            
    $this->unlockTree();
    
    //devuelve el contenido en Json de $this->data
    $this->returnJson();
  }

  public function executeDelete(sfWebRequest $request)
  {
      
    $this->form = new arPageDeleteForm();
    $params = $request->getParameter($this->form->getName());
  
    $this->form->bind($params);
        
    if ($this->checkView() 
          && $this->isCMSAdmin()
          && $request->isMethod(sfRequest::POST)
          && $this->form->isValid())
    {
        $this->lockTree();
       
        $this->page = $this->retrievePageForEditingById($params['id']);
        
        $this->parent = $this->page->getParent(true);
        
        //la página root no se puede borrar dan igual las condiciones
        if (!$this->parent)
        {
            $this->unlockTree();
            $this->data = array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array(),
                    "HTML" => "Can't delete root none");
        }
        else
        {
            $objId = $this->page->getId();
            // tom@punkave.com: we must delete via the nested set
            // node or we'll corrupt the tree. Nasty detail, that.
            // Note that this implicitly calls $page->delete()
            // (but the reverse was not true and led to problems).
            $this->page->getNode()->delete();
            $this->unlockTree();
            
             $this->data = array(
                    "status" => 200,
                    "errors" => array(),
                    "values" => array('id' => $objId),
                    "HTML" => "");
        }
        
    }
    else {
        
          $this->data = array(
                    "status" => 500,
                    "errors" => array(),
                    "values" => array($this->form->getErrors()),
                    "HTML" => '');
        
    }
    
    $this->returnJson();
   
   
  }
    
   /**
   * DOCUMENT ME
   * @param sfWebRequest $request
   * @return mixed
   */
  public function executeSettings(sfWebRequest $request)
  {
    $this->lockTree();
    $new = $request->getParameter('new');
    $this->parent = null;
    if ($new)
    {
      $this->page = new aPage();

      $this->parent = $this->retrievePageForEditingBySlugParameter('parent', 'manage');
      $event = new sfEvent($this->parent, 'a.filterNewPage', array());
      $this->dispatcher->filter($event, $this->page);
      $this->page = $event->getReturnValue();
    }
    else
    {
      if ($request->hasParameter('settings'))
      {
        $settings = $request->getParameter('settings');
        $this->page = $this->retrievePageForEditingById($settings['id']);
      }
      else
      {
        $this->page = $this->retrievePageForEditingByIdParameter();
      }
    }

    // get the form and page tags
    $this->stem = $this->page->isNew() ? 'a-create-page' : 'a-page-settings';
    $this->form = new aPageSettingsForm($this->page, $this->parent);

    $event = new sfEvent($this->page, 'a.filterPageSettingsForm', array('parent' => $this->parent));
    $this->dispatcher->filter($event, $this->form);
    $this->form = $event->getReturnValue();
    $mainFormValid = false;

    $engine = $this->page->engine;

    if ($request->hasParameter('settings'))
    {
      $settings = $request->getParameter('settings');
      if (isset($settings['joinedtemplate']))
      {
        list($engine, $template) = preg_split('/:/', $settings['joinedtemplate']);
        if ($engine === 'a')
        {
          $engine = '';
        }
      }
      $this->form->bind($settings);
      if ($this->form->isValid())
      {
        $mainFormValid = true;
      }
    }

    // Don't look at $this->page->engine which may have just changed. Instead look
    // at what was actually submitted and validated as the new engine name
    if ($engine)
    {
      $engineFormClass = $engine . 'EngineForm';
      if (class_exists($engineFormClass))
      {
        // Used for the initial render. We also ajax re-render this bit when they pick a
        // different engine from the dropdown, see below
        $this->engineForm = new $engineFormClass($this->page);
        $this->engineSettingsPartial = $engine . '/settings';
      }
    }

    if ($mainFormValid && (!isset($this->engineForm)))
    {
      // Avoid multiple updates of search index for performance
      $this->form->getObject()->blockSearchUpdates();
      $this->form->save();
      $this->form->getObject()->flushSearchUpdates();

      $this->unlockTree();

      return 'Redirect';
    }


    if ($request->hasParameter('enginesettings') && isset($this->engineForm))
    {
      // If it's a new page we need the page id so we can save the engine's setting
      $request->setParameter("enginesettings[pageid]", $this->page->id);
      $this->engineForm->bind($request->getParameter("enginesettings"));
      if ($this->engineForm->isValid())
      {
        if ($mainFormValid)
        {
          // Avoid multiple updates of search index for performance
          $this->form->getObject()->blockSearchUpdates();
          // Yes, this does save the same object twice in some cases, but
          // Symfony 1.4 embedded forms are an unreliable alternative with
          // many issues and no proper documentation

          $this->form->save();

          if ($new)
          {
            // If the page was new, we won't be able to save the
            // engine form if it's a conventional subclass of aPageForm;
            // they don't like being saved consecutively for the
            // same new object. Make a new form and bind it to exactly
            // the same data
            $this->engineForm = new $engineFormClass($this->page);
            $this->engineForm->bind($request->getParameter("enginesettings"));
            $this->forward404Unless($this->engineForm->isValid());
          }

          $this->engineForm->save();
          $this->form->getObject()->flushSearchUpdates();
          $this->unlockTree();
          return 'Redirect';
        }
      }
    }
    // The slug stem is what we try to append the title to when creating a new slug
    if ($new)
    {
      // TODO: make this UTF8-aware but not no-UTF8-support-hostile, etc.
      $this->slugStem = preg_replace('/\/$/', '', $this->parent->slug);
    }
    else
    {
      if (preg_match('/^(.*?)\/[^\/]*$/', $this->page->slug, $matches))
      {
        $this->slugStem = $matches[1];
      }
      else
      {
        $this->slugStem = $this->page->slug;
      }
    }
    $this->unlockTree();
  }
}
