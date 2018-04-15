<?php
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>

<ul class="a-blog-item-meta">
  <li id="post-published-at" class="post-date"><?php echo aDate::long($a_blog_post['published_at']); ?></li>
  <li class="post-author">
    <?php include_partial('arBlog/author', array('a_blog_post' => $a_blog_post)) ?>
  </li>
</ul>
