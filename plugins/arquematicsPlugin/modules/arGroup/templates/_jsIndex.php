<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<script type="text/javascript">
   $(document).ready(function()
   {
      
       
       $(document).userscreen({
            autocomplete_url: '<?php echo url_for('@search_users_byname_auto?username='.$aUserProfile->getUsername()); ?>',
            showOnLoad: '',
            //init page counter
            counter: 2
        });
        
         $('#content-groups').subscribers();
   });
</script>
