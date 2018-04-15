<?php $lang = isset($lang) ? $sf_data->getRaw('lang') : 'es'; ?>
<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false; ?>

<?php if ($arDiagram): ?>
<script type="text/javascript">
   

        /*
       app.value('globals', {pass:'<?php echo $arDiagram->EncContent->getContent() ?>',
         content:'<?php echo $arDiagram->getContent() ?>',
         title:'<?php echo $arDiagram->getTitle() ?>',
         lang:'<?php echo $lang ?>'});*/
     
    
       
</script>
<?php else: ?>
<script type="text/javascript">
    
        /*
         app.value('globals', {
                           content: false,
                           title: false,
                           pass: false,
                           lang:'<?php echo $lang ?>'}); 
                       
                       */
       
</script>
<?php endif; ?>
    
