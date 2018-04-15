<?php use_helper('I18N','a','ar') ?>
<?php 
        $data['form'] = get_partial('diagramEditor/formDiagram', array('form' => $form));
        $data['load'] = get_partial('diagramEditor/upload_button',array());
        $data['imageLoad'] = url_for('@diagram_simple_paint_load');
        $data['imageRes'] = '';
        $JsonData = json_encode($data,JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<?php // If this page is an admin page we don't want to present normal navigation relative to it. ?>
	<?php $page = aTools::getCurrentNonAdminPage() ?>
        <?php $root = aPageTable::retrieveBySlug('/') ?>
<head>
	<?php include_http_metas() ?>
	<?php include_metas() ?>
	<?php include_title() ?>

        <?php use_stylesheet("/arquematicsWorkflowPlugin/css/paint.css"); ?>
       
	<?php a_include_stylesheets() ?>
   
        <?php use_javascript("/arquematicsWorkflowPlugin/js/painter/file-uploader-paint.js"); ?>
        <?php use_javascript("/arquematicsWorkflowPlugin/js/painter/lang/". $culture.".js"); ?>
        <?php use_javascript("/arquematicsWorkflowPlugin/js/painter/tool.js"); ?>
        <?php use_javascript("/arquematicsWorkflowPlugin/js/painter/gui.js"); ?>
        <?php use_javascript("/arquematicsWorkflowPlugin/js/painter/init.js"); ?>
    
  </head>
<body>
	<div id="painter"></div>
        
    <?php //include_component('arChat', 'normalView') ?>
        
   <script type="text/javascript">
		var Painter=<?php echo $JsonData; ?>;
    </script>
    <?php a_include_javascripts() ?>
        
    <?php include_partial('API/globalArquematics') ?>
</body>
</html>