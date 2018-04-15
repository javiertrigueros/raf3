<?php $arComment = isset($arComment) ? $sf_data->getRaw('arComment') : false; ?>
<?php if ($arComment): ?>
<?php $arCommentUrl =url_for('@ar_comment_delete?id='.$arComment->getId()) ?>

<div class="modal fade" id="delete-comment-modal-<?php echo $arComment->getId() ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
            <?php echo __('Confirm Delete', array(), 'blog') ?>
            <?php echo __('You want to delete comment?', null, 'blog')  ?>
        </h4>
      </div>
      <div class="modal-body row-fluid">
          <div class="span4" style="padding-right: 10px;width: auto;border-right: 1px solid #DDDDDD;float:left;">
              <?php include_partial('arCommentAdmin/author', array('ar_comment' => $arComment)) ?>
          </div>
          <div class="span7">
               <?php echo nl2br2($arComment->getComment()); ?> 
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo __('Cancel', array(), 'blog') ?>
        </button>
        <button type="button" data-loading-text="<?php echo __('Deleting...', array(), 'blog') ?>" data-url="<?php echo $arCommentUrl ?>" class="btn btn-primary">
            <?php echo __('Accept', array(), 'blog') ?>
        </button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

$(document).ready(function()
 {
     
    $("#delete-comment-modal-<?php echo $arComment->getId() ?> div.modal-footer button.btn-primary").click(function (e) 
    {
        var f = document.createElement('form'),
            $btn = $(this);
            
        $btn.button('loading');
            
        f.style.display = 'none';
        
        this.parentNode.appendChild(f);
        f.method = 'POST';
        f.action = $(this).data('url');
        
       
        var m = document.createElement('input');
        m.setAttribute('type', 'hidden');
        m.setAttribute('name', '_csrf_token');
        m.setAttribute('value', $('#_csrf_token').val());
        f.appendChild(m);
        
        
        f.submit();
      
        return false;
        
    });
     
 });
</script>
<?php endif; ?>
