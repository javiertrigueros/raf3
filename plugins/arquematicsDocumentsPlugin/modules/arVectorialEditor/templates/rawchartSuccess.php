<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : null ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/animation.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_stylesheet("/arquematicsMenuPlugin/css/arAdminMenu.css"); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/raw/fontello/css/copy.css"); ?>


<?php use_stylesheet("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-bootstrap-colorpicker/css/colorpicker.css"); ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/raw/bower_components/codemirror/lib/codemirror.css"); ?> 
<?php use_stylesheet("/arquematicsDocumentsPlugin/js/vendor/nvd3/build/nv.d3.css"); ?>
<?php // <!-- nv.d3.js --> ?>
<?php use_stylesheet("/arquematicsDocumentsPlugin/css/raw/raw.css"); ?>

<?php // <!-- moment.js --> ?>
<?php //use_javascript("/arquematicsPlugin/js/components/moment/moment.js") ?>
<?php //use_javascript("/arquematicsPlugin/js/components/moment/lang/".$culture.".js") ?>

<?php use_javascript("/arquematicsPlugin/js/sjcl/sjcl.overwrite.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.core.js"); ?>
<?php include_partial('arWall/encrypt', array('lang' => $culture, 
                                            'sections' => array(),
                                            'aUserProfile' => $aUserProfile)); ?>

<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/arquematics.raw.js", "", array("raw_name" => true)); ?>
<?php // <!-- bootstrap --> ?>
<?php /*use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/bootstrap/dist/js/bootstrap.min.js", "", array("raw_name" => true)); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/bootstrap-colorpicker/js/bootstrap-colorpicker.js", "", array("raw_name" => true)); ?>
<?php // <!-- bootstrap-modal-carousel --> ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js", "", array("raw_name" => true)); ?>
<?php // <!-- d3 --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/d3/d3.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/d3-plugins/sankey/sankey.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/d3-plugins/hexbin/hexbin.js");?>
<?php // <!-- nv.d3.js --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/vendor/nvd3/build/nv.d3.js");?>

<?php // <!-- codemirror --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/codemirror/lib/codemirror.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/codemirror/addon/display/placeholder.js");?>
<?php //<!-- canvastoblob --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/canvas-toBlob.js/canvas-toBlob.js");?>
<?php //<!-- filesaver --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/FileSaver/FileSaver.js");?>
<?php //<!-- zeroclipboard --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/zeroclipboard/ZeroClipboard.js");?>
<?php //<!-- stats --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/jstat/dist/jstat.js");?>

<?php //<!-- angular --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular/angular.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-route/angular-route.min.js");?>
<?php //<!-- angular libs --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-animate/angular-animate.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-sanitize/angular-sanitize.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-strap/dist/angular-strap.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-ui/build/angular-ui.min.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-gettext/dist/angular-gettext.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/bower_components/angular-moment/angular-moment.js"); ?>

<?php //<!-- raw --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/lib/raw.js"); ?> 
<?php //<!-- app --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/js/app.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/js/services.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/js/controllers.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/js/filters.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/js/directives.js"); ?>
<?php //<!-- charts --> ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/histogram.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/barChar.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/simpleLineChart.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/pieChart.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/treemap.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/streamgraph.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/scatterPlot.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/packing.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/clusterDendrogram.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/voronoi.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/delaunay.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/alluvial.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/clusterForce.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/convexHull.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/hexagonalBinning.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/reingoldTilford.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/parallelCoordinates.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/circularDendrogram.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/smallMultiplesArea.js");?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/bumpChart.js"); */?>

<?php //use_javascript("/arquematicsDocumentsPlugin/js/raw/js/controlerEdit.js"); ?>
<?php //use_javascript("/arquematicsDocumentsPlugin/js/raw/js/controlerView.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/histogram.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/barChar.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/barCharMultiple.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/pieChart.js"); ?>
<?php use_javascript("/arquematicsDocumentsPlugin/js/raw/charts/distributionChar.js"); ?>

<?php slot('global-head-search')?>
<li class="ar-head-title">
    <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)); ?>
</li>
<?php end_slot() ?>

<?php  slot('global-head') ?>
 
<div class="navbar-inner" id="navbar-content">
<!-- Main navbar header -->
    <div class="navbar-header">
        <!-- Logo -->
        <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>
        <!-- Main navbar toggle -->
        <button data-target="#main-navbar-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
        
        
    </div> <!-- / .navbar-header -->
    <div class="collapse navbar-collapse main-navbar-collapse" id="main-navbar-collapse">
            
            <div class="right clearfix">
                <ul class="nav navbar-nav pull-right right-navbar-nav diagram-save-control">
                    <?php include_slot('global-head-search') ?>
                    
                    <li class="btn-group saveBtnGroup wmd-save-button">
                        <div class="ar-head-save">
                           <span id="editor_save" class="saveBtn btn btn-success">
                            <i class="icon-save"></i>
                            <span data-text="<?php echo __('Save and exit', array(), 'diagram-editor') ?>" data-text-saving="<?php echo __('Saving', array(), 'diagram-editor') ?>" class="save-btn-text"><?php echo __('Save and exit', array(), 'diagram-editor') ?></span>
                           </span> 
                        </div>
                    </li>
                </ul> <!-- / .navbar-nav -->
            </div> <!-- / .right -->
    </div><!-- / #main-navbar-collapse -->
</div>
<?php end_slot() ?>


<div class="modal-load modal-load-fix">
    <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>


<div ng-view class="wrap"></div>


<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('global-head-search','')?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>
