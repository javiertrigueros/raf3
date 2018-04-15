    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 *  depende de:
 *  - tmpl
 *  - http://github.com/embedly/embedly-jquery
 *  - jquery.ajaxQueue.js
 *  
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 * @param {type} tmpl
 */
(function ($, arquematics, tmpl) {

arquematics.link =  {
	options: {
             //elementos en la session
            sessionLinks: [],
            embedlyAPI:             '',
            cancel_url:             '',
            send_url:               '',
            
            input_control_message:      '#wallMessage_message',
            
            template_link:       '#template-link',
            template_video:      '#template-video',
            
            content: '#content',
            content_preview: '#link-preview-container',
            content_video_static: '.wall-link-video-static',

            content_dinamic: '.wall-link-dinamic',
            content_item:   '.wall-link-item',
            content_image: '.link-image',
            content_image_container: '.wall-link-image-container',

            
            cmd_link_image:     '.cmd-wall-link-image',       
            cmd_cancel:         '.cmd-remove-link',
            
            tool_focus:             '#wallLink_url',
            tool_handler:           '#arLink',
            tool_container:         '#link-control',
            has_content:            false,
            show_tool:              true
	},
        
        //resetea el contenido del control y lo activa para usar
        reset: function() 
        {
           $(this.options.content_preview).empty();
           $(this.options.tool_container).hide();
           //borra los elementos de sesion
           this.options.sessionLinks = [];
        },
        resetError: function() 
        {
             
        },
            
        hasContent: function()
        {
          return (this.options.sessionLinks.length > 0);   
        },
       
        init: function (options)
        {

           this.options = $.extend({}, this.options, options);
           
           this._initControlHandlers();
        },

      findLink: function(url)
      {
               var ret = -1,
                   i = 0;
               if (this.options.sessionLinks.length > 0)
               {
                   for (i = 0; i < this.options.sessionLinks.length; i++) 
                   {
                       
                        if (this.options.sessionLinks[i] === url){
                          return i;  
                        }  
                   }
               }
               return ret;
      },
        waitForContent: false,
        /**
         * inicializa controles estaticos
         * que no necesitan agregarse a nuevos elementos
         */
        _initControlHandlers: function () 
        {
           var that = this,
               options = this.options,
               $controlInput = $(options.input_control_message);
          
           var backspaceKey =   8,
               deleteKey =      46,
               spaceKey  =      32;

          
           $controlInput.on("keyup", function(e){
               var code = (e.keyCode ? e.keyCode : e.which);
               
               if ((!that.waitForContent) && 
                     ((code === backspaceKey) 
                   || (code === deleteKey)
                   || (code === spaceKey)))
               {
                 that.waitForContent = true;
                 that.sendContent(true);     
               }
           });
           
           $controlInput.on("paste", function(e){
         
               setTimeout(function (){
                   if (!that.waitForContent)
                   {
                       that.waitForContent = true;
                       that.sendContent(true);       
                   }
               }, 100);
               
           });
           
            $('body').bind('changeScrollContent', function (e, $node)
            {
               that._initDOM($node);
            }); 

            //espera a que los menus de la página se cargen
            $(window).bind('pa.loaded', function (e, $node)
            {
                that._initDOM($(options.content_preview));
                that._initDOM($(options.content));
            }); 


        },
        _addCancelHandlers: function($node) {
    
            var options = this.options
            , that = this;
    
            $node.find(options.cmd_cancel).click( function (e) 
        {
                e.preventDefault();
             
                var $cmd = $(e.currentTarget)
                , linkId = $cmd.parent().data('link-id');
                
                 $.ajax({
                        type: "POST",
                        url: options.cancel_url + linkId,
                        datatype: "json",
                        data: '',
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status === 200)
                            {
                                var $parentContent = $cmd.parent(); 
                                $parentContent.remove();
                                
                                if ($(options.content_preview).children().length <= 0)
                                {
                                    that.reset();
                                } 
                            }
                        }
                 });
            });
             
        },
        
        controlName: function()
        {
          return 'link';
        },
        
        _callNextLogic: function (previewMode, endIndex)
        {
           if (endIndex <= 0)
           {
               if (previewMode && (this.options.sessionLinks.length > 0))
               {
                    $(this.options.tool_container).removeClass('hide');
                    $(this.options.tool_container).show();
               }
               else if (!previewMode)
               {
                 arquematics.wall.context.next();      
               }
               //desbloquea las acciones
               this.waitForContent = false;
           }
        },

        encryptData: function(dataObject)
        {
          var passKey = arquematics.utils.randomKeyString(50)
          , dataEncryptObject = arquematics.simpleCrypt.encryptObj(passKey, dataObject);
         
          dataEncryptObject['wallLink[pass]'] = arquematics.crypt.encryptMultipleKeys(passKey);
          return {
            pass:passKey,
            data: dataEncryptObject
          };
        },
  
        sendContent: function (previewMode)
        {
           var that = this
           ,  options = this.options
           ,  searchText = $.trim($(options.input_control_message).val())
           ,  currentUrl = ''
               // urls will be an array of URL matches
            , urls = searchText.match(/((?:(http|https|Http|Https|rtsp|Rtsp):\/\/(?:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,64}(?:\:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,25})?\@)?)?((?:(?:[a-zA-Z0-9][a-zA-Z0-9\-]{0,64}\.)+(?:(?:aero|arpa|asia|a[cdefgilmnoqrstuwxz])|(?:biz|b[abdefghijmnorstvwyz])|(?:cat|com|coop|c[acdfghiklmnoruvxyz])|d[ejkmoz]|(?:edu|e[cegrstu])|f[ijkmor]|(?:gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(?:info|int|i[delmnoqrst])|(?:jobs|j[emop])|k[eghimnrwyz]|l[abcikrstuvy]|(?:mil|mobi|museum|m[acdghklmnopqrstuvwxyz])|(?:name|net|n[acefgilopruz])|(?:org|om)|(?:pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortuvyz]|(?:tel|travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]))|(?:(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9])\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])))(?:\:\d{1,5})?)(\/(?:(?:[a-zA-Z0-9\;\/\?\:\@\&\=\#\~\-\.\+\!\*\'\(\)\,\_])|(?:\%[a-fA-F0-9]{2}))*)?(?:\b|$)/gi);
           
            if ((urls !==null) && (urls.length > 0))
            {
                //urls unicas
                var uniqueUrls = urls.filter(function(elem, pos) {
                    return ((urls.indexOf(elem) === pos) 
                            && (that.findLink(elem) < 0));
                });
                
                if (uniqueUrls.length > 0)
                {
                     for (var i = 0,  
                          count = uniqueUrls.length,
                          endIndex = uniqueUrls.length;(i < count); i++) 
                     {
                        currentUrl = uniqueUrls[i];
                        //agrega el enlace a la lista
                        options.sessionLinks.push(currentUrl);
                                        
                        $.embedly( currentUrl, { 
                              maxWidth: 700,
                              maxHeight: 350,
                              secure: true,
                              autoplay: true,
                              wrapElement: 'div',
                              key : options.embedlyAPI,

                        success: function(oembed, dict){
                            
                            if (oembed !== null)
                            {       
                               
                                var oemtype = (oembed.type)?oembed.type:''
                                , embedlyData = { 'wallLink[oembed]': (oembed.html) ? arquematics.codec.Base64.encode(decodeURIComponent(oembed.html)): '',
                                                  'wallLink[title]': (oembed.title)?oembed.title:'',
                                                  'wallLink[thumb]': (oembed.thumbnail_url) ? oembed.thumbnail_url : '',
                                                  'wallLink[description]': (oembed.description) ? oembed.description : '',
                                                  'wallLink[provider]': (oembed.provider_name) ?  oembed.provider_name  : '',
                                                  'wallLink[url]': currentUrl
                                            }
                                , sendataAndPass
                                , sendData
                                , pass;


                                //encripta los datos si esta activa la criptografía
                                
                                if (arquematics.crypt)
                                {
                                   sendataAndPass = that.encryptData(embedlyData);
                                   sendData = sendataAndPass.data;
                                }
                                else
                                {
                                  sendData = embedlyData;
                                }

                                //el tipo de dato no esta encriptado
                                sendData['wallLink[oembedtype]'] = oemtype;
                                //tokem _csrf_token
                                sendData['wallLink[_csrf_token]'] = $("#wallLink__csrf_token").val();

                                $.ajaxQueue({
                                    type: "POST",
                                    url: options.send_url,
                                    cache: false,
                                    data: sendData,
                                    dataType: "json",
                                    success: function(dataJSON) 
                                    {
                                      
                                      if ((previewMode) && (dataJSON.status === 200))
                                      {
                                        var $contentNode = $(dataJSON.HTML);
                                        $(options.content_preview).prepend($contentNode);

                                        $contentNode.data('content',sendataAndPass.pass);

                                        $contentNode.show();
                           
                                        setTimeout($.proxy(function() {
                                          $.when(that.prepareContent($contentNode)) 
                                            .then(function (){
                                              that.addNodeHandlers($contentNode);
                                          });
                                        }, this), 2); 
                                        
                                      }
                                      
                                      endIndex--;
                                      
                                      that._callNextLogic(previewMode, endIndex);
                                    },
                                    error: function() 
                                    {
                                       endIndex--;
                                       that._callNextLogic(previewMode, endIndex);
                                    }
                                });
        
                            }
                            else
                            {
                              endIndex--;
                              that._callNextLogic(previewMode, endIndex);      
                            }
                            
                        },
                        error: function(node, dict)
                        {
                           endIndex--;
                           that._callNextLogic(previewMode, endIndex); 
                        }
                    });
                            
                  }
                
                }
                else
                {
                   that._callNextLogic(previewMode, 0);      
                }
            }
            else
            {
                that._callNextLogic(previewMode, 0);            
            }
            
        },
        
         /**
         * decodifica el contenido y agrega el contenido
         * al navegador
         *
         * @param {jquery} $node :
         */
         prepareContent: function($node)
         {
            var that = this
            , options = this.options
            , d = $.Deferred()
            
            ,renderImage = function($node, decryptedData)
            {
              var di = $.Deferred()
              , $imageContainer = $node.find(options.content_image_container);
             
              $.when(arquematics.graphics.getByDataOrURL(decryptedData.thumb, $imageContainer.width() )) 
                  .then(function (img){
              
                    var $imageNode = $(img).addClass('link-image link-video-image');
                    //para que guarde la imagen proporcion
                    $imageNode.attr('width','100%');
                    $imageNode.attr('height','auto');

                    $imageContainer.append($imageNode);

                    di.resolve();
              });

              return di;
            }
            , renderContent = function($nodeItem, data)
            {
              var $nodeContent;
              if (($nodeItem.data('oembedtype')  === 'rich')
                || ($nodeItem.data('oembedtype') === 'video'))
              {
                $nodeContent = $(options.template_video)
                                .tmpl($.extend({}, data, {preview: $node.data('preview')})); 
              }
              else
              {
                $nodeContent = $(options.template_link)
                              .tmpl($.extend({}, data, {preview: $node.data('preview')})); 
              }

              $nodeItem.empty();
              $nodeItem.append($nodeContent);
            };

            if ($node.hasClass(options.content_item.replace(/^\.+/, "")))
            {
              var decryptedData = arquematics.simpleCrypt.decryptObj(
                    $node.data('content'), 
                    {
                      url:$node.data('url'),
                      description:$node.data('description'),
                      thumb:$node.data('thumb'),
                      title:$node.data('title'),
                      provider:$node.data('provider'),
                      oembed:$node.data('oembed')
                    });
              //pone los datos
              $node.data('dec-content',decryptedData);
              
              renderContent($node, decryptedData);
              //agrega la imagen con el tamaño 
              //adecuado para el contenedor

              if (decryptedData && decryptedData.thumb)
              {
                return renderImage($node, decryptedData);
              }
              else d.resolve();
            }
            else
            {
              var itemIndex = $node.find(options.content_item).length;
              
              $node.find(options.content_item).each(function() {
                var $nodeItem = $(this);
               
                var decryptedData = arquematics.simpleCrypt.decryptObj(
                                      $nodeItem.data('content'), 
                                      {
                                        url:$nodeItem.data('url'),
                                        description:$nodeItem.data('description'),
                                        thumb:$nodeItem.data('thumb'),
                                        title:$nodeItem.data('title'),
                                        provider:$nodeItem.data('provider'),
                                        oembed:$nodeItem.data('oembed')
                                      });
                 //pone los datos
                $nodeItem.data('dec-content',decryptedData);

                renderContent($nodeItem,decryptedData);

                if (decryptedData && decryptedData.thumb)
                {
                  $.when(renderImage($nodeItem, decryptedData)) 
                    .then(function (){
                      itemIndex--; 
                      if (itemIndex === 0)
                      {
                        d.resolve();
                      }
                    });
                }
                else
                {
                  itemIndex--; 
                  if (itemIndex === 0)
                  {
                    d.resolve();
                  }
                }
               
              });
            }
          
          return d; 
         },

        _parseFrame: function($node)
        {
            var options = this.options
            , data = $node.data('dec-content')
            , $dinamicContent = $node.find(options.content_dinamic)
            , frameString = arquematics.codec.Base64.toString(data.oembed)
            , width
            , height
            , RegHeightExPattern = /height="(\d+)"/
            , RegWidthExPattern = /width="(\d+)"/
            , matches;
            
            matches = frameString.match(RegHeightExPattern);

            if (matches && (matches.length > 0))
           {
              height = matches[1];
            
              matches = frameString.match(RegWidthExPattern);
            
              if (matches && (matches.length > 0))
              {
                width = matches[1];
                //tiene las dos medidas "width y height" 
                // y ajustamos el frame al ancho de su contenedor 
                var proPorcionalSize =  arquematics.graphics.getProportionalResize(width, height, $dinamicContent.width(), window.screen.height * 0.5);
                
                frameString = frameString.replace(/width="(\d+)/,"width=" + proPorcionalSize.width);
                frameString = frameString.replace(/height="(\d+)/,"height=" + proPorcionalSize.height);
              }     
            }

            return frameString; 
        },
        
        addNodeHandlers: function ($node){
            var that = this;
            var options = this.options;
          
            $node.find(options.cmd_link_image).click( function (e) 
            {
                e.preventDefault();
                                
                var $cmd = $(e.currentTarget)
                    , $item = $cmd.parents(options.content_item)
                    , data = $item.data('dec-content')
                    , frameString = arquematics.codec.Base64.toString(data.oembed)
                    , $dinamicContent = $item.find(options.content_dinamic);
                    
                //ocultar imagen y texto de imagen
                $cmd.parents(options.content_video_static).hide();
 
                $dinamicContent.removeClass('hide');
                $dinamicContent.show();
                $dinamicContent.animate({'backgroundColor':'#ffff'},200);
                
                var $frameNode = $(frameString);

                //se trata de un dispositivo movil
                if ($dinamicContent.width() <= 300)
                {
                   $frameNode.attr('width','100%');
                   $frameNode.attr('height', '200px');     
                }
                else
                {
                   $frameNode.attr('width','100%');
                   $frameNode.attr('height', '370px');     
                }

                $dinamicContent.append($frameNode);
           });
           
           this._addCancelHandlers($node);
        },
        
        /**
         * el control tiene contenido esperando ser procesado
         * 
         * @return <boolean>: true tiene contenido
         */
        _hasContent: function (item) {
           return (item && (item.length > 0));
        },
         
        _validateURL: function(data){
          var RegExPattern = /^((http|https|ftp):\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
          return RegExPattern.test(data);
        },
        /**
         * el control tiene contenido que puede ser válido
         * @return <boolean>: true tiene contenido valido
         */
        validate: function(){
            var item = $.trim(this.element.val());

            return (this._hasContent(item) && this._validateURL(item));
        },
       
        update: function(message) 
        {
          var that = this;
         
          if (message instanceof arquematics.wall.message)
          {
              if (message.getState() === arquematics.wall.messageStatus.ready)
              {
                var $nodeItem = message.getContent();

                $.when(that.prepareContent($nodeItem)) 
                    .then(function (){
                       that.addNodeHandlers($nodeItem);
                });
              }
          }
          //resetea el control
          this.reset();
	},
        /**
         * lista de estados disponibles para ejecutar
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            var ret = [];
            ret.push(new arquematics.link.sendLinkContent());

            return ret;
        },
                    
        _initDOM: function($node)
        {
            var that = this;
            
             $.when(this.prepareContent($node))
                .then(function (){
                        that.addNodeHandlers($node);
                });
        }
};

arquematics.link.sendLinkContent = function () 
{
    this.name = 'sendLinkContent';

    this.go = function (params)
    {
        if (!arquematics.link.waitForContent)
        {
          arquematics.link.sendContent(false);      
        }
        else
        {
          //espera 1/2 segundo y vuelve a intentar
          setTimeout($.proxy(function() {
            this.go(params);
          }, this), 500);    
        }
        
    };
};

}(jQuery, arquematics, tmpl));