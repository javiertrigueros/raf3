/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 */

(function($, arquematics) {
    

$.widget( "arquematics.svgdoc", {
	options: {
            oryxDocTypes: ['bpmn','epc', 'uml', 'umlsequence', 'umlusecase', 'wireframe'],

            container_modal: '#documents',
            
            cmd_cancel: '.cmd-remove-document-vectorial,.cmd-remove-document',
            
            //el titulo se precesa igual
            //da lo mismo que sea un document-note o document-vectorial
            content_item: '.document-vectorial, .document-note',
            content: '.document-vectorial-content, .document-content',
            content_text: '.file-text-content',
            content_image: '.image-data',
            
            gallery_node: '.vectorialDocuments',
            
            content_preview: '#document-preview-container',
            
            blueimp_gallery: "#blueimp-gallery",
            
            has_content: false,
            show_tool: false,
            
            maxHeight: 250,
            
            waitForContent: false,
            
            //resetea el contenido del control y lo activa para usar
            resetControl: function(e, that) 
            {
              
            }
	},
                
        _create: function() {
            this._initEventHandlers();   
	},
       
        _initEventHandlers: function () {
           var options = this.options,
               that = this,
               $node = false;
           
            $(options.content_preview).find(options.cmd_cancel)
                .click(function (e) 
              {
                  e.preventDefault();
                    
                   var $cmd = $(e.currentTarget),
                       $parentContent = $cmd.parent(),
                       $preview =  $(options.content_preview),
                       docId = $cmd.data('document-id'),
                       docType = $cmd.data('type-name'),
                       docGuid = $cmd.data('guid'),
                       data = {
                            fromSession: true,
                            docGuid: docGuid,
                            id: docId,
                            type: docType
                        };
                       
                     $.ajax({
                            type: "DELETE",
                            url: options.delete_url + docGuid,
                            datatype: "json",
                            data: data,
                            cache: false,
                            beforeSend: function( xhr ) {
                                //xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                                $parentContent.animate({'backgroundColor':'#fb6c6c'},300);
                            }
                           })
                           .done(function( dataJSON ) {
                               $parentContent.remove();
                               
                               //oculta el control de previsualizacion
                               if ($preview.children().length <= 0)
                               {
                                 that.reset();   
                               }
                            })
                            .fail(function() {
                               console.log('content_preview sesion delete error'); 
                            });
              });
           
            $('body').bind('changeScrollContent', function (e, $node)
            {
               if ($node instanceof $)
               {
                 var $items = $node.find(options.content_item);
            
                 $items.each(function() {
                    that._prepareNode($(this));    
                 });
               }
            });
        },
        
        getElement: function ()
        {
          return this.element;  
        },
        controlName: function()
        {
          return 'svgdoc';
        },
        
        /**
         * lista de estados a ejecutar
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            var ret = [];
          
            return ret;
        },
        //resetea el contenido del control
        reset: function() 
        {
           //si se ha reseteado, ya no puede tener contenido en la
           //sesion
           this.options.has_content = false;
           
           $(this.options.content_preview).empty();
           $(this.element).hide();
	},

        _decodeNode: function ($node)
        {
           var options = this.options
             ,  $nodeContent = $node.find(options.content)
             ,  $nodeText = $node.find(options.content_text); 
         
          if (arquematics.crypt)
          {
             var  title = arquematics.simpleCrypt.decryptBase64($nodeContent.data('content'),$nodeContent.data('title'));
             
             $nodeContent.data('title',title);
             $nodeText.text(title);
             
             $nodeContent.attr('title',  title);
          }
        },
        
        _prepareNode: function($node)
        {
             this._decodeNode($node);
        },
        
        hasContent: function()
        {
           return this.options.has_content;
        },
             
        update: function(message) 
        {
          var that = this
          , options = this.options;
          
          if (message instanceof arquematics.wall.message)
          {
              var $node = message.getContent();
              
              if (message.getState() === arquematics.wall.messageStatus.ready)
              {
                var $items = $node.find(options.content_item);
            
                $items.each(function() {
                    that._prepareNode($(this));    
                });
              }
          }
           
          this.reset();
	},
        
        _init: function() 
        {
          var that = this
          ,   options = this.options;
          
          $(options.content_item).each(function()
          {
                var $node = $(this);
                that._decodeNode($node);
           });
	}
});

}(jQuery, arquematics));