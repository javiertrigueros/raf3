<div class="dz-message needsclick list-files-preview">
    <?php foreach($listFiles as $file): ?>
        <?php include_partial('arDrop/filePreview',array('file' => $file)); ?>
    <?php endforeach; ?>
</div>
