<?php if (count($listFriends) > 0): ?>
    <?php foreach($listFriends  as $friend): ?>
            <?php include_partial('arGroup/profileIgnoreRequest',
                    array('profile' => $friend,
                          'aUserProfile' => $aUserProfile)) ?>
    <?php endforeach; ?>
<?php endif; ?>