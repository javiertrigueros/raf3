<?php $aRouteUserProfile = isset($aRouteUserProfile)? $sf_data->getRaw('aRouteUserProfile') : null; ?>
<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : null; ?> 
<?php $formFirstLast = isset($formFirstLast)? $sf_data->getRaw('formFirstLast') : null; ?> 
<?php $formDescription = isset($formDescription)? $sf_data->getRaw('formDescription') : null; ?> 
<?php $downloadPrivateKey = isset($downloadPrivateKey)? $sf_data->getRaw('downloadPrivateKey') : false; ?> 
<?php $canEdit = isset($canEdit)? $sf_data->getRaw('canEdit') : false; ?> 

<div class="profile-info-content row-fluid">
    
    <?php if ($canEdit): ?>
        <div id="ui-profile-name" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3 id="profile-name" class="profile-head control-text ui-control-text row-fluid">
                <span class="profile-head-text ui-control-edit">
                    <span class="glyphicon glyphicon-pencil"></span>
                    <span class="status-text user-first-last-text"><?php echo $aRouteUserProfile->getFirstLast() ?></span>
                </span>
                <?php if (sfConfig::get('app_arquematics_encrypt') && $downloadPrivateKey): ?>
                  <button id="cmd-private-key" class="button-key btn btn-success " type="button">
                    <?php echo __('Private Key', array(),'profile'); ?>
                  </button>
                <?php include_js_call('arProfile/jsProfileKey', array('aUserProfile' => $aRouteUserProfile)); ?>
                <?php endif; ?> 
            </h3>
        
            <div id="container-title-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
                <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
                <form id="form-title" action="<?php echo url_for('@user_profile_update_name?username='.$aRouteUserProfile->getUsername()) ?>" method="post" class="controls-row ui-control-form">
                    <?php echo $formFirstLast->renderHiddenFields() ?>
                    <?php echo $formFirstLast['first_last']->render(array('class' => 'ui-control-text-input form-control')) ?>
                    <p class="controls-buttom">
                        <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>
                        <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
                    </p>
                </form>
            </div>
        </div>
    
        
        <div id="ui-profile-description" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="description-name" class="control-text ui-control-text ui-control-edit">
                <span class="glyphicon glyphicon-pencil"></span>
                <span class="status-text user-description"><?php echo $aRouteUserProfile->getDescription() ?></span>
            </div>
        
            <div id="container-description-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
                <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
                <form id="form-description" action="<?php echo url_for('@user_profile_update_description?username='.$aRouteUserProfile->getUsername()) ?>" method="post" class="controls-row ui-control-form">
                    <?php echo $formDescription->renderHiddenFields() ?>
                    <?php echo $formDescription['description']->render(array('class' => 'ui-control-text-input form-control')) ?>
                    <p class="controls-buttom">
                        <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>
                        <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
                    </p>
                </form>
            </div>
        </div>
    
        <?php include_component('arMap', 'viewEdit', array(
            'aUserProfile' => $aUserProfile,
            'aRouteUserProfile' => $aRouteUserProfile)); ?>
    
    <?php else: ?>
        <div id="ui-profile-name" class="ui-control">
            <h3 id="profile-name" class="profile-head control-text ui-control-text">
                <span class="status-text"><?php echo $aRouteUserProfile->getFirstLast() ?></span>  
            </h3>
        </div>
    
         <div id="ui-profile-description" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="description-name" class="control-text ui-control-text">
                <span class="status-text"><?php echo $aRouteUserProfile->getDescription() ?></span>
            </div>
        </div>

        <?php include_component('arMap', 'view', array(
            'aUserProfile' => $aUserProfile,
            'aRouteUserProfile' => $aRouteUserProfile)); ?>
    
    <?php endif; ?>

</div>    
<?php if ($canEdit): ?>
    
    <?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldeditor.js"); ?>
    
    <?php include_js_call('arProfile/jsProfile'); ?>
<?php endif; ?>