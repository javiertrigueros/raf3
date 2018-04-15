<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 6]>
<html xmlns="http://www.w3.org/1999/xhtml" id="ie6" lang="es-ES">
<![endif]-->
<!--[if IE 7]>
<html xmlns="http://www.w3.org/1999/xhtml" id="ie7" lang="es-ES">
<![endif]-->
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" id="ie8" lang="es-ES">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="es-ES">
<!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
<?php use_helper('a') ?>
<?php $page = aTools::getCurrentNonAdminPage() ?>
<?php $realPage = aTools::getCurrentPage() ?>
<?php $root = aPageTable::retrieveBySlug('/') ?>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
    
	<meta charset="UTF-8">
	<?php include_http_metas() ?>
	<?php include_metas() ?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
        <link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">

	<?php include_title() ?>
        <?php a_include_stylesheets() ?>
	<?php a_include_javascripts() ?>
        

	<?php if (has_slot('og-meta')): ?>
		<?php include_slot('og-meta') ?>
	<?php endif ?>
        <?php $fb_page_id = sfConfig::get('app_a_facebook_page_id') ?>
	<?php if ($fb_page_id): ?>
		<meta property="fb:page_id" content="<?php echo $fb_page_id ?>" />
	<?php endif ?>

	<link rel="shortcut icon" href="/favicon.ico" />

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

<body data-spy="scroll" data-target="#raw-nav" <?php if (has_slot('global-head')): ?> class='<?php include_slot('body_class') ?>'<?php endif ?>>
    
    <?php if (has_slot('global-head')): ?>
        <?php include_slot('global-head') ?>
    <?php else: ?>
        <?php include_partial('arWall/globalTools') ?>
    <?php endif ?>
    
    <?php if (has_slot('nav-modal-primary')): ?>
        <?php include_slot('nav-modal-primary'); ?>
    <?php endif; ?>
    
    <?php if (has_slot('a-page-header')): ?>
      <?php include_slot('a-page-header') ?>
    <?php endif ?>
    
    <?php echo $sf_data->getRaw('sf_content') ?>
    
    <?php if (has_slot('nav-modal-secundary')): ?>
        <?php include_slot('nav-modal-secundary'); ?>
    <?php endif; ?>
    
    <?php if (has_slot('a-footer')): ?>
        <?php include_slot('a-footer') ?>
    <?php endif ?>
    
    <?php include_partial('a/googleAnalytics') ?>

    <?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
    <?php include_partial('a/globalJavascripts') ?>
    <?php include_partial('API/globalArquematics') ?>
</body>
</html>
