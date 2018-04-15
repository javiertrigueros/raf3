<?php use_helper('I18N','a','ar') ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
   
  <?php use_stylesheet("/arquematicsPlugin/css/normalize.css"); ?>
  <?php use_stylesheet("/arquematicsPlugin/css/bootstrap.css"); ?>
  <?php use_stylesheet("/arquematicsPlugin/css/bootstrap-responsive.css"); ?>
  
  <?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome-ie7.css"); ?>
  <?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome.css"); ?>

  <?php use_stylesheet("/arquematicsWorkflowPlugin/css/mindmaps/common.css"); ?>
  <?php use_stylesheet("/arquematicsWorkflowPlugin/css/mindmaps/app.css"); ?>
  
  <?php use_stylesheet("/arquematicsWorkflowPlugin/css/wireframe/normalize.css"); ?>
  <?php use_stylesheet("/arquematicsWorkflowPlugin/css/wireframe/jquery-ui.css"); ?>
  <?php use_stylesheet("/arquematicsWorkflowPlugin/css/wireframe/style.css"); ?>
  
  <?php a_include_stylesheets() ?>
</head>
<body>
 <div class='container'>
     
   <div id="header" class="mmbar">
    <div id="icon-menu">
        <ul>
            <li class="navi"><span class="back">&nbsp;</span><a href="/wall">Atras</a></li>
        </ul> 
        <ul>
            <li title="<?php echo __('Copy',null,'wireframe') ?>" class="icon-copy" id="edit_copy"><?php echo __('Copy',null,'wireframe') ?></li>
            <li title="<?php echo __('Cut',null,'wireframe') ?>" class="icon-cut" id="edit_cut"><?php echo __('Cut',null,'wireframe') ?></li>
            <li title="<?php echo __('Paste',null,'wireframe') ?>" class="icon-paste" id="edit_paste"><?php echo __('Paste',null,'wireframe') ?></li>
            <li title="<?php echo __('Delete',null,'wireframe') ?>" class="icon-delete" id="edit_delete"><?php echo __('Delete',null,'wireframe') ?></li>
        </ul> 
        <ul>
            <li title="<?php echo __('Redo',null,'wireframe') ?>" class="icon-redo" id="editor_redo"><?php echo __('Redo',null,'wireframe') ?></li>
            <li title="<?php echo __('Undo',null,'wireframe') ?>" class="icon-undo" id="editor_undo"><?php echo __('Undo',null,'wireframe') ?></li>
        </ul>
        <ul>
            <li title="<?php echo __('In the Spotlight',null,'wireframe') ?>" class="icon-move_front" id="move_front"><?php echo __('In the Spotlight',null,'wireframe') ?></li>
            <li title="<?php echo __('In the background',null,'wireframe') ?>" class="icon-move_back" id="move_back"><?php echo __('In the background',null,'wireframe') ?></li>
            <li title="<?php echo __('One level up',null,'wireframe') ?>" class="icon-move_forwards" id="move_forwards"><?php echo __('One level up',null,'wireframe') ?></li>
            <li title="<?php echo __('One level down',null,'wireframe') ?>" class="icon-move_backwards" id="move_backwards"><?php echo __('One level down',null,'wireframe') ?></li>
        </ul>
        <ul>
            <li title="<?php echo __('Align Bottom',null,'wireframe') ?>" class="icon-aling_bottom" id="aling_bottom"><?php echo __('Align Bottom',null,'wireframe') ?></li>
            <li title="<?php echo __('Align centered on the horizontal axis',null,'wireframe') ?>" class="icon-aling_middle" id="aling_middle"><?php echo __('Align centered on the horizontal axis',null,'wireframe') ?></li>
            <li title="<?php echo __('Align Top',null,'wireframe') ?>" class="icon-aling_top" id="aling_top"><?php echo __('Align Top',null,'wireframe') ?></li>
            <li title="<?php echo __('Align Left',null,'wireframe') ?>" class="icon-aling_left" id="aling_left"><?php echo __('Align Left',null,'wireframe') ?></li>
            <li title="<?php echo __('Align centered on the vertical axis',null,'wireframe') ?>" class="icon-aling_center" id="aling_center"><?php echo __('Align centered on the vertical axis',null,'wireframe') ?></li>
            <li title="<?php echo __('Align Right',null,'wireframe') ?>" class="icon-aling_right" id="aling_right"><?php echo __('Align Right',null,'wireframe') ?></li>
            <li title="<?php echo __('Align same size',null,'wireframe') ?>" class="icon-aling_size" id="aling_size"><?php echo __('Align same size',null,'wireframe') ?></li>
        </ul>
        <ul>
            <li title="<?php echo __('Group',null,'wireframe') ?>" class="icon-shape_group" id="shape_group"><?php echo __('Group',null,'wireframe') ?></li>
            <li title="<?php echo __('Ungroup',null,'wireframe') ?>" class="icon-shape_ungroup" id="shape_ungroup"><?php echo __('Ungroup',null,'wireframe') ?></li>
        </ul>
            <ul class="control-save">
                <li title="<?php echo __('Save and exit',null,'wireframe') ?>" id="editor-save">
                    <span class="editor_save editor_save_in" id="editor_save_icon_id" style="color: black;"></span>
                    <span class="save-and-exit" id="editor_save_text_id" style="color: black;"><?php echo __('Save and exit',null,'wireframe') ?></span>
                </li>
            </ul>
    </div>
  </div>
    
    <div class=supercanvas >
       <div id=canvas-wrap class='browser-wrap' style="margin-top:-50px;padding:0"><span id=browser><span> </span></span><div id='canvas'><div id='guide-h' class='guide'></div><div id='guide-v' class='guide'></div></div></div>
    </div>
  </div>


  <!-- ///wirepanel START -->

  <div id="wirepanel" class=wirepanel>
    <div id='edit' class='highlightable'title='Change stencil type'><i class="icon-magic" ></i></div>
    <div id='edittxt' class='highlightable' title='Edit text'><i class="icon-pencil" ></i></div>
    <div id='lock' class='highlightable' title='Lock'><i class="icon-lock" ></i></div>
    <div id='unlock' class='highlightable' title='Unlock'><i class="icon-unlock" ></i></div>

    <div id='icons'>

      <div class='icon rect horizontal vertical highlightable' elementtype='box' title=Box></div>
      <div class='icon rect horizontal vertical highlightable' elementtype='roundbox' title='Rounded Box'></div>
      <div class='icon rect highlightable' elementtype='elipse' title='Ellipse/Circle' ></div>
      <div class='icon rect highlightable' elementtype='image' title=Image><i class="icon-picture"></i></div>
      <div class='icon rect highlightable' elementtype='paragraph' title='Paragraph' ><i class="icon-align-left"></i></div>
      <div class='icon rect highlightable' elementtype='list' title='List' ><i class="icon-list"></i></div>
      <div class='icon highlightable' elementtype='radiobtn' title='Radio button' ></div>
      <div class='icon highlightable' elementtype='checkbox' title='Checkbox' ></div>
      <div class='icon horizontal highlightable' elementtype='line_hor' title='Line' ></div>
      <div class='icon vertical highlightable' elementtype='line_vert' title='Line' ></div>
      <div class='icon horizontal highlightable' elementtype='slider' title='Slider' ></div>
      <div class='icon horizontal highlightable' elementtype='progressbar' title='Progress bar' ></div>

      <div class='icon horizontal highlightable' elementtype='combobox' title='Combo box' > <i class="icon-caret-down"></i> </div>
      <div class='icon horizontal highlightable' elementtype='scrollh'  title='Scrollbar (horizontal)' > <i class="icon-caret-left"></i> <i class="icon-caret-right"></i></div>
      <div class='icon rect horizontal highlightable' elementtype='headline' title='Headline' >H</div>
      <div class='icon rect horizontal highlightable' elementtype='headlinetxt' title='Headline(text)' >H</div>
      <div id=textinput class='icon horizontal rect highlightable ' elementtype='textinput' title='Text input' ></div>
      <div id=annotate class='icon horizontal rect vertical highlightable' elementtype='annotate' title='Add annotation' style='margin-top:-2px;' ><i class="icon-comments-alt"></i></div>
    </div>

    <div id='bring-back-front' >
      <div id='front' class=highlightable title='Bring to front'>
        <span></span>
      </div>
      <div id='back'class=highlightable title='Send to back'>
        <span></span>
      </div>
    </div>

    <div id='strokes'>
      <div class='stroke_width strokeweight highlightable' title='Stroke width: thin' stroke=1px><span class='line onepx '></span></div>
      <div class='stroke_width strokeweight highlightable' title='Stroke width: medium' stroke=2px><span class='line twopx '></span></div>
      <div class='stroke_width strokeweight highlightable' title='Stroke width: thick' stroke=4px ><span class='line fourpx '></span></div>
    </div>

    <div id=textalign >
      <div id=alignleft class=highlightable><i class="icon-align-left" title='Align left'></i></div>
      <div id=aligncenter class=highlightable><i class="icon-align-center" title='Center'></i></div>
      <div id=alignright class=highlightable><i class="icon-align-right" title='Aligne right'></i></div>
      <div id=alignjustify class=highlightable><i class="icon-align-justify" title='Justify'></i></div>
    </div>
    <div id=textsize >
      <div id='font-s' class=highlightable style="font-size:10px;margin-top:8px;" title='Small font'><i class="icon-font"></i></div>
      <div id='font-m' class=highlightable style="font-size:14px;margin-top:4px;" title='Medium font'><i class="icon-font"></i></div>
      <div id='font-l' class=highlightable style="font-size:18px;" title='Large font'><i class="icon-font"></i></div>
    </div>
    <div>


      <div id=show-border class=highlightable title='Change to fill color'>
        <span></span>
      </div>
      <div id=show-bg style='display:none;' class=highlightable title='Change to background color'>
        <span></span>
      </div>

      <div id='colorpickers'>
        <div class='colorpicker  nocolor' style='background:transparent;' title='Transparent'></div>
        <div class='colorpicker rect' style='background:#fff;' title='Fill color: white'></div>
        <div class='colorpicker rect' style='background:#ddd;' title='Fill color: #ddd'></div>
        <div class='colorpicker rect' style='background:#aaa;' title='Fill color: #aaa'></div>
        <div class='colorpicker rect' style='background:#777;' title='Fill color: #777'></div>
        <div class='colorpicker rect' style='background:#444;' title='Fill color: #444'></div>
        <div class='colorpicker rect' style='background:#111;' title='Fill color: #111'></div>
        <div class='colorpicker rect' style='background:tomato;' title='Fill color: tomato'></div>
      </div>

      <div id='bordercolors' style='display:none;'>

        <div class='brcolor noborder' style='border-color:transparent;' title='Border color: transparent'></div>
        <div class='brcolor' style='border-color:#fff;' title='Border color: #fff' ></div>
        <div class='brcolor' style='border-color:#ddd;' title='Border color: #ddd' ></div>
        <div class='brcolor' style='border-color:#aaa;' title='Border color: #aaa' ></div>
        <div class='brcolor' style='border-color:#777;' title='Border color: #777' ></div>
        <div class='brcolor' style='border-color:#444;' title='Border color: #444' ></div>
        <div class='brcolor' style='border-color:#111;' title='Border color: #111' ></div>
        <div class='brcolor' style='border-color:tomato;' title='Border color: tomato' ></div>

      </div>
    </div>


  </div>
  <!-- ///wirepanel END -->
<script>

var filename = 0
</script>

<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery.min.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery-ui.1.7.2.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/touch.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery.editnibble.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery.hotkeys-0.7.9.min.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery.autosize.js"); ?>

<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/jquery.tipsy.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/wireframe/wireframe.js"); ?>

<?php a_include_javascripts() ?>
<?php include_partial('API/globalArquematics') ?>
<script type="text/javascript">


$(document).ready(function()
{
    if (filename!=0) {InstantShowSaveButton();
        MakeCanvasDrag();
        makeDrag($('.wire'));
        makeDrag($('.aa'));
    }
});

</script>
</body>
</html>