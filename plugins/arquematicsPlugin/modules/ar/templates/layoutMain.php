<?php use_helper('a') ?>
<?php $page = aTools::getCurrentNonAdminPage() ?>
<?php $realPage = aTools::getCurrentPage() ?>
<?php $root = aPageTable::retrieveBySlug('/') ?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php include_http_metas() ?>
	<?php include_metas() ?>
    <?php include_title() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
         
	<!-- Open Sans font from Google CDN -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

    <?php a_include_stylesheets(); ?>
	<?php //a_include_javascripts(); ?>
	<!-- Pixel Admin's stylesheets -->
	<!--[if lt IE 9]>
		<script src="/arquematicsPlugin/assets/javascripts/ie.min.js"></script>
	<![endif]-->
</head>


<!-- 1. $BODY ======================================================================================
	
	Body

	Classes:
	* 'theme-{THEME NAME}'
	* 'right-to-left'      - Sets text direction to right-to-left
	* 'main-menu-right'    - Places the main menu on the right side
	* 'no-main-menu'       - Hides the main menu
	* 'main-navbar-fixed'  - Fixes the main navigation
	* 'main-menu-fixed'    - Fixes the main menu
	* 'main-menu-animated' - Animate main menu
-->
<body <?php if (has_slot('body_class')): ?> class='<?php include_slot('body_class') ?>'<?php endif ?>>

<div id="main-wrapper">


<!-- 2. $MAIN_NAVIGATION ===========================================================================

	Main navigation
-->
	<div id="main-navbar" class="navbar" role="navigation">
	<!-- Main menu toggle -->
            <button type="button" id="main-menu-toggle">
                <i class="navbar-icon fa fa-bars icon"></i>
                <span class="hide-menu-text">
                    <?php echo __('Hide Menu',array(),'arquematics') ?>
                </span>
            </button>	
            <?php include_slot('global-head') ?>
	</div> <!-- / #main-navbar -->
   <!-- /2. $END_MAIN_NAVIGATION -->


    <?php include_slot('global-main-app-menu') ?>
     <!-- / #main-app-menu -->

    <?php if (has_slot('nav-modal-primary')): ?>
            <?php include_slot('nav-modal-primary'); ?>
    <?php endif; ?>

        <?php echo $sf_data->getRaw('sf_content') ?>

        <?php if (has_slot('nav-modal-secundary')): ?>
            <?php include_slot('nav-modal-secundary'); ?>
        <?php endif; ?>

        <?php if (has_slot('main-menu-bg')): ?>
    	<div id="main-menu-bg">
    		<?php include_slot('main-menu-bg'); ?>
    	</div>
	<?php endif; ?>
</div> <!-- / #main-wrapper -->

<?php a_include_javascripts(); ?>

<?php include_partial('a/googleAnalytics'); ?>

<?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
<?php include_partial('a/globalJavascripts'); ?>
<?php include_partial('API/globalArquematics'); ?>
</body>
</html>