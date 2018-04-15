<?php
  // Compatible with sf_escaping_strategy: true
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php if (count($items) > 0): ?>
<section class="slider">
        <div id="<?php echo $id; ?>" class="flexslider">
          <ul class="slides">


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


 <?php $thumbnail = $original->getImgSrcUrl($thumbnailDimensions['width'], $thumbnailDimensions['height'], $thumbnailDimensions['resizeType'], $thumbnailDimensions['format'], false); ?>
 <?php //$full = $item->getImgSrcUrl($fullDimensions['width'], $fullDimensions['height'], $fullDimensions['resizeType'], $fullDimensions['format'], false);  ?>

 <li><img title="<?php echo $item->title ?>" src="<?php echo $thumbnail ?>" /></li>
             
    <?php $index++ ?>
      
<?php endforeach ?>
        </ul>
        </div>
      </section>                        

    
<?php endif ?>   
    
    