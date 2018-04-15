<?php

/**
 * arBlogItemCategoryForm form.
 *
 */
class arBlogItemCategoryForm extends BaseaCategoryForm
{
  public function configure()
  {
      unset(
            $this['id'],
            $this['slug'], 
            $this['created_at'], 
            $this['updated_at']
        );
    

    $this->setValidator('id', new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)));
    
    $this->setWidget('name',new sfWidgetFormInputText(
                array(), 
                array('class' => 'no-borders ui-control-text-input span12',
                      'autocomplete'=>'off')));
    
    $this->setValidator('name', new sfValidatorString(array('min_length' => 4,'max_length' => 24, 'required' => true)));
    
  
    
    
    $this->widgetSchema->setNameFormat('a_blog_categories[%s]');
  }
  
  
  protected function doSave($conn = null)
  {
        $catName = trim($this->getValue('name'));
        
        if (strlen($catName) > 0)
        {
          $this->arCategory = Doctrine_Core::getTable('aCategory')->findOneByName($catName); 
          
          if (!($this->arCategory && is_object($this->arCategory)))
          {
             parent::doSave($conn);
          }
          else {
             $this->setObject($this->arCategory);
          }
        }
  }
}
