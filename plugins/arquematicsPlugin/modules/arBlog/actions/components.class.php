<?php

/**
 * arBlogComponents Components.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arBlogComponents extends BaseArComponents
{
    
  public function executeShowTitleAndSlug(sfWebRequest $request)
  {
    $this->arBlogItemTitle = new arBlogItemTitle($this->a_blog_post);
    
    $this->arBlogItemSlug = new arBlogItemSlug($this->a_blog_post);
    
    $this->arBlogItemExcerpt = new arBlogItemExcerpt($this->a_blog_post);
  }
  
}