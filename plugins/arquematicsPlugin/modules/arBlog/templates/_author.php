<?php
  //$page = aTools::getCurrentNonAdminPage();
  //$page = aTools::getCurrentPage();
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  //$filterUrl = aUrl::addParams($page->getUrl(), array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author')));
?>

<?php if ($a_blog_post->getAuthor()): ?>
  <span class="a-blog-item-meta-label"><?php echo __('Posted By:',array(),'blog')  ?></span>
  <span id="a-blog-author"><?php echo aHtml::entities($a_blog_post->getAuthor()->getName()); ?></span>
<?php endif ?>