/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  - autosize: https://github.com/jackmoore/autosize.git
 *  - picture: jquery-picture.js
 *  
 * @param {jQuery} $
 * @param {arquematics} arquematics
 */
(function($, arquematics) {
    
arquematics.blogitem = function(options)
{
    var defOptions = {
            elem:                                   '',
            form:                                   '',
            input_control_title:                    '',
            input_control_message:                  '',
            input_control_select:                   '',
            
            content_media:       '.a-blog-item-media picture',
            update_button:      '#cmd-update-button-aBlog'};
    
     var that = this;
     
     options = this.options = $.extend({}, defOptions,options);
     
     if (options.elem === '#aBlog')
     {
        this.context = new arquematics.context(new arquematics.blogitem.sendBlogContent()); 
        
        this.tag = new arquematics.blogItemTag();
        this.context.add(new arquematics.tag.sendTagContentBlog());
     }
     else
     {
        this.context = new arquematics.context(new arquematics.blogitem.sendEventContent());
        
        this.tag = new arquematics.eventItemTag();
        this.context.add(new arquematics.tag.sendTagContentEvent());
     }
     
     function initControlHandlers()
     {              
            $(options.input_control_message).autosize();
            
            $(options.update_button).on("click", function (e) 
            {
               e.preventDefault();
               
               if (!that.context.lock)
               {
                   //bloquea todos los controles antes de nada
                   $('body').trigger('changeTabStatus', [false] );
                    
                   that.context.start();
               }
            });
            
            $('body').bind('changeScrollContent', function (e, $node)
            {
               $node.find(options.content_media).each(function() {
                var $node = $(this);
               
                $node.picture({inlineDimensions: true});        
              });
            });
      }
      
      function initDOM() 
      {
            $(options.content_media).each(function() {
                var $node = $(this);
               
                $node.picture({inlineDimensions: true});
           });
       }
      
      initControlHandlers();
      initDOM();  
};

arquematics.blogitem.prototype = {
    resetControl: function(e, that) 
    {
        var options = this.options;
        
        //$btn.button('reset');
              
        var $controlGroup = $(options.input_control_title).parent();
        $controlGroup.removeClass('error');
              
        $(options.input_control_title).val('');
        $(options.input_control_message).val('');
              
        $(options.input_control_title).focus(); 
    },
            
    resetControlError: function(e, that) 
    {
        var options = this.options;
        //,   $btn = $(options.cmd_update_button);
        
        //$btn.button('reset');
            
        var $controlGroup = $(options.input_control_title).parent();
        $controlGroup.addClass('error');
               
        $(options.input_control_title).focus();
    },
            
    /**
     * cambia el estado del boton
     * 
     * @param {type} enabled
     */
    _buttonControlStatus: function(enabled)
    {
             var $btn = $(this.options.update_button);
             
             if (enabled)
             {
               $btn.button('reset');
                //arreglo del bug de firefox
                setTimeout(function () {
                    $btn.removeClass('disabled');
                    $btn.removeAttr('disabled');
                }, 1);       
             }
             else
             {
               $btn.button('loading');     
             }
    },
            
    update: function(enableTabFuntions)
    {
        this._buttonControlStatus(enableTabFuntions);
    },
    
    getElement: function ()
    {
        return $(this.options.elem);  
    },
    controlName: function()
    {
        return 'BlogItem';
    },
    sendContent: function ()
    {
            var that = this
             ,   options = this.options
             ,   $form = $(options.form);
        
            var callBack = function(formData){
                 $.ajax({
                type: "POST",
                url: $form.attr('action'),
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                       setTimeout(function(){
                          window.location= dataJSON.values.url;
                          that.context.lock = false;
                       },20);
                       
                    }
                    else
                    {
                       that.resetControlError();
                       
                       that.context.lock = false;
                       //desbloquea los controles 
                       $('body').trigger('changeTabStatus', [true] );
                    }
                },
                statusCode: {
                    404: function() {
                       
                       that.resetControlError();
                       
                       that.context.lock = false;
                       //desbloquea los controles 
                       $('body').trigger('changeTabStatus', [true] );
                    },
                    500: function() {
                       that.resetControlError();
                       
                       that.context.lock = false;
                       //desbloquea los controles 
                       $('body').trigger('changeTabStatus', [true] );
                    }
                },
                error: function(dataJSON)
                {
                  that.resetControlError();
                  
                  that.context.lock = false;
                   //desbloquea los controles 
                   $('body').trigger('changeTabStatus', [true] );
                }
               });
            };
            
            
            
            if (arquematics.crypt)
            {
                       arquematics.utils.encryptFormAndSend(
                        $form,
                        callBack,
                        $(options.input_control_message));      
             }
             else
             {
                arquematics.utils.prepareFormAndSend(
                        $form,
                        callBack);     
             }
      }
};

arquematics.blogitem.sendBlogContent = function () 
{
       this.name = 'sendBlogContent';

       this.go = function ()
       {
           arquematics.wallBlog.sendContent();
       };
 };
 
arquematics.blogitem.sendEventContent = function () 
{
       this.name = 'sendEventContent';

       this.go = function ()
       {
         arquematics.wallEvent.sendContent();  
       };
 };
 

}(jQuery, arquematics));