/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * depende de:
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 */
(function ($, arquematics) {
    

$.widget( "arquematics.profile", {
	options: {
            container:              '.left-col',
            control_profile:        '#profile-wall',
            container_block:        '.rBlock',
            container_block_counter: '#block-counter',
            container_count_message: '#count-message',
            container_link_count_message: "#link-count-message",
            
            url_mutual_list: '',
            cmd_list_mutuals: '#cmd-list-mutuals'
        },
        //empieza en la segunda página
        page: 2,
        scrolling: false,
        _create: function() 
        {
          this._initControlHandlers();
          this.addNodeHandlers($(this.options.container));
	     },
        _initControlHandlers: function () 
        {
             var that = this
             ,   options = this.options;
             
             
             $('body').bind('changeScrollContent', function (e, $node, url, $nodeExtra)
             {
               if ($nodeExtra instanceof jQuery)
               {
                 that.page = 2;
                 that._renderContent($nodeExtra);
                 that.addNodeHandlers($nodeExtra);
                 
                 $("html, body").animate({ scrollTop: 0 }, "fast");
               }
             });
        },
        
        _renderContent: function ($nodeExtra)
        {
            var options = this.options;
             
             $(options.container_block).remove();
             
             $(options.container).prepend($nodeExtra);
        },
                
        addNodeHandlers: function ($node)
        {
            var that = this,
                options = this.options;

            $node.find(options.cmd_list_mutuals).on("click", function (e) 
            {
               e.preventDefault();
               if (!that.scrolling)
               {
                 that.send();      
               }
            });
        },
  
        send: function() 
        {
          var that = this
          , options = this.options
          , $nodeProfile =  $(options.control_profile);
          
         this.scrolling = true;
         
         //carga el loader
         $(options.cmd_list_mutuals + ' span').hide();
         $(options.cmd_list_mutuals + ' span.icon-plus-loader-container')
              .removeClass('hide')
              .show();
         
          
          $.ajax({
              type: "POST",
              url: $nodeProfile.data('url_mutual_list'),
              datatype: "json",
              data: 'pag=' + this.page,
              cache: false,
              success: function(dataJSON)
              {
                if (dataJSON.status === 200)
                {
                    that.page++;
                    var $node = $(dataJSON.HTML);
                    
                    $node.insertBefore(options.cmd_list_mutuals);
                    //desactiva el boton
                    if (dataJSON.values.isLastPage)
                    {
                       $(options.cmd_list_mutuals).off();
                       $(options.cmd_list_mutuals).remove();
                    }
                }
               
               $(options.cmd_list_mutuals + ' span').show();
               $(options.cmd_list_mutuals +  ' span.icon-plus-loader-container').hide();
                
                that.scrolling = false;
              },
              statusCode: {
                404: function() {
                     that.scrolling = false;
                 },
                 500: function() {
                     that.scrolling = false;     
                 }
              },
              error: function(dataJSON)
              {
                 that.scrolling = false;      
              }

            });
	},
        
        update: function(message) 
        {
          var options = this.options;
            
          if (message instanceof arquematics.wall.message)
          {
             //quita o añade 1 al marcador de mensajes
             var $nodeBlockCounter = $(options.container_block_counter)
             ,   $nodeLink =  $(options.container_link_count_message);
             
             if (message.state == arquematics.wall.messageStatus.del)
             {
                $nodeBlockCounter.data('message_count',$nodeBlockCounter.data('message_count') - 1);
             }
             else
             {
                $nodeBlockCounter.data('message_count',$nodeBlockCounter.data('message_count') + 1);
             }
             //desactiva la url si el usuario no tiene mensajes
             if ($nodeBlockCounter.data('message_count')  == 0)
             {
                $nodeLink.attr('href', '#');    
             }
             else
             {
                $nodeLink.attr('href', $nodeLink.data('url'));      
             }
             
             $(options.container_count_message).text($nodeBlockCounter.data('message_count'));
          }
	      },
        /**
         * lista de estados disponibles
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            return [];
        },
        
        _init: function()
        {
          
        }
});

}(jQuery, arquematics));