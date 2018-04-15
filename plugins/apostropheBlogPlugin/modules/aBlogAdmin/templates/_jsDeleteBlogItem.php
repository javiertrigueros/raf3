<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php $aBlogItemUrl = url_for('@ar_blog_post_edit?page_back=adminBlog&id='.$aBlogItem->getId()) ?>

<div class="modal fade" id="delete-modal-<?php echo $aBlogItem->getId() ?>">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo __('Confirm Delete', array(), 'blog') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo __('You want to delete post %item%?', array('%item%' => '<a href="'.$aBlogItemUrl.'">'.$aBlogItem->title.'</a>'), 'blog')  ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', array(), 'blog') ?></button>
        <button type="button" data-loading-text="<?php echo __('Deleting...', array(), 'blog') ?>" data-url="<?php echo url_for('@ar_blog_post_delete?id='.$aBlogItem->getId()) ?>" class="btn btn-primary"><?php echo __('Accept', array(), 'blog') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if ($aBlogItem): ?>
<script type="text/javascript">

$(document).ready(function()
 {
    $("#delete-<?php echo $aBlogItem->getId() ?>").click(function (e) 
    {
        e.preventDefault();
        
        $("#delete-modal-<?php echo $aBlogItem->getId() ?>").modal('show');
        
    });
 
    
    $("#delete-modal-<?php echo $aBlogItem->getId() ?> div.modal-footer button.btn-primary").click(function (e) 
    {
        e.preventDefault();
    
         var f = document.createElement('form');
         f.style.display = 'none';
         this.parentNode.appendChild(f);
         f.method = 'post';
         f.action = $(this).data('url');
         /*
         var m = document.createElement('input');
         m.setAttribute('type', 'hidden');
         m.setAttribute('name', 'sf_method');
         m.setAttribute('value', 'delete');
         f.appendChild(m);*/
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
