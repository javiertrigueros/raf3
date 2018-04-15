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

/**
 * Usage:
 *
 * From JavaScript, use:
 *     $(<select>).ReverseText({minlength: <M>, maxlength: <N>});
 *     where:
 *       <select> is the DOM node selector, e.g. "p"
 *       <M> is the minimum length of string to reverse (optional)
 *       <N> is the maximum length of string to reverse (optional)
 */

(function($) {

	// jQuery plugin definition
	$.fn.profileField = function(options) {

                 var defaults = {
                    //url que cambia el documento
                    url : ''
                  };
        
                  options = $.extend({}, defaults, options);
                  
		// merge default and user parameters
		params = $.extend( {minlength: 0, maxlength: 99999}, params);

		// traverse all nodes
		this.each(function() {

			// express a single node as a jQuery object
			var $t = $(this);

			// find text
			var origText = $t.text(), newText = '';

			// text length within defined limits?
			if (origText.length >= params.minlength &&  origText.length <= params.maxlength) {

				// reverse text
				for (var i = origText.length-1; i >= 0; i--) newText += origText.substr(i, 1);
				$t.text(newText);

			}

		});

		// allow jQuery chaining
		return this;
	};

})(jQuery);