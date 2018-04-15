<?php use_helper('I18N') ?>
<?php $enabledModules = sfConfig::get('sf_enabled_modules'); ?>

<?php if (in_array('arFileUpload', $enabledModules)): ?>
    <?php include_component('arFileUpload','showFileControl'); ?>
<?php endif; ?>

<?php if (in_array('arDrop', $enabledModules)): ?>
    <?php include_component('arDrop','showDropControl'); ?>
<?php endif; ?>

<?php if (in_array('arLink', $enabledModules)): ?>
    <?php include_component('arLink','showLinkControl'); ?>
<?php endif; ?>

<?php if (in_array('arDocEditor', $enabledModules)): ?>
    <?php include_component('arDocEditor','showControl'); ?>
<?php endif; ?>

<?php if (in_array('arLaverna', $enabledModules)): ?>
    <?php include_component('arLaverna','showControl'); ?>
<?php endif; ?>

<?php if (in_array('arVectorialEditor', $enabledModules)): ?>
    <?php include_component('arVectorialEditor','showControl'); ?>
<?php endif; ?>

<?php if (in_array('arMap', $enabledModules)): ?>
    <?php include_component('arMap','showMapControl'); ?> 
<?php endif; ?>
<?php //modal de documentos (arDocEditor || arVectorialEditor) ?>
<?php if ((in_array('arDocEditor', $enabledModules))
        || (in_array('arLaverna', $enabledModules))
        || (in_array('arVectorialEditor', $enabledModules))): ?>
    <?php include_js_call('arWall/jsDocuments'); ?>
<?php endif; ?>