<script type="text/javascript" >
$(document).ready(function()
{
     $('#ui-add-category').fieldeditor({
        onAddContent: function (e, dataJSON)
        {
            console.log('dataJSON');
            console.log(dataJSON);
            if ($.isPlainObject(dataJSON) 
                && (dataJSON.status === 200))
            {
              var option = $('option',$('#a_blog_post_categories_list')).filter(function () { return $(this).val() === dataJSON.values.id; });
              
              console.log('option');
              console.log(option);
              //si no existe la opccion la crea
              if ((!option) || (option.length === 0))
              {
                $('#a_blog_post_categories_list')
                    .prepend($("<option></option>")
                        .attr("value",dataJSON.values.id)
                        .attr("selected", 'selected')
                        .text(dataJSON.values.name));
                       
                
                $('#a_blog_post_categories_list').selectpicker('refresh');
              }
            }
        },
        resetInput: true,
        hideTextControl:false});
    
        $('#a_blog_post_categories_list').on('change', function (e) {
            
            var $categoriesListOpt = $('#a_blog_post_categories_list option:selected');

           
            if ($categoriesListOpt
                && ($categoriesListOpt.length > 0))
            {
                $('#a-blog-item-categories-list')
                    .removeClass('hide')
                    .show();
                
                var i = $categoriesListOpt.length;
                var categoriesText = '';
                $categoriesListOpt.each(function() 
                {
                    i--;
                    if (i > 0)
                    {
                        categoriesText += $(this).text() + ', ';   
                    }
                    else 
                    {
                        categoriesText += $(this).text();  
                    }
                });
               
                $('#a-blog-item-categories').text(categoriesText);  
            }
            else
            {
                $('#a-blog-item-categories').text('');  
                $('#a-blog-item-categories-list').hide();      
            }
        });

        $('#a_blog_post_categories_list').selectpicker({
                    hideDisabled: true,
                    display: true,
                    afterContainer:'#category-control',
                    countSelectedText: '<?php echo __('n categories selected',array(),'blog') ?>',
                    selectedTextFormat: 'count > 3'});
     
});
</script>