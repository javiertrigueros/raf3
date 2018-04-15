<?php use_helper('I18N','a','ar') ?>
<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : null ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php /*original
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/common.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/app.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/Aristo/jquery-ui-1.8.7.custom.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/minicolors/jquery.miniColors.css"); ?>
 */?> 

<?php //use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>
<?php //use_stylesheet("/arquematicsPlugin/css/vendor/fontello/css/fontello.css"); ?>
<?php //use_stylesheet("/arquematicsPlugin/css/vendor/fontello/css/animation.css"); ?>
<?php //use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>
<?php //use_stylesheet("/arquematicsPlugin/css/app.css"); ?>

<?php //use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/common.css"); ?>

    
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/fontello/css/copy-embedded.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/app.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/app.css"); ?>
<?php //use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/Aristo/jquery-ui-1.8.7.custom.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/mindmaps/minicolors/jquery.miniColors.css"); ?>

<?php //use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/dragscrollable.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/jquery.hotkeys.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/jquery.mousewheel.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/jquery.minicolors.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/jquery.tmpl.js"); ?>
  <?php //use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/swfobject.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/downloadify.min.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/libs/events.js"); ?>

  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/MindMaps.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/i18n/". $culture."/Command.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/CommandRegistry.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Action.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Util.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Point.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Document.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/MindMap.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Node.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/NodeMap.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/UndoManager.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/UndoController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ClipboardController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ZoomController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ShortcutController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/HelpController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/FloatPanel.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Navigator.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Inspector.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ToolBar.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/StatusBar.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/CanvasDrawingTools.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/CanvasView.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/CanvasPresenter.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ApplicationController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/MindMapModel.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/NewDocument.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/OpenDocument.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/SaveDocument.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/MainViewController.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Storage.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Event.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/Notification.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/StaticCanvas.js"); ?>
  <?php //interesantes pero no uso la funcionalidad ?>
  <?php //use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/PrintController.js"); ?>
  <?php //use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/ExportMap.js"); ?>
  <?php //use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/AutoSaveController.js"); ?>
  <?php //use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/FilePicker.js"); ?>
  <?php use_javascript("/arquematicsDocumentsPlugin/js/mindmaps/SaveController.js"); ?>
 
 <?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
 <?php include_partial('arWall/encrypt', array(
        'sections' => array(),
        'aUserProfile' => $aUserProfile))?>

<?php /*
 <div class="mmbar" id="header">
  
      <div id="icon-menu" class="buttons-left">
       
           <ul>
            <li class="navi">
                <span class="back">&nbsp;</span>
        
                <?php echo link_to(__('Back',array(),'profile'),'@wall',array('id' => 'home_wall')); ?>
        
            </li>
          </ul> 
          <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>

          <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>
          
          
          <ul class="control-save buttons-right"></ul>

      </div>
  
   </div> */?>

<?php slot('global-head-search','')?>

<?php slot('global-head')?>
    <!-- Navbar -->
    <div class="ng-scope">
        <div class="tg_page_head ng-scope">
            <div  class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav mindmap-menu">

                    <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>
                    <div class="mindmap-menu-container">
                        <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>
                    
                        <ul class="cmd-buttons-left"></ul>
                        
                        <ul class="pull-right mindmap-menu-right">
                            <li class="btn-group saveBtnGroup wmd-save-button">
                                <span id="editor_save" class="saveBtn btn btn-success">
                                    <i class="icon-save"></i>
                                    <span data-text="<?php echo __('Save and exit', array(), 'diagram-editor') ?>" data-text-saving="<?php echo __('Saving', array(), 'diagram-editor') ?>" class="save-btn-text"><?php echo __('Save and exit', array(), 'diagram-editor') ?></span>
                                </span>
                            </li>
                        </ul>
                        
                    </div>
                    
                </div>
                <?php include_slot('global-head-extra') ?>
            </div>
        </div>
    </div>
    <!--/Navbar-->
<?php end_slot() ?>

  <div id="container">
    <div id="topbar"></div>
      
    <div id="canvas-container">
      <div id="drawing-area" class="no-select"></div>
    </div>
    <div id="bottombar">
     
      <div id="statusbar">
        <div class="buttons buttons-left buttons-small buttons-less-padding"></div>
      </div>
    </div>
    
  </div>


 <?php include_js_call('arVectorialEditor/jsMindMap', array(
     'documentType' => $documentType,
     'arDiagram' => $arDiagram)) ?>
