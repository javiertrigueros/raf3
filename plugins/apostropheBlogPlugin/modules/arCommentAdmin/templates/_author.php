<?php $ar_comment = isset($ar_comment) ? $sf_data->getRaw('ar_comment') : false; ?>

<ul id="comment-<?php echo $ar_comment->getId() ?>">
<?php if ($ar_comment->hasUserProfile()): ?>
    <li>
        <?php include_partial('arProfile/imageMini', 
                        array('image' => $ar_comment->getUserImage())) ?>
        <?php echo $ar_comment->getUserProfileLongName() ?>
    </li>
    <?php else:?>
    <li>
      <?php echo gravatarImage($ar_comment->getCommentAuthorEmail(), 'small') ?>
      <?php echo aHtml::entities($ar_comment->comment_author) ?> 
    </li>
<?php endif; ?>
    <li>
      <a href="mailto:<?php echo $ar_comment->getEmail(); ?>"><?php echo $ar_comment->getEmail(); ?></a>  
    </li>
    <li>
       <?php echo $ar_comment->getIp() ?> 
    </li>
</ul>