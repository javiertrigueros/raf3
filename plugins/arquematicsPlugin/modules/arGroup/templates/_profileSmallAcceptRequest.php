<div id="men<?php echo $profile->getId() ?>" class="message user-item" data-id="<?php echo $profile->getId() ?>">            
    <div class="user-item-content">
             <a href="<?php echo url_for('@user_profile?username='.$profile->getUsername()) ?>">
                <?php include_partial('arProfile/imageAvatarWall', array('image' => $profile->getProfileImage(),'class' => "message-avatar")) ?>
             </a>
             <?php echo link_to($profile->getFirstLast(),'user_profile',$profile,array('class' => 'username message-name')) ?>
    </div>
    <div class="user-item-controls">
            <button data-loading-text="<?php echo __("send...",array(),'wall') ?>" data-friend-id="<?php echo $profile->getId(); ?>"  class="btn btn-primary accept btn-warning"><?php echo __("Accept",array(),'profile') ?></button>
            <button data-loading-text="<?php echo __("send...",array(),'wall') ?>" data-friend-id="<?php echo $profile->getId(); ?>"  class="btn ignore btn-default"><?php echo __("Reject",array(),'profile') ?></button> 
    </div>
</div>



        
 