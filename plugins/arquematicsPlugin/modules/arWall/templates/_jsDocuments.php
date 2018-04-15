<?php if (has_slot('docs-svg-enabled') || has_slot('docs-enabled')): ?>
<!-- Modal -->
<div class="modal fade" id="documents-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
            <?php echo __('New Document:', null, 'documents') ?>
            <?php echo __('types available', null, 'documents') ?>
        </h4>
      </div>
      <div class="modal-body">
        <ul class="ar-documents">
             <?php //include_slot('docs-laverna') ?>
             <?php //include_slot('docs-enabled') ?>
             <?php include_slot('docs-svg-enabled') ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __('Close', null, 'wall') ?></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">    
     $(document).ready(function()
     {
         $('#documentEditor').click(function (e) 
           {
                e.preventDefault();
                
                $('#documents-modal').modal('show');
           });
     });
</script>

<?php endif; ?>