<?php $form = isset($form ) ? $sf_data->getRaw('form') : null; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null; ?>

<?php $tags = $aBlogItem->getTagObjects(); ?>
<?php $tagsCount = count($tags); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/fuelux/css/pillbox.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/fuelux/js/pillbox.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.tageditor.js"); ?>

 <h4 class="clearfix"><?php echo __('Tags',array(),'blog') ?></h4>
 
 <div class="fuelux">
     <?php if ($tagsCount > 0): ?>
     <div id="tagsPillbox" class="pillbox">
        <ul>
        <?php foreach ($tags as $tag): ?>
                <li data-value="<?php echo $tag->getId() ?>" class="status-warning"><?php echo $tag->getName() ?></li>
        <?php endforeach ?>    
        </ul>
    </div>
    <?php else:?>
    <div id="tagsPillbox" class="pillbox hide">
        <ul></ul>
    </div>
    <?php endif; ?>
 </div>
 
 <div id="ui-add-tag">
    <div id="blog-add-tag" class="control-text ui-control-text">
        <span class="glyphicon glyphicon-plus"></span>
        <?php echo __('Add new tag',array(),'blog') ?>
    </div>
    
    <form method="post" action="<?php echo url_for('ar_blog_create_tag') ?>" id="form-tag-create" class="ui-control-form">
    <?php echo $form->renderHiddenFields() ?>
    <div id="container-add-tag-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
           <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php echo $form['name']->render(array('class' => 'ui-control-text-input form-control')) ?>
            <p class="controls-buttom">
                <a id="cmd-send-tag" data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>&nbsp;
                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
            </p>
    </div>
    </form>
</div>

<?php include_js_call('arBlog/jsTag') ?>