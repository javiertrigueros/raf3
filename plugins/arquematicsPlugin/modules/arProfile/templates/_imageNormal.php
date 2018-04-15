<?php $image = isset($image) ? $sf_data->getRaw('image') : false; ?>
<?php $class = isset($class) ? $sf_data->getRaw('class') : false; ?>
<?php if ($image): ?>
<img class="<?php echo ($class)?$class:''; ?>" src='<?php echo url_for("@user_resource?type=arProfileUpload&name=".$image->getBaseName()."&format=".$image->getExtension()."&size=normal") ?>' id='image'/>
<?php else: ?>
<img class="<?php echo ($class)?$class:''; ?>" src="/arquematicsPlugin/images/unknown.normal.jpg" />
<?php endif ?> 