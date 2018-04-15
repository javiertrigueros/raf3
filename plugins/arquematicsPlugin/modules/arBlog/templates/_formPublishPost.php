<?php $formBlogPost = isset($formBlogPost) ? $sf_data->getRaw('formBlogPost') : null; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null; ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?>

<?php use_helper('a','arBlog') ?>

<?php use_stylesheets_for_form($formBlogPost) ?>

<?php use_javascripts_for_form($formBlogPost) ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldstatus.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.datetimer.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.blogpublisher.js"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-switch/bootstrap-switch.css"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-switch/bootstrap-switch.js"); ?>

 <a class="btn btn-default btn-sm" target="_blank" href="<?php echo url_for('a_blog_post',$aBlogItem) ?>">
   <span class="glyphicon glyphicon-search"></span>&nbsp;<?php echo __('Preview',array(),'blog') ?>   
 </a>

<form method="post" action="<?php echo url_for('ar_blog_update_blog', $aBlogItem) ?>" id="form-update" class="">

 <?php echo $formBlogPost->renderHiddenFields() ?>


<div id="ui-status" class="ui-control misc-pub-section">
   
   <div id="item-status" class="control-text ui-control-text">
       <p>
         <span class="glyphicon glyphicon-pencil"></span>
         <span><?php echo __('Status',array(),'blog') ?>:</span>  
       </p>
       <?php echo $formBlogPost['is_publish']->render(array('class' => 'ui-control-text-input form-control')) ?>
   </div>
   
</div>
    
 <div id="ui-comments" class="ui-control misc-pub-section">
     
     <div id="item-coments" class="control-text ui-control-text">
        <p>
            <span class="glyphicon glyphicon-pencil"></span>
            <?php echo __('Comments',array(),'blog') ?>:
        </p>
        <?php echo $formBlogPost['allow_comments']->render(array('class' => 'form-control')) ?> 
     </div>
     
</div>
 
 <div id="ui-author" class="ui-control misc-pub-section">
     <div id="item-author" class="control-text ui-control-text ui-control-edit">
        <span class="glyphicon glyphicon-pencil"></span>
        <span><?php echo __('Author',array(),'blog') ?>:</span>
        <span class="status-text"><?php echo $aBlogItem->getAuthor()->getName() ?></span> 
     </div>
     <div id="item-author-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
        <?php echo $formBlogPost['author_id']->render(array('class' => 'ui-control-text-input form-control')) ?>
        <p class="controls-buttom">
            <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Accept',null,'blog'); ?></a>&nbsp;
            <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
        </p>
    </div>
</div>
 
<div id="ui-date" class="ui-control misc-pub-section">
    
    <div id="item-date" class="control-text ui-control-text ui-control-edit">
        <span class="glyphicon glyphicon-calendar"></span>
        <span class="status-text"><?php echo getDateTimeForEdit($aBlogItem); ?></span> 
    </div>
    <div id="item-date-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
        <?php echo $formBlogPost['published_at']->render(array('class' => 'ui-control-text-input form-control')) ?>
        
        <p class="controls-buttom">
            <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Accept',null,'blog'); ?></a>&nbsp;
            <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
        </p>
    </div>
</div> 

<?php if ($aBlogItem->getIsSave()): ?>
    <button type="button" data-text="<?php echo __("Update",array(),'blog') ?>" data-text-draft="<?php echo __("Save draft",array(),'blog') ?>" data-loading-text="<?php echo __("send...",array(),'wall') ?>" id="update-button"  class="btn btn-success misc-pub-section">
       <?php if ($aBlogItem->getIsPublish()): ?>
        <?php echo __("Update",array(),'blog') ?>
       <?php else: ?>
        <?php echo __("Save draft",array(),'blog') ?>
       <?php endif; ?>
    </button> 
<?php else: ?>
    <button type="button" data-text="<?php echo __("Publish",array(),'blog') ?>" data-text-draft="<?php echo __("Save draft",array(),'blog') ?>" data-loading-text="<?php echo __("send...",array(),'wall') ?>" id="update-button"  class="btn btn-success misc-pub-section">
        <?php if ($aBlogItem->getIsPublish()): ?>
            <?php echo __("Publish",array(),'blog') ?>
        <?php else: ?>
            <?php echo __("Save draft",array(),'blog') ?>
        <?php endif; ?>
    </button> 
<?php endif; ?>
   
    
<?php echo $formBlogPost['categories_list']->render(array( 'title' => __("Select categories",array(),'blog'), 'class' => 'hide')) ?>
<?php echo $formBlogPost['tags_list']->render() ?>

</form>

<?php include_js_call('arBlog/jsArDateTime', array('nameId' => 'a_blog_post_published_at',
                                                    'culture' => $culture)) ?>
<?php include_js_call('arBlog/jsPublishPost', array('culture' => $culture, 'aBlogItem' => $aBlogItem)) ?>
