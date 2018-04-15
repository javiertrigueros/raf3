<?php use_helper("a") ?>
<form id="a-event-new-form" method="post" action="<?php echo url_for('ar_blog_event_create') ?>">
        <?php echo $form->renderHiddenFields() ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
            <?php echo $form['title']->render(array('class' => 'control-border-wall form-control form-wall')) ?>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
            <?php echo $form['message']->render(array('class' => 'control-border-wall form-control form-wall')) ?>
        </div>
        
        <?php echo $form['groups']->render(array('class' => 'hide')) ?>
</form>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.ajaxQueue.js"); ?>
<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery-picture.js"); ?> 

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.tag.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.blogitem.js"); ?>

<?php include_js_call('arBlog/jsTabEvents') ?>