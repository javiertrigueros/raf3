<?php use_helper('a','I18N') ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.iframe.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.prettyfile.js"); ?>

<div class="profile-block">
            <div id="profile_image" class="panel profile-photo profile-photo-extra">
                <a href="<?php echo url_for('user_profile',$aUser) ?>">
                <?php include_partial('arProfile/imageBig', 
                    array('image' => $arProfileImage)); ?>
                </a>
            </div>
            <?php if ($can_edit): ?>
            <div class="image-control">
            <form target="iframe-post-form" id="form_profile_image_send" name="form_profile_image_send"  action="<?php echo url_for('user_profile_send_image',$aUser) ?>" enctype="multipart/form-data" method="post">
                <?php echo $form->renderHiddenFields() ?>
                <?php echo $form->renderGlobalErrors() ?>
                <?php echo $form['name']->render() ?>
                <?php echo $form['name']->renderError() ?>
            </form>
            </div>
            <?php endif ?>
</div>


<?php if ($can_edit): ?>
    <?php include_js_call('arProfile/jsProfileImage'); ?>
<?php endif ?>