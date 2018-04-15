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

	<!--[if lt IE 9]>
  	<link rel="stylesheet" type="text/css" href="/apostrophePlugin/css/a-ie.css" />	
  	<link rel="stylesheet" type="text/css" href="/css/ie6.css" />		
		<script type="text/javascript">
			$(document).ready(function() {
				apostrophe.IE6({'authenticated':<?php echo ($sf_user->isAuthenticated())? 'true':'false' ?>, 'message':<?php echo json_encode(__('You are using IE6! That is just awful! Apostrophe does not support editing using Internet Explorer 6. Why don\'t you try upgrading? <a href="http://www.getfirefox.com">Firefox</a> <a href="http://www.google.com/chrome">Chrome</a> 	<a href="http://www.apple.com/safari/download/">Safari</a> <a href="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx">IE8</a>', null, 'apostrophe')) ?>});
			});
		</script>
	<![endif]-->
</head>

<body <?php if (has_slot('body_class')): ?> class='<?php include_slot('body_class') ?>'<?php endif ?>>

	<?php echo $sf_data->getRaw('sf_content') ?>
    
    <?php if (has_slot('nav-modal-secundary')): ?>
        <?php include_slot('nav-modal-secundary'); ?>
    <?php endif; ?>
    
    <?php if (has_slot('a-footer')): ?>
        <?php include_slot('a-footer') ?>
    <?php endif ?>
    <?php a_include_javascripts(); ?>
    <?php include_partial('a/googleAnalytics') ?>

    <?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
    <?php include_partial('a/globalJavascripts') ?>
    <?php include_partial('API/globalArquematics') ?>
</body>
</html>