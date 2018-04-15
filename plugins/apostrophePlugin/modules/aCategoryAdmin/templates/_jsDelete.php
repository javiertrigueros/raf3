<?php $object = isset($object) ? $sf_data->getRaw('object') : false; ?>

<div class="modal fade" id="delete-modal-<?php echo $object->getId() ?>">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo __('Confirm Delete', array(), 'blog') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo __('You want to delete category "%item%"?', array('%item%' => $object->getName()), 'apostrophe')  ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', array(), 'blog') ?></button>
        <button type="button" data-loading-text="<?php echo __('Deleting...', array(), 'blog') ?>" data-url="<?php echo url_for('aCategoryAdmin/index').'/'.$object->getId() ?>" class="btn btn-primary"><?php echo __('Accept', array(), 'blog') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if ($object): ?>
<script type="text/javascript">

$(document).ready(function()
 {
     
    $("#delete-<?php echo $object->getId() ?>").click(function (e) 
    {
        e.preventDefault();
        
        $("#delete-modal-<?php echo $object->getId() ?>").modal('show');
        
    });
     
    $("#delete-modal-<?php echo $object->getId() ?> div.modal-footer button.btn-primary").click(function (e) 
    {
         
        var f = document.createElement('form'),
            $btn = $(this);
            
        $btn.button('loading');
         
         f.style.display = 'none';
         this.parentNode.appendChild(f);
         f.method = 'post';
         f.action = $(this).data('url');
         var m = document.createElement('input');
         m.setAttribute('type', 'hidden');
         m.setAttribute('name', 'sf_method');
         m.setAttribute('value', 'delete');
         f.appendChild(m);
         var m = document.createElement('input');
         m.setAttribute('type', 'hidden');
         m.setAttribute('name', '_csrf_token');
         
         <?php 
         // CSRF protection
         $form = new BaseForm();
         $getCSRFToken = $form->getCSRFToken();
         ?>
                     
         m.setAttribute('value', '<?php echo $getCSRFToken ?>');
         f.appendChild(m);
         
         f.submit(); 
        
         return false;
        
    });
     
 });
</script>
<?php endif; ?>
