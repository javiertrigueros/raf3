<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $status = $a_blog_post->getStatus(); 
?>
<?php echo __($status,null,'blog'); ?>