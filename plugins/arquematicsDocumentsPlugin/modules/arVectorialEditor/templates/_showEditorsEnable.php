<?php $editors = isset($editors) ? $sf_data->getRaw('editors') : array();?>

<?php //use_stylesheet("/arquematicsWorkflowPlugin/css/diagram-component.css"); ?>
   
<?php foreach ($editors as $editor): ?>
    <a href='<?php echo url_for('@diagram_new?type='.$editor['id']); ?>' class='diagram-tool <?php echo $editor['name']; ?>'><?php echo $editor['name']; ?></a>
<?php endforeach; ?>




