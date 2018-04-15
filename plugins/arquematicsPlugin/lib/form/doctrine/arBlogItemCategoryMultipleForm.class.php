<?php

/**
 * arBlogItemCategoryMultipleForm form.
 *
 */
class arBlogItemCategoryMultipleForm extends BaseaCategoryForm
{
  public function configure()
  {
      unset(
            $this['id'],
            $this['slug'],
            $this['name'], 
            $this['created_at'], 
            $this['updated_at']
        );
      
    $user = sfContext::getInstance()->getUser();
    $q = Doctrine::getTable('aCategory')->addCategoriesForUser($user->getGuardUser(), $user->hasCredential('admin'));
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'query' => $q),array('class' => 'span12 hide')));
    $this->setValidator('categories_list',
      new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  'aCategory', 'query' => $q, 'required' => false)));

    $this->widgetSchema->setNameFormat('a_blog_category_post[%s]');
  }
  /*
  public function updateCategoriesList($addValues)
  {
    // Add any new categories (categories_list_add), and restore any
    // categories we didn't have the privileges to remove
    
    $link = array();
    if(!is_array($addValues))
    {
      $addValues = array();
    }
    foreach ($addValues as $value)
    {
      $existing = Doctrine::getTable('aCategory')->findOneBy('name', $value);
      if ($existing)
      {
        $aCategory = $existing;
      }
      else
      {
        $aCategory = new aCategory();
        $aCategory['name'] = $value;
      } 
      $aCategory->save();
      $link[] = $aCategory['id'];
    }
    if(!is_array($this->values['categories_list']))
    {
      $this->values['categories_list'] = array();
    }
    $reserved = $this->getAdminCategories();
    foreach ($reserved as $category)
    {
      if (!in_array($category->id, $this->values['categories_list']))
      {
        $this->values['categories_list'][] = $category->id;
      }
    }
    foreach ($link as $id)
    {
      if (!in_array($id, $this->values['categories_list']))
      {
        $this->values['categories_list'][] = $id;
      }
    }
  }

  protected function doSave($con = null)
  {
    $this->updateCategoriesList(isset($this->values['categories_list_add']) ? $this->values['categories_list_add'] : array());
    parent::doSave($con);
  }
  */
}
