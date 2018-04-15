<?php $message = isset($message) ? $sf_data->getRaw('message') : null; ?>

<div class="messages-footer controls-message-controls" >
    <a class="cmd-comment-link" href="#"><?php echo  __('Comment',null,'wall') ?></a>
    <?php $countComments = count($message->Comments) ?>
    <?php $viewComments = sfConfig::get('app_arquematics_plugin_wall_comments_view', 4); ?>
    <?php if (($countComments > 0)
             && ($countComments > $viewComments)) : ?>
            &nbsp;·&nbsp;
            <a href="#" class="cmd-view-comments">
                <?php echo __('View %count% comments more',array('%count%' => $countComments - $viewComments),'wall') ?>
            </a>
    <?php endif ?>
</div>