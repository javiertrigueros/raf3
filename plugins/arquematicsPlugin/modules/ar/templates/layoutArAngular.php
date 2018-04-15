<?php use_helper('I18N','a','ar') ?>
<?php $page = aTools::getCurrentNonAdminPage() ?>
<?php $realPage = aTools::getCurrentPage() ?>
<?php $root = aPageTable::retrieveBySlug('/') ?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="es-ES" ng-app="raw">
<head profile="http://gmpg.org/xfn/11">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_http_metas() ?>
    <?php include_metas() ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <?php include_title() ?>
    <?php a_include_stylesheets() ?>

    <?php if (has_slot('og-meta')): ?>
        <?php include_slot('og-meta') ?>
    <?php endif ?>
        <?php $fb_page_id = sfConfig::get('app_a_facebook_page_id') ?>
    <?php if ($fb_page_id): ?>
        <meta property="fb:page_id" content="<?php echo $fb_page_id ?>" />
    <?php endif ?>

    <link rel="shortcut icon" href="/favicon.ico" />
</head>

<body <?php if (has_slot('global-head')): ?> class='<?php include_slot('body_class') ?>'<?php endif ?>>
    
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
    
    <?php a_include_javascripts() ?>
    <?php include_partial('ar/googleAnalytics') ?>
    <?php include_partial('API/globalArquematics') ?>
</body>
</html>