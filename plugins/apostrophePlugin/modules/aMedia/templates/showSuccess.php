<?php
  // Compatible with sf_escaping_strategy: true
  $mediaItem = isset($mediaItem) ? $sf_data->getRaw('mediaItem') : null;
  $layout = isset($layout) ? $sf_data->getRaw('layout') : null;
?>
<?php use_helper('I18N', 'Date','a','ar') ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_stylesheet("/apostrophePlugin/css/a-engines.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-components.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-forms.css");  ?>

<?php use_javascript("/apostrophePlugin/js/plugins/jquery.hoverIntent.js"); ?>

<?php use_javascript("/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php slot('global-head-extra')?>
<div class="a-ui a-admin-header global-head-extra">
    <div class="container-global-head-extra-inner">
       <div class="icon32 media-long-icon" id="icon-themes"></div>
        <h3 class="a-admin-title">
            <?php echo __('Edit file "%file%"', array('%file%' => htmlspecialchars($mediaItem->getTitle())), 'apostrophe') ?>
        </h3>
        
        <?php include_partial('arCommentAdmin/flashes') ?>
        <div class="pager-control">
            <?php //include_partial('aPager/simple', array('pager' => $pager, 'pagerUrl' =>  url_for('aGroupAdmin/index'))) ?>
        </div>
    </div>
</div>
<?php end_slot() ?>

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
                    <?php include_slot('global-head-search') ?>
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </ul> <!-- / .navbar-nav -->
            </div> <!-- / .right -->
        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>


<?php //include_partial('arMenuAdmin/globalHeadMenuAdmin', array('pageBack' => arMenuInfo::ADMINMEDIA)); ?>

<?php $type = aMediaTools::getAttribute('type') ?>
<?php $selecting = aMediaTools::isSelecting() ?>

<div id="content-wrapper">
    <div class="profile-row">
        <div class="a-media-library">
            <div class="a-media-items">
                <?php include_partial('aMedia/mediaItem', array('class' => 'a-media-item-show','mediaItem' => $mediaItem, 'layout' => $layout, 'i' => 1, 'selecting' => $selecting, 'autoplay' => false)) ?>
            </div>
        </div>
    </div>
</div>

<?php // Media Sidebar is wrapped slot('a-subnav') ?>
<?php //include_component('aMedia', 'browser') ?>

<?php a_js_call('apostrophe.selectOnFocus(?)', '.a-select-on-focus') ?>

<?php $body_class = 'a-admin a-admin-generator aUserAdmin index a-media a-media-index'?>
<?php $body_class .= 'a-media a-media-show'?>
<?php $body_class .= ($page->admin) ? ' aMediaAdmin':'' ?>
<?php $body_class .= ($selecting) ? ' a-media-selecting a-previewing':'' ?>
<?php //slot('body_class', $body_class) ?>

<?php slot('body_class',  'theme-default main-menu-animated page-media main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg') ?>

<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>