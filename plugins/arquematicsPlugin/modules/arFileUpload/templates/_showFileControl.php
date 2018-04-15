<?php $uploads = isset($uploads) ? $sf_data->getRaw('uploads') : array(); ?>

<?php $uploadsGuiIds = isset($uploadsGuiIds) ? $sf_data->getRaw('uploadsGuiIds') : array(); ?>
<?php $userid = isset($userid) ? $sf_data->getRaw('userid') : null; ?>

<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>

<?php use_stylesheet("/arquematicsPlugin/css/jquery.fileupload-ui.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/load-image.min.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/canvas-to-blob.min.js"); ?> 

<?php use_javascript("/arquematicsPlugin/js/jquery.iframe-transport.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.fileupload.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.fileupload-fp.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.fileupload-ui.js"); ?>

<?php include_js_call('arFileUpload/jsFileControl', array(
    'uploads' => $uploads,
    'uploadsGuiIds' => $uploadsGuiIds,
    'showTool' => $showTool,
    'hasContent' => $hasContent,
    'userid' => $userid)) ?>

<div  id="file-control" class="border hide">
    <form id="fileupload" action="<?php echo url_for('@wall_file_send') ?>" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <!-- The global progress information -->
            <div class="span10 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
            
            <div class="span11">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    
                    <span><?php echo __('Add files...',array(),'wall') ?></span>
                    <?php echo $form; ?>
                    <input type="file" name="files[]" multiple>
                </span>
               
                <button type="reset" class="btn btn-warning cancel">
                    
                    <span><?php echo __('Cancel upload',array(),'wall') ?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    
                    <span><?php echo __('Delete',array(),'wall') ?></span>
                </button>
                <input type="checkbox" class="toggle">
            </div>
           
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br>
        <!-- The table listing the files available for upload/download -->
        
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
 </div>
