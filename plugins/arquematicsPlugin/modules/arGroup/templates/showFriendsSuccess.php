<?php use_helper('I18N','Partial','a','ar') ?>

<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $formSearch = isset($formSearch) ? $sf_data->getRaw('formSearch') : null; ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arGroupTheme.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arGroupCommon.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arFriends.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.livequery.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.infinite.js"); ?>
   
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/group/arquematics.friendscreen.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/group/arquematics.friends.js"); ?>


<div id="content-wrapper">
    <div class="profile-row">
        <div class="content-container-friends" id="content-container">
    <ul class="nav nav-tabs friends-tabs" id="friends-tabs-menu">
        <li class="active"><a href="#content-friends" data-is-friend="true"><?php echo __('Subscribers',array(),'profile'); ?></a></li>
        <li><a href="#content-ignore" data-is-friend="false"><?php echo __('Rejected',array(),'profile'); ?></a></li>
    </ul>
     
    <div class="tab-content">
        <div id="content-friends" data-is_last_page="<?php echo (1 >= $aUserProfile->totalPagesFriends(true))?'true':'false' ?>"  class="tab-pane active container-fluid">
            <?php include_component('arGroup','listFriends', array('page' => 1,'aUserProfile' => $aUserProfile)); ?>
            <div id="friends-loader" class="loader hide">
                  <img class="loader-img" src="/arquematicsPlugin/images/loaders/general-loader.gif">
            </div>
        </div>
        <div id="content-ignore" data-is_last_page="<?php echo (1 >= $aUserProfile->totalPagesFriends(false))?'true':'false' ?>"  class="tab-pane container-fluid" >
            <?php include_component('arGroup','listIgnore', array('page' => 1,'aUserProfile' => $aUserProfile)); ?>
            <div id="ignore-loader" class="loader hide">
                  <img class="loader-img" src="/arquematicsPlugin/images/loaders/general-loader.gif">
            </div>
        </div>
    </div>
</div>

<form id="add_friend" action="<?php echo url_for('@add_friend') ?>" method="POST" enctype="multipart/form-data">
    <?php echo $form['profile_list_id']->render() ?>
    <?php echo $form->renderHiddenFields() ?>
</form>

    </div>
</div>

<?php slot('global-head-search')?>
<li>
    <form id="form_user_search" class="navbar-form pull-left" action="<?php echo url_for('@search_friends_byname') ?>"  method="POST">
        <div class="input-group"> 
           <?php echo $formSearch['search']->render(array('placeholder' => __('Search', null, 'apostrophe'),'class' => 'form-control')) ?> 
           <?php echo $formSearch->renderHiddenFields(); ?>
           <span class="form-search navbar-icon cmd-search"> 
              <i id="cmd-search" class="fa fa-search"></i>
           </span> 
        </div>
   </form>
</li>
<?php end_slot() ?>

<?php slot('global-head')?>
<div id="header">

<div class="navbar-inner">
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

<?php include_slot('global-head-extra') ?>
</div>
<?php end_slot() ?>


<?php include_js_call('arGroup/jsShowFriends') ?>

<?php slot('body_class','body-group theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>