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
    
<?php include_http_metas() ?>
<?php include_metas() ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />    
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />

<?php include_title() ?>
<?php a_include_stylesheets() ?>


<!--[if lt IE 7]>                 
	<link rel="stylesheet" type="text/css" href="/css/ie6style.css" />
	<script type="text/javascript" src="/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo, span.overlay, a.zoom-icon, a.more-icon, #menu, #menu-right, #menu-content, ul#top-menu ul, #menu-bar, .footer-widget ul li, span.post-overlay, #content-area, .avatar-overlay, .comment-arrow, .testimonials-item-bottom, #quote, #bottom-shadow, #quote .container');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="/css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="/css/ie8style.css" />
<![endif]-->
<!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="/apostrophePlugin/css/a-ie.css" />
    <link rel="stylesheet" type="text/css" href="/css/ie6.css" />
    <script type="text/javascript">
      $(document).ready(function() {
        apostrophe.IE6({'authenticated':<?php echo ($sf_user->isAuthenticated())? 'true':'false' ?>, 'message':<?php echo json_encode(__('You are using IE6! That is just awful! Apostrophe does not support editing using Internet Explorer 6. Why don\'t you try upgrading? <a href="http://www.getfirefox.com">Firefox</a> <a href="http://www.google.com/chrome">Chrome</a>   <a href="http://www.apple.com/safari/download/">Safari</a> <a href="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx">IE8</a>', null, 'apostrophe')) ?>});
      });
    </script>
  <![endif]-->



</head>

<?php // a-body-class allows you to set a class for the body element from a template ?>
<?php // body_class is preserved here for backwards compatibility ?>

<?php $a_bodyclass = '' ?>
<?php $a_bodyclass .= (has_slot('a-body-class')) ? get_slot('a-body-class') : '' ?>
<?php $a_bodyclass .= (has_slot('body_class')) ? get_slot('body_class') : '' ?>
<?php $a_bodyclass .= ($page && $page->archived) ? ' a-page-unpublished' : '' ?>
<?php $a_bodyclass .= ($page && $page->view_is_secure) ? ' a-page-secure' : '' ?>
<?php $a_bodyclass .= ($page) ? ' a-page-id-'.$page->id.' a-page-depth-'.$page->level : '' ?>
<?php $a_bodyclass .= (sfConfig::get('app_a_js_debug', false)) ? ' js-debug':'' ?>
<?php $a_bodyclass .= ($realPage && !is_null($realPage['engine'])) ? ' a-engine':'' ?>
<?php $a_bodyclass .= ($sf_user->isAuthenticated()) ? ' logged-in':' logged-out' ?>

<body class="<?php echo $a_bodyclass ?>">
	
        <?php include_partial('ar/globalTools',array('root' => $root)) ?>
    
	<div id="top-header">
		<div id="top-shadow"></div>
		<div id="bottom-shadow"></div>
		<div class="container clearfix">                    
                        <?php if (has_slot('a-tabs')): ?>
                            <?php include_slot('a-tabs') ?>
                        <?php else: ?>
                            <?php include_component('arNavigation', 'tabs', array('depth' => 999, 'class' => 'nav top-menu','root' => $root, 'active' => $page, 'name' => 'main', 'draggable' => true, 'dragIcon' => false)) # Top Level Navigation ?>
                        <?php endif ?>
                        
                        <?php if (has_slot('sidebar_search')): ?>
                            <?php include_slot('sidebar_search') ?>
                        <?php endif ?>
                    
                        <?php include_component('arAuth', 'showHomeLogin', array('page' => $page,'root' => $root )); ?>
                  
		</div> <!-- end .container -->
	</div> <!-- end #top-header -->

	<div id="content-area">
		<div id="content-top-light">
			<div id="top-stitch"></div>
			<div class="container">
				<div id="logo-area">
                                    
                                     <?php if (has_slot('a-logo')): ?>
                                        <?php include_slot('a-logo') ?>
                                    <?php else: ?>
                                         <?php a_slot('logo', 'arLogo', array(
                                        'class' => 'none',
                                        'singleton' => true,
                                        'defaultImage' => '/arquematicsPlugin/images/logo_web.png',
                                        'history' => false,
                                        'link' => url_for('@homepage'),
                                        'global' => true,
                                        'width' => 140,
                                        'height' => 140,
                                        'resizeType' => 'c',
                                        'constraints' => array('minimum-width' => 100))) ?>
                                    <?php endif ?>

				</div> <!-- end #logo-area -->
                                
                                 <?php echo $sf_data->getRaw('sf_content') ?>


			</div> <!-- end .container -->
		</div> <!-- end #content-top-light -->
	<div id="bottom-stitch"></div>
        
        
	</div> <!-- end #content-area -->

	<div id="footer">
		<div id="footer-top-shadow" class="clearfix">
			<div class="container">
                            
                            
                            <div class="clearfix" id="footer-widgets">
                                <div class="footer-widget widget_recent_entries" >
                                    
                                    <?php if ($page && is_object($page)): ?>
                                     <?php  a_area('foot-left', array(
                                        'allowed_types' => array('arMenuCMS', 'arLast', 'aRawHTML'),
                                        'slug' => $page->slug,
                                        'history' => false,
                                        'areaLabel' => __('Add Content',array(),'blog'),
                                        'type_options' => array(
                                            'arLast' => array(
                                                'class' => 'recent-last'
                                            ),
                                            'arMenuCMS' => array('class' => 'widgettitle'),
                                            'aText' => array(
                                                'multiline' => false
                                            ),
                                            'aRawHTML' => array(),
                                        ))); ?>
                                    <?php endif; ?>
                                   
                                    
                                    
                                </div> <!-- end .footer-widget -->
                                
                            </div>
                            
				
                            <?php include_partial('ar/language') ?>
			</div> <!-- end .container -->
		</div> <!-- end #footer-top-shadow -->
		<div id="footer-bottom-shadow"></div>
		<div id="footer-bottom">
			
		</div> <!-- end #footer-bottom -->
	</div> <!-- end #footer -->


<?php //include_partial('ar/googleAnalytics') ?>

<?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
<?php //include_partial('ar/globalJavascripts') ?>
<?php //include_partial('API/globalArquematics') ?>

<?php if (has_slot('extra-modals')): ?>
        <?php include_slot('extra-modals') ?>
<?php endif; ?>
        
     
<?php a_include_javascripts(); ?>

<?php include_partial('a/googleAnalytics'); ?>

<?php // Invokes apostrophe.smartCSS, your project level JS hook and a_include_js_calls ?>
<?php include_partial('a/globalJavascripts'); ?>
<?php include_partial('API/globalArquematics'); ?>

</body>
</html>