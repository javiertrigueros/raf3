<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $profile = isset($profile) ? $sf_data->getRaw('profile') : false; ?> 

<div id="men<?php echo $profile->getId() ?>" class="<?php echo $class; ?> hand-open member-frame" data-id="<?php echo $profile->getId() ?>"> 
    <div class="close">
        <span class="glyphicon glyphicon-trash user-icon"></span>
    </div>
    <div class="add-user cmd-user<?php echo $profile->canAddUser($aUserProfile->getId())?'':' hide' ?>">
        <span class="glyphicon glyphicon-plus user-icon"></span>
        <span class="glyphicon glyphicon-user"></span>
    </div>
    <div class="remove-request cmd-user<?php echo $profile->canRemoveRequest()?'':' hide' ?>">
        <span class="glyphicon glyphicon-minus user-icon"></span>
        <span class="glyphicon glyphicon-user"></span>
    </div>
    <div class="remove-suscriptor cmd-user<?php echo $profile->canRemoveSuscriptor()?'':' hide' ?>">
        <span class="glyphicon glyphicon-minus user-icon"></span>
        <span class="glyphicon glyphicon-user"></span>
    </div>
    <a class="hand-open username" href="<?php echo url_for('user_profile',$profile) ?>">
        <?php include_partial('arProfile/imageNormal', 
                        array('image' => $profile->getProfileImage(),
                              'class' => "user-thumbnail")) ?>
        <span><?php echo $profile->getFirstLast() ?></span>
    </a>
</div>