<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;

  $excerptLength = (sfConfig::get('app_aBlog_excerpts_length')) ? sfConfig::get('app_aBlog_excerpts_length') : 30;
?>

<?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
<?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
<?php //echo aHtml::simplify($a_blog_post->getRichTextForArea('blog-body', $excerptLength), array('allowedTags' => '<a><em><strong>'))  ?>
<?php echo $a_blog_post->getRichTextForArea('blog-body', $excerptLength)  ?>