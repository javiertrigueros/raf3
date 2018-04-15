<?php $viewComments = sfConfig::get('app_arquematics_plugin_wall_comments_view', 4); ?>
<?php $message = isset($message) ? $sf_data->getRaw('message') : null; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : null; ?>
<?php $canDelete = isset($canDelete) ? $sf_data->getRaw('canDelete') : false; ?>
<?php $comments = $message->Comments ?>
<?php $last = count($comments) ?>

<div class="message-comments <?php echo ($last <= 0)?'hide':'' ?>"> 
    <?php if ($last > 0): ?>
        <?php $index = 0; ?>
        <?php foreach($comments as $c): ?>
            <?php include_partial("arWall/comment", 
                        array('canDelete' => $canDelete,
                            'authUser' =>  $authUser,
                            'comment' => $c,
                              'display' => ($index >= ($last - $viewComments)))) ?>
            <?php $index++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>