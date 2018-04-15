<?php $document = isset($document) ? $sf_data->getRaw('document') : false ?>
<?php $preview = isset($preview) ? $sf_data->getRaw('preview') : false ?>
<?php $isEncrypt = sfConfig::get('app_arquematics_encrypt', false); ?>

<div class="document-vectorial" id="document-vectorial<?php echo $document->getId() ?>">

    <span data-type-name="vectorial-doc" data-guid="<?php echo $document->getGuid(); ?>"  data-document-vectorial-id="<?php echo $document->getId(); ?>" id="remove-document-vectorial-<?php echo $document->getId(); ?>" class="icon-remove-document cmd-remove-document-vectorial fa fa-times-circle"></span>
    
<a class="content-data document-vectorial-content" 
   href="<?php echo url_for('@diagram_view?name='.$document->getType().'&guid='.$document->getGuid()) ?>"
   <?php if ($isEncrypt): ?>
    data-id="<?php echo $document->getId() ?>"
    data-is-encode="true"
    data-url="<?php echo url_for('@diagram_view?name='.$document->getType().'&guid='.$document->getGuid()) ?>"
    data-title="<?php echo $document->getTitle() ?>" 
    data-content=""
    data-document-type="<?php echo $document->getType() ?>"
    data-content-enc="<?php echo $document->EncContent->getContent() ?>"
    data-image="<?php echo $document->getDataImage();?>" 
   <?php else: ?>
    data-id="<?php echo $document->getId() ?>"
    data-is-encode="true"
    data-url="<?php echo url_for('@diagram_view?name='.$document->getType().'&guid='.$document->getGuid()) ?>"
    data-title="" 
    data-content=""
    data-document-type="<?php echo $document->getType() ?>"
    data-content-enc=""
   <?php endif; ?>
   >

   <?php if ($isEncrypt): ?>
        <span class="<?php echo $document->getIcon(); ?>"></span>
        <span class="document-text file-text file-text-content">
            
        </span>
   <?php else: ?>
        <span class="<?php echo $document->getIcon(); ?>"></span>
        <span class="document-text file-text file-text-content">
            <?php echo $document->getTitle(); ?>
        </span>
   <?php endif; ?>
</a>
</div>