/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * depende de https://github.com/HPNeo/gmaps
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 */
(function ($, arquematics) {
    

$.widget( "arquematics.geotag", {
	options: {
            
            content:            '#content',
            
            content_item_message_content: '.message-user-content',
            
            form:               '#map-form',
            
            input_control_formated_address:     '#locate_formated_address',
            input_control_locate_hash:          '#locate_hash',
            
            input_control_message:      '#wallMessage_message',
            
            //geotags salvados en la sesion
            sessionGeoTagsSave: [],
            
            locate_latitude:        '#locate_latitude',
            locate_longitude:       '#locate_longitude',
            locate_south_west_lat:  '#locate_south_west_lat',
            locate_south_west_lng:  '#locate_south_west_lng',
            locate_north_east_lng:  '#locate_north_east_lng',
            locate_north_east_lat:  '#locate_north_east_lat',
            locate_zoom:            '#locate_zoom',
            
            map_template:           '#map-template',
            
            container_buttons_wall:  '#buttons-wall-container',
            map_template_preview:   '#map-template-preview',
            
            cancel_url:             '',
            wall_url:               '/wall',
            cmd_cancel:             '.cmd-remove-map',
            cmd_geotag:             '.cmd-geotag',
            
            map_height:             250,
            map_control:            null,
            
            send_button:            '#map-send',
            preview_cancel:         '#map-preview-cancel',
            
            preview_container:      '#map-preview-container',
            
            tool_handler:           '#arMap',
            tool_container:         '#map-control',
            tool_focus:             '#locate_formated_address'
        },
        waitForContent: false,
        geo: false,
        
        //ultimo tag 
        lastTag: null,
         //resetea el contenido del control
        reset: function() 
        {
           $(this.options.preview_container).empty();
           //$(this.options.preview_container).hide();
           //borra los elementos de sesion
           this.options.sessionGeoTagsSave = [];
        },
        _create: function() 
        {
           
            this.geo = new google.maps.Geocoder();
            
            this._initControlHandlers();
            
           
        },
        _initControlHandlers: function () 
        {
           var that = this,
               options = this.options,
               $controlInput = $(options.input_control_message);
       
           $('body').bind('changeScrollContent', function (e, $node, url)
           {
             
              $node.find(options.content_item_message_content)
                   .each(function() {
                
                that._addContentLinks($(this), url);
                
                that._addContentHandlers($(this));
              });
               
              $node.find('.map-item')
                .each(function() {
                    that._addMapHandlers($(this)); 
                });
           });
       
           var backspaceKey =   8,
               deleteKey =      46,
               spaceKey  =      32;
         
          
           $controlInput.on("keyup", function(e){
               var code = e.keyCode ? e.keyCode : e.which;
               

               if ((!that.waitForContent) && 
                     ((code === backspaceKey) 
                   || (code === deleteKey)
                   || (code === spaceKey)
                   || (code === 0)))
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
           
           //guarda el ultimo tag
           $('body').bind("resetWallContent",function (e, url){
              var geotag = arquematics.utils.getParamFromUrl(url, 'geotag');
              
              if (geotag && ($.type(geotag) === "string"))
              {
                that.lastTag = geotag;      
              }
              else
              {
                that.lastTag = null;      
              }
            });
        },
        
        hasContent: function()
        {
          return (this.options.sessionGeoTagsSave.length > 0);   
        },
        
        _callNextLogic: function (sendStatus)
        {
           if (sendStatus.endIndex <= 0)
           {
               if (sendStatus.previewMode && (this.options.sessionGeoTagsSave.length > 0))
               {
                    $(this.options.tool_container).removeClass('hide');
                    $(this.options.tool_container).show();
               }
               else if (!sendStatus.previewMode)
               {
                 arquematics.wall.context.next();      
               }
               //desbloquea las acciones
               this.waitForContent = false;
           }
        },
                
        _addContentLinks: function ($node, url)
        {
           var options = this.options,
               contentText = $node.html(),
               $nodeBefore = $node.clone(true),
               geotags = contentText.match(/\{([^}]+)\}/g) || [],
               geotag = arquematics.utils.getParamFromUrl(url, 'geotag');
           
           geotags = geotags.filter(function(elem, pos) {
                    return ((geotags.indexOf(elem) === pos));
                });
           
           if (geotags.length > 0)
           {
            for (var i = 0, 
                     count = geotags.length,
                     tagHash,
                     tagTextTmp,
                     tagText;
                     (i < count); i++) 
            {
              tagText = geotags[i];
              tagTextTmp = tagText.replace(/^{/,'');
              tagTextTmp = $.trim(tagTextTmp.replace(/}$/,''));

              tagHash = arquematics.utils.sha256(tagTextTmp);
              
              if (geotag === tagHash)
              {
                contentText = contentText.replace(tagText, '<a data-node-id="geotag-' + i + '" data-hash="'+ tagHash +'" class="cmd-geotag user-tag" href="' + options.wall_url + '"><i class="fa fa-map-marker"></i> ' + tagText + '  <i class="fa fa-times-circle"></i></a>');        
              } 
              else
              {
                contentText = contentText.replace(tagText, '<a data-node-id="geotag-' + i + '" data-hash="'+ tagHash +'" class="cmd-geotag user-tag" href="' + options.wall_url + '?geotag=' + tagHash + '"><i class="fa fa-map-marker"></i> ' + tagText + '  <i class="fa fa-times-circle hide"></i></a>');        
              }
            }
            
            $node.html(contentText);
            
            //clona eventos anteriores del nodo
            $nodeBefore.find('.user-tag').each(function(){
              var $currentBefore = $(this).clone(true);
              $node.find('[data-node-id="'+ $currentBefore.data('node-id') + '"]').replaceWith($currentBefore);
            });
            
           }
        },
                
        _onTagClick: function ($node)
        {
            var url = $node.attr("href"),
                options = this.options;
            
            $('body').trigger('resetWallContent', url);
            
            $('#content-wrapper').scrollToMe();

            $('body').trigger('activateControl', this.controlName());
        },        
        
        _renderMapPreview: function(item) 
        {
             var options = this.options;
             
             var data = $(options.map_template_preview).tmpl( item )
                                .appendTo( options.preview_container );  
                  
             return data;
         },
         
         _addContentHandlers: function ($node)
         {
              var that = this,
                 options = this.options;
         
             $node.find(options.cmd_geotag).each(function() {
                    var $tagGeoNode = $(this);
                
                    $tagGeoNode.on("click",function(e){
                        e.preventDefault();
                   
                        that._onTagClick($(this));
                    });
             });
         },
         _addMapHandlers: function ($node)
         {
             var that = this,
                 options = this.options;
         
                $node.find(options.cmd_cancel).click(function (e) 
                {
                    e.preventDefault();
                
                    var $cmd = $(e.currentTarget);
                
                    var mapId = $cmd.data('map-id');
                
                    that.waitForContent = true; 
                
                    $.ajax({
                        type: "POST",
                        url: options.cancel_url + mapId,
                        datatype: "json",
                        data: '',
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status === 200)
                            {
                                var $parentContent = $cmd.parent();
                                
                                //borra de la lista de guardados
                                var index = options.sessionGeoTagsSave.indexOf($parentContent.data('hash'));
                                
                                if (index >= 0)
                                {
                                   options.sessionGeoTagsSave.splice(index, 1);     
                                }
                                
                                $parentContent.remove();
                            }
                            
                            that.waitForContent = false; 
                        }
                    });
                });
                
                
                arquematics.geomap.renderStaticMap($node);
                     
         },
         
        controlName: function()
        {
          return 'map';
        },
                
        removeHash: function(tags)
        {
            var ret = [];
            var tag = '';
            for (var i = 0, count = tags.length;(i < count); i++) 
            {
              tag = tags[i].replace(/^{/,'');
              tag = $.trim(tag.replace(/}$/,''));
              ret.push(tag);          
            }
            return ret;
        },
                
        sendContent: function (previewMode)
        {
           
           var that = this,
               options = this.options,
               searchText = $.trim($(options.input_control_message).val()),
               // geotags
               geotags = searchText.match(/\{([^}]+)\}/g);

            if ((geotags !==null) && (geotags.length > 0))
            {
                geotags = this.removeHash(geotags);
                
                var uniqueGeotags = geotags.filter(function(elem, pos) {
                    return ((geotags.indexOf(elem) === pos) 
                        && ($.inArray(arquematics.utils.sha256(elem), options.sessionGeoTagsSave) < 0));
                });

                if (uniqueGeotags.length > 0)
                {
                     var sendStatus = {
                       previewMode:  previewMode,
                       endIndex: uniqueGeotags.length  
                     };
                     
                     for (var i = 0, count = uniqueGeotags.length;(i < count); i++) {

                        this._geoLocate(uniqueGeotags[i], sendStatus);
                        
                     }
                }
                else
                {
                  that._callNextLogic({previewMode: previewMode, endIndex: 0 });      
                }
            }
            else
            {
                that._callNextLogic({previewMode: previewMode, endIndex: 0 });            
            }    
        },
                
        /**
         * 
         * @param {string} addressText
         * @param {object} sendStatus
         */
        _geoLocate: function (addressText, sendStatus)
        {
            
          var that = this,
              options = this.options;
      
          this.geo.geocode( {'address': addressText }, function(results, status)
          {
            
            if (typeof(results[0]) !== "undefined")
            {
                if (status === 'OK')
                {
                    that._setDataForm(results[0], addressText);
                   
                    $.ajaxQueue({
                        type: "POST",
                        url: $(options.form).attr('action'),
                        cache: false,
                        data: $(options.form).find('input, select, textarea').serialize(),
                        dataType: "json",
                        success: function(dataJSON) 
                        {
                            if ((sendStatus.previewMode) && (dataJSON.status === 200)
                                && ($.inArray(dataJSON.values.hash, options.sessionGeoTagsSave) < 0))
                            {
                               var $node = that._renderMapPreview(dataJSON.values);
                               
                               $('body').trigger('changeControlContent', [$node] );
                               
                               that._addMapHandlers($node);
                               
                               options.sessionGeoTagsSave.push(dataJSON.values.hash);
                            }
                            else if ((dataJSON.status === 200)
                             && ($.inArray(dataJSON.values.hash, options.sessionGeoTagsSave) < 0))
                            {
                              options.sessionGeoTagsSave.push(dataJSON.values.hash);      
                            }
                            
                            sendStatus.endIndex--;
                            that._callNextLogic(sendStatus);
                         },
                         error: function() 
                         {
                                sendStatus.endIndex--;
                                that._callNextLogic(sendStatus);
                         }
                      });
                }
                else
                {
                    sendStatus.endIndex--;
                    that._callNextLogic(sendStatus);
                }
                
            }
            else
            {
                 sendStatus.endIndex--;
                 that._callNextLogic(sendStatus);
            }
          });  
        },
        
        _setDataForm: function (result, addressText)
        {
            var options = this.options;
            var controlText = $(options.input_control_message).val();
             
             controlText  = controlText.replace('{' + addressText + '}', '{' + result.formatted_address + '}');
             
             $(options.input_control_message).val(controlText);
            
             if (arquematics.crypt)
             {
               $(options.input_control_formated_address).val(arquematics.crypt.encryptMultipleKeys(result.formatted_address));
             }
             else
             {
               $(options.input_control_formated_address).val(result.formatted_address);     
             }
                    
             $(options.input_control_locate_hash).val(arquematics.utils.sha256(result.formatted_address));
             
             return result.formatted_address;
        },
        
        update: function(message) 
        {
          var that = this
            , options = this.options;
            
      
          if (message instanceof arquematics.wall.message)
          {
              var $messageContent = message.getContent();
              
              if (message.getState() === arquematics.wall.messageStatus.del)
              {
               
                  var $nodeGeoTag = $messageContent.find('.user-tag[data-hash="'+ this.lastTag + '"]').first();
               
                  if ($nodeGeoTag && ($nodeGeoTag.length > 0))
                  {
                     $('body').trigger('resetWallContent',  $nodeGeoTag.attr("href"));      
                  }
                    
              }
              else if (message.getState() === arquematics.wall.messageStatus.reset)
              {
                that.lastTag = null;     
              }
              else
              {
                  var $content = $messageContent.find(options.content_item_message_content);
            
                  this._addContentLinks($content);
                  this._addContentHandlers($content);
            
                  $messageContent.find('.map-item').each(function() {
                    that._addMapHandlers($(this)); 
                  });     
               }
          }
       
          this.reset();
	},
                
        /**
         * lista de estados disponibles
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            var ret = [];
            ret.push(new arquematics.geotag.sendMapContent());
            
            return ret;
        },
        
        _init: function()
        {
           var that = this,
               options = this.options;
          
          //espera que carge la página
          $(window).bind('pa.loaded', function (e)
          {
            $(options.preview_container).find('.map-item')
                .each(function() {
                    that._addMapHandlers($(this)); 
                });
                
            $(options.content).find(options.content_item_message_content)
                   .each(function() {

                that._addContentLinks($(this), location.search);
                
                that._addContentHandlers($(this));
            });
           
            $(options.content).find('.map-item')
              .each(function() {
                    that._addMapHandlers($(this)); 
                });
          }); 
        }
});

arquematics.geotag = {};

arquematics.geotag.sendMapContent = function () 
{
    this.name = 'sendMapContent';
    
    this.go = function (params)
    {  
        var geotag = $('#locate_formated_address').data('geotag');
        
        if (!geotag.waitForContent)
        {
            geotag.waitForContent = true; 
            geotag.sendContent(false); 
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



}(jQuery, arquematics));