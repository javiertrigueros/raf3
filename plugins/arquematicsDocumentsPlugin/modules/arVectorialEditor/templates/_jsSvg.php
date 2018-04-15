<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : array() ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<script type="text/javascript">
    
$(document).ready(function()
{
   <?php // svgEditor.DIAGRAM_TYPE es un string ?>
    svgEditor.DIAGRAM_TYPE='<?php echo $documentType['name']; ?>';
    svgEditor.WAIT_ICON='<?php echo sfConfig::get('app_arquematics_waint_icon'); ?>';
    
    <?php if ($arDiagram): ?>
           svgEditor.autoload = true;
           svgEditor.DATA='<?php echo $arDiagram->getDataImage(); ?>';
           svgEditor.TITLE='<?php echo $arDiagram->getTitle(); ?>';
           <?php if (sfConfig::get('app_arquematics_encrypt', false)): ?>
                svgEditor.PASS = '<?php echo $arDiagram->EncContent->getContent() ?>';
           <?php else: ?>
                svgEditor.PASS = false;
           <?php endif ?>
    <?php else: ?>
           svgEditor.autoload = false;
           svgEditor.TITLE= false;
           svgEditor.DATA= false;
           svgEditor.PASS = false;
    <?php endif ?>
        svgEditor.lang = '<?php echo $culture ?>';
    
    // Run init once DOM is loaded
    $(svgEditor.init);
    //carga la imagen si es necesario
    svgEditor.arLoadDataFromString();
});  
</script>