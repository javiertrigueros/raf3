<?php use_helper('I18N', 'Date','a','ar') ?>
<?php
  // Compatible with sf_escaping_strategy: true
  $active = isset($active) ? $sf_data->getRaw('active') : null;
  $firstPass = isset($firstPass) ? $sf_data->getRaw('firstPass') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
	$popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array() ;
	$allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array() ;
?>

<?php ($totalItems > 1) ? $singleItem = false : $singleItem = true; ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_stylesheet("/apostrophePlugin/css/a-engines.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-components.css"); ?>
<?php use_stylesheet("/apostrophePlugin/css/a-forms.css");  ?>


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


<?php /*slot('a-page-header') ?>
  <div class="a-ui a-admin-header">
    <h3 class="a-admin-title"><?php echo link_to(a_('Media Library'), 'aMedia/resume') ?></h3>
  </div>
<?php end_slot() */?>

<div id="content-wrapper">
    <div class="profile-row">
       
        <?php include_component('aMedia', 'browser') ?>

<div class="a-media-library">

	<div class="a-media-toolbar">
		<h3><?php echo __('Annotate ' . aMediaTools::getBestTypeLabel(), null, 'apostrophe') ?></h3>
	</div>

  <?php if ($postMaxSizeExceeded): ?>
  	<h3><?php echo __('File too large. Limit is %POSTMAXSIZE%', array('%POSTMAXSIZE%' => ini_get('post_max_size')), 'apostrophe') ?></h3>
  <?php endif ?>

	<div class="a-media-items">				
		<form method="post" action="<?php echo a_url('aMedia', 'editMultiple') ?>" enctype="multipart/form-data" id="a-media-edit-form-0" class="a-ui a-media-edit-form">		

			<?php if (!$singleItem): ?>
				<ul class="a-ui a-controls top a-align-right">
					<li><?php echo a_anchor_submit_button(a_('Save ' . aMediaTools::getBestTypeLabel()), array('big','a-show-busy','a-media-multiple-submit-button')) ?></li>
					<li><?php echo link_to('<span class="icon"></span>'.a_("Cancel"), "aMedia/resume", array("class"=>"a-btn icon a-cancel alt big a-js-media-edit-multiple-cancel")) ?></li>
				</ul>
			<?php endif ?>
					
			<div class="a-form-row a-hidden">
			<?php echo $form->renderHiddenFields() ?>
	  	</div>
		
		<?php $n = 0 ?>

		<?php for ($i = 0; ($i < aMediaTools::getOption('batch_max')); $i++): ?>
		  <?php if (isset($form["item-$i"])): ?>
		    <?php // What we're passing here is actually a widget schema ?>
		    <?php // (they get nested when embedded forms are present), but ?>
		    <?php // it supports the same methods as a form for rendering purposes ?>

			    <?php // Please do not remove this div, I need it to implement the remove button ?>
			    <div id="a-media-editor-<?php echo $i ?>" class="a-media-editor<?php echo ($n%2 == 0)? ' a-even':' a-odd' ?><?php echo ($i == $totalItems-1)? ' last':'' ?>">
	 			    <?php include_partial('aMedia/edit', 
	 								array(
	 									"item" => false, 
	 			        		"firstPass" => $firstPass, 
	 									"form" => $form["item-$i"], 
	 									"n" => $n, 
	 									'i' => $i,
										'popularTags' => $popularTags,
										'allTags' => $allTags,
	 									'itemFormScripts' => false,
	 									)) ?>
					</div>
				<?php $n++ ?>
		  <?php endif ?>
		<?php endfor ?>
		<ul class="a-ui a-controls bottom a-align-left">
			<li><?php echo link_to('<span class="icon"></span>'.a_("Cancel"), "aMedia/resume", array("class"=>"a-btn icon a-cancel alt big a-js-media-edit-multiple-cancel")) ?></li>
			<li><?php echo a_anchor_submit_button(a_('Save ' . aMediaTools::getBestTypeLabel()), array('big','a-show-busy','a-media-multiple-submit-button')) ?></li>
		</ul>
		<?php include_partial('aMedia/itemFormScripts', array('i'=>$i)) ?>
		</form>
	</div>
</div>

    </div>
</div>

<?php a_js_call('apostrophe.enableMediaEditMultiple()') ?>


<?php slot('body_class',  'a-media a-media-upload theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg') ?>

<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
