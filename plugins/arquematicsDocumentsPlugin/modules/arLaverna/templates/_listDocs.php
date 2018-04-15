<?php $message = isset($message) ? $sf_data->getRaw('message') : false; ?>
<?php $documents = ($message)? $message->LavernaDocs: array(); ?>

<?php if (($documents) && (count($documents) > 0)): ?>
<div class="documents vectorialDocuments">
    <?php foreach($documents as $doc): ?>
        <?php if ($doc->isNoteType()): ?>
            <?php include_partial('arLaverna/note',array('preview' => false, 'document' => $doc)); ?> 
        <?php else: ?>
            <?php include_partial('arVectorialEditor/vectorialDoc',array('preview' =>false, 'document' => $doc)); ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
