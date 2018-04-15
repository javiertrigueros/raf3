<script type="text/javascript">

 $(document).ready(function()
 {
      $("#upload_name").prettyfile({
                setvalue: false,
                html: '<div id="update"><?php echo __('Update Photo', null, 'profile'); ?> </div>'
	});
        
    $("#upload_name").change(function(){
        $("#profile_image")
                .empty()
                .html('<div class="image-loader"></div>');
        
        $("#form_profile_image_send").submit();
    });
    

    $('#form_profile_image_send').iframePostForm
    ({
        json : true,
        complete : function (data)
        {
            if (data.status === 200)
            {
              $("#profile_image").empty().html(data.HTML);
              $(".app-menu").attr('src', data.values.small);
            }
        }
    });
    
    $('#upload_name').show();
    
    
    $('#upload_name').css("width",  $('.image-control').width());
    $('#upload_name').css("height", $('.image-control').height());
   
    $('#upload_name').css("left",- $('.image-control').width() / 2);
    $('#upload_name').css("top", $('.image-control').height() / 3);
    
    $('.image-control').mouseover(function() {
       $('#update').css({color: "#FFFFFF",
                        background: '#FF0000',
                        cursor:'pointer'
                    });
    });
    
    $('.image-control').mouseout(function() {
       $('#update').css({
            color: "#fff",
            background: "#5cb85c"
        });
    });
    /*
    $('.image-control').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
       
        $('#upload_name').trigger('click');
    });*/
  

    
    
 });
</script>