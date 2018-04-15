<?php $arMutualFriends = isset($arMutualFriends) ? $sf_data->getRaw('arMutualFriends') : null; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php foreach ($arMutualFriends as $friend): ?>
    <?php if ($aUserProfile->getId() === $friend->getId()): ?>
            <a class="member-frame col-xs-5 col-sm-5 col-md-5 col-lg-5" title="<?php echo $friend->getFirstLast() ?>" data-user-title="<?php echo $friend->getFirstLast() ?>" href="<?php echo url_for('wall') ?>">
                            <?php include_partial('arProfile/imageAvatarWall', 
                                array('image' => $friend->getProfileImage(),
                                    'class' => 'small-wall-image-profile' )); ?>
                                <span><?php echo $friend->getFirstLast(); ?></span>
            </a>
    <?php elseif ($friend->countUserMessages() == 0): ?>
            <a class="member-frame col-xs-5 col-sm-5 col-md-5 col-lg-5" title="<?php echo $friend->getFirstLast() ?>" data-user-title="<?php echo $friend->getFirstLast() ?>" href="<?php echo url_for('user_profile',$friend) ?>">
                            <?php include_partial('arProfile/imageAvatarWall', 
                                array('image' => $friend->getProfileImage(),
                                    'class' => 'small-wall-image-profile' )); ?>
                                <span><?php echo $friend->getFirstLast(); ?></span>
            </a>
    <?php else: ?>
            <a class="member-frame col-xs-5 col-sm-5 col-md-5 col-lg-5" title="<?php echo $friend->getFirstLast() ?>" data-user-title="<?php echo $friend->getFirstLast() ?>" href="<?php echo url_for('@wall?pag=1&userid='.$friend->getId()) ?>">
                            <?php include_partial('arProfile/imageAvatarWall', 
                                array('image' => $friend->getProfileImage(),
                                'class' => 'small-wall-image-profile' )); ?>
                                <span><?php echo $friend->getFirstLast(); ?></span>
            </a>
    <?php endif; ?>
<?php endforeach; ?>
