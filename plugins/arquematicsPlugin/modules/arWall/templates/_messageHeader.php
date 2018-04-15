<?php $message = isset($message) ? $sf_data->getRaw('message') : null; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : null; ?>
<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>    
<?php $canDelete = isset($canDelete) ? $sf_data->getRaw('canDelete') : false; ?>
<?php $messageUser = $message->getUser(); ?>
<?php $time = $message->getCreatedAt(); ?>

<div class="messages-heading message-header" >
    <?php if (($aUserProfileFilter 
                && ($aUserProfileFilter->getId() === $messageUser->getId()))
                || ($authUser->getId() === $messageUser->getId())): ?>
   
    <div class="message-user-link-info">
        <?php echo link_to( $messageUser->getFirstLast(),'user_profile',$messageUser,array('class' => 'user_link')); ?>
        <span class="mytime" title="<?php echo $time ?>"><?php time_stamp($time);?></span> 
    </div>
   
    <?php else: ?>
    <div class="message-user-link-info">
        <a class="user_link" href="<?php echo url_for('@wall?userid='.$messageUser->getId()) ?>" >
            <?php echo $messageUser->getFirstLast() ?>
        </a>
        <span class="mytime" title="<?php echo $time ?>"><?php time_stamp($time);?></span> 
    </div>
    <?php endif; ?>
   
    <?php if ($canDelete): ?>
        <span data-message-id="<?php echo $message->getId() ?>" id="remove-message-<?php echo $message->getId() ?>" class="cmd-message-delete fa fa-trash-o message-icon-remove"></span>
     <?php endif  ?>
</div>
