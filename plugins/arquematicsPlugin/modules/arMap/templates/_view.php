<?php $locate = isset($locate) ? $sf_data->getRaw('locate') : false; ?>

<div id="ui-profile-map" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="map-name" class="control-text ui-control-text">
                <?php if (sfConfig::get('app_arquematics_encrypt', false) && $locate): ?>
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <span data-encrypt-text="<?php echo $locate->EncContent->getContent() ?>" class="status-text content-text"></span>
                <?php elseif ($locate): ?>
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <span data-encrypt-text="" class="status-text content-text">
                        <?php echo $locate->getFormatedAddress() ?>
                    </span>
                <?php endif; ?>
            </div>
    
            <div id="map-container" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
                <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php include_partial('arMap/locate', array('locate' => $locate))?>
            </div> 
</div>

<?php include_js_call('arMap/jsMapProfileView'); ?>

