<?php use_helper('I18N','a','ar') ?>

<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $aRouteUserProfile = isset($aRouteUserProfile) ? $sf_data->getRaw('aRouteUserProfile') : false; ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $canEdit = isset($canEdit) ? $sf_data->getRaw('canEdit') : false; ?> 


<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arProfile.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php include_partial('arWall/encrypt', array(
    'sections' => array('body' => 'body'),
    'aUserProfile' => $aUserProfile))?>


<?php slot('global-head-search','')?>

<?php /* slot('global-main-app-menu')?>
<div id="main-menu" role="navigation">
    <div id="main-menu-inner">
      <div class="menu-content top" id="menu-content-demo">

      </div>
    </div> <!-- / #main-menu-inner -->
</div> 
<?php end_slot(); */?> 
    
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
         <?php include_component('arProfile', 'showProfile', 
                array('aRouteUserProfile' => $aRouteUserProfile,
                      'aUserProfile' => $aUserProfile,
                      'downloadPrivateKey' => $canEdit,
                      'canEdit' => $canEdit)) ?>
    </div>
  </div>
</div>

<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>