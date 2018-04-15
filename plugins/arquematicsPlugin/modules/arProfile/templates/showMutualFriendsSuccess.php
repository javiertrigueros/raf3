<?php use_helper('I18N','a','ar') ?>

<?php $arMutualFriends = isset($arMutualFriends) ? $sf_data->getRaw('arMutualFriends') : array(); ?>
<?php $aRouteUserProfile = isset($aRouteUserProfile)? $sf_data->getRaw('aRouteUserProfile') : null; ?>
<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $countMutualFriends = isset($countMutualFriends)? $sf_data->getRaw('countMutualFriends') : 0; ?> 

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arProfile.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.infinite.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/profile/arquematics.mutualfriends.js"); ?>

<?php include_js_call('arProfile/jsMutualFriends', array('aUserProfile' => $aUserProfile,
                                                         'aUserProfileFilter' => $aRouteUserProfile)); ?>
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
         <?php include_partial('arProfile/showProfileImage', 
                array('arProfileImage' => $aRouteUserProfile->getProfileImage(),
                      'aUser' => $aRouteUserProfile,
                      'can_edit' => false,
                      'form' => false)) ?>

           <?php include_component('arProfile','showProfileBlockCounter',
                                        array('disableMessage' => false,
                                              'disableFriends' => true,
                                              'aUserProfile' => $aUserProfile,
                                              'aRouteUserProfile' => $aRouteUserProfile)); ?>
      </div>
        
    </div>
    <div class="right-col profile-content-right col-xs-12 col-sm-12 col-md-8 col-lg-8">
         <div class="mutual-friends-title row-fluid clearfix">
                 <div id="icon-themes" class="icon32 users-long-icon"></div>
                 <h3 class=" a-admin-title"><?php echo __("%user%'s Subscribers.", array('%user%' => $aRouteUserProfile->getFirstLast()), 'arquematics') ?></h3>
             </div>
             <div class="profile-info-content row-fluid">
                <div id="content-mutual-friends" class="row-fluid clearfix">
                 <?php include_partial('arProfile/listMutualFriendsExt', array(
                    'arMutualFriends' => $arMutualFriends,
                    'aUserProfile' => $aRouteUserProfile)); ?>
                    <div id="mutual-profile-loader" class="loader hide">
                        <img class="loader-img" src="/arquematicsPlugin/images/loaders/general-loader.gif">
                    </div>
                </div>
            </div>

    </div>
  </div>
</div>



<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>    
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>