<?php use_helper('a','ar','arGravatar') ?>
<?php $comment = isset($comment) ? $sf_data->getRaw('comment') : false; ?>
<?php $viewComment = isset($viewComment) ? $sf_data->getRaw('viewComment') : false; ?>
<?php $isCommentMaxLevel = sfConfig::get('app_arquematics_comments_max_level', 3) <= $comment->getLevel()  ?>
<li id="li-comment-<?php echo $comment->getId() ?>" class="<?php echo ($viewComment)?'':'hide'; ?> comment even thread-even depth-1">
    <div class="comment-body">
        <div class="clearfix" id="comment-2">
            <div class="avatar-box">
                <?php if ($comment->hasUserProfile()): ?>
                    <?php include_partial('arProfile/imageAvatar', 
                        array('image' => $comment->getUserImage(),
                              'class' => 'avatar avatar-67 photo')) ?>
                    <span class="avatar-overlay"></span>
                <?php elseif ($comment->hasCommentAuthorUrl()):?>
                    <a rel="external nofollow" href="<?php echo $comment->getCommentAuthorUrl()?>">
                        <?php echo gravatarImage($comment->getCommentAuthorEmail()) ?>
                        <span class="avatar-overlay"></span>
                    </a>
                <?php else:?>
                    <?php echo gravatarImage($comment->getCommentAuthorEmail()) ?>
                    <span class="avatar-overlay"></span>
                <?php endif; ?>
             </div> <!-- end .avatar-box -->
            
            <div class="comment-wrap">
                <div class="comment-meta commentmetadata">
                    <span class="fn">
                        <?php if ($comment->hasUserProfile()): ?>
                            <?php echo $comment->getUserProfileLongName() ?>
                        <?php elseif ($comment->hasCommentAuthorUrl()):?>
                            <a class="url" rel="external nofollow" href="<?php echo $comment->getCommentAuthorUrl()?>"><?php echo aHtml::entities($comment->comment_author) ?></a>
                        <?php else:?>
                            <?php echo aHtml::entities($comment->comment_author) ?>
                        <?php endif; ?>
                    </span> / 
                    <span class="comment-date">
                        <?php echo aDate::long($comment->getCreatedAt()); ?>
                    </span> 
                </div>
                <?php if (!$comment->getCommentApproved()): ?>
                    <code>
                        <em class="moderation">
                            <?php echo __('Your comment is awaiting moderation.', null, 'blog') ?>
                            <br />
                        </em>
                    </code>
                <?php endif; ?>
                
                <div class="comment-content">
                    <?php //echo aHtml::textToHtml($comment->getComment()) ?>
                    <?php echo nl2br2($comment->getComment()); ?>
                </div> <!-- end comment-content-->
                
                <?php if (!$isCommentMaxLevel): ?>
                <div class="reply-container">
                    <a data-active="false" data-parent_id="<?php echo $comment->getId() ?>" data-text_reply="<?php echo __('Reply', null, 'blog') ?>" data-text_reply_cancel="<?php echo __('Cancel Reply', null, 'blog') ?>"  href="#li-comment-<?php echo $comment->getId() ?>" class="comment-reply-link"><?php echo __('Reply', null, 'blog') ?></a>
                </div>
                <?php endif; ?>
                
            </div> <!-- end comment-wrap-->
            <div class="comment-arrow"></div>
        </div> <!-- end comment-body-->
     </div> <!-- end comment-body-->
     
     <?php $children = $comment->getChildren() ?>
     <?php if ($children && (count($children) > 0)): ?>
        <ul class="children">
            <?php foreach ($children as $child): ?>
                <?php include_partial('arComment/comment', 
                        array('viewComment' => true,
                              'comment' => $child)) ?>
            <?php endforeach; ?>
        </ul>
     <?php endif; ?>
</li>
           