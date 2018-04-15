<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php //slot('global-head',''); ?>
<a href="<?php echo url_for('@ar_blog_post_edit?page_back='.arMenuInfo::ADMINBLOG.'&id='.$a_blog_post->getId()); ?>">
    <?php echo $a_blog_post->title ?>
</a>