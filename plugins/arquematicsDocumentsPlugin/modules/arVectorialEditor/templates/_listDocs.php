<?php $message = isset($message) ? $sf_data->getRaw('message') : false; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : false; ?>

<?php $vectorialImages = $message->getVectorialImages($authUser->getId()) ?>
<?php if (($vectorialImages) && (count($vectorialImages) > 0)): ?>
<div class="vectorialDocuments">
    <?php foreach($vectorialImages as $diagram): ?>
        <?php include_partial('arVectorialEditor/vectorialDoc',array('preview' => false, 'document' => $diagram)); ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
