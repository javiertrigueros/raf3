<?php $hasMedia = isset($hasMedia) ? $sf_data->getRaw('hasMedia') : false; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php $width = isset($width) ? $sf_data->getRaw('width') : 130; ?>
<?php $height = isset($height) ? $sf_data->getRaw('height') : 130; ?>
<?php if ($hasMedia): ?>
<div class="thumb">
  <a title="<?php echo $aBlogItem->getTitle() ?>" href="<?php echo url_for('a_blog_post',$aBlogItem) ?>">
  <?php include_component('arMedia','showImage',
          array(
              'resizeType' => 'c',
              'width' => $width,
              'height' => $height,
              'mediaItem' => $aBlogItem->getImage())); ?>
    </a>
</div><!-- end .post-thumbnail -->
<?php endif; ?>
