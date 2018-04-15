
<?php if (count($profileList) > 0): ?>
    <?php foreach($profileList  as $profile): ?>
            <?php include_partial('arGroup/profileSmall', 
                        array('aUserProfile' => $aUserProfile,
                                'profile' => $profile,
                                'class' => $class)) ?>
    <?php endforeach; ?>
<?php endif; ?>