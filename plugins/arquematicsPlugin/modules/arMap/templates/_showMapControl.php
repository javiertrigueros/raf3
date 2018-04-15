<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arMaps.css"); ?>

<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>

<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>
<?php $content = isset($content) ? $sf_data->getRaw('content') : false; ?>
<?php $sessionLinks = isset($sessionLinks) ? $sf_data->getRaw('sessionLinks') : false; ?>

<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $listLocate = isset($listLocate) ? $sf_data->getRaw('listLocate') : array(); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arMaps.css"); ?>

<?php include_js_call('arMap/jsMapControl',  array(
    'content' => $content,
    'sessionLinks' => $sessionLinks,
    'hasContent' => $hasContent,
    'showTool' => $showTool)) ?>

<div id="map-control" class="<?php echo (!$hasContent)?'hide':''; ?>">
   
  <?php include_partial('arMap/listPreview', array('hasContent' => $hasContent, 'listLocate' => $listLocate)) ?>
  
  <form id="map-form" action="<?php echo url_for('@wall_send_map') ?>" method="POST" enctype="multipart/form-data" class='form-link'>
    <?php echo $form->renderHiddenFields() ?>
  </form>
    
 </div>