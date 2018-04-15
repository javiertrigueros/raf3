<?php use_helper('I18N', 'Date','a','ar') ?>
<?php
  // Compatible with sf_escaping_strategy: true
  $label = isset($label) ? $sf_data->getRaw('label') : null;
  $limitSizes = isset($limitSizes) ? $sf_data->getRaw('limitSizes') : null;
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  $pagerUrl = isset($pagerUrl) ? $sf_data->getRaw('pagerUrl') : null;
  $results = isset($results) ? $sf_data->getRaw('results') : null;
  $current = isset($current) ? $sf_data->getRaw('current') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
?>

<?php $type = aMediaTools::getAttribute('type') ?>
<?php $selecting = aMediaTools::isSelecting() ?>
<?php $multipleStyle = (($type === 'image') || (aMediaTools::isMultiple())) ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_stylesheet("/apostrophePlugin/css/a-engines.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-components.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-forms.css");  ?>

<?php use_javascript("/apostrophePlugin/js/plugins/jquery.hoverIntent.js"); ?>

<?php use_javascript("/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php //use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/components/bootstrap-switch/bootstrap-switch.js"); ?>

<?php slot('global-head-search')?>
 <?php include_component('aMedia', 'showSearch') ?>
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
                <?php include_partial('aMedia/mediaHeader', array('uploadAllowed' => $uploadAllowed, 'embedAllowed' => $embedAllowed)) ?>        
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

<div id="content-wrapper">
    <div class="profile-row">
        <?php // Media Sidebar is wrapped slot('a-subnav') ?>
        <?php include_component('aMedia', 'browser') ?>


        <div class="im_page_wrap ng-scope">
    
            <?php include_partial('aMedia/addForm', array('uploadAllowed' => $uploadAllowed, 'embedAllowed' => $embedAllowed)) ?>
            <?php if (aMediaTools::isSelecting() || aMediaTools::userHasUploadPrivilege()): ?>
			<?php if (aMediaTools::isSelecting()): ?>
				<div class="a-media-selection">
			    <?php if ($multipleStyle): ?>
			      <?php include_component('aMedia', 'selectMultiple', array('limitSizes' => $limitSizes, 'label' => (isset($label)?$label:null))) ?>
			    <?php else: ?>
			      <?php include_component('aMedia', 'selectSingle', array('limitSizes' => $limitSizes, 'label' => (isset($label)?$label:null))) ?>
			    <?php endif ?>
				</div>
			<?php endif ?>
            <?php endif ?>


                <div class="left-col media-lib">
                    <?php include_slot('a-subnav') ?>
                </div>
      
                <div class="right-col">
      
                    <div class="ng-scope">
                        <div class="im_history_col">
            
                        <?php if ($pager->count()): ?>
                            <div class="a-media-library-controls a-ui top">
                                <?php include_partial('aMedia/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl, 'max_per_page' => $max_per_page, 'enabled_layouts' => $enabled_layouts, 'layout' => $layout)) ?>
                            </div>
                        <?php endif ?>

                        <?php // This should never have been disabled for cases where there are zero images matching. ?>
                        <?php // That is exactly when you need it most to understand why you don't see nuttin'! ?>
                        <?php // Overrides to the contrary must be at project level only. -Tom ?>
                        <?php if ($limitSizes): ?>
                            <div class="a-media-selection-contraints clearfix">
                                <?php include_partial('aMedia/describeConstraints', array('limitSizes' => $limitSizes)) ?>
                            </div>
                        <?php endif ?>

                        <div class="a-media-items clearfix <?php echo $layout['name'] ?>">
                        <?php for ($n = 0; ($n < count($results)); $n += $layout['columns']): ?>
                        <div class="a-media-row clearfix">
                            <?php for ($i = $n; ($i < min(count($results), $n + $layout['columns'])); $i++): ?>
                                <?php include_partial('aMedia/mediaItem', array('mediaItem' => $results[$i], 'layout' => $layout, 'i' => $i, 'selecting' => $selecting, 'autoplay' => true)) ?>
                            <?php endfor ?>
                        </div>
                        <?php endfor ?>
                        </div>

                        <?php if ((!$pager->count()) && (aMediaTools::userHasUploadPrivilege())): ?>
                            <?php include_partial('aMedia/noMediaItemsArea') ?>
                        <?php endif ?>

                        <div class="a-media-footer clearfix">
                            <div class="a-media-library-controls a-ui bottom">
                                <?php include_partial('aMedia/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl, 'max_per_page' => $max_per_page, 'enabled_layouts' => $enabled_layouts, 'layout' => $layout)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        
    </div>
</div>



<?php a_js_call('apostrophe.selectOnFocus(?)', '.a-select-on-focus') ?>

<?php if ($layout['name'] != "four-up"): ?>
	<?php a_js_call('apostrophe.mediaEmbeddableToggle(?)', array('selector' => '.a-media-item.a-embedded-item')) ?>
<?php endif ?>

<?php if ($layout['name'] == "four-up" && !$selecting): ?>
<?php a_js_call('apostrophe.mediaFourUpLayoutEnhancements(?)', array('selector' => '.four-up .a-media-item.a-type-image')) ?>
<?php endif ?>


<?php $body_class = 'a-admin a-admin-generator aUserAdmin index a-media a-media-index'?>
<?php $body_class .= ($page->admin) ? ' aMediaAdmin':'' ?>
<?php $body_class .= ($selecting) ? ' a-media-selecting':'' ?>
<?php $body_class .= ' '.$layout['name'] ?>

<?php slot('body_class',   'theme-default main-menu-animated page-media main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg') ?>

<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>