<?php use_helper('I18N', 'Date','a','ar') ?>

<?php
 
  // Compatible with sf_escaping_strategy: true
  $allPagesTree = isset($allPagesTree) ? $sf_data->getRaw('allPagesTree') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $parent = isset($parent) ? $sf_data->getRaw('parent') : null;
  $inherited = isset($inherited) ? $sf_data->getRaw('inherited') : false;
  
  $slugStem = isset($slugStem) ? $sf_data->getRaw('slugStem') : null;
  
  $formPageDelete = isset($formPageDelete) ? $sf_data->getRaw('formPageDelete') : null;
  
?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>    
<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/app.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>
<?php use_javascript("/arquematicsMenuPlugin/js/accordion.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/load-style.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/colors-fresh.css"); ?>
  
<?php use_javascript("/arquematicsMenuPlugin/js/arquematics.pageEditor.js"); ?>

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
                    <span class="icon32 page-long-icon" id="icon-themes"></span>
                    <span class="admin-text" ><?php echo __('Manage pages / Main menu', array(), 'adminMenu') ?></span>
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
        <div class="container-fluid main-content a-ui a-admin-container container-global-head-extra">
            <div class="row-fluid">
                <div id="wpwrap" class="span12">
    <form id="update-nav-menu" class="control-group" action="<?php echo url_for('@ar_page_admin_update') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div id="wpcontent">

        <div id="wpbody">
            <div id="wpbody-content" class="im_page_wrap"  tabindex="0">
                <div class="wrap">
                    <div id="nav-menus-frame">
                        <div id="menu-settings-column" class="metabox-holder">

                            <div id="side-sortables" class="accordion-container">
		
                            <ul class="outer-border">
                                <li id="add-page" class="control-section accordion-section  open add-page" >
                                    <h3 class="accordion-section-title hndle" tabindex="0" title="<?php echo __('New Page', null, 'adminMenu') ?>">
                                        <?php echo a_('New Page') ?>
                                    </h3>
                                    <div class="accordion-section-content ">
                                        <div id="new-page-form" class="inside">
                                            <div id="posttype-page" class="posttypediv">
                                   
                                                <?php include_component('arPageAdmin','showForm', array('page' => $page,
                                                                                             'parent' => $parent)) ?>
                                            
                                                <p class="button-controls">
                                                    <span class="add-to-menu">
                                                        <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php echo __('Add to Menu',null,'configure') ?>" name="add-post-type-menu-item" id="submit-page" />
                                                        <span class="spinner"></span>
                                                    </span>
                                                </p>
                                            </div><!-- /.posttypediv -->
                                        </div><!-- .inside -->
                                    </div><!-- .accordion-section-content -->                  
                                </li><!-- .accordion-section -->
                            </ul><!-- .outer-border -->
                            </div><!-- .accordion-container -->
                        </div><!-- /#menu-settings-column -->
        
        
	<div id="menu-management-liquid">

			
				<div class="menu-edit ">
					
                                    
					<div id="post-body">
						<div id="post-body-content">
                                                    <h3><?php echo __('Menu Structure', null, 'configure') ?></h3>
                                                    <div class="drag-instructions post-body-plain" >
                                                        <p><?php echo __('Place each item in the order you prefer. Click on the arrow to the right of the item to display more configuration options.', null, 'configure') ?></p>
                                                    </div>
                                                    <div id="menu-instructions" class="post-body-plain">
                                                        <p><?php echo __('Add menu items from the right column.', null, 'configure') ?></p>
                                                    </div>
                                                                        
                                                    <ul class="menu" id="menu-to-edit">
                                                        
                                                        <?php include_partial("arPageAdmin/pageList", array('pages' => $allPagesTree)) ?>
                                                    </ul>
						</div><!-- /#post-body-content -->
					</div><!-- /#post-body -->
                                        
					
                                        
				</div><!-- /.menu-edit -->
			<!-- /#update-nav-menu -->

	</div><!-- /#menu-management-liquid -->
	</div><!-- /#nav-menus-frame -->
</div><!-- /.wrap-->

<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->

    </form>

</div><!-- wpwrap -->
            </div><!--/row-->
        </div><!--/.fluid-container-->
    </div>
</div>

<div class="modal fade" id="delete-page-modal">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
            <?php echo __('Confirm Delete', array(), 'configure') ?>
        </h4>
      </div>
      <div id="modal-body-content" data-warm-text="<?php echo __('You want to delete ${pagename}?', null, 'configure')  ?>" class="modal-body row-fluid">
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo __('Cancel', array(), 'configure') ?>
        </button>
        <button id="cmd-delete-page-accept" type="button" data-loading-text="<?php echo __('Deleting...', array(), 'configure') ?>"  class="btn btn-primary">
            <?php echo __('Accept', array(), 'configure') ?>
        </button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<form id="delete-page-form" action="<?php echo url_for('@ar_page_admin_delete') ?>" method="post" enctype="multipart/form-data">
     <?php echo $formPageDelete->renderHiddenFields() ?>
</form>

<div class="modal-load modal-load-fix">
    <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>

<!-- The template to display category menu item -->
<script id="template-page" type="text/x-jquery-tmpl">
    <li data-text_event="<?php echo __('Events', null, 'configure') ?>" data-text_blog="<?php echo __('Blog', null, 'configure') ?>" data-text_page="<?php echo __('Page', null, 'configure') ?>" data-depth="0" data-menu-type="event" data-name="${title}" data-url="${url}" data-id="${id}" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive" id="menu-item-${id}">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title">
                                            <span class="menu-item-title">${title}</span>
                                            <span style="display: none;" class="is-submenu">
                                                <?php echo __('subelement', null, 'configure') ?>
                                            </span>
                                        </span>
					<span class="item-controls">
						<span class="item-type"><?php echo __('Page', null, 'configure') ?></span>
						<span class="item-order hide-if-js">
							<a class="item-move-up" href="#"><abbr title="<?php echo __('Move up', null, 'configure') ?>">↑</abbr></a>
							|
							<a class="item-move-down" href="#"><abbr title="<?php echo __('Move down', null, 'configure') ?>">↓</abbr></a>
						</span>
						<a href="#" title="<?php echo __('Edit menu item', null, 'configure') ?>" id="edit-${id}" class="item-edit"><?php echo __('Edit menu item', null, 'configure') ?></a>
					</span>
				</dt>
			</dl>

			<div id="menu-item-settings-${id}" class="menu-item-settings" style="display: none;">
				
				
				<div class="menu-item-actions description-wide submitbox">
                                    <a href="#" id="cmd-send-page${id}" class="btn btn-primary span4 update-page"  data-loading-text="<?php echo __("send...",array(),'wall') ?>"><?php echo __('Save',null,'configure') ?></a>
                                    <a href="#" id="delete-${id}" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
    </li>
</script>

<?php include_js_call('arPageAdmin/jsPageEditor'); ?>

<?php //arreglo para que los modales salgan centrados ?>
<?php include_js_call('ar/jsFixModal') ?>


<?php slot('body_class','non_osx is_1x wp-admin wp-core-ui js  menu-max-depth-0 nav-menus-php auto-fold admin-bar branch-3-7 version-3-7 admin-color-fresh locale-es-es no-customize-support a-admin a-admin-generator aUserAdmin index theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
