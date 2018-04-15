<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $listLocate = isset($listLocate) ? $sf_data->getRaw('listLocate') : array(); ?>

<div id="map-preview-container" class="preview-container control-border col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <?php if ($hasContent) : ?>
    <?php foreach($listLocate as $locate): ?>
            <?php include_partial('arMap/locate', array('preview' => true, 'locate' => $locate)) ?>
    <?php endforeach; ?>
 <?php endif; ?>
</div>