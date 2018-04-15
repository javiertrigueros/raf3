<?php $locate = isset($locate) ? $sf_data->getRaw('locate') : false; ?>

<?php if ($locate): ?>
<div data-hash="<?php echo $locate->getHash(); ?>"
     <?php if (sfConfig::get('app_arquematics_encrypt', false)): ?>
     data-content-enc="<?php echo $locate->EncContent->getContent(); ?>"
     data-content=""
     <?php else: ?>
     data-content-enc=""
     data-content="<?php echo $locate->getFormatedAddress(); ?>"
     <?php endif; ?>
     data-map-id="<?php echo $locate->getId() ?>" class="profile-map-item content-data col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
</div>
<?php else: ?>
<div data-hash=""
     data-content-enc=""
     data-content=""
     data-map-id="" class="profile-map-item content-data col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
</div>

<?php endif; ?>