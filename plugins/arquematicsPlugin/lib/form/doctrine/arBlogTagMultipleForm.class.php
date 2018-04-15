<?php


class arBlogTagMultipleForm extends BaseFormDoctrine
{
    
  public function configure()
  {
     parent::configure();
    
     /*
     unset(
	$this['author_id'],
        $this['page_id'],
	$this['title'],
	$this['slug'],
	$this['slug_saved'],
	$this['excerpt'],
	$this['status'],
        $this['allow_comments'],
	$this['template'],
	$this['published_at'],
	$this['disqus_thread_identifier'],
	$this['type'],
	$this['start_date'],
	$this['start_time'],
	$this['end_date'],
	$this['end_time'],
	$this['location'],
	$this['created_at'],
	$this['updated_at']
    );*/
     
    $this->setWidget('id',new sfWidgetFormInputHidden());
    
    $this->setValidator('id', new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => true)));
    
    $q = $this->getObject()->getTagQuery();
    
    $this->setWidget('tags_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tag', 'method' => 'getName', 'query' => $q),array('class' => 'span12 hide')));
    
    $this->setDefault('tags_list' ,$this->getObject()->getTagsArrayIds());
    
    $this->setValidator('tags_list', new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  'Tag', 'column' => 'id', 'min' => 1, 'max' => 10, 'required' => true)));
   
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

 
    $this->widgetSchema->setNameFormat('a_blog_tag_post[%s]');
  }
  
  
  public function getModelName()
  {
    return 'aBlogItem';
  }
  
  public function updateTags($addTagsListId, $conn)
  {
       $object = $this->getObject();
       $object->addTagByArrayId($addTagsListId, $conn);
  }

  protected function doSave($con = null)
  {  
    $this->updateTags(isset($this->values['tags_list']) ? $this->values['tags_list'] : array(),$con);
    parent::doSave($con);
  }
}
