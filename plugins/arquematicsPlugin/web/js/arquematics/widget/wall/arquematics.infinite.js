/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias:
 *  - bootstrap-dropdown.js
 *  - bootstrap-alert.js
 */

$.widget( "arquematics.infinite", {
	options: {
           //url con el contenido
           url : '',
           //pagina de inicio
           initPage: 0,
           //donde se coloca el contenido nuevo
           content: '#content',
           //disparador en %
           trigger : 75,
           //lo que se muestra en onload
           showOnLoad: '',
           
           resetControlError: function(e, that) 
           {
               
           }
         
        },
        reset_url: '',        
        scrolling: true,
        counter: 0,
        _create: function() 
        {
            this.reset_url = this.options.url;
            this._initEventHandlers();
	},
        _update: function(){
            
        },
        
        load: function()
        {
            var that = this;
            var options = this.options;
            
            var dataString = "pag=" + this.counter;
             
            this.scrolling = true;
            this.counter++;
                    
            var $loader = $(options.showOnLoad);      
            $loader.removeClass('hide');
            $loader.show();
                    
            $.ajax({
                type: "GET",
                url: options.url,
                datatype: "json",
                data: dataString,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                        $loader.hide();
                                       
                        var $node = $(dataJSON.HTML);

                        $(options.content).append($node);
                        
                        if ((typeof dataJSON.extraHTML !== "undefined")
                            && dataJSON.extraHTML)
                        {
                          $('body').trigger('changeScrollContent', [$node, options.url, $(dataJSON.extraHTML)] );  
                        }
                        else
                        {
                          $('body').trigger('changeScrollContent', [$node, options.url] );      
                        }
                                            
                        if (dataJSON.values.isLastPage)
                        {
                            that.element.off('scroll');
                        }                    
                    }
                    else
                    {
                        //loader.remove();
                        $loader.hide();
                    }                
                    that.scrolling = false;
                    
                },
                statusCode: 
                {
                    404: function() 
                    {
                       that._trigger('resetControlError',null, that); 
                    },
                    500: function()
                    {
                       that._trigger('resetControlError',null, that);        
                    }
                 },
                 error: function(dataJSON)
                 {
                     that._trigger('resetControlError',null, that);       
                 }
            });
            
        },
        _setOption: function( key, value ) {
		this.options[ key ] = value;
		this._update();
	},
        _addScrollHandler: function () 
        {
            var that = this;
            var options = this.options;
           
            $(this.element).on('scroll', function ( e ){
                var wintop = $(window).scrollTop();
                var docheight = $(that.element).height();
                var winheight = $(window).height();
                
                if ((Math.round( wintop / ( docheight - winheight ) * 100 ) 
                > options.trigger ) &&  !(that.scrolling))
                {
                    that.load();
                }         
            });
            
        },
        _initEventHandlers: function () 
        {
           var that = this;
           var options = this.options;
           
           this._addScrollHandler();
            
           $('body').bind("resetWallContent",function (e, url){

              if (url && ($.type(url) === "string"))
              {
                options.url = $.trim(url);      
              }
              else
              {
                options.url = that.reset_url;        
              }
              
              that.counter = 1;
              $(options.content).empty();
              $(that.element).off('scroll');
              
              that._addScrollHandler();
              that.load();
            });
        },
        
        _init: function()
        {
            this.scrolling = false;
            this.counter = this.options.initPage;
        }
});