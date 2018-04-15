<?php $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null; ?>
<td>
  <ul class="a-ui a-admin-td-actions">
    <?php echo linkToEditPost($a_blog_post) ?>
    <?php echo linkToDeletePost($a_blog_post) ?>
  </ul>
</td>
