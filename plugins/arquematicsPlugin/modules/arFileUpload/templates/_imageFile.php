<?php $file = isset($file) ? $sf_data->getRaw('file') : false; ?>
<?php
/* tipos de imagen
* mini
* small
* big
* original
*/
?>
<a id="file-<?php echo $file->getId()  ?>" rel="" class="" href="<?php echo url_for("@user_resource?type=arWallUpload&name=".$file->getBaseName()."&format=".$file->getExtension()."&size=big") ?>">
    <img src="<?php echo url_for("@user_resource?type=arWallUpload&name=".$file->getBaseName()."&format=".$file->getExtension()."&size=small") ?>" alt="<?php echo $file->getName() ?>" />
</a>