<?php use_helper('I18N') ?>
<?php use_helper('a') ?>

<?php //mmirar los mensaje son incopatible con el cms ?>
<?php //use_helper('JavascriptBase') ?>
<?php //use_helper("sfFlashMessage") ?>

<?php  $hasArDiagram = isset($hasArDiagram) ? $sf_data->getRaw('hasArDiagram') : false; ?>

 <?php use_stylesheet("/arquematicsPlugin/css/fileuploader.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/css/wall.css"); ?>

 <?php use_stylesheet("/arquematicsPlugin/js/fancybox/jquery.fancybox.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/css/bootstrap.css"); ?>

 
<?php

 
 use_javascript("/arquematicsPlugin/js/fancybox/jquery.mousewheel.js");
 use_javascript("/arquematicsPlugin/js/fancybox/jquery.fancybox.js");
 

 use_javascript("/arquematicsPlugin/js/jquery.timeago.js");
 use_javascript("/arquematicsPlugin/js/locales/jquery.timeago.$culture.js");
 

 use_javascript("/arquematicsPlugin/js/bootstrap-button.js");
 use_stylesheet("/arquematicsPlugin/css/bootstrap-responsive.css"); 
?>
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome-ie7.css"); ?>
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome.css"); ?>
<?php

 use_javascript("/arquematicsPlugin/js/jquery.infinite.js");
 use_javascript("/arquematicsPlugin/js/file-uploader.js");
 use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); 
 use_javascript("/arquematicsPlugin/js/jquery.webcam.js");
 use_javascript("/arquematicsPlugin/js/jquery.color.js");
 use_javascript("/arquematicsPlugin/js/jquery.embedly.js");
 use_javascript("/arquematicsPlugin/js/jquery.wallform.js");
 
 use_javascript("/arquematicsPlugin/js/wall.webcamWall.js");
 use_javascript("/arquematicsPlugin/js/wall.js");
 
 ?>
<?php //include_javascripts() ?>

<script type="text/javascript">
    
    $(document).ready(function()
    {
        $(document).infinite({
                url : '<?php echo url_for('@wall_message_list') ?>',
                initPage: 2,
                trigger: 60,
                showOnLoad: '<div id="wall-loader"><img alt="loader" src="/arquematicsPlugin/images/loader.gif" /></div>'
        });
        
        $("span.mytime").timeago({refreshMillis:10000});
       
        <?php if ($isAuth): ?>
        
            var changeControls = function(){
                //cada 10 seg
                $("span.mytime").timeago({refreshMillis:10000});
                return false;
            };
         
         
            $('#wallMessage_message').wall({
                            wall_send_map: '<?php echo url_for("@wall_send_map?username=". $authUser->getUsername()) ?>',
                            length: '100',
                            onChange: changeControls
                        });
      
            <?php if ($hasActiveUpdateButton): ?>
                $('#wallMessage_message').focus();
                $('#wall-buttons').css("margin-top","5px");
                $('#update_button').show();
            <?php endif ?>
                         
            
      <?php endif ?>
    });
 
</script>



                
<div id="wall_container">
    
    <?php if ($isAuth): ?>
            <?php include_partial('arWall/form', 
                            array(
                                'form' => $form,
                                'formLinks' => $formLinks,
                                'diagrams' => $diagrams,
                                'imageUploads' => $imageUploads,
                                'documents' => $documents,
                                'uploads' => $uploads
                                )); ?>
    <?php endif ?>
    
    <div id="content">
    <?php if ($has_messages): ?>
        <?php include_partial('arWall/listMessages', 
            array('pager' => $pager,
                'has_messages' => $has_messages,
                'countMessages' => $countMessages,
                'profileImage' => $arProfileImage)); ?>
    <?php endif ?>
    </div>
</div>

<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>


