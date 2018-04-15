<?php $formLink = isset($formLink) ? $sf_data->getRaw('formLink') : null; ?>
<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>
<?php $content = isset($content) ? $sf_data->getRaw('content') : false; ?>
<?php $listLinks = isset($listLinks) ? $sf_data->getRaw('listLinks') : false; ?>
<?php $sessionLinks = isset($sessionLinks) ? $sf_data->getRaw('sessionLinks') : array(); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arlinks.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.ajaxQueue.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery.embedly.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/components/JavaScript-Load-Image/js/load-image.all.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.graphics.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.link.js"); ?>

<?php include_js_call('arLink/jsLink', array(
    'content' => $content,
    'sessionLinks' => $sessionLinks,
    'hasContent' => $hasContent,
    'showTool' => $showTool)); ?>

<div id="link-control" class="<?php echo (!$hasContent)?'hide':'' ?>">
    
   <?php include_partial('arLink/listPreview', array('hasContent' => $hasContent, 'listLinks' => $listLinks)) ?>
   <form id="link-form" action="<?php echo url_for('@wall_link_send') ?>" method="POST" enctype="multipart/form-data" class='form-link'>
            <?php echo $formLink->renderHiddenFields() ?>
   </form>
 </div>