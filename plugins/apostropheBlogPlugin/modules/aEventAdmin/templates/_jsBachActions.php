<div class="modal fade" id="bach-actions-blog-delete-modal">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo __('Confirm Delete', array(), 'blog') ?></h4>
      </div>
      <div id="delete-body-content" class="modal-body">
          <p class='info-text'><?php echo __('Deleting multiple events',array(),'blog') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', array(), 'blog') ?></button>
        <button type="button" data-loading-text="<?php echo __('Deleting...', array(), 'blog') ?>" data-url="" class="btn btn-primary"><?php echo __('Accept', array(), 'blog') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

$(document).ready(function()
 {
    var isSendControl = false;
     
    $("#cmd-batch-action").click(function (e) 
    {
        e.preventDefault();
        
        var selectItem = $('#batch-action').val();
        
        if (selectItem === 'batchDelete')
        {
          var $items = $('input.a-admin-batch-checkbox:checked');
          
          if ($items.length > 0)
          {
            isSendControl = false;
            
            $("#bach-actions-blog-delete-modal div.modal-footer button.btn-primary").button('reset');
           
            $('#delete-body-content p.item-to-add').remove();
          
            $items.each(function(index ) {
                var $row = $(this).parents('.a-admin-row');
                var $itemToAdd = $('<p class="item-to-add"></p>');
                var $itemLink = $row.find('.a-column-title a').clone();
                
                $itemToAdd.append($itemLink);
                $('#delete-body-content').append($itemToAdd);
                
            });
            
            
             $("#bach-actions-blog-delete-modal").modal('show');
          }
      
        }
        else
        {
           $('#a-admin-batch-form').submit();     
        }
       
    });
    
    $("#bach-actions-blog-delete-modal div.modal-footer button.btn-primary").click(function (e) 
    {
        e.preventDefault();
        var $btn = $(this);
        
        $btn.button('loading');
       
        $('#a-admin-batch-form').submit(function(e) {
            
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function() {
                },
                error: function() {
                }
            });
        });
        
        if (!isSendControl)
        {
            isSendControl = true;
            $('#a-admin-batch-form').submit();
        }
            
        
    });
    
 });
</script>
