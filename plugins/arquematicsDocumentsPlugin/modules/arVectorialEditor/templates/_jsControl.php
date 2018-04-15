<script type="text/javascript">    
     $(document).ready(function()
     {
         
        var $paint = $('#document-control').svgdoc({
            delete_url: '<?php echo url_for('@laverna_doc_notes_delete?guid=') ?>',
            has_content: <?php echo ($hasContent)?'true':'false' ?>,
            show_tool: <?php echo ($showTool)?'true':'false' ?>
        });

        arquematics.wall.subscribeTool($paint.data('svgdoc'));
     });
</script>