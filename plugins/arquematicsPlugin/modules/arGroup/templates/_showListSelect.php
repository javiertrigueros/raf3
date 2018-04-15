<?php $activeListControl = isset($activeListControl) ? $sf_data->getRaw('activeListControl') : ''; ?>
<?php $selectContainer = isset($selectContainer) ? $sf_data->getRaw('selectContainer') : ''; ?>
<?php $hasList = isset($selectContainer) ? $sf_data->getRaw('hasList') : false; ?>

<?php if ($hasList): ?>
<div id="control-list-select-buttons-<?php echo $tab['name']; ?>" class="btn-group control-list-select col-xs-8 col-sm-8 col-md-4 col-lg-4">
           
</div>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-select/bootstrap-select.css"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-select/bootstrap-select.js"); ?>

<?php include_js_call('arGroup/jsListSelect', array(
    'selectContainer' => $selectContainer,
    'activeListControl' => $activeListControl)) ?>

<?php endif; ?>
