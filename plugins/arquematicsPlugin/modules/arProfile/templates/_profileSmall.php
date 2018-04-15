<div id="men<?php echo $profile->getId() ?>" class="<?php echo $class; ?> hand-open member-frame" data-id="<?php echo $profile->getId() ?>"> 
    <?php include_partial('arProfile/imageAvatarWall', 
                        array('image' => $profile->getProfileImage(),
                              'class' => "user-thumbnail")) ?>
    <?php echo link_to($profile->getFirstLast(),'user_profile',$profile,array('class' => 'username')) ?>
</div>