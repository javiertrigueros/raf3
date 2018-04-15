<?php
  // Compatible with sf_escaping_strategy: true
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php if (count($items) > 0): ?>

<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/jquery.galereya.css"); ?>
   
<?php //http://jquerypicture.com/ ?>
<?php //http://webcodebuilder.com/examples/flexslider-kwiks/index.html ?>
<?php //http://aozora.github.io/bootmetro/demo/hub.html# ?>
<?php //http://tympanus.net/Development/GammaGallery/ ?>

<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery.galereya.js"); ?>
<div id="<?php echo $id;  ?>">
    
 <?php $galleryWidth = (int)sfConfig::get('app_arquematics_gallery_width'); ?>
 <?php $galleryWidthMax = (int)sfConfig::get('app_arquematics_gallery_width_max'); ?>
 <?php $galleryHeightMax = (int)sfConfig::get('app_arquematics_gallery_height_max'); ?>
 <?php foreach ($items as $item): ?>
  <?php // Returns same object where applicable ?>
  <?php $original = $item->getCropOriginal() ?>

          <?php $dimensionsMax = aDimensions::constrain(
                $original->width,
                $original->height,
                $original->format,
                array(
                    "width" =>  ($original->width < $galleryWidthMax)?$original->width: $galleryWidthMax ,
                    "height" => ($original->height < $galleryHeightMax )?$original->height:$galleryHeightMax,
                    "resizeType" => 'c'
           )); ?>
    
          <?php $dimensions = aDimensions::constrain(
                $original->width,
                $original->height,
                $original->format,
                array(
                    "width" =>  $galleryWidth,
                    "height" => ($original->height < $galleryHeightMax )?false:$galleryHeightMax,
                    "resizeType" => 'c'
           )); ?>
           <?php //siempre tiene título ?>
           <?php $description = (strlen(trim($item->description)) > 0)?$item->description: $item->title; ?>
            
    
           <img src="<?php echo $original->getScaledUrl($dimensions) ?>" 
                width="<?php echo $dimensions['width'] ?>"
                height="<?php echo $dimensions['height'] ?>"
                atl="<?php echo $item->title ?>"
                data-fullsrc="<?php echo $original->getScaledUrl($dimensionsMax) ?>"
                data-desc="<?php echo $description ?>"
            />       
<?php endforeach ?>
 </div>

<?php include_js_call('arGalleryResponsiveSlot/jsGallery', array('id' => $id)) ?>

<?php endif ?>   
    
    