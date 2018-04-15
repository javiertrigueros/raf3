<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>

<script type="text/javascript">
    $(document).ready(function()
    {
        
        <?php if ($aUserProfileFilter &&  $aUserProfile 
                && ($aUserProfileFilter->getId() !== $aUserProfile->getId())): ?>
         $(document).infinite({
                url : '<?php echo url_for('@wall?userid='.$aUserProfileFilter->getId()) ?>',
                initPage: 2,
                trigger: 60,
                showOnLoad: '.wall-loader'
         });
        <?php else: ?>
         $(document).infinite({
                url : '<?php echo url_for('@wall') ?>',
                initPage: 2,
                trigger: 60,
                showOnLoad: '.wall-loader'
         });
        <?php endif; ?>
        
        arquematics.wall.init(
                { url_delete_comment: '<?php echo url_for('@wall_comment_delete?id=') ?>',
                  url_delete_message: '<?php echo url_for('@wall_message_delete?id=') ?>'
                });
        
        
        arquematics.tab.subscribeTab(arquematics.wall);
        
    });
</script>