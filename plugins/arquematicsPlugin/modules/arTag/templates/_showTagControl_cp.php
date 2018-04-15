<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $userTags = isset($userTags) ? $sf_data->getRaw('userTags') : array(); ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') :null; ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.ajaxQueue.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.tag.js"); ?>



<?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
    <?php if ($userTags && (count($userTags) > 0)): ?>
    <div id="tag-control-nav" class="panel panel-transparent profile-skills">
        <div class="panel-heading">
            <span class="panel-title">Skills</span>
	</div>
        <div id="tag-control-list" class="panel-body" data-wall_index_url="<?php echo url_for('@wall') ?>">
            <span class="label label-primary">UI/UX</span>
            <span class="label label-primary">Web design</span>
            <span class="label label-primary">Photoshop</span>
            <span class="label label-primary">HTML</span>
            <span class="label label-primary">CSS</span>
	</div>
    </div>
    <div id="tag-control-nav" class="well sidebar-nav sidebar-nav-fixed">
        <ul id="tag-control-list" data-wall_index_url="<?php echo url_for('@wall') ?>" class="nav nav-pills nav-stacked">
            <?php foreach ($userTags as $tag): ?>
                <?php include_partial('arTag/tagItemEnc', array('tag' => $tag)) ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php else: ?>
    <div id="tag-control-nav" class="panel panel-transparent profile-skills">
        <div class="panel-heading">
            <span class="panel-title">Skills</span>
	</div>
    </div>
    <div id="tag-control-nav" class="well sidebar-nav sidebar-nav-fixed">
        <ul id="tag-control-list" class="nav nav-pills nav-stacked">
    
        </ul>
    </div>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>


<form id="tag-form" action="<?php echo url_for('@wall_tag_send') ?>" method="POST" enctype="multipart/form-data" class="form-tag hide">
<?php echo $form->renderHiddenFields() ?>
</form>
