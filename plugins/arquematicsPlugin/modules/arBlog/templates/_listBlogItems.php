<?php  $message = isset($message) ? $sf_data->getRaw('message') : false; ?>
<?php  $blogItems = ($message)? $message->getBlogItems(): array(); ?>
<?php  $authUser = ($authUser)? $sf_data->getRaw('authUser'): false; ?>
<?php if (count($blogItems)): ?>

<?php try { ?>

<?php foreach($blogItems as $blogItem): ?>
 <?php include_partial("arBlog/wallBlogItem", array('authUser' => $authUser,
                                                    'message' => $message,
                                                    'aBlogItem' => $blogItem)) ?>
<?php endforeach; ?>

<?php } catch (Exception $e) { 
  echo '<h3>'.__("You don't currently have a blog or events engine page, please create one.", null, 'arquematics').'</h3>';
 } ?>

<?php endif ?>