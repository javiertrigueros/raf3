<?php $file = isset($file) ? $sf_data->getRaw('file') : false ?>
<?php $isEncrypt = sfConfig::get('app_arquematics_encrypt', false); ?>

<div class="document-file document-file-container document-file-no-image" id="document-file-<?php echo $file->getId() ?>">
<a class="content-data  document-file-item document-file-visor" 
   href="<?php echo url_for('@laverna_doc').'#/note/edit/'.$file->getGuid(); ?>"
   <?php if ($isEncrypt): ?>
    data-id="<?php echo $file->getId() ?>"
    data-load-url="<?php echo url_for('@laverna_doc').'#/note/edit/'.$file->getGuid(); ?>"
    data-inline="true"
    data-url="<?php echo url_for('@laverna_doc').'#/note/edit/'.$file->getGuid(); ?>"
    data-name="<?php echo $file->getTitle() ?>"
    data-src="<?php echo $file->getContent();?>"
    data-content=""
    data-files='<?php echo $file->getFilesArrString(); ?>'
    data-guid='<?php echo $file->getGuid(); ?>'
    data-files-loaded='[]'
    data-content-enc="<?php echo $file->EncContent->getContent() ?>"
    data-document-type="text/plain" 
   <?php else: ?>
   
   <?php endif; ?>
   >
   <?php if ($isEncrypt): ?>
        <span class="<?php echo $file->getIcon() ?>"></span>
        <span class="file-text file-text-content">
            
        </span>
   <?php else: ?>
        <span class="<?php echo $file->getIcon() ?>"></span>
        <span class="file-text file-text-content">
          <?php echo $file->getTitle(); ?>
        </span>
   <?php endif; ?>
</a>
</div>
