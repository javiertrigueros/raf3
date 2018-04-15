
<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : null ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/animation.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/svg-edit/fontello/css/fontello.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/js/oryx/lib/ext-2.0.2/resources/css/ext-all.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/oryx/lib/ext-2.0.2/resources/css/xtheme-gray.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/oryx/css/theme_norm.css"); ?>
    
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/app.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/oryx/oryx.css"); ?>


<?php use_javascript("/apostrophePlugin/js/jquery-1.8.3.js"); ?>
<?php use_javascript("/apostrophePlugin/js/plugins/jquery-ui/ui/jquery-ui.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php include_partial('arWall/encrypt', array(
        'sections' => array(),
        'aUserProfile' => $aUserProfile))?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/svgOptimiser/svg.data.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/svgOptimiser/svg.optimize.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/svgOptimiser/svg.interface.js"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/oryx/i18n/".$culture.".js"); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/oryx/arquematics.oryx.min.js", "", array("raw_name" => true)); ?>


<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/lib/prototype-1.5.1.js", "", array("raw_name" => true)); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/lib/path_parser.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/lib/ext-2.0.2/adapter/ext/ext-base.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/lib/ext-2.0.2/ext-all.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/lib/ext-2.0.2/color-field.js", "", array("raw_name" => true)); ?>

<?php // oryx editor language files ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/i18n/".$culture.".js"); ?>

<?php /* :TODO: errores con la compresion de archivos */ ?> 
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core1.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core2.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core3.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core4.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core5.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core6.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core7.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core8.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core9.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core10.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core11.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/oryx.core12.js", "", array("raw_name" => true)); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/profiles/bpmn2.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/BPMN11.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/BPMN.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/CreateProcessVariant.js"); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/profiles/bpmn3.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ShapeRepository.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/PropertyWindow.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ComplexListField.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ComplexTextField.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/CanvasResize.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/CanvasResizeButton.js"); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/profiles/bpmn4.js", "", array("raw_name" => true)); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/DragDropResize.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/SelectedRect.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/GridLine.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Resizer.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Core/Command/Move.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/RenameShapes.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Undo.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Arrangement.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Wireframe.js"); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/profiles/bpmn5.js", "", array("raw_name" => true)); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/scripts/plugins/wireframe.js", "", array("raw_name" => true)); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Grouping.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ShapeHighlighting.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/HighlightingSelectedShapes.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/DragDocker.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/DockerCreation.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/SelectionFrame.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ShapeHighlighting.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/HighlightingSelectedShapes.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Overlay.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Edit.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/KeysMove.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/File.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Save.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ContainerLayouter.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/EdgeLayouter.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Toolbar.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/ShapeMenuPlugin.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/Plugins/Loading.js"); ?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/oryx/profiles/bpmn6.js", "", array("raw_name" => true)); ?>


<div data-keyboard="false" data-backdrop="static" id="simple-text-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog modal-vertical-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cmd-cancel-box" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 id="modal-title" class="modal-title"></h2>
      </div>
      <div class="modal-body">
           <input type="text" id="input_box_label" class="form-control" name="input_box_label" autocomplete="off"><span class="help-inline help-block" id="new_list_name_help"></span>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default cmd-cancel-box"  type="submit"><?php echo __('cancel', null, 'profile'); ?></button>
        <button class="btn btn-primary cmd-accept-box"  type="submit"><?php echo __('Accept', null, 'profile'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal-load modal-load-fix">
    <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>


<?php include_partial('arVectorialEditor/jsDiagram',  array(
    'documentType' => $documentType,
    'form'  => $form,
    'arDiagram' => $arDiagram)) ?>

<?php slot('global-head-search','')?>
<?php slot('global-head','') ?>

<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed mmc dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>

