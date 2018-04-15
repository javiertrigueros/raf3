<div class="modal fade" id="cms-about-sms">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
            <?php //echo __('Alcoor',array(),'arquematics') ?>
            <?php echo __('Version',array(),'arquematics') ?>
        </h4>
      </div>
      <div id="delete-body-content" class="modal-body">
          <p>
             <?php echo __('About long',array(),'arquematics') ?> 
          </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __('Accept', array(), 'blog') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

$(document).ready(function()
 {

    $(".cms-admin-about").click(function (e) 
    {
        e.preventDefault();
        
        $('#cms-about-sms').modal('show');
       
    });
    
 });
</script>
