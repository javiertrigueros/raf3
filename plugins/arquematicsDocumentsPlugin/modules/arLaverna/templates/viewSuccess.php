<?php use_helper('I18N','Partial', 'a','ar') ?>

<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/app.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arWall.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery-timeago/jquery.timeago.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/jquery-timeago/locale/jquery.timeago.$culture.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.friendselector.js"); ?> 

<?php include_partial('arWall/encrypt', array(
    'sections' => array('updateboxarea' => '#updateboxarea',
                        'mainNodeId' => '#content',
                        'secundaryNode' => '#tag-control-list'),
    'aUserProfile' => $aUserProfile))?>

<?php /*slot('global-head')?>

    <!-- Navbar -->
    <div class="ng-scope ">
        <div class="tg_page_head ng-scope">
            <div  class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav">
                    <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </div>
                <?php include_slot('global-head-extra') ?>
            </div>
        </div>
    </div>
    <!--/Navbar-->
<?php end_slot() */ ?>

<?php slot('body_class','wall-body')?>
<?php slot('global-head','') ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>