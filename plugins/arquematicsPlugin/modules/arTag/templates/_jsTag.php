<script type="text/javascript">
     $(document).ready(function()
     {
         
        arquematics.tag.init({
            wall_url:               '<?php echo url_for('@wall') ?>',
            cancel_url:     '<?php echo url_for('@wall_tag_cancel?id=') ?>',
            send_url:       '<?php echo url_for('@wall_tag_send') ?>'
        });
        
        arquematics.wall.subscribeTool(arquematics.tag);
     });
</script>

<?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
<!-- The template to display links available -->
<script id="template-tag" type="text/x-jquery-tmpl">
<div id="tag-control-${id}" class="cmd-tag tag-item label label-primary" 
          data-tag_url="<?php echo url_for('@wall?tag=') ?>${hash}" 
          data-count="${count}" 
          data-hash="${hash}"
          data-tag_id="${id}">
        
        <span data-encrypt-text="${encrypt_text}" class="content-text"></span>
        <i class="hide cmd-filter-tag tag-remove-circle fa fa-times-circle"></i>
        <span class="tag-counter">(${count})</span>
</div>
</script> 
<?php else: ?>
<script id="template-tag" type="text/x-jquery-tmpl">
<div id="tag-control-${id}" class="cmd-tag tag-item label label-primary" 
          data-tag_url="<?php echo url_for('@wall?tag=') ?>${hash}" 
          data-count="${count}" 
          data-hash="${hash}"
          data-tag_id="${id}">
        
        <span class="content-text">${name}</span>
        <i class="hide cmd-filter-tag tag-remove-circle fa fa-times-circle"></i>
        <span class="tag-counter">(${count})</span>
</div>
</script> 
<?php endif; ?>
