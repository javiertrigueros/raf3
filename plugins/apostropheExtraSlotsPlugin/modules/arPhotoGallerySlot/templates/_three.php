<?php
  // Compatible with sf_escaping_strategy: true
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php if (count($items) > 0): ?>

<!-- Main Area -->
    <!-- Slider -->
    <div class="row-fluid">
            <div class="span12" id="slider">
            <!-- Top part of the slider -->
            <div class="row-fluid">
                <div class="span12" id="carousel-bounding-box">
                <div id="<?php echo 'arPhotoGallery'.$id; ?>" class="carousel slide">
                <!-- Carousel items -->
                    <div class="carousel-inner" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.carousel-link">
                        <?php $index = 0 ?>
                        <?php foreach ($items as $item): ?>
  <?php // Returns same object where applicable ?>
  <?php $original = $item->getCropOriginal() ?>

  <?php $thumbnailDimensions = aDimensions::constrain(
    $original->width,
    $original->height,
    $original->format,
    array(
      "width" =>  $options['gridWidth'],
      "height" => $options['gridHeight'],
      "resizeType" => 'c'
  )) ?>


  <?php $fullDimensions = aDimensions::constrain(
    $item->width,
    $item->height,
    $item->format,
    array(
      "width" =>  $options['width'],
      "height" => $options['height'],
      "resizeType" => $options['resizeType']
  )) ?>


 <?php $thumbnail = $original->getImgSrcUrl($thumbnailDimensions['width'], $thumbnailDimensions['height'], $thumbnailDimensions['resizeType'], $thumbnailDimensions['format'], false); ?>
 <?php $full = $item->getImgSrcUrl($fullDimensions['width'], $fullDimensions['height'], $fullDimensions['resizeType'], $fullDimensions['format'], false);  ?>

    <?php if ($index == 0): ?>
         <div class="active item carousel" data-slide-number="<?php echo $index; ?>"><a href="<?php echo $full ?>" title="<?php echo $item->title ?>" class="carousel-link"><img src="<?php echo $thumbnail ?>" /></a></div>
    <?php else: ?>
         <div class="item carousel" data-slide-number="<?php echo $index; ?>"><a href="<?php echo $full ?>" title="<?php echo $item->title ?>" class="carousel-link"><img src="<?php echo $thumbnail ?>" /></a></div>
    <?php endif; ?>                  
    <?php $index++ ?>
<?php endforeach ?>
                             
                    </div>
                    <!-- Carousel nav -->
                    <a class="carousel-control left" href="<?php echo '#arPhotoGallery'.$id; ?>" data-slide="prev">‹</a>
                    <a class="carousel-control right" href="<?php echo '#arPhotoGallery'.$id; ?>" data-slide="next">›</a>
                </div>
                </div>
                
            </div>
     
    </div>
    </div> <!--/Slider-->
    
<?php endif ?>   
    
    