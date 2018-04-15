<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arProfile.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php slot('global-head')?>
<div id="navbar-content" class="navbar-inner">
<!-- Main navbar header -->
    <div class="navbar-header">
        <!-- Logo -->

        <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>
        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <div class="right clearfix">
                <ul class="nav navbar-nav pull-right right-navbar-nav">
                    <?php include_slot('global-head-search') ?>
               
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </ul> <!-- / .navbar-nav -->
            </div> <!-- / .right -->
        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>


<div id="content-wrapper">
    <div class="profile-row">
        <div class="left-col">
            <div class="rBlock">
                <?php  include_partial('arProfile/showProfileImage', 
                                array('arProfileImage' => $aRouteUserProfile->getProfileImage(),
                                    'aUser' => $aRouteUserProfile,
                                    'can_edit' => $canEdit,
                                    'form' => $form))  ?>

                <?php include_component('arProfile','showProfileBlockCounter',
                                        array('disableMessage' => false,
                                              'disableFriends' => false,
                                              'aUserProfile' => $aUserProfile,
                                              'aRouteUserProfile' => $aRouteUserProfile)); ?>
            </div>
        </div>
        <div class="right-col">
            <div class="profile-info-content row-fluid">
                
                <div id="ui-profile-name" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 id="profile-name" class="profile-head control-text ui-control-text row-fluid">
                        <span class="profile-head-text ui-control-edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <span class="status-text user-first-last-text"><?php echo $aRouteUserProfile->getFirstLast() ?></span>
                        </span>
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
                
                <div id="ui-profile-pass" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div id="profile-pass" class="control-text ui-control-text ui-control-edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                        <span class="status-text user-description"><?php echo __('Change current password', null, 'arquematics'); ?></span>
                    </div>
        
                    <div id="profile-pass-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
                        <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <form id="form-profile-pass" action="<?php echo url_for('@user_profile_update_pass?username='.$aRouteUserProfile->getUsername()) ?>" method="post" class="controls-row ui-control-form">
                            <?php echo $formPassForm->renderHiddenFields() ?>
                            
                            <div class="input-group has-success simple">
                                <?php echo $formPassForm['password']->render(array('placeholder' => __('New Password', null, 'arquematics'), 'class' => 'ui-control-pass ui-control-text-input form-control')) ?>
                                <p class="help-block"></p>
                            </div>
                            
                            <div class="input-group has-success simple">
                                <?php echo $formPassForm['password_again']->render(array('placeholder' => __('Repeat Password', null, 'arquematics'), 'class' => 'ui-control-pass-again ui-control-text-input form-control')) ?>
                                <p class="help-block"></p>
                            </div>
                            
                            <p class="controls-buttom">
                                <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Update password',null,'arquematics'); ?></a>
                                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
                            </p>
                        </form>
                    </div>
                </div>
                
                <?php if (sfConfig::get('app_facebook_enable')): ?>
                <div id="ui-profile-facebook" class="ui-control col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <div class="signin-configure">
                        <!-- Facebook -->
                        <?php echo link_to(__('Connect to %net%', array('%net%' => '<span>Facebook</span>'), 'arquematics'), '@facebook_connect', array('class' => 'signin-btn')); ?>
                    </div>  
                </div>
                <?php endif; ?>
                

            </div>
        </div>
    </div>
</div>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldeditor.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldpass.js"); ?>

<?php include_js_call('arProfile/jsConfigure'); ?>

<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>