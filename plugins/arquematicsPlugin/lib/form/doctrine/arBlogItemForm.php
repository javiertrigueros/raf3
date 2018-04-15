<?php

class arBlogItemForm extends BaseFormDoctrine
{
  protected $engine = 'aBlog';
  
  protected $arCategory = false;
  protected $arTag = false;

  public function setup()
  {
    parent::setup();
    sfProjectConfiguration::getActive()->loadHelpers(array('I18N','a'));
    
    unset(
      $this['type'], 
      $this['page_id'],
      $this['created_at'],
      $this['updated_at'],
      $this['slug'],
      $this['slug_saved'],
      $this['tags'],
      $this['title'],
      $this['status']
    );
    
    $this->setWidget('id',new sfWidgetFormInputHidden());
    
    $this->setValidator('id', new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => true)));
    
    $user = sfContext::getInstance()->getUser();
    
    $q = Doctrine::getTable('aCategory')->addCategoriesForUser($user->getGuardUser(), $user->hasCredential('admin'));
    $this->setWidget('categories_list',  new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'query' => $q),array('class' => 'span12 hide')));
    $this->setValidator('categories_list', new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  'aCategory', 'query' => $q, 'required' => false)));

    $this->setWidget('categories_name',new sfWidgetFormInputText(array(), 
                array('class' => 'no-borders ui-control-text-input span12',
                      'autocomplete'=>'off')));
    
    $this->setValidator('categories_name', new sfValidatorString(array('min_length' => 4,'max_length' => 24, 'required' => false)));
    
    
    $q = $this->getObject()->getTagQuery();
    
    $this->setWidget('tags_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tag', 'method' => 'getName', 'query' => $q),array('class' => 'hide')));
    $this->setValidator('tags_list', new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  'Tag', 'column' => 'id', 'min' => 1, 'max' => 10, 'required' => false)));
 
    $q = Doctrine::getTable('aBlogItem')->queryForAuthors();
    $this->setWidget('author_id', new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q),array('class' => 'ui-control-text-input')));
    $this->setValidator('author_id', new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q, 'required' => false)));
    
    $this->statusChoices = array(
        'draft' => __('Draft',array(),'blog'),
        'published' => __('Published',array(),'blog')
      );
     
    $this->widgetSchema['is_publish']  = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['is_publish']  = new sfValidatorBoolean(array('required' => true));
    
    //ha sido guardada
    $this->widgetSchema['is_save']  = new sfWidgetFormInputHidden();
    $this->validatorSchema['is_save']  = new sfValidatorBoolean(array('required' => true));
    
   
    $this->widgetSchema['allow_comments'] = new sfWidgetFormInputCheckbox(array(),array('class' => 'no-borders ui-control-text-input'));
    $this->validatorSchema['allow_comments'] = new sfValidatorBoolean(array('required' => false));
    
    
    $this->setWidget('published_at',new arDateTimeWidget(array('culture' => $user->getCulture()), 
                array('class' => 'no-borders ui-control-text-input span12',
                      'autocomplete'=>'off')));
    
    
    $this->setValidator('published_at', new sfValidatorArDateTime(
            array(
                'datetime_output' => 'Y-m-d H:i:s',
                'format' => aDate::getDateTimeFormatPHP($user->getCulture()),
                'required' => true)
            ));
    
  }
  
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['editors_list']))
    {
      $this->setDefault('editors_list', $this->object->Editors->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $categories = $this->object->Categories->getPrimaryKeys();
      
      $this->setDefault('categories_list', count($categories)> 0?$categories:'');
    }
    
    
    if (isset($this->widgetSchema['tags_list']))
    {
      $tagsArrayIds = $this->object->getTagsArrayIds();
     
      $this->setDefault('tags_list', count($tagsArrayIds) > 0?$tagsArrayIds:'');
    }
    
    
    if (isset($this->widgetSchema['is_save']))
    {
      $this->setDefault('is_save', 1);
    }
   
    
    if (isset($this->widgetSchema['is_publish']))
    {
       $this->setDefault('is_publish', $this->object->getIsPublish());    
    }
    
    if (isset($this->widgetSchema['allow_comments']))
    {
       $this->setDefault('allow_comments', $this->object->getAllowComments());
    }

  }
  
  protected function doSave($conn = null)
  {

     $blogPost = $this->getObject();
    
     $blogPost->saveCategories($this->getValue('categories_list'),$conn);
     $blogPost->savePageCategories($this->getValue('categories_list'),$conn);
     
     $tagsList = $this->getValue('tags_list');
     
     
     if ($tagsList && is_array($tagsList))
     {
        $blogPost->saveTags($this->getValue('tags_list'),$conn);
        $blogPost->savePageTags($this->getValue('tags_list'),$conn);  
     }
     else if ($tagsList && is_int($tagsList))
     {
        $blogPost->saveTags(array($tagsList),$conn);
        $blogPost->savePageTags(array($tagsList),$conn);    
     }
     else
     {
        $blogPost->saveTags(false,$conn);
        $blogPost->savePageTags(false,$conn);  
     }
     
     
    
     
     parent::doSave($conn);
  }
  
  public function getModelName()
  {
    return 'aBlogItem';
  }
}