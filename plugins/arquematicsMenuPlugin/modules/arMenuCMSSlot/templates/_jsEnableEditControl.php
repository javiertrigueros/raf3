<?php $id = isset($id) ? $sf_data->getRaw('id') : false; ?>
<script type="text/javascript" >
        $(document).ready(function() {
            $('#form-menu-edit-<?php echo $id ?>').show();
            $('#a-slot-edit-<?php echo $id ?>').html('<span class="icon"></span><?php echo __('Change title',null,'configure') ?>');
            
            $('#cmd-edit-menu-<?php echo $id ?>').on( "click", function(e) {
                $('#form-menu-edit-<?php echo $id ?>').submit();
                return false;
            });
         });
</script>
