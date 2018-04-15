<?php $formBlogPost = isset($formBlogPost) ? $sf_data->getRaw('formBlogPost') : null; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null; ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?>

<?php use_helper('a','arBlog') ?>

<?php use_stylesheets_for_form($formBlogPost) ?>
<?php use_javascripts_for_form($formBlogPost) ?>

<?php use_javascript("/arquematicsPlugin/js/components/state-machine/state-machine.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldstatus.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.datetimer.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.datetimerange.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.blogpublisher.js"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-switch/bootstrap-switch.css"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-switch/bootstrap-switch.js"); ?>

 <a class="btn btn-default btn-sm" target="_blank" href="<?php echo url_for('a_blog_post',$aBlogItem) ?>">
   <span class="glyphicon glyphicon-search"></span>&nbsp;<?php echo __('Preview',array(),'blog') ?>   
 </a>

<form method="post" action="<?php echo url_for('ar_blog_update_event', $aBlogItem) ?>" id="form-update" class="">

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


<div id="ui-date-range" class="ui-control misc-pub-section">
    <div class="ui-control-text-composed ui-control-status-zone">
         <div id="item-date" class="control-text ui-control-text extra-separation ui-control-edit">
            <span class="glyphicon glyphicon-calendar"></span>
            <span class="status-text"><?php echo getTextEventStart($aBlogItem, $culture); ?></span> 
        </div>
        <?php if ($aBlogItem->hasEndDate()): ?>
        <div id="item-date" class="control-text ui-control-text-extra extra-separation ui-control-edit">
             <span class="glyphicon glyphicon-calendar"></span>
            <span class="status-text-extra"><?php echo getTextEventEnd($aBlogItem, $culture); ?></span> 
        </div>
        <?php else: ?>
        <div id="item-date" class="control-text ui-control-text-extra extra-separation hide ui-control-edit">
            <span class="glyphicon glyphicon-calendar"></span>
            <span class="status-text-extra"><?php echo getTextEventEnd($aBlogItem, $culture); ?></span> 
        </div>
        <?php endif; ?>
    </div>
   
    
    <?php if ($aBlogItem->hasEndDate()): ?>
    <div class="alert alert-warning alert-dismissable ui-control-input-zone control-group hide">
        <?php echo $formBlogPost['start_date']->render(array('class' => 'start-date ui-control-text-input form-control')); ?>
        <?php echo $formBlogPost['start_time']->render(array('class' => 'start-time ui-control-text-input form-control')); ?>
        
        <div class="ui-control-add-date-end hide">
            <span class="glyphicon glyphicon-plus"></span>
            <?php echo __('Add end date',null,'blog'); ?>
        </div>
        <div class="ui-control-remove-date-end">
            <span class="glyphicon glyphicon-minus"></span>
            <?php echo __('Remove end date',null,'blog'); ?>
        </div>
        <?php echo $formBlogPost['end_date']->render(array(
                      'class' => 'end-date ui-control-text-input form-control',
                      'autocomplete'=>'off')); ?>
        
        <?php echo $formBlogPost['end_time']->render(array(
                      'class' => 'end-time ui-control-text-input form-control',
                      'autocomplete'=>'off')); ?>
        
         <p class="controls-buttom">
            <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Accept',null,'blog'); ?></a>&nbsp;
            <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
        </p>
    </div>
    <?php else: ?>
    <div class="alert alert-warning alert-dismissable ui-control-input-zone hide control-group">
        <?php echo $formBlogPost['start_date']->render(array('class' => 'start-date ui-control-text-input form-control')); ?>
        <?php echo $formBlogPost['start_time']->render(array('class' => 'start-time ui-control-text-input form-control')); ?>
        
        <div class="ui-control-add-date-end">
            <span class="glyphicon glyphicon-plus"></span>
            <?php echo __('Add end date',null,'blog'); ?>
        </div>
        <div class="ui-control-remove-date-end hide">
            <span class="glyphicon glyphicon-minus"></span>
            <?php echo __('Remove end date',null,'blog'); ?>
        </div>
        <?php echo $formBlogPost['end_date']->render(array('class' => 'end-date ui-control-text-input form-control hide')); ?>
        <?php echo $formBlogPost['end_time']->render(array('class' => 'end-time ui-control-text-input form-control hide')); ?>
         <p class="controls-buttom">
            <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Accept',null,'blog'); ?></a>&nbsp;
            <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
        </p>
    </div>
    <?php endif; ?>
    
</div>
    
<?php echo $formBlogPost['categories_list']->render(array( 'title' => __("Select categories",array(),'blog'), 'class' => 'hide')) ?>
<?php echo $formBlogPost['tags_list']->render() ?>

</form>

<?php include_js_call('arBlog/jsArDateTime', array('nameId' => 'a_blog_post_published_at',
                                                    'culture' => $culture)) ?>
                                                           
<?php include_js_call('arBlog/jsArDate', array('nameId' => 'a_blog_post_start_date',
                                                    'culture' => $culture)); ?>
<?php include_js_call('arBlog/jsArTime', array('nameId' => 'a_blog_post_start_time',
                                                    'culture' => $culture)); ?>
<?php include_js_call('arBlog/jsArDate', array('nameId' => 'a_blog_post_end_date',
                                                    'culture' => $culture)); ?>
<?php include_js_call('arBlog/jsArTime', array('nameId' => 'a_blog_post_end_time',
                                                    'culture' => $culture)); ?>
<?php include_js_call('arBlog/jsPublishEvent', array('culture' => $culture, 'aBlogItem' => $aBlogItem)); ?>