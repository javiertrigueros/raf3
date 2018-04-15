<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $locate = isset($locate) ? $sf_data->getRaw('locate') : false; ?>

<div id="ui-profile-map" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="map-name" class="control-text ui-control-text ui-control-edit">
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php if (sfConfig::get('app_arquematics_encrypt', false) && $locate): ?>
                    <span data-encrypt-text="<?php echo $locate->EncContent->getContent() ?>" class="status-text content-text"></span>
                <?php elseif ($locate): ?>
                    <span data-encrypt-text="" class="status-text content-text">
                        <?php echo $locate->getFormatedAddress() ?>
                    </span>
                <?php else: ?>
                    <span data-encrypt-text="" class="status-text content-text">
                        <?php echo __('Add location',null,'profile') ?>
                    </span>
                <?php endif; ?>
                
            </div>
            <div id="map-description-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
                <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
                <form id="form-geolocation" action="<?php echo url_for('@user_profile_send_map?username='.$aUserProfile->getUsername()) ?>" method="post" class="controls-row ui-control-form">
                    <?php echo $form->renderHiddenFields() ?>
                    
                     <?php if (sfConfig::get('app_arquematics_encrypt', false) && $locate): ?>
                        <?php echo $form['formated_address']->render(array('autocomplete'=>'off',
                                                                       'data-encrypt-text' => $locate->EncContent->getContent(),
                                                                       'class' => 'ui-control-text-input form-control control-crypt')) ?>
                     <?php else: ?>
                        <?php echo $form['formated_address']->render(array('autocomplete'=>'off',
                                                                       'data-encrypt-text' => '',
                                                                       'class' => 'ui-control-text-input form-control control-crypt')) ?>
                    <?php endif; ?>
                    
                    <?php include_partial('arMap/locate', array('locate' => $locate))?>
                    
                    <p class="controls-buttom">
                        <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>
                        <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
                    </p>
                </form>
            </div> 
</div>

<?php include_js_call('arMap/jsMapProfileEdit'); ?>