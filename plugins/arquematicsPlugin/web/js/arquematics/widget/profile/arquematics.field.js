/* 
 * @description actualización de un campo en la base de datos
 * 
 * @autor Javier Trigueros Martinez de los Huertos
 * 
 * @copyright Alcoor jun 2012 GPL
*/
(function ($) {

  $.fn.field = function(options) {
  

    var defaults = {
            form: '',
            formName: '',
            container: '',
            save: '',
            textSelector: false,
            cancel: ''
	};
                
    options = $.extend({}, defaults, options);
    var elem = $(this);
    
    $(options.save).click(function (){
        // not sure if you wanted this, but I thought I'd add it.
        // get an associative array of just the values.
        var values = {};
            $inputs.each(function(i, el) {
                values[el.name] = $(el).val();
            });
    });
    
    //evento cambio de estado del boton de edicion
    $('body').bind('changeProfile', function () 
    {
        $(options.container).alert('close');
    });
    
    elem.click(function () 
    {
       elem.hide();
       
       elem.after(options.form);
       
       $(options.container).alert();
       
       $(options.container).bind('closed', function () 
       {
            elem.show();
       });
      
       $(options.cancel).bind('click', function () 
       {
        $(options.container).alert('close');
       });
       
       $(options.save).bind('click', function () 
       {
            var $inputs = $(options.formName + ' input, ' + options.formName + ' textarea, ' + options.formName + ' select');
            var dataString = '';
            $inputs.each(function(i, el) {
                dataString += el.name + '=' + $(el).val() + '&';
            });
          
            //desactiva el botton
            $(options.save).button('loading');
                
                $.ajax({
                    type: "POST",
                    url:  $(options.formName).attr('action'),
                    datatype: "json",
                    data: dataString,
                    cache: false,
                    success: function(dataJSON)
                    {
                        
                        if (dataJSON.status === 200)
                        {
                          console.log('options.textSelector');
                          console.log(options.textSelector);
                          if (options.textSelector)
                          {
                            $(options.textSelector).text(dataJSON.HTML);
                          }
                          else
                          {
                            elem.text(dataJSON.HTML);
                          }

                          options.form = dataJSON.values.frm;
                          $(options.container).alert('close');
                        } 
                    },
                    error: function() 
                    { 
                      
                    }
                });
                return false;
            
       });
       
       
       $(options.focus).focus();
       
    });

    return this;
  };
  
})(jQuery);