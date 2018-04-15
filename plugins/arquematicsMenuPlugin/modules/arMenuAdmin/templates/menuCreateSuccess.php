<?php use_helper('I18N','a','ar','arMenu') ?>
<?php
  $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es';
  // Compatible with sf_escaping_strategy: true
  $allPagesData = isset($allPagesData) ? $sf_data->getRaw('allPagesData') : null;
  $aPage = isset($aPage) ? $sf_data->getRaw('aPage') : false;
  $arMenu = isset($arMenu) ? $sf_data->getRaw('arMenu') : false;
  $formMenuEditForm = isset($formMenuEditForm) ? $sf_data->getRaw('formMenuEditForm') : false;
  
  $treeMenuNodes = isset($treeMenuNodes) ? $sf_data->getRaw('treeMenuNodes') : false;
  
  $categories = isset($categories) ? $sf_data->getRaw('categories') : false;
?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/app.css"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/load-style.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/colors-fresh.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/configure.css");  ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>
<?php /*use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/app.css");*/ ?>

<?php use_javascript("/arquematicsMenuPlugin/js/accordion.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
 
<?php use_javascript("/arquematicsMenuPlugin/js/arquematics.menuEditor.js"); ?>

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
                    <span class="admin-text" >
                        <?php echo __('Edit Menu', null, 'configure'); ?>
                        <?php if ($treeMenuNodes): ?>
                          <strong id="menu-name">
                          <?php echo $treeMenuNodes->getFirst()->getName() ?>
                          </strong>   
                        <?php endif; ?>
                    </span>
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


<?php /*slot('global-head-extra')?>
<div class="a-ui a-admin-header global-head-extra">
    <div class="container-global-head-extra-inner">
       <div class="icon32 blog-long-icon" id="icon-themes"></div>
       <h3 class="a-admin-title"><?php echo __('Edit Menu', null, 'configure'); ?></h3>
    </div>
</div>
<?php end_slot() ?>

<?php slot('global-head')?>
    <!-- Navbar -->
    <div class="ng-scope">
        <div class="tg_page_head ng-scope">
            <div  class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav">
                    <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::HOME)); ?> 
                    <ul id="control-save-menu-main" class="control-save menu-save">
                        <li title="<?php echo __('Save and exit', null, 'configure') ?>" id="editor_save">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                        </li>
                       
                    </ul>
                </div>
                <?php include_slot('global-head-extra') ?>
            </div>
        </div>
    </div>
    <!--/Navbar-->
<?php end_slot() */ ?>
    
<?php include_js_call('arMenuAdmin/jsMenuEditor') ?>

<div class="modal-load modal-load-fix">
    <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>

<?php slot('body_class','non_osx is_1x wp-admin wp-core-ui js  menu-max-depth-0 nav-menus-php auto-fold admin-bar branch-3-7 version-3-7 admin-color-fresh locale-es-es no-customize-support a-admin a-admin-generator aUserAdmin index theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>

<div id="content-wrapper">
    <div class="profile-row">
        <div class="container-fluid main-content a-ui a-admin-container container-global-head-extra">
            <div class="row-fluid">
                <div id="wpwrap" >
    <form id="update-nav-menu" class="control-group" action="<?php echo url_for('@menu_edit') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <?php echo $formMenuEditForm->renderHiddenFields() ?>
<div id="wpcontent">

<div id="wpbody">

<div id="wpbody-content"  tabindex="0">
    
    <div class="wrap">
	
	<div id="nav-menus-frame">
        
            <div id="menu-settings-column" class="metabox-holder">

		<div class="clear"></div>

                    <div id="side-sortables" class="accordion-container">
		
                    <ul class="outer-border">
                        
                    <li class="control-section accordion-section  open add-page" id="add-page">
                        <h3 class="accordion-section-title hndle" tabindex="0" title="<?php echo __('Pages', null, 'configure') ?>"><?php echo __('Pages', null, 'configure') ?></h3>
                        <div class="accordion-section-content ">
                            <div class="inside">
                                <div id="posttype-page" class="posttypediv">
                                    <ul id="posttype-page-tabs" class="posttype-tabs add-menu-item-tabs">
                                        <li>
                                            <a class="nav-tab-link" data-type="page-all" href="#"><?php echo __('All pages', null, 'configure') ?></a>
                                        </li>
                                    </ul><!-- .posttype-tabs -->
                                    <div id="page-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
                                       <?php include_partial('arMenuAdmin/pagesList', array('treeDataNodes' => $allPagesData )); ?>
                                    </div><!-- /.tabs-panel -->

                                    <p class="button-controls">
                                        <span class="list-controls">
                                            <a href="#" class="select-all"><?php echo __('Select all', null, 'configure') ?></a>
                                        </span>

                                        <span class="add-to-menu">
                                            <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php echo __('Add to Menu',null,'configure') ?>" name="add-post-type-menu-item" id="submit-posttype-page" />
                                            <span class="spinner"></span>
                                        </span>
                                    </p>

                                </div><!-- /.posttypediv -->
                            </div><!-- .inside -->
			</div><!-- .accordion-section-content -->                  
                     </li><!-- .accordion-section -->
                     
                     <li class="control-section accordion-section add-categoty" id="add-categoty">
                        <h3 class="accordion-section-title hndle" tabindex="0" title="<?php echo __('Categories', null, 'configure') ?>"><?php echo __('Categories', null, 'configure') ?></h3>
                        <div class="accordion-section-content ">
                            <div class="inside">
                                <div id="posttype-page" class="posttypediv">
                                    
                                    
                                    <ul class="taxonomy-tabs add-menu-item-tabs" id="taxonomy-category-tabs">
                                        <li class="tabs">
                                            <a href="#blog-category-all" data-type="blog-category-all" class="nav-tab-link"><?php echo __('Blog Categories', null, 'configure') ?></a>
                                        </li>
                                        <li>
                                            <a href="#event-category-all" data-type="event-category-all"  class="nav-tab-link"><?php echo __('Events Categories', null, 'configure') ?></a>
                                        </li>
                                    </ul>
                                    
                                    <div id="blog-category-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
                                        <?php include_partial('arMenuAdmin/categoriesList', 
                                                                array('type' => 'post',
                                                                    'categories' => $categories)); ?>
                                    </div><!-- /.tabs-panel -->
                                    <div id="event-category-all" class="tabs-panel tabs-panel-view-all hide">
                                        <?php include_partial('arMenuAdmin/categoriesList', 
                                                                array('type' => 'event',
                                                                    'categories' => $categories)); ?>
                                    </div><!-- /.tabs-panel -->

                                    <p class="button-controls">
                                       
                                        <span class="add-to-menu">
                                            <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php echo __('Add to Menu',null,'configure') ?>" name="add-post-type-menu-item" id="submit-posttype-category" />
                                            <span class="spinner"></span>
                                        </span>
                                    </p>

                                </div><!-- /.posttypediv -->
                            </div><!-- .inside -->
			</div><!-- .accordion-section-content -->                  
                     </li><!-- .accordion-section -->
                                            
                     <li class="control-section accordion-section add-custom-links" id="add-custom-links">
                        <h3 class="accordion-section-title hndle" tabindex="0" title="<?php echo __('Links',null,'configure') ?>"><?php echo __('Links',null,'configure') ?></h3>
			<div class="accordion-section-content ">
                            <div class="inside">
                                <div class="customlinkdiv" id="customlinkdiv">
		
                                <p id="menu-item-url-wrap">
                                    <label class="howto" for="custom-menu-item-url">
                                        <span><?php echo __('URL',null,'configure') ?></span>
                                        <input id="custom-menu-item-url" name="menu-item-url" type="text" class="code menu-item-textbox" value="http://" />
                                    </label>
                                </p>

                                <p id="menu-item-name-wrap">
                                    <label class="howto" for="custom-menu-item-name">
                                        <span><?php echo __('Text link', null,'configure') ?></span>
                                        <input id="custom-menu-item-name" name="menu-item-title" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php echo __('Menu Item', null, 'configure') ?>" />
                                    </label>
                                </p>

                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php echo __('Add to Menu', null, 'configure') ?>" name="add-custom-menu-item" id="submit-customlinkdiv" />
                                        <span class="spinner"></span>
                                    </span>
                                </p>

                                </div><!-- /.customlinkdiv -->
								</div><!-- .inside -->
			</div><!-- .accordion-section-content -->
                    </li><!-- .accordion-section -->
                    
                    
		</ul><!-- .outer-border -->
	</div><!-- .accordion-container -->

	</div><!-- /#menu-settings-column -->
        
        
        
	<div id="menu-management-liquid">
				<div class="menu-edit ">
				
                                        <div class="hide alert ui-control-text-form control-group" id="container-title-form">
                                            <button type="button" class="btn-cancel close cancel">×</button>
                                                <?php echo $formMenuEditForm['name']->render() ?>
                                                
                                                <span class="a-blog-item-status control-item-status" id="a-blog-item-status-input">&ndash; <?php echo __('Menu name:', null, 'configure') ?></span>
                                                <p class="controls-buttom">
                                                    <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-accept btn-primary send" href="#"><?php echo __('Accept',null,'blog'); ?></a>&nbsp;
                                                    <a data-name="<?php echo $treeMenuNodes->getFirst()->getName() ?>" class="btn-cancel cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
                                                </p>
                                            
                                        </div>
                                        
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
                                                        <?php include_partial("arMenuAdmin/menuList", array('treeMenuNodes' => $treeMenuNodes)) ?>
                                                    </ul>
						</div><!-- /#post-body-content -->
					</div><!-- /#post-body -->
                                        
					<div class="nav-menu-footer">
                                            <button class="btn btn-primary btn-send-menu menu-save" id="control-save-menu-btn" data-loading-text="<?php echo __('Send...', null, 'configure') ?>"><?php echo __('Save menu and exit', null, 'configure') ?></button>
					</div><!-- /.nav-menu-footer -->
                                        
				</div><!-- /.menu-edit -->
			<!-- /#update-nav-menu -->

	</div><!-- /#menu-management-liquid -->
	</div><!-- /#nav-menus-frame -->
</div><!-- /.wrap-->

<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->

    </form>

<div id="wpfooter">
		
	<div class="clear"></div>
</div>



<div class="clear"></div>

</div><!-- wpwrap -->
            </div>
        </div>
    </div>
</div>

<!-- The template to display category blog menu item -->
<script id="template-menu-category-blog" type="text/x-jquery-tmpl">
    <li data-depth="0" data-menu-type="blog" data-name="${name}" data-url="${url}" data-id="${id}" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive" id="menu-item-${id}">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title">
                                            <span class="menu-item-title">${name}</span>
                                            <span style="display: none;" class="is-submenu">
                                                <?php echo __('subelement', null, 'configure') ?>
                                            </span>
                                        </span>
					<span class="item-controls">
						<span class="item-type"><?php echo __('Blog Category', null, 'configure') ?></span>
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
				<p class="description-wide">
					<label for="edit-menu-item-title-${id}">
						<?php echo __('Navigation label', null, 'configure') ?><br>
						<input type="text" value="${name}" name="menu-item-title" class="widefat edit-menu-item-title nav-label" id="edit-menu-item-title-${id}">
					</label>
				</p>
				
				<div class="menu-item-actions description-wide submitbox">
                                    <p class="link-to-original">
                                        <?php echo __('Original',null,'configure') ?>: <a href="${url}">${name}</a>
                                    </p>
                                    <a href="#" id="delete-${id}" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		</li>
</script>
<!-- The template to display category menu item -->
<script id="template-menu-category-event" type="text/x-jquery-tmpl">
    <li data-depth="0" data-menu-type="event" data-name="${name}" data-url="${url}" data-id="${id}" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive" id="menu-item-${id}">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title">
                                            <span class="menu-item-title">${name}</span>
                                            <span style="display: none;" class="is-submenu">
                                                <?php echo __('subelement', null, 'configure') ?>
                                            </span>
                                        </span>
					<span class="item-controls">
						<span class="item-type"><?php echo __('Event Category', null, 'configure') ?></span>
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
				<p class="description-wide">
					<label for="edit-menu-item-title-${id}">
						<?php echo __('Navigation label', null, 'configure') ?><br>
						<input type="text" value="${name}" name="menu-item-title" class="widefat edit-menu-item-title nav-label" id="edit-menu-item-title-${id}">
					</label>
				</p>
				
				<div class="menu-item-actions description-wide submitbox">
                                    <p class="link-to-original">
                                        <?php echo __('Original',null,'configure') ?>: <a href="${url}">${name}</a>
                                    </p>
                                    <a href="#" id="delete-${id}" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		</li>
</script>

<!-- The template to display page menu item -->
<script id="template-menu-page" type="text/x-jquery-tmpl">
    <li data-depth="0" data-menu-type="page" data-name="${name}" data-url="${url}" data-id="${id}" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive" id="menu-item-${id}">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title">
                                            <span class="menu-item-title">${name}</span>
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
				<p class="description-wide">
					<label for="edit-menu-item-title-${id}">
						<?php echo __('Navigation label', null, 'configure') ?><br>
						<input type="text" value="${name}" name="menu-item-title" class="widefat edit-menu-item-title nav-label" id="edit-menu-item-title-${id}">
					</label>
				</p>
				
				<div class="menu-item-actions description-wide submitbox">
                                    <p class="link-to-original">
                                        <?php echo __('Original',null,'configure') ?>: <a href="${url}">${name}</a>
                                    </p>
                                    <a href="#" id="delete-${id}" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		</li>
</script>

<!-- The template to display personaliced menu item -->
<script id="template-menu-link" type="text/x-jquery-tmpl">
 <li data-depth="0" data-id="" data-menu-type="link" data-name="${name}" data-url="${url}" class="menu-item menu-item-depth-0 menu-item-custom pending menu-item-edit-inactive" id="menu-item-${id}" style="display: list-item;">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title">${name}</span> <span style="display: none;" class="is-submenu"><?php echo __('subelement', null, 'configure') ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo __('Personalized', null, 'configure') ?></span>
						<span class="item-order hide-if-js">
							<a class="item-move-up" href="#"><abbr title="<?php echo __('Move up', null, 'configure') ?>">↑</abbr></a>
							|
							<a class="item-move-down" href="#"><abbr title="<?php echo __('Move down', null, 'configure') ?>">↓</abbr></a>
                                                </span>
                                                <a href="#" title="<?php echo __('Edit menu item', null, 'configure') ?>" id="edit-${id}" class="item-edit"><?php echo __('Edit menu item', null, 'configure') ?></a>
					</span>
				</dt>
			</dl>

			<div id="menu-item-settings-${id}" class="menu-item-settings hide" >
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-${id}">
							<?php echo __('URL', null, 'configure') ?><br>
							<input type="text" value="${url}" name="menu-item-url[${id}]" class="widefat code edit-menu-item-url" id="edit-menu-item-url-${id}">
						</label>
					</p>
                                        <p class="description-wide">
                                            <label for="edit-menu-item-title-${url}">
						<?php echo __('Navigation label', null, 'configure') ?><br>
						<input type="text" value="${name}" name="menu-item-title[${id}]" class="widefat edit-menu-item-title nav-label" id="edit-menu-item-title-${id}">
                                            </label>
                                        </p>
				
				
				<div class="menu-item-actions description-wide submitbox">
                                    <a href="#" id="delete-${id}" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a> 
				</div>

				
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
	</li>
  </script>
