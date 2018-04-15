
<script type="text/javascript">

$(document).ready(function()
 {
        arquematics.login.init({ send_private_key: <?php echo (sfConfig::get('app_arquematics_send_private_key',false))?'true':'false' ?>});
        //inicia el script
        arquematics.login.context.startOAuth(); 
 });
</script>