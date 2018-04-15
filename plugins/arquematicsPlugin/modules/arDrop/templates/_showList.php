<?php if ((($listAllFiles) && (count($listAllFiles) > 0))
        || (($documents) && (count($documents) > 0))): ?>
<?php $countFiles = 0 ?>
<div class="list-files" data-loaded="false">
    <?php if (($listViewImages) && (count($listViewImages) == 1)): ?>
       <?php include_partial('arDrop/fileImageOne',array('file' => $listViewImages[0])); ?>
    <?php elseif (($listViewImages) && (count($listViewImages) > 1)): ?>
        <?php foreach($listViewImages as $file): ?>
            <?php $countFiles++ ?>
            <?php include_partial('arDrop/fileImage',array('showView' => true, 'lastFile' => ($countFiles === 4), 'countMoreImages' => $countMoreImages, 'file' => $file)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (($listImages) && (count($listImages) > 0)): ?>
        <?php foreach($listImages as $file): ?>
            <?php include_partial('arDrop/file',array('showView' => false, 'lastFile' => false,'file' => $file)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (($listFiles) && (count($listFiles) > 0)): ?>
        <?php foreach($listFiles as $file): ?>
            <?php include_partial('arDrop/file',array('showView' => true, 'lastFile' => false, 'file' => $file)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (($documents) && (count($documents) > 0)): ?>
        <?php foreach($documents as $doc): ?>
        <?php if ($doc->isNoteType()): ?>
            <?php include_partial('arLaverna/note',array('preview' => false, 'file' => $doc)); ?>
        <?php elseif ($doc->isOryxType()): ?>
            <?php include_partial('arVectorialEditor/orxyDoc',array('preview' => false, 'file' => $doc)); ?> 
        <?php elseif ($doc->isRawchartType()): ?>
            <?php include_partial('arVectorialEditor/rawchartDoc',array('preview' => false, 'file' => $doc)); ?> 
        <?php else: ?>
            <?php include_partial('arVectorialEditor/vectorialDoc',array('preview' =>false, 'file' => $doc)); ?>
        <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endif; ?>