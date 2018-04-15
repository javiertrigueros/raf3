<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?>

<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>

<?php $countMessages = isset($countMessages) ? $sf_data->getRaw('countMessages') : false; ?>

<?php $hasProfileFilterAccepted =  isset($hasProfileFilterAccepted) ? $sf_data->getRaw('hasProfileFilterAccepted') : false; ?>

<?php $currentPage = isset($currentPage) ? $sf_data->getRaw('currentPage') : 1; ?>

<?php use_helper('I18N','Partial', 'a','ar') ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arWall.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/js/components/typeahead.js/typeaheadjs.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery-timeago/jquery.timeago.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery-timeago/locale/jquery.timeago.$culture.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>


<?php use_javascript("/arquematicsPlugin/assets/javascripts/bootstrap.min.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/typeahead.js/typeahead.bundle.js"); ?>


<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
<?php include_partial('arWall/encrypt', array(
    'sections' => array('updateboxarea' => '#updateboxarea',
                        'mainNodeId' => '#content',
                        'secundaryNode' => '#tag-control-list'),
    'aUserProfile' => $aUserProfile))?>

<div id="content-wrapper">
<!-- Profile -->
    <div class="profile-row">
        <div class="left-col">
            <?php include_component('arProfile','showProfileWall', array( 'aUserProfileFilter' => $aUserProfileFilter, 'aUserProfile' => $aUserProfile)); ?>
            <?php include_component('arTag','showTagControl'); ?>
	</div>
        
	<div class="right-col">

				<hr class="profile-content-hr no-grid-gutter-h">
				
				<div class="profile-content">
                                        <?php include_component('arWall','showTabsTools'); ?>
					
					<div id="tab-container" class="tab-content tab-content-bordered panel-padding">
						<div class="widget-article-messages tab-pane panel no-padding no-border fade in active" id="profile-tabs-board">
                                                    <?php include_component('arWall','showTabsControls', array('aUserProfileFilter' => $aUserProfileFilter,'aUserProfile' => $aUserProfile)); ?>
                                                    <div class="wall-loader loader hide">
                                                       <img class="loader-img" src="/arquematicsPlugin/images/loaders/general-loader.gif">
                                                    </div> 
                                                    <div id="content">
                                                        
                                                        <?php include_partial('arWall/listMessages', 
                                                            array('pager' => $pager,
                                                                'currentPage' => $currentPage,
                                                                'initPage' => true,
                                                                'authUser' => $authUser,
                                                                'hasProfileFilterAccepted' => $hasProfileFilterAccepted,
                                                                'aUserProfileFilter' => $aUserProfileFilter,
                                                                'has_messages' => $has_messages,
                                                                'countMessages' => $countMessages)); ?>
                                                    
                                                           
                                                   </div>
                                                   <div class="wall-loader loader hide">
                                                       <img class="loader-img" src="/arquematicsPlugin/images/loaders/general-loader.gif">
                                                   </div> 
						</div> <!-- / .tab-pane -->
					</div> <!-- / .tab-content -->
				</div>
	</div>
    </div>
</div> <!-- / #content-wrapper -->

<?php slot('global-head-search'); ?>
     <?php include_component('arGroup','showButtomRequest'); ?> 
     <?php include_component('arWall','showSearch', array('aUserProfileFilter' => $aUserProfileFilter)); ?> 
<?php end_slot(); ?>

<?php slot('global-main-app-menu')?>
    <?php include_component('arMenuAdmin','showAppsMenu'); ?> 
<?php end_slot(); ?> 
    
<?php slot('global-head')?>
<div id="navbar-content" class="navbar-inner">
<!-- Main navbar header -->
    <div class="navbar-header">
        <!-- Logo -->

        <a href="/" class="navbar-brand">
            <div>
                <img alt="<?php echo sfConfig::get('app_a_title_simple') ?>" src="/arquematicsPlugin/assets/images/pixel-admin/main-navbar-logo.png">
            </div>
            <?php echo sfConfig::get('app_a_title_simple') ?>
        </a>
        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo url_for('@wall?pag=1'); ?>"><?php echo __('Home', null, 'arquematics') ?></a>
                </li>
                <?php include_component('arMenuAdmin','showExplorerMenu'); ?>
            </ul> <!-- / .navbar-nav -->

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

<?php include_component("arWall","showCommentFormTemplate"); ?>

<?php if (has_slot('user-gallery')): ?>
    <?php include_slot('user-gallery') ?>
<?php endif ?> 


<?php include_js_call('arWall/jsMain'); ?>
<?php //adorno menu ?>
<?php slot('main-menu-bg',''); ?>

<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg mmc'); ?>