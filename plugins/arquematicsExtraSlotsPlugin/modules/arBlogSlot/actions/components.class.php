<?php
class arBlogSlotComponents extends aSlotComponents
{
  protected $modelClass = 'aBlogPost';
  protected $formClass = 'arBlogSlotForm';
  //public $handSelected = false;
  protected $hasItemsAtCat = false;
  

  public function setup()
  {
    parent::setup();
  }
  
  public function getQuery()
  {
    // Explicit select() mandatory with orderByList
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c')
      ->select($this->modelClass . '.*, a.*, c.*');
     
    Doctrine::getTable($this->modelClass)->addPublished($q);
    if (isset($this->values['title_or_tag']) && ($this->values['title_or_tag'] === 'title'))
    {
      $this->handSelected = true;
      if (isset($this->values['blog_posts']) && count($this->values['blog_posts']))
      {
        $this->hasItemsAtCat = true;
        $q->andWhereIn('id', $this->values['blog_posts']);
        $q = aDoctrine::orderByList($q, $this->values['blog_posts']);
      }
      else
      {
        $q->andWhere('0 <> 0');
      }
      //echo $q->getSqlQuery();
      // Works way better when you actually return it!
      
      return $q;
    }
    else
    {
        
      if (isset($this->values['categories_list']))
      {
        // This doesn't cut it because we wind up not knowing about the
        // other categories of each post, which breaks our "link to best page
        // for this post" algorithm
        // $q->andWhereIn('c.id', $this->values['categories_list']);
        // This would be nice but Doctrine croaks parsing it
        // $q->andWhere($this->modelClass . '.id IN (SELECT iblog.id FROM ' . $this->modelClass . ' iblog INNER JOIN iblog.Categories ic WITH ic.id IN ?)', array($this->values['categories_list']));

        // Let's cheat and use aMysql to pull the blog item IDs that have the relevant categories in a lightweight way,
        // then do a whereIn clause. It's not ideal, but it works well in practice
        $sql = new aMysql();
        $blogItemsForCategories = $sql->queryScalar('SELECT i.id FROM a_blog_item i INNER JOIN a_blog_item_to_category ic ON i.id = ic.blog_item_id AND ic.category_id = :category_ids', array('category_ids' => $this->values['categories_list']));
        
        
        if ($blogItemsForCategories 
                && is_array($blogItemsForCategories)
                && count($blogItemsForCategories) > 0)
        {
          $this->hasItemsAtCat = true;
          // So we use this after all, but we'll fetch all the categories for the posts in a second pass, sigh
          $q->andWhereIn($this->modelClass . '.id', $blogItemsForCategories);  
        }
        
      }
      
      
      if (isset($this->values['tags_list']) && strlen($this->values['tags_list']) > 0)
      {
        PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $this->values['tags_list'], $q, array('nb_common_tags' => 1));
      }
      
      if (!isset($this->values['count']))
      {
        $this->values['count'] = 3;
      }
      $q->limit($this->values['count']);
      $q->orderBy('published_at desc');
      return $q;
    }
  }
    
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();

    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new $this->formClass($this->id, $this->slot->getArrayValue());
    }

    $this->popularTags = PluginTagTable::getPopulars(null, array('sort_by_popularity' => true), false, 10);
    if (sfConfig::get('app_a_all_tags', true))
    {
  	  $this->allTags = PluginTagTable::getAllTagNameWithCount();
    }
    else
    {
      $this->allTags = array();
    }
  }
  

  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = $this->getQuery();


    $this->options['class'] = $this->getOption('class', '');
    
    $this->aCategory = false;
    if (isset($this->values['categories_list']))
    {
      $aCategory = Doctrine_Core::getTable('aCategory')->retrieveById($this->values['categories_list']);
      $this->aCategory = ($aCategory && is_object($aCategory))?$aCategory:false;  
    }
    
    $this->title_head = false;
    if ((isset($this->values['title_head'])) 
        && (strlen(trim($this->values['title_head'])) > 0))
    {
      $this->title_head = $this->values['title_head'];
              
    }
    else if ($this->handSelected)
    {
       $this->title_head = ''; 
    }
    else
    {
       $this->title_head = ($this->aCategory)?a_('Recent From %cat%',array('%cat%' => $this->aCategory->getName())):'';   
    }
   
    //echo $q->getSqlQuery();
    
    if (($this->hasItemsAtCat) 
        && (isset($this->values['title_or_tag']) 
        || isset($this->values['categories_list']) 
        || isset($this->values['tags_list'])))
    {
        
        $this->aBlogPosts = $q->execute();
        
        $this->aBlogPostsMedia = array();
        aBlogItemTable::populatePages($this->aBlogPosts);
    }
    else
    {
        
        $this->aBlogPosts = array();
        $this->aBlogPostsMedia = array();
        
        aBlogItemTable::populatePages($this->aBlogPosts);
    }
    	
  }

}
