/* 
 * @description funciones del control de documentos
 * 
 * @autor Javier Trigueros Martinez de los Huertos
 * 
 * @copyright Arquematics Nov 2012
*/
(function ($) {

  $.fn.title = function(options) {
  
    
        var defaults = {
            //url del para cambiar de titulo el documento
            url : ''
	};
                
        options = $.extend({}, defaults, options);
                
        var docTitle = "";
        
        $('.doc-title').click(
            function() 
            {
                $('#title-modal').modal('show');
                $("#doc_title").focus();
                return false;
            }
        );
            
        $('#form_rename_doc').submit(
            function () {
                $('.accept-rename').trigger('click');
                return false;
            });
            
        $('.cancel-rename').click(
            function() 
            { 
              $('#title-modal').modal('hide');
              $("#doc_title").val(docTitle);
              return false;  
            }
        );
        
        $('.close').click(
            function() 
            {
                $('.cancel-rename').trigger('click');
                return false;  
            }
        );
        
            
        $('#title-modal').on('show', function () {
            // do something…
            //guardar el valor del elemento
            docTitle = $("#doc_title").val() ;
        });
            
        $('.accept-rename').click(
            function() 
            {   
                var dataString = 'doc[title]=' + $("#doc_title").val()
                    + '&doc[id]=' + $("#doc_id").val()
                    + '&doc[_csrf_token]=' + $("#doc__csrf_token").val();
                
                //desactiva el botton
                $('.accept').button('loading');
                
                $.ajax({
                    type: "POST",
                    url: options.url,
                    datatype: "json",
                    data: dataString,
                    cache: false,
                    success: function(dataJSON)
                    {
                        if (dataJSON.status == 200)
                        {
                            /*
                            $.each(dataJSON.values, function(key, element) {
                                alert('key: ' + key + '\n' + 'value: ' + element);
                            });
                            */
                           $('.doc-title').text(dataJSON.HTML);

                           //$.address.value(dataJSON.values.url); 
                           
                        }
                        $('.accept').button('reset');
                        $('#title-modal').modal('hide');
                        
                    },
                    error: function() 
                    { 
                        $('.accept').button('reset');
                        $('#title-modal').modal('hide');
                    }
                });
                return false;
            }
        );
   
    return this;
  };
  
})(jQuery);