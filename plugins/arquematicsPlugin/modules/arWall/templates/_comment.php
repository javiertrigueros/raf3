<?php use_helper('Date',
        'JavascriptBase',
        'I18N',
        'Wall') ?>

<?php $display = isset($display) ? $sf_data->getRaw('display') : true; ?>
<?php $canDelete = isset($canDelete) ? $sf_data->getRaw('canDelete') : false; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : true; ?>
<?php $commentUser = $comment->getUser() ?>

<div class="comment<?php echo (!$display)?' hide':'' ?>" id="comment-<?php echo $comment->getId() ?>">
    <div class="comment-icon comment-wall-icon">
        <?php include_partial('arProfile/imageSmall', 
                    array('class' => 'comment-avatar', 'image' => $comment->getImageUserProfile())) ?> 
    </div> 
    <div class="comment-text comment-wall-text">
        
        <?php echo link_to($commentUser->getName(),'user_profile',$commentUser,array('class' => 'user_link comment-link')) ?> 
        <?php if ($canDelete || ($authUser->getId() == $commentUser->getId())): ?>
            <span data-comment-id="<?php echo $comment->getId() ?>"
                  id="remove-comment-<?php echo $comment->getId() ?>"
                  class="cmd-comment-delete fa fa-trash-o comment-icon-remove">
            </span>
        <?php endif; ?>
        
        <?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
        <div class="comment-main-content content-text" data-encrypt-text="<?php echo $comment->EncContent->getContent() ?>">
                  
        </div>
        <?php else: ?>
        <div class="comment-main-content content-text">
           <?php echo nl2br2($comment->getComment(), true); ?>    
        </div>
        <?php endif; ?>
        
        <span class="mytime" title="<?php echo $comment->getCreatedAt() ?>"><?php time_stamp($comment->getCreatedAt()); ?></span>
    </div>
    <div style="clear:both"></div>
</div>
