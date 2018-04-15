<?php $locate = isset($locate) ? $sf_data->getRaw('locate') : false; ?>
<?php $preview = isset($preview) ? $sf_data->getRaw('preview') : false; ?>

<?php if ($locate): ?>

<div data-hash="<?php echo $locate->getHash(); ?>"
     <?php if (sfConfig::get('app_arquematics_encrypt', false)): ?>
     data-content-enc="<?php echo $locate->EncContent->getContent(); ?>"
     data-content=""
     <?php else: ?>
     data-content-enc=""
     data-content="<?php echo $locate->getFormatedAddress(); ?>"
     <?php endif; ?>
     data-map-id="<?php echo $locate->getId() ?>" class="map-item content-data">
   
   <?php if ($preview): ?>
        <span data-map-id="<?php echo $locate->getId(); ?>" id="remove-map-<?php echo $locate->getId(); ?>" class="icon-remove-map cmd-remove-map fa fa-times-circle"></span>
   <?php endif; ?>
  
   <div class="map-static col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
   <div class="map-dinamic col-xs-12 col-sm-12 col-md-12 col-lg-12"></div> 
</div>

<?php else: ?>

<div data-hash=""
     data-content-enc=""
     data-content=""
     data-map-id="" class="map-item content-data">
    
   <div class="map-static col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
   <div class="map-dinamic col-xs-12 col-sm-12 col-md-12 col-lg-12"></div> 
</div>

<?php endif; ?>