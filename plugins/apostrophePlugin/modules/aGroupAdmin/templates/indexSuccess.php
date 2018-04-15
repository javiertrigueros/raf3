<?php use_helper('I18N', 'Date','a','ar') ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

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
        
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <ul class="nav navbar-nav a-ui a-controls a-admin-controls ar-button-new">
                <li>
                    <span class="icon32 groups-long-icon" id="icon-themes"></span>
                    <span class="admin-text" ><?php echo __('Manage groups', array(), 'apostrophe') ?></span>
                </li>
                <li class="a-admin-action-new">
                    <?php echo link_to('<span class="icon"></span>'.__('New Group', array(), 'apostrophe'), 'aGroupAdmin/new', array('class' => 'a-btn big icon a-add')) ?>
                </li>         
                <?php include_partial('arCommentAdmin/flashes') ?>
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

<?php include_partial('aGroupAdmin/assets') ?>

<div id="content-wrapper">
    <div class="profile-row">
        <div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?> <?php echo $sf_request->getParameter('class') ?>">

            <div class="a-admin-content main">
		
		<?php //include_partial('aGroupAdmin/filters', array('form' => $filters, 'configuration' => $configuration, 'filtersActive' => $filtersActive)) ?>
		
		<?php //include_partial('aGroupAdmin/flashes') ?>
		
		<form action="<?php echo url_for('a_group_admin_collection', array('action' => 'batch')) ?>" method="post" id="a-admin-batch-form">
				
                    <?php include_partial('aGroupAdmin/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>

                    <ul class="a-ui a-admin-actions" >
                        <?php include_partial('aGroupAdmin/list_batch_actions', array('helper' => $helper)) ?>
                    </ul>
                    <button id="cmd-batch-action" class="btn btn-primary" type="button">
                        <?php echo __('Go', array(), 'blog') ?>
                    </button>
		</form>
				
            </div>

            <div class="a-admin-footer">
                <?php include_partial('aGroupAdmin/list_footer', array('pager' => $pager)) ?>
            </div>
        </div>
    </div>
</div>

<?php slot('body_class','a-admin a-admin-generator aUserAdmin index theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>