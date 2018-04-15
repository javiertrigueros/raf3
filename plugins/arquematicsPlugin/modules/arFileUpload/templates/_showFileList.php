<?php $files = isset($files) ? $sf_data->getRaw('files') : false; ?>
<?php $hasFiles = isset($hasFiles) ? $sf_data->getRaw('hasFiles') : false; ?>
<?php $imageFiles = isset($imageFiles) ? $sf_data->getRaw('imageFiles') : false; ?>
<?php $d3Files = isset($d3Files) ? $sf_data->getRaw('d3Files') : false; ?>
<?php $resourceFiles = isset($resourceFiles) ? $sf_data->getRaw('resourceFiles') : false; ?>
 

<?php if ($hasFiles): ?>
    <div class="files">
    
    <?php if (count($imageFiles) > 0): ?>
        <?php foreach($imageFiles as $file): ?>
            <?php include_partial("arFileUpload/imageFile", array('file' => $file)) ?>
        <?php endforeach; ?>
    <?php endif; ?>
        
    <?php if (count($d3Files) > 0): ?>
        <?php foreach($d3Files as $file): ?>
            <?php include_partial("arFileUpload/3dFile", array('file' => $file)) ?>
        <?php endforeach; ?>
    <?php endif; ?>
        
    <?php if (count($resourceFiles) > 0): ?>
        <?php foreach($resourceFiles as $file): ?>
            <?php include_partial("arFileUpload/file", array('file' => $file)) ?>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>
<?php endif; ?>