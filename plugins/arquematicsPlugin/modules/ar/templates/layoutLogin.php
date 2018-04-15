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

	<!-- Pixel Admin's stylesheets -->
        <?php a_include_stylesheets() ?>
	<!-- Pixel Admin's stylesheets -->
	<!--[if lt IE 9]>
		<script src="/arquematicsPlugin/assets/javascripts/ie.min.js"></script>
	<![endif]-->
</head>

<!-- 1. $BODY ======================================================================================
	
	Body

	Classes:
	* 'theme-{THEME NAME}'
	* 'right-to-left'     - Sets text direction to right-to-left
-->
<body <?php if (has_slot('body_class')): ?> class='<?php include_slot('body_class') ?>'<?php endif ?>>

<?php echo $sf_data->getRaw('sf_content') ?>

<?php a_include_javascripts(); ?>
<?php include_partial('a/googleAnalytics') ?>
<?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
<?php include_partial('a/globalJavascripts') ?>
<?php include_partial('API/globalArquematics') ?>
</body>
</html>