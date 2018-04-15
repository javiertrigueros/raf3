<?php $ar_comment = isset($ar_comment) ? $sf_data->getRaw('ar_comment') : false; ?>
<?php echo nl2br2($ar_comment->getComment()); ?> 
