<?php if (count($listFriends) > 0): ?>
    <?php foreach($listFriends  as $friend): ?>
            <?php include_partial('arGroup/profileAcceptRequest',array('profile' => $friend)) ?>
    <?php endforeach; ?>
<?php endif; ?>