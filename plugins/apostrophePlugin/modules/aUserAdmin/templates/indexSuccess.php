<?php use_helper('I18N','Date', 'Partial', 'a','ar') ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php slot('global-head-search')?>
    <li class="ar-filter-search nav-icon-btn nav-icon-btn-success">
        <a href="#">
            <i class="nav-icon fa fa-filter"></i>
        </a>
    </li>
    <li>
        <div class="ar-head-search">
            <form action="<?php echo url_for('a_user_admin_collection', array('action' => 'filter')) ?>" method="post" id="ar-simple-search">
                <?php echo $filters['username']->render(array('id' => 'sf_guard_user_filters_username2', 'autocomplete' => 'off', 'class' => 'form-control im_dialogs_search_field ng-pristine ng-valid', 'placeholder' => __('Search',null,'apostrophe'))) ?>
                <a data-url="<?php echo url_for('a_user_admin_collection' , array('action' => 'filter', '_reset' => '')) ?>" class="im_dialogs_search_clear <?php echo (!$filtersActive && $filtersNameActive)?'':'hide' ?>"></a>
                <?php echo $filters->renderHiddenFields() ?>
            </form>
        </div>
    </li>
<?php end_slot() ?>

<?php slot('global-head-extra')?>
<div class="a-ui a-admin-header global-head-extra">
    <div class="container-global-head-extra-inner">
        <div class="icon32 users-long-icon" id="icon-themes"></div>
        <h3 class="a-admin-title"><?php echo __('Manage users', array(), 'apostrophe') ?></h3>
        <ul class="a-ui a-controls a-admin-controls ar-button-new">
            <?php include_partial('aUserAdmin/list_actions', array('helper' => $helper)) ?>   
        </ul>
        <?php include_partial('arCommentAdmin/flashes') ?>
        <div class="pager-control">
            <?php include_partial('aPager/simple', array('pager' => $pager, 'pagerUrl' =>  url_for('aUserAdmin/index'))) ?>
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
                    <span class="icon32 users-long-icon" id="icon-themes"></span>
                    <span class="admin-text" ><?php echo __('Manage users', array(), 'apostrophe') ?></span>
                </li>
                <?php include_partial('aUserAdmin/list_actions', array('helper' => $helper)) ?>          
                <?php //include_partial('aUserAdmin/flashes') ?>
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
       <?php include_partial('aUserAdmin/filters_extra', array('form' => $filters, 'configuration' => $configuration, 'filtersActive' => $filtersActive)) ?>

        <div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?> <?php echo $sf_request->getParameter('class') ?>">
            <div class="a-admin-content main">
                <form action="<?php echo url_for('a_user_admin_collection', array('action' => 'batch')) ?>" method="post" id="a-admin-batch-form">
                    <?php include_partial('aUserAdmin/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
                    <ul class="a-ui a-admin-actions" >
                        <?php include_partial('aUserAdmin/list_batch_actions', array('helper' => $helper)) ?>
                    </ul>
                    <button id="cmd-batch-action" class="btn btn-primary" type="button">
                        <?php echo __('Go', array(), 'blog') ?>
                    </button>
                </form>
            </div>

            <div class="a-admin-footer">
                <?php include_partial('aUserAdmin/list_footer', array('pager' => $pager)) ?>
            </div>
        </div>
    </div>
</div>


<?php include_js_call('aUserAdmin/jsFilter') ?>

<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>