<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : null ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/animation.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/explorer/app/styles/main.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/fontello/css/fontello.css"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/explorer/app/bower_components/modernizr/modernizr.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/vendor/polyfill/pathseg/pathseg.js"); ?>


<?php include_js_call('arLaverna/jsMain' , array('toggle' => false)); ?>

<?php slot('global-main-app-menu')?>
    <div id="main-menu" role="navigation">
        <div id="main-menu-inner">
            <ul id="tools_left" class="navigation tools_panel">
                <li class="tool_button" id="tool_select" title="Select Tool"></li>
                <li class="tool_button" id="tool_layer" title="Layers"></li>
                <li class="tool_button" id="tool_zoom" title="More Zoom"></li>
                <li class="tool_button" id="tool_zoom_minus" title="Less Zoom"></li>
                <li class="tool_button" id="tool_fhpath" title="Pencil Tool"></li>
                <li class="tool_button" id="tool_line" title="Line Tool"></li>
                <li class="tool_button" id="tool_rect"  title="Rectangle"></li>
                <li class="tool_button" id="tool_ellipse" title="Ellipse"></li>
                <li class="tool_button" id="tool_path" title="Path Tool"></li>
                <li class="tool_button" id="tool_text" title="Text Tool"></li>
            </ul> <!-- tools_left .navigation -->
	   </div> <!-- / #main-menu-inner -->
    </div> 
<?php end_slot(); ?> 
    
<?php  slot('global-head')?>
<div id="navbar-content" class="navbar-inner">
<!-- Main navbar header -->
    <div class="navbar-header">
        <a id="takeBack" href="<?php echo url_for('@wall?pag=1'); ?>" class="navbar-brand">
            <?php echo __('Go to Wall', null, 'adminMenu'); ?>
        </a>
        
        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <ul class="control-tools title-buttons">
                <li id="tool_undo" title="Undo [Z]">
                    <i class="icon-ccw"></i>
                </li>
                <li id="tool_redo" title="Redo [Y]">
                    <i class="icon-cw"></i>
                </li>
                <li id="tool_source" title="Edit Source [U]">
                    <i class="icon-code"></i>
                </li>
                <li id="tool_wireframe" title="Wireframe Mode [F]">
                    <i class="icon-codeopen"></i>
                </li>
            </ul> <!-- / .navbar-nav -->
            <ul id="app-buttoms" class="control-tools app-title-buttons">
                        
            </ul>

             <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>

   
            <ul id="tool_save" class="nav pull-right right-navbar-nav pull-right svg-menu-right">
                <li class="btn-group saveBtnGroup wmd-save-button">
                    <span id="editor_save" class="saveBtn btn btn-success">
                        <i class="icon-floppy"></i>
                        <span data-text="<?php echo __('Save and exit', array(), 'diagram-editor') ?>" data-text-saving="<?php echo __('Saving', array(), 'diagram-editor') ?>" class="save-btn-text"><?php echo __('Save and exit', array(), 'diagram-editor') ?></span>
                    </span>
                </li>
            </ul>

        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>


<?php slot('body_class','loading')?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>

<?php /* aqui el nuevo codigo */ ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/editor/jgraduate/css/jPicker.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/editor/jgraduate/css/jgraduate.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/editor/spinbtn/JQuerySpinBtn.css"); ?>


<?php //use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php //use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/fontello/css/fontello.css"); ?>
<?php //use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/fontello/css/animation.css"); ?>


<?php //use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php //use_stylesheet("/arquematicsPlugin/css/app.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/editor/svg-editor.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/svg-edit/arSvgEditor.css"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/jgraduate/jpicker.js");?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/js-hotkeys/jquery.hotkeys.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/jquerybbq/jquery.bbq.min.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svgicons/jquery.svgicons.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/jgraduate/jquery.jgraduate.min.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/spinbtn/JQuerySpinBtn.min.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/touch.js");?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svgedit.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/jquery-svg.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/contextmenu/jquery.contextMenu.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/browser.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svgtransformlist.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/math.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/units.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svgutils.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/sanitize.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/history.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/coords.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/recalculate.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/select.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/draw.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/path.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svgcanvas.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/svg-editor.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/locale/locale.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/svg-edit/editor/contextmenu.js");?>

<?php /*
<!-- If you do not wish to add extensions by URL, you can load them
by creating the following file and adding by calls to svgEditor.setConfig -->
<!--<script src="/arquematicsDocumentsPlugin/js/svg-edit/editor/config.js"></script>  -->
*/?>

<!-- recursos jquery.sidr.js -->
<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.light.css"); ?>
<?php //use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>
<?php use_javascript("/arquematicsMenuPlugin/js/jquery.sidr.js"); ?>

<?php slot('global-head-search','')?>

<?php /*slot('global-head')?>
    <!-- Navbar -->
    <div class="ng-scope">
        <div class="tg_page_head ng-scope">
            <div  class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav">

                    <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>

                    <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>
                    
                    <ul id="main-menu" class="control-tools title-buttons">
                        <li id="tool_undo" title="Undo [Z]">
                            <i class="icon-ccw"></i>
                        </li>
                        <li id="tool_redo" title="Redo [Y]">
                            <i class="icon-cw"></i>
                        </li>
                        
                        <li id="tool_source" title="Edit Source [U]">
                            <i class="icon-code"></i>
                        </li>
    
                        <li id="tool_wireframe" title="Wireframe Mode [F]">
                            <i class="icon-codeopen"></i>
                        </li>
    
                    </ul>
                    <ul id="app-buttoms" class="control-tools app-title-buttons">
                        
                    </ul>
                    <!--
                    <ul class="control-save">
                        <li class="" id="tool_save" title="<?php echo __('Save and exit', array(),'diagram-editor'); ?>">
                            <span id="save-text" class="save-and-exit-text spin-control"><?php echo __('Save and exit', array(),'diagram-editor'); ?></span>
                            <span id="spin-icon" class="spin-save-icon hide spin-control"></span>
                            <span id="save-icon" class="glyphicon glyphicon-floppy-disk spin-control"></span>
                        </li>
                    </ul>
                    -->
                    <ul id="tool_save" class="pull-right svg-menu-right">
                            <li class="btn-group saveBtnGroup wmd-save-button">
                                <span id="editor_save" class="saveBtn btn btn-success">
                                    <i class="icon-floppy"></i>
                                    <span data-text="<?php echo __('Save and exit', array(), 'diagram-editor') ?>" data-text-saving="<?php echo __('Saving', array(), 'diagram-editor') ?>" class="save-btn-text"><?php echo __('Save and exit', array(), 'diagram-editor') ?></span>
                                </span>
                            </li>
                   </ul>
                    
                </div>
                <?php include_slot('global-head-extra') ?>
            </div>
        </div>
    </div>
    <!--/Navbar-->
<?php end_slot() */?>

<div id="svg_editor">

<div id="rulers">
	<div id="ruler_corner"></div>
	<div id="ruler_x">
		<div>
			<canvas height="15"></canvas>
		</div>
	</div>
	<div id="ruler_y" class="sidr-max-height">
		<div>
			<canvas width="15"></canvas>
		</div>
	</div>
</div>

<div id="workarea">
<style id="styleoverrides" type="text/css" media="screen" scoped="scoped"></style>
<div id="svgcanvas" style="position:relative">

</div>
</div>


<div id="layerpanel" class="hide" >
		
    <div id="layerbuttons">
        <div id="layer_new" class="layer_button"  title="<?php echo __('New Layer', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-plus-sign"></span>
        </div>
	<div id="layer_delete" class="layer_button"  title="<?php echo __('Delete Layer', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-minus-sign"></span>
        </div>
	<div id="layer_rename" class="layer_button"  title="<?php echo __('Rename Layer', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-font"></span>
        </div>
	<div id="layer_up" class="layer_button"  title="<?php echo __('Move Layer Up', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-arrow-up"></span>
        </div>
	<div id="layer_down" class="layer_button"  title="<?php echo __('Move Layer Down', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-arrow-down"></span>
        </div>
	<div id="layer_moreopts" class="layer_button"  title="<?php echo __('More Options', null, 'svg-editor') ?>">
            <span class="glyphicon glyphicon-cog"></span>
        </div>
    </div>
		
    <table id="layerlist">
	<tr class="layer">
            <td class="layervis"></td>
            <td class="layername"><?php echo __('Layer 1', null, 'svg-editor') ?></td>
	</tr>
    </table>
    <button class="btn btn-primary close-layers-btn close-layers"><?php echo a_('Close') ?></button>
</div>
    
    
<div id="sidepanels">
    <!-- Buttons when a single element is selected -->
	<div id="selected_panel" class="control-panel">
		<div class="toolset">
			
			<div class="push_button" id="tool_clone" title="Duplicate Element [D]"></div>
			<div class="push_button" id="tool_delete" title="Delete Element [Delete/Backspace]"></div>
			
			<div class="push_button" id="tool_move_top" title="Bring to Front [ Ctrl+Shift+] ]"></div>
			<div class="push_button" id="tool_move_bottom" title="Send to Back [ Ctrl+Shift+[ ]"></div>
                        <div class="control-path">
                           <div class="push_button" id="tool_topath" title="Convert to Path"></div>
                           <div class="push_button" id="tool_reorient" title="Reorient path"></div> 
                        </div>
			<?php //<div class="push_button" id="tool_make_link" title="Make (hyper)link"></div> ?>
			<div id="idLabel" class="control-elem row-fluid" title="Identify the element">
				<span class="elem-identify icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1">id:</span>
				<input id="elem_id" class="attr_changer col-xs-6 col-sm-6 col-md-6 col-lg-6" data-attr="id" size="10" type="text"/>
			</div>
		</div>
            
                <div class="toolset control-elem row-fluid">
                        <span id="strokeLabel" data-attr="Stroke Width" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
			<input class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="stroke_width" title="Change stroke width by 1, shift-click to change by 0.1" size="2" value="5" type="text" data-attr="Stroke Width"/>
		</div>
            
                <div class="toolset control-elem row-fluid" id="tool_opacity" title="Change selected item opacity">
                    <span id="group_opacityLabel" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
                    <input id="group_opacity" class="col-xs-5 col-sm-5 col-md-5 col-lg-5" size="3" value="100" type="text"/>
				
                    <div id="opacity_dropdown" class="dropdown">
                        <span class="right-caret cmd-button-dropdown"></span>
                        <ul>
                            <li class="special"><div id="opac_slider"></div></li>
                        </ul>
                    </div>
                </div>

		<div id="tool_angle" class="toolset control-elem row-fluid" title="Change rotation angle" class="toolset">
			<span id="angleLabel" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
			<input class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="angle" size="2" value="0" type="text"/>
		</div>
            
                
		
		<div class="toolset control-elem row-fluid" id="tool_blur" title="Change gaussian blur value">
			
                        <span id="blurLabel" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
			<input id="blur" size="2" class="col-xs-5 col-sm-5 col-md-5 col-lg-5" value="0" type="text"/>
			<div id="blur_dropdown" class="dropdown">
                                <span class="right-caret cmd-button-dropdown"></span>
				
				<ul>
                                    <li class="special"><div id="blur_slider"></div></li>
				</ul>
			</div>
		</div>
            
		<div class="dropdown toolset control-elem row-fluid" id="tool_position" title="Align Element to Page">
                    <div id="cur_position" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 icon_label cmd-button-dropdown"></div>
		</div>
            
		<div id="xy_panel" class="toolset control-elem row-fluid">
			<label>
				x: <input id="selected_x" class="attr_changer" title="Change X coordinate" size="3" data-attr="x"/>
			</label>
			<label>
				y: <input id="selected_y" class="attr_changer" title="Change Y coordinate" size="3" data-attr="y"/>
			</label>
		</div>
	</div>
    
        <div id="text_panel" class="control-panel font-panel">
		
                <span id="tool_bold" title="Bold Text [B]" class="cmd-bold glyphicon glyphicon-bold"></span>
                <span id="tool_italic" title="Italic Text [I]" class="cmd-italic glyphicon glyphicon-italic"></span>
		
		<div class="toolset" id="tool_font_family">

			<!-- Font family -->
			<input id="font_family" type="text" title="Change Font Family" size="6"/>
			
			<div id="font_family_dropdown" class="dropdown">
                                <span class="caret-correction caret cmd-button-dropdown"></span>
				
				<ul>
					<li style="font-family:serif">Serif</li>
					<li style="font-family:sans-serif">Sans-serif</li>
					<li style="font-family:cursive">Cursive</li>
					<li style="font-family:fantasy">Fantasy</li>
					<li style="font-family:monospace">Monospace</li>
				</ul>
			</div>
		</div>

		<label id="tool_font_size" title="Change Font Size">
			<span id="font_sizeLabel" class="icon_label"></span>
			<input id="font_size" size="3" value="0" type="text"/>
		</label>
		
		<!-- Not visible, but still used -->
		<input id="text" type="text" size="35"/>
	</div>
    
        
	
	<!-- Buttons when multiple elements are selected -->
	<div id="multiselected_panel" class="control-panel">
		
		<div class="push_button" id="tool_clone_multi" title="Clone Elements [C]"></div>
		<div class="push_button" id="tool_delete_multi" title="Delete Selected Elements [Delete/Backspace]"></div>
		
		<div class="push_button" id="tool_group_elements" title="Group Elements [G]"></div>
		<?php /*<div class="push_button" id="tool_make_link_multi" title="Make (hyper)link"></div> */ ?>
		<div class="push_button" id="tool_alignleft" title="Align Left"></div>
		<div class="push_button" id="tool_aligncenter" title="Align Center"></div>
		<div class="push_button" id="tool_alignright" title="Align Right"></div>
		<div class="push_button" id="tool_aligntop" title="Align Top"></div>
		<div class="push_button" id="tool_alignmiddle" title="Align Middle"></div>
		<div class="push_button" id="tool_alignbottom" title="Align Bottom"></div>
		<?php /*
                <label id="tool_align_relative"> 
			<span id="relativeToLabel">relative to:</span>
			<select id="align_relative_to" title="Align relative to ...">
			<option id="selected_objects" value="selected">selected objects</option>
			<option id="largest_object" value="largest">largest object</option>
			<option id="smallest_object" value="smallest">smallest object</option>
			<option id="page" value="page">page</option>
			</select>
		</label> */ ?>
	</div>

	<div id="rect_panel" class="control-panel">
		<div class="toolset control-elem row-fluid">
			
				<span id="rwidthLabel" title="Change rectangle width" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
				<input id="rect_width" class="attr_changer" size="3" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" data-attr="width"/>
			
		</div>
                <div class="toolset control-elem row-fluid">
				<span id="rheightLabel" title="Change rectangle height" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
				<input id="rect_height" class="attr_changer" size="3" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" data-attr="height"/>
			
		</div>
		<label id="cornerRadiusLabel"  class="toolset">
			<span class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1" title="Change Rectangle Corner Radius" ></span>
			<input class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="rect_rx" size="3" value="0" type="text" data-attr="Corner Radius"/>
		</label>
	</div>

	<div id="image_panel" class="control-panel">
           
            <div class="toolset control-elem row-fluid">
                <span id="iwidthLabel" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
                <input id="image_width" class="attr_changer col-xs-6 col-sm-6 col-md-6 col-lg-6" title="Change image width" size="3" data-attr="width"/>

                <span id="iheightLabel" class="icon_label col-xs-1 col-sm-1 col-md-1 col-lg-1"></span>
                <input id="image_height" class="attr_changer col-xs-6 col-sm-6 col-md-6 col-lg-6" title="Change image height" size="3" data-attr="height"/>
            </div>
            <?php /*
            <div class="toolset">
		<label id="tool_image_url">url:
			<input id="image_url" type="text" title="Change URL" size="35"/>
		</label>
		<label id="tool_change_image">
			<button id="change_image_url" style="display:none;">Change Image</button>
			<span id="url_notice" title="NOTE: This image cannot be embedded. It will depend on this path to be displayed"></span>
		</label>
            </div> */?>
        </div>

	<div id="circle_panel" class="control-panel">
		<div class="toolset">
			<label id="tool_circle_cx">cx:
			<input id="circle_cx" class="attr_changer" title="Change circle's cx coordinate" size="3" data-attr="cx"/>
			</label>
			<label id="tool_circle_cy">cy:
			<input id="circle_cy" class="attr_changer" title="Change circle's cy coordinate" size="3" data-attr="cy"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_circle_r">r:
			<input id="circle_r" class="attr_changer" title="Change circle's radius" size="3" data-attr="r"/>
			</label>
		</div>
	</div>

	<div id="ellipse_panel" class="control-panel">
		<div class="toolset">
			<label id="tool_ellipse_cx">cx:
			<input id="ellipse_cx" class="attr_changer" title="Change ellipse's cx coordinate" size="3" data-attr="cx"/>
			</label>
			<label id="tool_ellipse_cy">cy:
			<input id="ellipse_cy" class="attr_changer" title="Change ellipse's cy coordinate" size="3" data-attr="cy"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_ellipse_rx">rx:
			<input id="ellipse_rx" class="attr_changer" title="Change ellipse's x radius" size="3" data-attr="rx"/>
			</label>
			<label id="tool_ellipse_ry">ry:
			<input id="ellipse_ry" class="attr_changer" title="Change ellipse's y radius" size="3" data-attr="ry"/>
			</label>
		</div>
	</div>

	<div id="line_panel" class="control-panel">
		<div class="toolset">
			<label id="tool_line_x1">x1:
			<input id="line_x1" class="attr_changer" title="Change line's starting x coordinate" size="3" data-attr="x1"/>
			</label>
			<label id="tool_line_y1">y1:
			<input id="line_y1" class="attr_changer" title="Change line's starting y coordinate" size="3" data-attr="y1"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_line_x2">x2:
			<input id="line_x2" class="attr_changer" title="Change line's ending x coordinate" size="3" data-attr="x2"/>
			</label>
			<label id="tool_line_y2">y2:
			<input id="line_y2" class="attr_changer" title="Change line's ending y coordinate" size="3" data-attr="y2"/>
			</label>
		</div>
	</div>

	

	<!-- formerly gsvg_panel -->
	<div id="container_panel" class="control-panel">

		<!-- Add viewBox field here? -->

		<label id="group_title" title="Group identification label">
			<span>label:</span>
			<input id="g_title" data-attr="title" size="10" type="text"/>
		</label>
	</div>
	
	<div id="use_panel" class="control-panel">
		<div class="push_button" id="tool_unlink_use" title="Break link to reference element (make unique)"></div>
	</div>
	
	<div id="g_panel">
		<div class="push_button" id="tool_ungroup" title="Ungroup Elements [G]"></div>
	</div>

	<!-- For anchor elements -->
	<div id="a_panel">
		<label id="tool_link_url" title="Set link URL (leave empty to remove)">
			<span id="linkLabel" class="icon_label"></span>
			<input id="link_url" type="text" size="35"/>
		</label>	
	</div>
	
	<div id="path_node_panel" class="control-panel">
		<div class="tool_button push_button_pressed" id="tool_node_link" title="Link Control Points"></div>
		<label id="tool_node_x">x:
			<input id="path_node_x" class="attr_changer" title="Change node's x coordinate" size="3" data-attr="x"/>
		</label>
		<label id="tool_node_y">y:
			<input id="path_node_y" class="attr_changer" title="Change node's y coordinate" size="3" data-attr="y"/>
		</label>
		
		<select id="seg_type" title="Change Segment type">
			<option id="straight_segments" selected="selected" value="4">Straight</option>
			<option id="curve_segments" value="6">Curve</option>
		</select>
		<div class="tool_button" id="tool_node_clone" title="Clone Node"></div>
		<div class="tool_button" id="tool_node_delete" title="Delete Node"></div>
		<div class="tool_button" id="tool_openclose_path" title="Open/close sub-path"></div>
		<div class="tool_button" id="tool_add_subpath" title="Add sub-path"></div>
	</div>
</div>
    
<div id="right-panel">
    <div id="image-import">
        
    </div>     
</div>
<?php /*
<div id="main_button">
	<div id="main_icon" class="tool_button" title="Main Menu">
		<span>SVG-Edit</span>
		<div id="logo"></div>
		<div class="dropdown"></div>
	</div>
		
	<div id="main_menu"> 
	
		<!-- File-like buttons: New, Save, Source -->
		<ul>
			<li id="tool_clear">
				<div></div>
				New Image (N)
			</li>
			
			<li id="tool_open" style="display:none;">
				<div id="fileinputs">
					<div></div>
				</div>
				Open Image
			</li>
			
			<li id="tool_import" style="display:none;">
				<div id="fileinputs_import">
					<div></div>
				</div>
				Import Image
			</li>
			
			<li id="tool_save">
				<div></div>
				Save Image (S)
			</li>
			
			<li id="tool_export">
				<div></div>
				Export
			</li>
			
			<li id="tool_docprops">
				<div></div>
				Document Properties (D)
			</li>
		</ul>

		<button id="tool_prefs_option">
			Editor Options
		</button>


	</div>
</div>
*/ ?>

<div id="cur_context_panel" class="control-panel">
		
</div>
    
<div id="tools_bottom" class="tools_panel">

        <?php /*
	<!-- Zoom buttons -->
	<div id="zoom_panel" class="toolset" title="Change zoom level">
		<label>
		<span id="zoomLabel" class="zoom_tool icon_label"></span>
		<input id="zoom" size="3" value="100" type="text" />
		</label>
		<div id="zoom_dropdown" class="dropdown">
			<button></button>
			<ul>
				<li>1000%</li>
				<li>400%</li>
				<li>200%</li>
				<li>100%</li>
				<li>50%</li>
				<li>25%</li>
				<li id="fit_to_canvas" data-val="canvas">Fit to canvas</li>
				<li id="fit_to_sel" data-val="selection">Fit to selection</li>
				<li id="fit_to_layer_content" data-val="layer">Fit to layer content</li>
				<li id="fit_to_all" data-val="content">Fit to all content</li>
				<li>100%</li>
			</ul>
		</div>
		<div class="tool_sep"></div>
	</div> */ ?>

	<div id="tools_bottom_2">
		<div id="color_tools">
			
			<div class="color_tool" id="tool_fill">
				<label class="icon_label" for="fill_color" title="Change fill color"></label>
				<div class="color_block">
					<div id="fill_bg"></div>
					<div id="fill_color" class="color_block"></div>
				</div>
			</div>
		
			<div class="color_tool" id="tool_stroke">
				<label class="icon_label" title="Change stroke color"></label>
				<div class="color_block">
					<div id="stroke_bg"></div>
					<div id="stroke_color" class="color_block" title="Change stroke color"></div>
				</div>
				
			</div>
                    
                        <?php /*
                        <div style="display:none">
                    
                                <div id="toggle_stroke_tools" title="Show/hide more stroke tools"></div>
				
				<div class="stroke_tool">
					<select id="stroke_style" title="Change stroke dash style">
						<option selected="selected" value="none">&#8212;</option>
						<option value="2,2">...</option>
						<option value="5,5">- -</option>
						<option value="5,2,2,2">- .</option>
						<option value="5,2,2,2,2,2">- ..</option>
					</select>
				</div>

 				<div class="stroke_tool dropdown" id="stroke_linejoin">
					<div id="cur_linejoin" title="Linejoin: Miter"></div>
					<button></button>
 				</div>
 				
 				<div class="stroke_tool dropdown" id="stroke_linecap">
					<div id="cur_linecap" title="Linecap: Butt"></div>
					<button></button>
 				</div>

                        </div>*/ ?>
		</div>

	</div>

	<div id="tools_bottom_3">
		<div id="palette_holder"><div id="palette" title="Click to change fill color, shift-click to change stroke color"></div></div>
	</div>
	<!-- <div id="copyright"><span id="copyrightLabel">Powered by</span> <a href="http://svg-edit.googlecode.com/" target="_blank">SVG-edit v2.6-beta</a></div> -->
</div> <!-- tools_bottom -->

<div id="option_lists" class="dropdown">
	<ul id="linejoin_opts">
		<li class="tool_button current" id="linejoin_miter" title="Linejoin: Miter"></li>
		<li class="tool_button" id="linejoin_round" title="Linejoin: Round"></li>
		<li class="tool_button" id="linejoin_bevel" title="Linejoin: Bevel"></li>
	</ul>
	
	<ul id="linecap_opts">
		<li class="tool_button current" id="linecap_butt" title="Linecap: Butt"></li>
		<li class="tool_button" id="linecap_square" title="Linecap: Square"></li>
		<li class="tool_button" id="linecap_round" title="Linecap: Round"></li>
	</ul>
	
	<ul id="position_opts" class="optcols3">
		<li class="push_button" id="tool_posleft" title="Align Left"></li>
		<li class="push_button" id="tool_poscenter" title="Align Center"></li>
		<li class="push_button" id="tool_posright" title="Align Right"></li>
		<li class="push_button" id="tool_postop" title="Align Top"></li>
		<li class="push_button" id="tool_posmiddle" title="Align Middle"></li>
		<li class="push_button" id="tool_posbottom" title="Align Bottom"></li>
	</ul>
</div>

<!-- hidden divs -->
<div id="color_picker"></div>

</div> <!-- svg_editor -->

<?php /*
<div id="svg_source_editor">
	<div class="overlay"></div>
	<div id="svg_source_container">
		<div id="tool_source_back" class="toolbar_button">
			<button id="tool_source_save">Apply Changes</button>
			<button id="tool_source_cancel">Cancel</button>
		</div>
		<div id="save_output_btns">
			<p id="copy_save_note">Copy the contents of this box into a text editor, then save the file with a .svg extension.</p>
			<button id="copy_save_done">Done</button>
		</div>
		<form>
			<textarea id="svg_source_textarea" spellcheck="false"></textarea>
		</form>
	</div>
</div>*/ ?>

<div class="modal fade" id="svg-source-editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="source-cancel-extra" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo __('SVG code', array(),'svg-editor'); ?></h4>
                    </div>
                    <div class="modal-body svg-source">
                        <form>
                            <textarea rows="16" class="ui-control-text-input form-control svg-source-text" id="svg_source_textarea" spellcheck="false"></textarea>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="source_cancel" type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo __('Cancel', array(), 'blog') ?></button>
                        <button id="source_save" type="button" class="btn btn-primary"><?php echo __('Accept', array(), 'blog') ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
</div><!-- /.modal svg-source-editor -->

<div class="modal fade" id="message-info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="message-info-cancel-extra" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo __('SVG code', array(),'svg-editor'); ?></h4>
                    </div>
                    <div class="modal-body svg-source">
                       
                    </div>
                    <div class="modal-footer">
                        <button id="message-info-cancel" type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo __('Cancel', array(), 'blog') ?></button>
                        <button id="message-info-save" type="button" class="btn btn-primary"><?php echo __('Accept', array(), 'blog') ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
</div><!-- /.modal message-info -->

<div id="svg_docprops">
	<div class="overlay"></div>
	<div id="svg_docprops_container">
		<div id="tool_docprops_back" class="toolbar_button">
			<button id="tool_docprops_save">OK</button>
			<button id="tool_docprops_cancel">Cancel</button>
		</div>


		<fieldset id="svg_docprops_docprops">
			<legend id="svginfo_image_props">Image Properties</legend>
			<label>
				<span id="svginfo_title">Title:</span>
				<input type="text" id="canvas_title"/>
			</label>

			<fieldset id="change_resolution">
				<legend id="svginfo_dim">Canvas Dimensions</legend>

				<label><span id="svginfo_width">width:</span> <input type="text" id="canvas_width" size="6"/></label>

				<label><span id="svginfo_height">height:</span> <input type="text" id="canvas_height" size="6"/></label>

				<label>
					<select id="resolution">
						<option id="selectedPredefined" selected="selected">Select predefined:</option>
						<option>640x480</option>
						<option>800x600</option>
						<option>1024x768</option>
						<option>1280x960</option>
						<option>1600x1200</option>
						<option id="fitToContent" value="content">Fit to Content</option>
					</select>
				</label>
			</fieldset>

			<fieldset id="image_save_opts">
				<legend id="includedImages">Included Images</legend>
				<label><input type="radio" name="image_opt" value="embed" checked="checked"/> <span id="image_opt_embed">Embed data (local files)</span> </label>
				<label><input type="radio" name="image_opt" value="ref"/> <span id="image_opt_ref">Use file reference</span> </label>
			</fieldset>
		</fieldset>

	</div>
</div>

<div id="svg_prefs">
	<div class="overlay"></div>
	<div id="svg_prefs_container">
		<div id="tool_prefs_back" class="toolbar_button">
			<button id="tool_prefs_save">OK</button>
			<button id="tool_prefs_cancel">Cancel</button>
		</div>

		<fieldset>
			<legend id="svginfo_editor_prefs">Editor Preferences</legend>

			<label><span id="svginfo_lang">Language:</span>
				<!-- Source: http://en.wikipedia.org/wiki/Language_names -->
				<select id="lang_select">
				  <option id="lang_ar" value="ar">العربية</option>
					<option id="lang_cs" value="cs">Čeština</option>
					<option id="lang_de" value="de">Deutsch</option>
					<option id="lang_en" value="en" selected="selected">English</option>
					<option id="lang_es" value="es">Español</option>
					<option id="lang_fa" value="fa">فارسی</option>
					<option id="lang_fr" value="fr">Français</option>
					<option id="lang_fy" value="fy">Frysk</option>
					<option id="lang_hi" value="hi">&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;, &#2361;&#2367;&#2306;&#2342;&#2368;</option>
					<option id="lang_it" value="it">Italiano</option>
					<option id="lang_ja" value="ja">日本語</option>
					<option id="lang_nl" value="nl">Nederlands</option>
					<option id="lang_pl" value="pl">Polski</option>
					<option id="lang_pt-BR" value="pt-BR">Português (BR)</option>
					<option id="lang_ro" value="ro">Română</option>
					<option id="lang_ru" value="ru">Русский</option>
					<option id="lang_sk" value="sk">Slovenčina</option>
					<option id="lang_zh-TW" value="zh-TW">繁體中文</option>
				</select>
			</label>

			<label><span id="svginfo_icons">Icon size:</span>
				<select id="iconsize">
					<option id="icon_small" value="s">Small</option>
					<option id="icon_medium" value="m" selected="selected">Medium</option>
					<option id="icon_large" value="l">Large</option>
					<option id="icon_xlarge" value="xl">Extra Large</option>
				</select>
			</label>

			<fieldset id="change_background">
				<legend id="svginfo_change_background">Editor Background</legend>
				<div id="bg_blocks"></div>
				<label><span id="svginfo_bg_url">URL:</span> <input type="text" id="canvas_bg_url"/></label>
				<p id="svginfo_bg_note">Note: Background will not be saved with image.</p>
			</fieldset>

			<fieldset id="change_grid">
				<legend id="svginfo_grid_settings">Grid</legend>
				<label><span id="svginfo_snap_onoff">Snapping on/off</span><input type="checkbox" value="snapping_on" id="grid_snapping_on"/></label>
				<label><span id="svginfo_snap_step">Snapping Step-Size:</span> <input type="text" id="grid_snapping_step" size="3" value="10"/></label>
				<label><span id="svginfo_grid_color">Grid color:</span> <input type="text" id="grid_color" size="3" value="#000"/></label>
			</fieldset>

			<fieldset id="units_rulers">
				<legend id="svginfo_units_rulers">Units &amp; Rulers</legend>
				<label><span id="svginfo_rulers_onoff">Show rulers</span><input type="checkbox" value="show_rulers" id="show_rulers" checked="checked"/></label>
				<label>
					<span id="svginfo_unit">Base Unit:</span>
					<select id="base_unit">
						<option value="px">Pixels</option>
						<option value="cm">Centimeters</option>
						<option value="mm">Millimeters</option>
						<option value="in">Inches</option>
						<option value="pt">Points</option>
						<option value="pc">Picas</option>
						<option value="em">Ems</option>
						<option value="ex">Exs</option>
					</select>
				</label>
				<!-- Should this be an export option instead? -->
<!-- 
				<span id="svginfo_unit_system">Unit System:</span>
				<label>
					<input type="radio" name="unit_system" value="single" checked="checked"/>
					<span id="svginfo_single_type_unit">Single type unit</span>
					<small id="svginfo_single_type_unit_sub">CSS unit type is set on root element. If a different unit type is entered in a text field, it is converted back to user units on export.</small>
				</label>
				<label>
					<input type="radio" name="unit_system" value="multi"/>
					<span id="svginfo_multi_units">Multiple CSS units</span> 
					<small id="svginfo_single_type_unit_sub">Attributes can be given different CSS units, which may lead to inconsistant results among viewers.</small>
				</label>
 -->
			</fieldset>

		</fieldset>

	</div>
</div>

<div id="dialog_box">
	<div class="overlay"></div>
	<div id="dialog_container">
		<div id="dialog_content"></div>
		<div id="dialog_buttons"></div>
	</div>
</div>

<ul id="cmenu_canvas" class="contextMenu">
	<li><a href="#cut">Cut</a></li>
	<li><a href="#copy">Copy</a></li>
	<li><a href="#paste">Paste</a></li>
	<li><a href="#paste_in_place">Paste in Place</a></li>
	<li class="separator"><a href="#delete">Delete</a></li>
	<li class="separator"><a href="#group">Group<span class="shortcut">G</span></a></li>
	<li><a href="#ungroup">Ungroup<span class="shortcut">G</span></a></li>
	<li class="separator"><a href="#move_front">Bring to Front<span class="shortcut">SHFT+CTRL+]</span></a></li>
	<li><a href="#move_up">Bring Forward<span class="shortcut">CTRL+]</span></a></li>
	<li><a href="#move_down">Send Backward<span class="shortcut">CTRL+[</span></a></li>
	<li><a href="#move_back">Send to Back<span class="shortcut">SHFT+CTRL+[</span></a></li>
</ul>


<ul id="cmenu_layers" class="contextMenu">
	<li><a href="#dupe">Duplicate Layer...</a></li>
	<li><a href="#delete">Delete Layer</a></li>
	<li><a href="#merge_down">Merge Down</a></li>
	<li><a href="#merge_all">Merge All</a></li>
</ul>

<div class="modal-load modal-load-fix">
    <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>

 <?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
 <?php include_partial('arWall/encrypt', array(
        'sections' => array(),
        'aUserProfile' => $aUserProfile))?>

 <?php include_js_call('arVectorialEditor/jsSvg', array(
     'culture' => $culture,
     'documentType' => $documentType,
     'arDiagram' => $arDiagram)) ?>

<?php slot('body_class','theme-default main-menu-animated page-profile'); ?>