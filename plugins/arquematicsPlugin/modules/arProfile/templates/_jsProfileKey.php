<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : false; ?> 
<div class="modal fade modal-key" id="private-key-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo __('Private Key', array(),'profile'); ?></h4>
                    </div>
                    <div id="private-key-content" class="modal-body modal-body-key">
                       
                    </div>
                    <div class="modal-footer">
                        <span class="button-friend pull-left">
                            <button id="cmd-private-key-download" class="btn btn-success" type="button">
                                <?php echo __('Download Key', array(), 'profile') ?>
                            </button>
                        </span>
                        <button id="cmd-private-key-accept" type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __('Accept', array(), 'blog') ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
        
<script type="text/javascript">
 $(document).ready(function()
 {
    $("#cmd-private-key").click(function (e) 
    {
       e.preventDefault();
       e.stopPropagation();
       
       var ecc = arquematics.utils.read(
                        '<?php echo $aUserProfile->getId() ?>-key',
                        'arquematics.ecc',
                        '<?php echo $aUserProfile->getStoreKey() ?>');
       
       $('#private-key-content').html(arquematics.utils.readKeyForUser(ecc, false));
       
       $('#private-key-modal').modal('show');
       
       return false;
    });
    
    $("#cmd-private-key-download").click(function (e) 
    {
      e.preventDefault();
      e.stopPropagation();
      
      var ecc = arquematics.utils.read(
                        '<?php echo $aUserProfile->getId() ?>-key',
                        'arquematics.ecc',
                        '<?php echo $aUserProfile->getStoreKey() ?>');
                
                //image/png;base64
      var uriContent = "data:text/plain;charset=utf-8," +
              encodeURIComponent(arquematics.utils.readKeyForUser(ecc, true));
      
      var link = document.createElement('a');
      link.href = uriContent;
      link.download = 'key.txt';
      link.target = "_back";
      
      if (document.createEvent) {
            var e = document.createEvent('MouseEvents');
            e.initEvent('click', true, true);
            link.dispatchEvent(e);
      }
      
      return false;
    });
    
});
</script>