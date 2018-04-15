<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $categories = $a_blog_post->Categories; 
  $i = count($categories);
if ($i > 0)
{
   foreach($categories as $category)
   {
      echo link_to($category->name, '@a_blog_admin_addFilter?name=categories_list&value='.$category->id, 'post=true'); 
      $i--;
       if ($i > 0)
       {
         echo ",&nbsp;";  
       }
   } 
}
?>