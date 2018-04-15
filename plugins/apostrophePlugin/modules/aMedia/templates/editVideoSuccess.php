<?php use_helper('I18N', 'Date','a','ar') ?>
<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $item = isset($item) ? $sf_data->getRaw('item') : null;
  $serviceError = isset($serviceError) ? $sf_data->getRaw('serviceError') : null;
	$i = 0;
	$submitSelector = $item ? ('#' . $item->getSlug() . '-submit') : '.a-media-multiple-submit-button';	
?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_stylesheet("/apostrophePlugin/css/a-engines.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-components.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-forms.css");  ?>

<?php use_javascript("/apostrophePlugin/js/plugins/jquery.hoverIntent.js"); ?>

<?php use_javascript("/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

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
        
        <?php //include_slot('global-head-extra') ?>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <ul class="nav navbar-nav a-ui a-controls a-admin-controls ar-button-new">
                <li>
                     <?php echo link_to('<span class="icon32 media-long-icon" id="icon-themes"></span> <span class="admin-text" >'.__('Media Library', null, 'apostrophe').'</span>', 'aMedia/index', array('class' => '')) ?>
                </li>      
            </ul>
            
            <div class="right clearfix">
                <ul class="nav navbar-nav pull-right right-navbar-nav">
                    <?php //include_slot('global-head-search') ?>
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </ul> <!-- / .navbar-nav -->
            </div> <!-- / .right -->
        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>


<div id="content-wrapper">
    <div class="profile-row">
        
        <div class="a-media-library">
            <?php include_component('aMedia', 'browser') ?>

            <div class="a-media-toolbar">
            <h3>
  		<?php if ($item): ?> 
  			<?php echo __('Editing Video: %title%', array('%title%' => $item->getTitle()), 'apostrophe') ?>
                <?php else: ?> 
  			<?php echo __('Add Video', null, 'apostrophe') ?> 
  		<?php endif ?>
            </h3>
            </div>

            <div class="a-media-items a-media-edit-video">				
                <?php include_partial('aMedia/edit', array('item' => $item, 'form' => $form, 'popularTags' => $popularTags, 'allTags' => $allTags, 'formAction' => a_url('aMedia', 'editVideo'), 'editVideoSuccess' => true)) ?>		
            </div>
        </div>
        
    </div>
</div>

<?php a_js_call('apostrophe.mediaEnableUploadMultiple()') ?>

<?php slot('body_class',  'a-media a-media-upload video theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg') ?>

<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
