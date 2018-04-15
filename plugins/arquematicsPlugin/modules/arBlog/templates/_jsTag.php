<script type="text/javascript" >
     
$(document).ready(function()
{

    $('#tagsPillbox').pillbox(
    {
      onClickDelete: function (itenVal)
      {
         var $optionsList = $('#a_blog_post_tags_list option'),
             $option = $optionsList.filter(function () { 
                    return ($(this).val() === itenVal); 
            }),
            $optionsSelected = $('#tagsPillbox ul li');       
            
         $option.removeAttr('selected');
        
         if ($optionsSelected
            && ($optionsSelected.length > 0))
         {
            var i = $optionsSelected.length;
            var tagsText = '';
            $optionsSelected.each(function() 
            {
                i--;
                if (i > 0)
                {
                    tagsText += $(this).text() + ', ';   
                }
                else 
                {
                    tagsText += $(this).text();  
                }
            });
               
            $('#a-blog-item-tags').text(tagsText);  
         }
         else
         {
           $('#a-blog-item-tags').text('');
           $('#tagsPillbox').hide();
           $('#a-blog-item-tag-list').hide();   
         }
      }  
    });
    
     
     $('#ui-add-tag').fieldeditor({
        sendClose: false,
        onAddContent: function (e, dataJSON)
        {
            if ($.isPlainObject(dataJSON) 
                && (dataJSON.status === 200)
                && (typeof dataJSON.values.id !== 'undefined')
                && (typeof typeof dataJSON.values.name !== 'undefined' !== 'undefined'))
            {
              var $option = $('option',$('#a_blog_post_tags_list')).filter(function () { 
                  return $(this).val() === dataJSON.values.id; 
              });

              if ((!$option) || ($option.length === 0))
              {
                $('#tagsPillbox ul').append('<li class="status-warning" data-value="' + dataJSON.values.id +'">' + dataJSON.values.name + '</li>');
                  
                $('#a_blog_post_tags_list')
                    .append($("<option></option>")
                        .attr("value",dataJSON.values.id)
                        .attr("selected", 'selected')
                        .text(dataJSON.values.name));
                
              }
              else if ($option && (!$option.attr("selected")))
              {
                   $option.attr("selected","selected");
                   
                    $('#tagsPillbox ul').append('<li class="status-warning" data-value="' + dataJSON.values.id +'">' + dataJSON.values.name + '</li>');  
              }
              
              var $tagListOpt = $('#a_blog_post_tags_list option:selected');
              
              if ($tagListOpt.length > 0)
              {
                  
                  $('#tagsPillbox')
                        .removeClass('hide')
                        .show();
                  $('#a-blog-item-tag-list')
                    .removeClass('hide')
                    .show();
                  
                  var i = $tagListOpt.length;
                  var tagsText = '';
                  $tagListOpt.each(function() 
                  {
                    i--;
                    if (i > 0)
                    {
                        tagsText += $(this).text() + ', ';   
                    }
                    else 
                    {
                        tagsText += $(this).text();  
                    }
                   });
               
                   $('#a-blog-item-tags').text(tagsText);    
              }
              else
              {
                 $('#tagsPillbox').hide();
                 $('#a-blog-item-tag-list').hide();     
              }
            }
            
            $('#a_blog_tag_search_tag').val(false);
            
        },
        resetInput: true,
        hideTextControl:false});
        
        $('#a_blog_tag_name').tagdeditor({
            form_search_tag: '#form-tag-create'
        });
});

</script>