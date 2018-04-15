<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?> 

<script type="text/javascript" >
     
$(document).ready(function()
{
    
     $("#a_blog_post_is_publish").bootstrapSwitch({offText:'<?php echo __('Draft',array(),'blog')  ?>',
                                                   onText: '<?php echo __('Published',array(),'blog') ?>'});
    
    
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
    
    $("#a_blog_post_allow_comments").bootstrapSwitch(
                {offText:'<?php echo __('Disabled',array(),'blog')  ?>',
                  onText: '<?php echo __('Enabled',array(),'blog') ?>'}
                );
                
          
    
    $('#ui-date').datetimer({
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
        });
       
    $('#ui-author').fieldstatus({
        onChangeContent: function (data)
            {
                $('#a-blog-author').text(data);
            }
        });
        
    
    $('#ui-date-range').datetimerange({
        format:'<?php echo aDate::getMomentDateTimeFormat($culture); ?>',
        status: '<?php echo $aBlogItem->getDateStatus(); ?>',
        text_info_undefined: '<?php echo __("Undefined start date",array(),'blog'); ?>',
        text_info_init_date: '<?php echo __("Start date",array(),'blog'); ?>',
        text_info_end_date:  '<?php echo __("End date",array(),'blog') ?>'
    });
    
   
    $('#col-publish').blogpublisher(
        {
            fieldsToSend: ['#a_blog_post_publication',
                '#a_blog_post_author_id',
                '#a_blog_post_published_at',
                '#a_blog_post_id',
                '#a_blog_post_is_publish',
                '#a_blog_post_is_save',
                '#a_blog_post_categories_list',
                '#a_blog_post_tags_list',
                '#a_blog_post_allow_comments',
                '#a_blog_post__csrf_token'],
            is_event: true,
            takeBackUrl: $('#takeBack').attr('href')
        }
    );
    
});

</script>