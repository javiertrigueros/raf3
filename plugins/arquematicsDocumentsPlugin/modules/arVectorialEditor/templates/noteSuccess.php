<?php use_helper('I18N','Partial', 'a','ar') ?>

<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>

<?php $formNote = isset($formNote) ? $sf_data->getRaw('formNote') : false; ?>
<?php $formFile = isset($formFile) ? $sf_data->getRaw('formFile') : false; ?>
<?php $formDiagram = isset($formDiagram) ? $sf_data->getRaw('formDiagram') : false; ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/arDocumentControl.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/animation.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/arExplorer.css"); ?>
<?php //use_stylesheet("/arquematicsDocumentsPlugin/js/explorer/app/bower_components/handsontable/dist/handsontable.full.css"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/explorer/app/bower_components/modernizr/modernizr.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php include_js_call('arWall/jsMain'); ?>
<!-- Content -->
       <div id="content-wrapper">
       </div>

       <div id="cmd-navbar">
       </div>

        <!-- Modal -->
        <div id="modal"></div>

        <!-- Brand layer -->
        <div id="brand-layer"></div>

       <script data-main="/arquematicsDocumentsPlugin/js/explorer/app/scripts/main" src="/arquematicsDocumentsPlugin/js/explorer/app/bower_components/requirejs/require.js"></script>

<form id="note-form" class="hide" method="post" action="<?php echo url_for('laverna_doc_notes_main') ?>">
    <?php echo $formNote ?>
</form>

<form id="file-form" class="hide" method="post" action="<?php echo url_for('laverna_doc_notes_main').'/files' ?>">
    <?php echo $formFile->renderHiddenFields() ?>
</form>
        
<form id="diagram-form" class="hide" method="post" action="<?php echo url_for('laverna_doc_notes_main') ?>">
    <?php echo $formDiagram ?>
</form>
        

<?php slot('global-head-search'); ?>
     <!-- 3. $NAVBAR_ICON_BUTTONS =======================================================================

			Navbar Icon Buttons

			NOTE: .nav-icon-btn triggers a dropdown menu on desktop screens only. On small screens .nav-icon-btn acts like a hyperlink.

			Classes:
			* 'nav-icon-btn-info'
			* 'nav-icon-btn-success'
			* 'nav-icon-btn-warning'
			* 'nav-icon-btn-danger' 
                -->
     <?php //include_component('arGroup','showButtomRequest'); ?> 
      <!-- /3. $END_NAVBAR_ICON_BUTTONS -->

     <li id="form-search">
        <form class="navbar-form pull-left">
            <input type="text" class="form-control" placeholder="Search">
        </form>
     </li>
<?php end_slot(); ?>

<?php slot('global-main-app-menu')?>
    <div id="main-menu" role="navigation">
        <div id="main-menu-inner">
            <ul id="sidebar-menu" class="navigation">
                                
            </ul> <!-- / .navigation -->
	</div> <!-- / #main-menu-inner -->
    </div> 
<?php end_slot(); ?> 
    
<?php slot('global-head')?>
<div id="navbar-content" class="navbar-inner">
<!-- Main navbar header -->
    <div class="navbar-header">
        <div id="logo">
           
        </div>
        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <ul class="nav navbar-nav">
                <li id="home-navbar">
                    <a href="<?php echo url_for('@wall?pag=1'); ?>" class="home-item"><?php echo __('Go to Wall', null, 'adminMenu'); ?></a>
                </li>
                <?php include_component('arMenuAdmin','showExplorerMenu'); ?>
                <?php // de momento el buscar desconectado ?>
                <?php //include_slot('global-head-search') ?>
            </ul> <!-- / .navbar-nav -->

            <div class="right clearfix">
                
                
                <ul class="nav navbar-nav pull-right right-navbar-nav">
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </ul> <!-- / .navbar-nav -->
                <div id="cmd-buttoms">
                    
                </div>
            </div> <!-- / .right -->
        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>

<?php //slot('body_class','theme-default main-menu-animated page-profile'); ?>
<?php slot('body_class','loading')?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
