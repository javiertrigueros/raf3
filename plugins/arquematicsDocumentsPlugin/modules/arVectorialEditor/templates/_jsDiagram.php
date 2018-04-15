<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : false ?>


<script id="template-header" type="text/x-jquery-tmpl">
<div role="navigation" class="navbar navbar-inverse" id="main-navbar">
    <!-- Main menu toggle -->
    <button id="main-menu-toggle" type="button">
        <i class="navbar-icon fa fa-bars icon"></i>
        <span class="hide-menu-text">Ocultar Menú</span>
    </button>
        
    <div class="navbar-inner" id="navbar-content">
        <div id="header" class="navbar-header">
            <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>

            <button data-target="#main-navbar-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <i class="navbar-icon fa fa-bars"></i>
            </button>
        </div> 
        <div class="collapse navbar-collapse main-navbar-collapse" id="main-navbar-collapse">
            <div  class="mindmap-menu-container">
                       
                        <ul class="cmd-buttons-left orxy-diagram">
                            <ul class="buttons-group">
                                <li id='editor_undo' class='icon-undo' title='<?php echo __('Undo', array(), 'diagram-editor') ?>'>
                                    <i class="icon-ccw"></i>
                                </li>
                                <li id='editor_redo' class='icon-redo' title='<?php echo __('Redo', array(), 'diagram-editor') ?>'>
                                    <i class="icon-cw"></i>
                                </li>
                            </ul>
                            <ul class="buttons-group">
                                <li id='edit_copy' class='icon-copy' title='<?php echo __('Copy', array(), 'diagram-editor') ?>'>
                                    <i class="icon-docs"></i>
                                </li>
                                <li id='edit_cut' class='icon-cut' title='<?php echo __('Cut', array(), 'diagram-editor') ?>'>
                                    <i class="icon-scissors"></i>
                                </li>
                                <li id='edit_paste' class='icon-paste-cmd' title='<?php echo __('Paste', array(), 'diagram-editor') ?>'>
                                    <i class="icon-paste"></i>
                                </li>
                                <li id='edit_delete' class='icon-delete' title='<?php echo __('Delete', array(), 'diagram-editor') ?>'>
                                     <i class="icon-cancel"></i>
                                </li>
                            </ul>
                        </ul>

                         <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>
                        
                        <ul class="pull-right mindmap-menu-right buttons-save-group">
                            <li class="btn-group saveBtnGroup wmd-save-button">
                                <span id="editor_save" class="saveBtn btn btn-success">
                                    <i class="icon-save"></i>
                                    <span data-text="<?php echo __('Save and exit', array(), 'diagram-editor') ?>" data-text-saving="<?php echo __('Saving', array(), 'diagram-editor') ?>" class="save-btn-text"><?php echo __('Save and exit', array(), 'diagram-editor') ?></span>
                                </span>
                            </li>
                        </ul>  
            </div>
        </div>
</div>
</script>

<?php /*
<script id="template-header" type="text/x-jquery-tmpl">
<div id='header' class="ng-scope">
        <div class="tg_page_head ng-scope">
            <div class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav mindmap-menu">
                    <a href="<?php echo url_for('@wall?pag=1') ?>" class="navbar-brand" id="takeBack">
                        <span class="tg_head_logo">&nbsp;</span><span class="tg_head_logo_text ng-binding"><?php echo __('Go to Wall',array(),'adminMenu') ?></span>
                    </a>
                    <div class="mindmap-menu-container">
                            <?php include_partial('arVectorialEditor/formDiagram',  array(
                                'documentType' => $documentType,
                                'form'  => $form,
                                'arDiagram' => $arDiagram)) ?>
                    
                        <ul class="cmd-buttons-left orxy-diagram">
                            <ul class="buttons-group">
                                <li id='editor_undo' class='icon-undo' title='<?php echo __('Undo', array(), 'diagram-editor') ?>'><?php echo __('Undo', array(), 'diagram-editor') ?></li>
                                <li id='editor_redo' class='icon-redo' title='<?php echo __('Redo', array(), 'diagram-editor') ?>'><?php echo __('Redo', array(), 'diagram-editor') ?></li>
                            </ul>
                            <ul class="buttons-group">
                                <li id='edit_copy' class='icon-copy' title='<?php echo __('Copy', array(), 'diagram-editor') ?>'><?php echo __('Copy', array(), 'diagram-editor') ?></li>
                                <li id='edit_cut' class='icon-cut' title='<?php echo __('Cut', array(), 'diagram-editor') ?>'><?php echo __('Cut', array(), 'diagram-editor') ?></li>
                                <li id='edit_paste' class='icon-paste' title='<?php echo __('Paste', array(), 'diagram-editor') ?>'><?php echo __('Paste', array(), 'diagram-editor') ?></li>
                                <li id='edit_delete' class='icon-delete' title='<?php echo __('Delete', array(), 'diagram-editor') ?>'><?php echo __('Delete', array(), 'diagram-editor') ?></li>
                            </ul>
                            
                        </ul>
                        
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
           </div>
        </div>
</div>
</script>

*/ ?>

        
        <?php /*
        <ul>
            <li id='move_front' class='icon-move_front' title='"+ ORYX.I18N.Arrangement.btf +"'>" + ORYX.I18N.Arrangement.btf +"</li>
            <li id='move_back' class='icon-move_back' title='"+ ORYX.I18N.Arrangement.btb +"'>" + ORYX.I18N.Arrangement.btb + "</li>
            <li id='move_forwards' class='icon-move_forwards' title='"+ ORYX.I18N.Arrangement.bf +"'>" + ORYX.I18N.Arrangement.bf + "</li>
            <li id='move_backwards' class='icon-move_backwards' title='"+ ORYX.I18N.Arrangement.bb +"'>"+ ORYX.I18N.Arrangement.bb +"</li>
        </ul>
        
        <ul>
            <li id='aling_bottom' class='icon-aling_bottom' title='"+ ORYX.I18N.Arrangement.ab +"'>" + ORYX.I18N.Arrangement.ab +"</li>
            <li id='aling_middle' class='icon-aling_middle' title='"+ ORYX.I18N.Arrangement.am +"'>" + ORYX.I18N.Arrangement.am +"</li>
            <li id='aling_top' class='icon-aling_top' title='"+ ORYX.I18N.Arrangement.at +"'>" + ORYX.I18N.Arrangement.at +"</li>
            <li id='aling_left' class='icon-aling_left' title='"+ ORYX.I18N.Arrangement.al +"'>" + ORYX.I18N.Arrangement.al +"</li>
            <li id='aling_center' class='icon-aling_center' title='"+ ORYX.I18N.Arrangement.ac +"'>" + ORYX.I18N.Arrangement.ac +"</li>
            <li id='aling_right' class='icon-aling_right' title='"+ ORYX.I18N.Arrangement.ar +"'>" + ORYX.I18N.Arrangement.ar +"</li>
            <li id='aling_size' class='icon-aling_size' title='"+ ORYX.I18N.Arrangement.as +"'>" + ORYX.I18N.Arrangement.as +"</li>
        </ul>
        <ul>
            <li id='shape_group' class='icon-shape_group' title='"+ ORYX.I18N.Grouping.group +"'>"+ ORYX.I18N.Grouping.group +"</li>
            <li id='shape_ungroup' class='icon-shape_ungroup' title='"+ ORYX.I18N.Grouping.ungroup +"'>" + ORYX.I18N.Grouping.ungroup + "</li>
        </ul> */?>
        
<script type="text/javascript">
<?php
    // problemas con Prototype
    // Prototype.js define Array.prototype.toJSON()
    //  
    // http://stackoverflow.com/questions/710586/json-stringify-bizarreness
    // ORYX To resolve the incompatibilities,  window.JSON && window.JSON.stringify
?>
if(window.Prototype) {
    delete Object.prototype.toJSON;
    delete Array.prototype.toJSON;
    delete Hash.prototype.toJSON;
    delete String.prototype.toJSON;
}
        if(!ORYX) var ORYX = {};
        if(!ORYX.CONFIG) ORYX.CONFIG = {};
        ORYX.CONFIG.WEB_URL = '<?php echo url_for('@homepage') ?>';
        ORYX.CONFIG.ROOT_PATH = '/arquematicsDocumentsPlugin/js/oryx';
        ORYX.CONFIG.PLUGINS_CONFIG  = '/arquematicsDocumentsPlugin/js/oryx/profiles/default.xml';
        ORYX.CONFIG.SSET='stencilsets<?php echo $documentType['extra']; ?>';
        ORYX.CONFIG.DIAGRAM_TYPE='<?php echo $documentType['name'] ?>';
        ORYX.CONFIG.WAIT_ICON='<?php echo sfConfig::get('app_arquematics_waint_icon') ?>';
        ORYX.CONFIG.HOME_LINK='<?php echo url_for('@wall') ?>';
        ORYX.CONFIG.HOME_TEXT='<?php echo __('Go to Wall',array(),'adminMenu') ?>';
        ORYX.CONFIG.SSEXTS=[];
        ORYX.CONFIG.REDIR='<?php echo url_for('@wall')?>';
        //id de la plantilla header
        ORYX.CONFIG.HEADER = 'template-header'
        //ORYX.Plugins = false;
        //ORYX.Plugins.Loading = false;
        ORYX.CONFIG.DISABLE_GRADIENT = true;
        
        <?php if ($arDiagram): ?>
           ORYX.CONFIG.autoload = true;
           ORYX.CONFIG.SAVE='<?php echo url_for('@diagram_update?guid='.$arDiagram->getGuid().'&name='.$documentType['name'])?>';
           ORYX.CONFIG.DATA='<?php echo $arDiagram->getContent(); ?>';
           <?php if (sfConfig::get('app_arquematics_encrypt', false)): ?>
            ORYX.CONFIG.PASS = '<?php echo $arDiagram->EncContent->getContent() ?>';
           <?php else: ?>
            ORYX.CONFIG.PASS = false;
           <?php endif ?>
        <?php else: ?>
           ORYX.CONFIG.autoload = false;
           ORYX.CONFIG.SAVE='<?php echo url_for('@diagram_create?name='.$documentType['name'])?>';
           ORYX.CONFIG.DATA = false;
           ORYX.CONFIG.PASS = false;
        <?php endif ?>
    
    
</script>