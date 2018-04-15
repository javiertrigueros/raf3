<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>

<script type="text/javascript">
$(document).ready(function()
{
    $('#user-profile').mutualfriends({
            url_load: '<?php echo url_for('user_profile_mutual_view',$aUserProfile); ?>'
        }); 
});
</script>