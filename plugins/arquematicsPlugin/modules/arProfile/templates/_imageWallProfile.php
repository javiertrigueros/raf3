<?php $image = isset($image) ? $sf_data->getRaw('image') : false; ?>
<?php $class = isset($class) ? $sf_data->getRaw('class') : false; ?>
<?php if ($image): ?>
<img src='<?php echo url_for("@user_resource?type=arProfileUpload&name=".$image->getBaseName()."&format=".$image->getExtension()."&size=wallProfile") ?>' id='image'/>
<?php else: ?>
<img src="/arquematicsPlugin/images/unknown.big.jpg" />
<?php endif ?> 