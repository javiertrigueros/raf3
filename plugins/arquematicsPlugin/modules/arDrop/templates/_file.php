<?php $file = isset($file) ? $sf_data->getRaw('file') : false ?>
<?php $lastFile = isset($lastFile) ? $sf_data->getRaw('lastFile') : false ?>
<?php $showView = isset($showView) ? $sf_data->getRaw('showView') : false ?>
<?php $isEncrypt = sfConfig::get('app_arquematics_encrypt', false); ?>
<?php $countMoreImages = isset($countMoreImages) ? $sf_data->getRaw('countMoreImages') : 0 ?>

<div class="<?php echo (!$showView)?'hide':'' ?> document-file document-file-container document-file-no-image" id="document-file-<?php echo $file->getId() ?>">
<a class="content-data  document-file-item <?php if ($file->isVisorType()) { echo 'document-file-visor'; } ?>" 
   href="<?php echo url_for('@drop_file_view?slug='.$file->getSlug()) ?>"
   <?php if ($isEncrypt): ?>
    data-id="<?php echo $file->getId() ?>"
    data-load-url="<?php echo url_for('@drop_file_view?slug='.$file->getSlug()); ?>"
    data-url="false"
    data-inline="false"
    data-name="<?php echo $file->getName() ?>"
    data-src=""
    data-content=""
    data-content-enc="<?php echo $file->EncContent->getContent(); ?>"
    data-document-type="<?php echo $file->getType() ?>" 
   <?php else: ?>
   
   <?php endif; ?>
   >
   <?php if (!$isEncrypt): ?>
        <span class="ad-icon-mini file-mini file-text-content <?php echo $file->getFileIcon() ?>"></span>
        <span class="file-text file-text-content">
            <?php echo $file->getName(); ?>
        </span>
   <?php else: ?>
        <span class="ad-icon-mini file-mini file-text-content <?php echo $file->getFileIcon() ?>"></span>
        <span class="file-text file-text-content">
        
        </span>
   <?php endif; ?>
</a>
</div>
