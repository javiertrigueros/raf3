<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>

<script type="text/javascript">
$(document).ready(function(){
    arquematics.tab.init({
        element:'.control-update',
        showTabs: <?php echo ($aUserProfileFilter &&  $aUserProfile)?'false':'true'; ?>});
});
</script>