<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?> 

<script type="text/javascript" >
     
$(document).ready(function()
{
    
     $('#col-publish').blogpublisher({
            takeBackUrl: $('#takeBack').attr('href')
        });
          
    var $dateTimerControl = $('#ui-date').datetimer({
          is_save: <?php echo ($aBlogItem->getIsSave())?'true':'false'; ?>,
          is_draft: <?php echo ($aBlogItem->getIsPublish())?'false':'true'; ?>,
          format_long:'<?php echo aDate::getMomentLongDateFormat($culture); ?>',
          format:'<?php echo aDate::getMomentDateTimeFormat($culture); ?>',
          
          input_control_publication: '#a_blog_post_publication',
         
          text_info_draft: '<?php echo __('Draft',array(),'blog') ?>',
          text_info_published: '<?php echo __('Published',array(),'blog') ?>',
          text_info_scheduled: '<?php echo __('Publication scheduled',array(),'blog') ?>',
          text_status_published:'<?php echo __('Published',array(),'blog') ?>',
          text_status_scheduled:'<?php echo __('Scheduled on',array(),'blog') ?>',
          text_status_now:'<?php echo __('Publish now',array(),'blog') ?>',
          onChangeContent: function (data, isScheduled, textInfo)
          {
            $('#blog-item-info-status-text').text(textInfo);
          }
        }).data('datetimer');
        
        
        $("#a_blog_post_is_publish").bootstrapSwitch(
                {  
                    offText:'<?php echo __('Draft',array(),'blog')  ?>',
                    onText: '<?php echo __('Published',array(),'blog') ?>'}
                );
                
         $('#a_blog_post_is_publish').on('switchChange.bootstrapSwitch', function(event, state) {
            if (state)
            {
                $('#update-button').text($('#update-button').data('text'));   
            }
            else
            {
                $('#update-button').text($('#update-button').data('text-draft'));       
            }
        });
        /*
        $('#ui-status').fieldstatus({
        onChangeContent: function (data)
            {
               $('#a-blog-item-status').text('- ' + data);
           
                var $controlPublicationOption = $('#a_blog_post_publication option:selected');

                if ($controlPublicationOption && ($controlPublicationOption.length > 0))
                {
                    var isDraft = ($controlPublicationOption.val() === 'draft');
                    //cambia la option del control
                    $dateTimerControl.setOption('is_draft',isDraft);
                    
                    $('#a_blog_post_is_draft').val((isDraft)?'1':'0');
                }
                else
                {//posible error
                    $dateTimerControl.setOption('is_draft', false);
                    $('#a_blog_post_is_draft').val('0');
                }
                
                $('#blog-item-info-status-text').text($dateTimerControl.getInfoText());
            }
        });*/
        $('#ui-author').fieldstatus({
        onChangeContent: function (data)
            {
                $('#a-blog-author').text(data);
            }
        });
        
        $("#a_blog_post_allow_comments").bootstrapSwitch({   
                    offText:'<?php echo __('Disabled',array(),'blog')  ?>',
                    onText: '<?php echo __('Enabled',array(),'blog') ?>'});
});

</script>