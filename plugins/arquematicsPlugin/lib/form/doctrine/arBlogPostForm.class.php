<?php

class arBlogPostForm extends arBlogItemForm
{
  public function setup()
  {
    parent::setup();
    
    $this->widgetSchema->setNameFormat('a_blog_post[%s]');
  }

  public function getModelName()
  {
    return 'aBlogPost';
  }

}
