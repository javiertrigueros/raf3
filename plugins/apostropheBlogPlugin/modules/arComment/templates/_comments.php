<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>

<?php $countText = ($aBlogItem->countComments() > 0)?$aBlogItem->countComments():''; ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
<?php use_javascript("/apostropheBlogPlugin/js/arquematics.comments.js"); ?> 

<?php include_js_call('arComment/jsComments'); ?>

<div class="clearfix" id="comment-wrap">
    <h3 class="main-title" id="comments"><span data-counter="<?php echo $aBlogItem->countComments() ?>" id="comments-counter"><?php echo $countText ?></span> <?php echo __('Comments', null, 'blog') ?></h3>
    <?php include_partial('arComment/commentsList', array('aBlogItem' => $aBlogItem)) ?>
               
  <?php if ($aBlogItem->getAllowComments()): ?>
    <?php include_component('arComment','showFormComment', array('aBlogItem' => $aBlogItem)); ?>
  <?php endif; ?>				
</div>