<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<ul id="comment-list" class="commentlist clearfix">
   <?php $comments = $aBlogItem->getRootComments() ?>
   <?php if ($comments && (count($comments) > 0)): ?>
    <?php foreach ($comments as $comment): ?>
        <?php include_partial('arComment/comment', 
                array('viewComment' => true,
                     'comment' => $comment)) ?>
    <?php endforeach; ?> 
   <?php endif; ?>
</ul>
                
