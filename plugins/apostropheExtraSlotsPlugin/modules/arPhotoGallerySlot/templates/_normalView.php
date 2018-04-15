<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $itemIds = isset($itemIds) ? $sf_data->getRaw('itemIds') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
?>

<?php use_helper('a') ?>

<?php if ($editable): ?>

 <?php // Normally we have an editor inline in the page, but in this ?>
 <?php // case we'd rather use the picker built into the media plugin. ?>
 <?php // So we link to the media picker and specify an 'after' URL that ?>
 <?php // points to our slot's edit action. Setting the ajax parameter ?>
 <?php // to false causes the edit action to redirect to the newly ?>
 <?php // updated page. ?>

 <?php slot("a-slot-controls-$pageid-$name-$permid") ?>
   <li class="a-controls-item choose-images">
     <?php aRouteTools::pushTargetEngineSlug('/admin/media', 'aMedia') ?>
     <?php echo link_to('<span class="icon"></span>' . a_get_option($options, 'chooseLabel', a_('Choose Images')),
       'aMedia/select',
       array(
         'query_string' => 
           http_build_query(
             array_merge(
               $options['constraints'],
               array("multiple" => true,
               "aMediaIds" => implode(",", $itemIds),
               "type" => "image",
               "label" => a_get_option($options, 'browseLabel', a_('Creating a Gallery.')),
               "after" => a_url('arPhotoGallerySlot', 'edit') . "?" . 
                 http_build_query(
                   array(
                     "slot" => $name, 
                     "slug" => $slug, 
                     "permid" => $permid,
                     // actual_url will be added by JS, window.location is more reliable than
                     // guessing at the full context here when we might be in an AJAX update etc.
                     "noajax" => 1))))),
         'class' => 'a-btn icon a-media a-inject-actual-url a-js-choose-button')) ?>
     <?php aRouteTools::popTargetEnginePage('aMedia') ?>
   </li>

   <?php include_partial('a/variant', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>

 <?php end_slot() ?>

<?php endif ?>
    
<?php use_stylesheet("/apostropheExtraSlotsPlugin/css/arExtraSlots.css"); ?>
<?php use_stylesheet("/apostropheExtraSlotsPlugin/css/bootstrap-image-gallery.css"); ?>
   
<?php use_stylesheet("/arquematicsPlugin/css/normalize.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/bootstrap.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/bootstrap-responsive.css"); ?>
   
<?php use_javascript("/arquematicsPlugin/js/bootstrap-2.0.2.js"); ?>

<?php use_javascript("/apostropheExtraSlotsPlugin/js/load-image.js"); ?>
<?php use_javascript("/apostropheExtraSlotsPlugin/js/bootstrap-image-gallery.js"); ?>
   
   
<?php include_partial('arPhotoGallerySlot/'. $options['gridTemplate'], array('items' => $items, 'id' => $id, 'options' => $options, 'placeholder' => count($items))) ?>

<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade modal-fullscreen-stretch" tabindex="-1">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn btn-primary btn-success" data-dismiss="modal"><?php echo a_('Close'); ?></a>
    </div>
</div>

<?php if (count($items) > 0): ?>

<script type="text/javascript">
    $(document).ready(function()
    {
        var $carouselGall = $('<?php echo '#arPhotoGallery'.$id ?>').carousel({pause: true,
                            interval: false}).data('carousel');
         
        $carouselGall.pause();
        
        $('#modal-gallery').on('display', function (e) {
               var modalData = $(this).data('modal');
              
               var $carousel = $('<?php echo '#arPhotoGallery'.$id ?>').carousel(modalData.options.index).data('carousel');
               
               $carousel.pause();
        });
       
    });
 </script>
 
 <?php endif; ?>