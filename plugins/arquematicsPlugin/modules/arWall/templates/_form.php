<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>

<?php //use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-typeahead/bootstrap-typeahead.js"); ?>
<?php //use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.mention.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.infinite.js"); ?>   
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.wall.js"); ?>

<?php include_js_call('arWall/jsTabWall',array('aUserProfileFilter' => $aUserProfileFilter,'aUserProfile' => $aUserProfile)) ?>
<?php /*
 * jsTag es dependiente de jsTabWall, y no tiene sentido que actue
 * este uno y el otro no
 */
?>
<?php include_js_call('arTag/jsTag') ?>

<form action="<?php echo url_for('@wall_message_send') ?>" id="wall_send_content" class="tab-from comment-text no-padding no-border" method="POST" enctype="multipart/form-data">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <?php echo $form['message']->render(array('rows' => '1' ,'class' => 'control-border-wall form-control form-wall')) ?>
    <?php echo $form['message']->renderError() ?>
    <?php echo $form['groups']->render() ?>
    <?php /*
    <div class="expanding-input-hidden" style="margin-top: 10px;">
        <label class="checkbox-inline pull-left">
            <input type="checkbox" class="px">
            <span class="lbl">Private message</span>
        </label>
        <?php echo $form['groups']->render() ?>
	<button class="btn btn-primary pull-right">Leave Message</button>
        <?php //include_component('arGroup','showListSelect', array('aUserProfile' => $aUserProfile,'tab' => $tab)); ?>
                
        <?php //include_component('arWall','showButtonTools'); ?>
    </div> */ ?>
</form>

<?php include_partial("arWall/fields", array('form' => $form)); ?>

