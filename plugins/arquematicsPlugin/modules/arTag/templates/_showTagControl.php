<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $userTags = isset($userTags) ? $sf_data->getRaw('userTags') : array(); ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') :null; ?>
<?php $activeTag = isset($activeTag) ? $sf_data->getRaw('activeTag') :false; ?>


<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.ajaxQueue.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.tag.js"); ?>



<?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
    <?php if ($userTags && (count($userTags) > 0)): ?>
    <div id="tag-control-nav" class="panel panel-transparent profile-skills">
        <div class="panel-heading">
            <span class="panel-title"><?php echo __('Tags', null, 'wall'); ?></span>
	</div>
        <div id="tag-control-list" class="panel-body" data-active_tag="<?php echo ($activeTag)?$activeTag:'false' ?>"   data-wall_index_url="<?php echo url_for('@wall') ?>">
            <?php foreach ($userTags as $tag): ?>
                <?php include_partial('arTag/tagItemEnc', array('tag' => $tag)) ?>
            <?php endforeach; ?>
	</div>
    </div>
    
    <?php else: ?>
    <div id="tag-control-nav" class="panel panel-transparent profile-skills">
        <div class="panel-heading">
            <span class="panel-title"><?php echo __('Tags', null, 'wall'); ?></span>
	</div>
        <div id="tag-control-list" class="panel-body" data-active_tag="false"   data-wall_index_url="<?php echo url_for('@wall') ?>">
            
	</div>
    </div>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>


<form id="tag-form" action="<?php echo url_for('@wall_tag_send') ?>" method="POST" enctype="multipart/form-data" class="form-tag hide">
<?php echo $form->renderHiddenFields() ?>
</form>
