<?php $ar_comment = isset($ar_comment) ? $sf_data->getRaw('ar_comment') : false; ?>
<?php echo $ar_comment->getCommentApproved()?__('Approved', null, 'blog'):__('Pending', null, 'blog'); ?> 





