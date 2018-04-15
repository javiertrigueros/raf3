<?php $file = isset($file) ? $sf_data->getRaw('file') : false; ?>
<?php
/* tipos de imagen
* mini
* small
* big
* original
*/
?>
<a id="file-<?php echo $file->getId()  ?>" rel="" class="" href="<?php echo url_for("@3d_obj_view?name=".$file->getBaseName()."&format=".$file->getExtension()) ?>"><?php echo $file->getName() ?></a>