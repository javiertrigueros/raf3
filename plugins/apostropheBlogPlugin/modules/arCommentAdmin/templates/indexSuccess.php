<?php use_helper('I18N', 'Date','a','ar','arGravatar') ?>

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
                    <span class="icon32 comments-long-icon" id="icon-themes"></span>
                    <span class="admin-text" ><?php echo __('Manage comments', array(), 'apostrophe') ?></span>
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

<div id="content-wrapper">
    <div class="profile-row">
        <div class="a-ui a-admin-container container-global-head-extra">
            <div class="a-admin-content main">
                <form id="a-admin-batch-form" action="<?php echo url_for('@ar_comment_admin').'/batch/action' ?>" method="post">
                    <?php include_partial('arCommentAdmin/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
                    <ul class="a-ui a-controls a-admin-actions">
                        <?php include_partial('arCommentAdmin/list_batch_actions', array('helper' => $helper)) ?>
                    </ul>
                    <button id="cmd-batch-action" class="btn btn-primary" type="button">
                        <?php echo __('Go', array(), 'blog') ?>
                    </button>
                </form>
            </div>
            <div class="a-admin-footer">
                <?php include_partial('arCommentAdmin/list_footer', array('pager' => $pager)) ?>
            </div>
        </div>
    </div>
</div>

<?php //arreglo para que los modales salgan centrados ?>
<?php include_js_call('ar/jsFixModal') ?>

<?php slot('body_class','a-admin a-admin-generator aUserAdmin index theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
