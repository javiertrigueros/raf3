<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null; ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-select/bootstrap-select-blog.css"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-select/bootstrap-select.js"); ?>

<h4 id="category-control" class="clearfix span12"><?php echo __('Categories',array(),'blog') ?></h4>
 
 <div id="ui-add-category">
     <div id="blog-add-cat" class="control-text ui-control-text">
        <span class="glyphicon glyphicon-plus"></span>
        <?php echo __('Add new category',array(),'blog') ?>
    </div>
     
    <form method="post" action="<?php echo url_for('@ar_blog_create_cat') ?>" id="form-cat-create" class="ui-control-form">
    <?php echo $form->renderHiddenFields() ?>
    <div id="container-add-category-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
        <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $form['name']->render(array('class' => 'ui-control-text-input form-control')) ?>
            <p class="controls-buttom">
                <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>&nbsp;
                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
            </p>
    </div>
    </form>
 </div>

<?php include_js_call('arBlog/jsCategory') ?>