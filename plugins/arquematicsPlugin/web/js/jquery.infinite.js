( function( $ ) {
	$.fn.infinite = function( options ) {
	
		var defaults = {
                        //url del listado
			url : '',
                        //pagina de inicio
                        initPage: 0,
                        //donde se coloca el contenido nuevo
                        content: '#content',
                        //disparador en %
			trigger : 75,
                        //lo que se muestra en onload
			showOnLoad: ''
		};
                
                
                defaults = $.extend({}, defaults, options);
                
                var scrolling = false;
                var counter = defaults.initPage;
                
                var wintop = $(window).scrollTop();
                var docheight = $(this).height();
                var winheight = $(window).height();
                var element = $(this);
                
                /**
                 * carga una pagina ajax. Si es la ultima
                 * pagina deja de ejecutar peticiones
                 */
                $.fn.infinite.loadData = function()
                {
                    var dataString = "id=" + counter;
                     
                    scrolling = true;
                    counter++;
                    
                    var loader = jQuery(defaults.showOnLoad);
                    $("#content").append(loader);
                    
                          $.ajax({
                                type: "POST",
                                url: defaults.url,
                                datatype: "json",
                                data: dataString,
                                cache: false,
                                success: function(dataJSON)
                                {
                                    if (dataJSON.status == 200)
                                    {
                                        $("#wall-loader").remove();
                                       
                                        var node = $("<div id='page" + counter + "'></div>");
                                        node.append(dataJSON.HTML);
    
                                        $(defaults.content).append(node);
                                        $('body').trigger('changeScrollContent', [node] );
                                        
                                        if (dataJSON.values.isLastPage)
                                        {
                                            element.unbind('scroll');
                                        }
                                        
                                    }
                                    else
                                    {
                                        $("#wall-loader").remove();
                                    }
                                    
                                    scrolling = false;
                                }
                            });

                };
                
                $('body').bind("resetWallContent",function (e){
                     $("#content").empty();
                     counter = 0;
                     $.fn.infinite.loadData();
                     
                });

                
		return this.each( function() {
                   
                    $(this).bind( 'scroll', function ( e ){
                         wintop = $(window).scrollTop();
                         docheight = $(this).height();
                         winheight = $(window).height();
                
                       if ((Math.round( wintop / ( docheight - winheight ) * 100 ) 
                           > defaults.trigger ) &&  !(scrolling))
                        {
                         $.fn.infinite.loadData()
                        }
                        
                        return true;
                    });
                    
		});
	}
})( jQuery );
