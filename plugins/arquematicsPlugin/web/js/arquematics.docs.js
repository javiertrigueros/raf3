/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 */
$.widget( "arquematics.docs", {
	options: {
            preview_cancel: '#doc-preview-cancel',
            preview_container: '#doc-preview',
            tool_handler: '#arDoc',
            tool_container: '#doc-control',
            tool_focus: '#wallMessage_message',
            has_content: false,
            show_tool: false,
            culture: 'es',
            
            //resetea el contenido del control y lo activa para usar
            resetControl: function(e, that) 
            {
               $(that.options.preview_container).remove();
               
               $(that.options.tool_focus).focus();
            },
            
            changeContent: function() {
             
               
            }
	},
        _setOption: function( key, value ) {
		this.options[ key ] = value;
		this._update();
	},
        _create: function() {
                this._super();
		this._update();
                this._initEventHandlers();
                
                this._trigger( "complete", null, { value: 100 } );
	},
                
        
        _initCancelHandlers: function() {
            var options = this.options;
            
            this._on($(this.options.preview_cancel), {
                click: function (e) {
                    e.preventDefault();
                    
                    //desactiva los eventos del botton
                    var btn = $(e.currentTarget);
                    btn.off();
                  
                    var that = this;
                    
                    $.ajax({
                        type: "POST",
                        url: this.options.cancel_url,
                        datatype: "json",
                        data: '',
                        cache: false,
                        success: function(dataJSON)
        {
                            if (dataJSON.status === 200)
                            {
                                $(options.preview_container).remove();
           
                                that._trigger('resetControl', e, that);
                            }
                            else
                            {
                                that._trigger('resetControl',null, that);
                            }
	},
                        error: function(node, dict)
                        {
                           that._trigger('resetControl',null, that); 
                        }
                    });
                }
            }); 
        },
        _initEventHandlers: function () {
           this._initCancelHandlers();
        },
        
        _update: function() {
	
	},
        getElement: function ()
        {
          return this.element;  
        },
        controlName: function()
        {
          return 'docs';
        },
        update: function() 
        {
          var options = this.options;
          
          if (options.show_tool)
          {
              if (options.wall.getStatus === 'wall')
              {
                options.wall.sendContent();
              } 
          }
	},
        //resetea el contenido del control
        reset: function() 
        {
            $(this.options.preview_container).remove();
	},
        /**
         * ha cambiado el contenido de la pagina
         * @param node
         */
        change: function (node)
        {
            
        },
        init: function(node) 
        {
           
	}
});

