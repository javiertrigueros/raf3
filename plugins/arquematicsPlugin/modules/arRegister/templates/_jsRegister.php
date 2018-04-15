
<script type="text/javascript">

$(document).ready(function()
 {
        $('.cmd-terms').on('click', function(e)
        {
            e.preventDefault();
            
            $('#conditions-terms').modal('show');

        });
        
        $('.cmd-close-conditions-terms').on('click', function(e)
        {
            e.preventDefault();
            
            $('#conditions-terms').modal('hide');
            
            if ($(this).hasClass('accept-extra'))
            {
                $('.terms-agree').click();     
            }
        });

        $('#register-login-form').on('submit', function(e)
        {
            e.preventDefault();
              
            var form = $(this)
           , formData = form.find('input, select, textarea').serialize();
            
             $('#cmd-send-login').button('loading');
             
             $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false})
               .done(function(dataJSON)
                {
                   //reset
                   $(".form-group").each(function() {
                        var $formGroup = $(this);
                     
                        $formGroup.removeClass('has-error');
                     
                        $formGroup.find('.help-block')
                            .text('');
                   });
                   
                   window.location.href = dataJSON.url;
                    
                })
               .fail(function(jqXHR, textStatus, errorThrown)
               {
                 var res = $.parseJSON(jqXHR.responseText);
                 
                 //reset
                 $(".form-group").each(function() {
                     var $formGroup = $(this);
                     
                     $formGroup.removeClass('has-error');
                     
                     $formGroup.find('.help-block')
                        .text('');
                 });
                  
                 if (res)
                 {
                    var keys = $.map(res, function(item, key) {
                        $('.err-' + key).removeClass('hide');
                       
                        $('.err-' + key)
                            .parents('.form-group')
                            .addClass('has-error');
                            
                        if (!$('.err-' + key).hasClass('checkbox'))
                        {
                            $('.err-' + key).text(item);       
                        }
                         
                        return key;
                    });
                  }
                  
                  $('#cmd-send-login').button('reset');
               });
        });
 });
</script>