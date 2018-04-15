 /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 *  depende de:
 *  - tmpl
 *  
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 */
(function ($, arquematics) {

arquematics.tag =  {
	options: {
            wall_url:                   '/wall',
            cancel_url:                 '',
   
            form:                       '#tag-form',
            input_control_tag_name:     '#ar_tag_name',
            input_control_tag_hash:     '#ar_tag_hash',
            
            input_control_message:      '#wallMessage_message',
            
            content:                    '#content',
            content_item_base:          '#tag-control-',
            
            content_user_tag:           '.user-tag',
            content_control_tag:        '.tag-item',
            
            content_item_counter:       '.tag-counter',
            content_item_remove:        '.tag-remove-circle',
            
            content_item_message_content: '.message-user-content, .message-user-content-blog',
            
            content_control:            '#tag-control-list',
            content_col:                '#tag-control-nav',
            
            template_tag:               '#template-tag',
            
            cmd_tag:                    '.cmd-tag',

            tool_handler:           '#arTag',
            tool_container:         '#tag-control'
	},
        
        //resetea el contenido del control y lo activa para usar
        reset: function() 
        {
         
          
        },
        resetError: function() 
        {
             
        },
            
        changeContent: function() {
             
               
        },
        
       
       init: function (options)
       {

           this.options = $.extend({}, this.options, options);
           
           this.sendManager = new arquematics.tag.SendManager(this);
           
           this._initControlHandlers();
           
           this._initDOM($(this.options.content), location.search);
	},
       
        lastTag: null,
        /**
         * inicializa controles estaticos
         * que no necesitan agregarse a nuevos elementos
         */
        _initControlHandlers: function () 
        {
           var that = this,
               options = this.options;
       
           $('body').bind('changeScrollContent', function (e, $node, url)
           {
               that._initDOM($node, url);
           });
           
           $('body').bind('activateControl', function (e, controlName)
           {
               if (controlName !== that.controlName())
               {
                   that._resetActiveTagControls();    
               }
           });
           
           //guarda el ultimo tag
           $('body').bind("resetWallContent",function (e, url)
           {
              var tag = arquematics.utils.getParamFromUrl(url, 'tag');
              
              if (tag && ($.type(tag) === "string"))
              {
                that.lastTag = tag;      
              }
              else
              {
                that.lastTag = null;      
              }
            });
           
           //handlers columna derecha
           this._addNodeHandlers($(options.content_control));
           
        },
           
        _renderLink: function(item) {
             var options = this.options;

             return $(options.template_tag).tmpl( item );
        },
        
        controlName: function()
        {
          return 'tags';
        },
        /**
         * contexto de ejecución
         * 
         * @return {context} object
         */       
        getContext: function()
        {
           return arquematics.wall.context;
        },
             
       update: function(message) 
        {   
          var options = this.options
            , that = this
            , $nodeTagLink
            , sessionTagsSave = this.sendManager.sessionTagsSave;
          
          
          for (var i = 0,  count = sessionTagsSave.length; i < count; i++) 
          {
              if (sessionTagsSave[i].count >= 2)
              {
                  
                 $(options.content_col).show();
                 $(options.content_control).show();
                 
                 $(options.content_item_base + sessionTagsSave[i].id)
                         .find(options.content_item_counter)
                         .text('(' + sessionTagsSave[i].count + ')');
                         
                 $(options.content_item_base + sessionTagsSave[i].id).data('count', sessionTagsSave[i].count);
              }
              else
              {
                $nodeTagLink = this._renderLink(sessionTagsSave[i]);
                
                $nodeTagLink.data('count', sessionTagsSave[i].count);
                $nodeTagLink.appendTo(options.content_control);
                
                $(options.content_col).show();
                $(options.content_control).show();
                
                // desencripta el contenido con el trigger
                $('body').trigger('changeControlContent', [$nodeTagLink] );
                
                $nodeTagLink.on("click",function(e){
                    e.preventDefault();
                   
                   that._onTagClick($(this));
                });
                      
              }    
          }
          
          if (message instanceof arquematics.wall.message)
          {
              var $contentDOM = message.getContent();
              
              if (message.getState() === arquematics.wall.messageStatus.del)
              {
                 //se ha borrado un mensaje
                 that._removeTagDOMControls($contentDOM);
                 
                 var $nodeTag = $(options.content_control_tag + '[data-hash="' + this.lastTag +'"]')
                 //resetea el contenido del muro si procede
                 if ($nodeTag && ($nodeTag.length <= 0))
                 {
                     $('body').trigger('resetWallContent',  options.wall_url);      
                 }
                 else if (this.lastTag && (this.lastTag != null))
                 {
                   $('body').trigger('resetWallContent',  options.wall_url + '?tag=' + this.lastTag);            
                 }
              }
              else if (message.getState() === arquematics.wall.messageStatus.reset)
              {
                that.lastTag = null;
                this._resetActiveTagControls();
              }
              else
              {
                //agrega Handlers al nuevo contenido
                //del muro
                this._initDOM($contentDOM);     
               }
          }
          //resetea sendManager
          this.sendManager.reset();
	},
                
        _removeTagDOMControls: function($contentDOM)
        {
            
             var options = this.options
             ,   $messageTags = $contentDOM.find(options.content_user_tag);
             
             //recorre la lista de tags en el mensaje
             $messageTags.each(function() {
                var $controlNode = $(options.content_control_tag + '[data-hash="' + $(this).data('hash') +'"]')
                , $counter = $controlNode.find(options.content_item_counter);

                $controlNode.data('count', $controlNode.data('count') - 1); 
               
                if ($controlNode.data('count') <= 0)
                {
                  $controlNode.remove();      
                }
                else
                {
                  //-1 el número de tags en el control
                  $counter.text('(' + $controlNode.data('count') + ')');      
                }
                 
             });    
        },
        
        /**
         * 
         * si en la sesion activa esta marcado un
         * tag lo tomamos de aqui
         * 
         * @returns {string| null} : null si no tenemos activo ningun tag
         */
        _getControlActiveTag: function ()
        {
           var $nodeTagControlActive = $(this.options.content_control + ' li.active')
           ,   ret = null;
           
           if ($nodeTagControlActive && ($nodeTagControlActive.length > 0))
           {
              ret = $nodeTagControlActive.data('hash');    
           }
   
           return ret;
        },
        
        _addContentLinks: function ($node, url)
        {
           var options = this.options,
               $nodeBefore = $node.clone(true),
               contentText = $node.html(),
               tags = contentText.match(/#\w+/g) || [],
               //parametro tag actual que se
               // ha pasado por la url
               // o esta activo en la sesion
               tag = arquematics.utils.getParamFromUrl(url, 'tag')
                    || this._getControlActiveTag();
         
           
            tags = tags.filter(function(elem, pos) {
                    return ((tags.indexOf(elem) === pos));
                });
         
           if (tags.length > 0)
           {
            for (var i = 0, 
                     count = tags.length,
                     tagHash,
                     tagText;
                     (i < count); i++) 
            {
              tagText = tags[i];
              tagHash = arquematics.utils.sha256(tagText.replace(/#/,''));
              
              if (tag === tagHash)
              {
                contentText = contentText.replace(new RegExp(tagText, 'g'), '<a data-node-id="tag-' + i + '" data-hash="'+ tagHash +'" class="'+  options.cmd_tag.replace('\.','') + ' user-tag" href="' + options.wall_url + '">' + tagText + '  <i class="fa fa-times-circle"></span></i></a>');   
              }
              else 
              {
                contentText = contentText.replace(new RegExp(tagText, 'g'), '<a data-node-id="tag-' + i + '" data-hash="'+ tagHash +'" class="'+  options.cmd_tag.replace('\.','') + ' user-tag" href="' + options.wall_url + '?tag=' + tagHash + '">' + tagText + '  <i class="fa fa-times-circle hide"></i></a>'); 
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
                
        _resetActiveTagControls: function ()
        {
            var options = this.options;
             
            $(options.content_control + ' li').removeClass('active');
            
            $(options.content_control + ' li').each(function() {
                var $node = $(this);
                
                $node.find(options.content_item_counter)
                        .removeClass('hide')
                        .show();
                $node.find(options.content_item_remove).hide();
                $node.find('a').attr("href",$node.data('tag_url'));          
            });
        },
                
        _onTagClick: function ($node)
        {
            var hash = $node.data('hash'),
                url = $node.hasClass('user-tag')?$node.attr("href"):$node.data('tag_url'),
                options = this.options,
                $nodeContentControl = $(this.options.content_control),
                activeTagHash = $nodeContentControl.data('active_tag'),
                $controlNode = $(options.content_control + ' div[data-hash="'+ hash + '"]');
            
            
            //borra totas las etiquetas activas
            $(options.content_control + ' div').removeClass('active');
            $(options.content_control).find(options.content_item_counter)
                                            .removeClass('hide')
                                            .show();
            $(options.content_control).find(options.content_item_remove).removeClass('hide');
            $(options.content_control).find(options.content_item_remove).hide();
            
            if (activeTagHash && (activeTagHash === $node.data('hash')))
            {
               url = $nodeContentControl.data('wall_index_url'); 
               $nodeContentControl.data('active_tag',false);
               
               $controlNode.find(options.content_item_counter)
                            .removeClass('hide')
                            .show();
               $controlNode.find(options.content_item_remove).hide();
            }
            else
            {
               $nodeContentControl.data('active_tag',$node.data('hash'));
               $controlNode.addClass('active');
               
               $controlNode.find(options.content_item_counter).hide();
               $controlNode.find(options.content_item_remove).show();
            }
            
            $('body').trigger('resetWallContent', url);
            $('#content-wrapper').scrollToMe();
            
            $('body').trigger('activateControl', this.controlName());
        },
        
        _addNodeHandlers: function ($node){
            var that = this;
            var options = this.options;
            
            $node.find(options.cmd_tag).each(function() {
                var $tagNode = $(this);
                
                $tagNode.on("click",function(e){
                    e.preventDefault();
                   
                   that._onTagClick($(this));
                });
            });
        },
        
        /**
         * lista de estados disponibles para ejecutar
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            var ret = [];
            ret.push(new arquematics.tag.sendTagContent());
            
            return ret;
        },
                    
        _initDOM: function($node, url)
        {
            var that = this,
                options = this.options;
            
            $node.find(options.content_item_message_content).each(function()
            {
                var $node = $(this);
               
                that._addContentLinks($node, url);
                that._addNodeHandlers($node);
            });
        }
};

arquematics.blogItemTag = function(options){
    var defOptions = {
            form:                       '#tag-form',
            input_control_tag_name:     '#ar_tag_name',
            input_control_tag_hash:     '#ar_tag_hash',
            
            input_control_message:      '#a_blog_new_post_message'
	};
        this.options = options = $.extend({}, defOptions,options);
        
        this.sendManager = new arquematics.tag.SendManager(this);
};

arquematics.blogItemTag.prototype = {
        /**
         * contexto de ejecución
         * 
         * @return {context} object
         */       
        getContext: function()
        {
           return arquematics.wallBlog.context;
        }
};

arquematics.eventItemTag = function(options){
     var defOptions = {
            form:                       '#tag-form',
            input_control_tag_name:     '#ar_tag_name',
            input_control_tag_hash:     '#ar_tag_hash',
            
            input_control_message:      '#a_new_event_message'
	};
        this.options = options = $.extend({}, defOptions,options);
        
        this.sendManager = new arquematics.tag.SendManager(this);
};

arquematics.eventItemTag.prototype = {
        /**
         * contexto de ejecución
         * 
         * @return {context} object
         */       
        getContext: function()
        {
           return arquematics.wallEvent.context;
        }
};

/**
 * se encarga de enviar las nuevas etiquetas
 * 
 * @param {object} tag
 */
arquematics.tag.SendManager = function (tag)
{
    if(!(this instanceof arquematics.tag.SendManager))
    {
           throw new arquematis.exception.invalid("arquematics.tag.SendManager:Constructor called as a function");
    }
    this.tag = tag;
    //espera por el contenido
    this.waitForContent = false;
    //tags de la session
    this.sessionTags = [];
     //tags salvados finalmente en la sesion
    this.sessionTagsSave = []; 
};
    
arquematics.tag.SendManager.prototype = {
     _removeHash: function(tags)
     {
            var ret = [];
            for (var i = 0, count = tags.length;(i < count); i++) 
            {
              ret.push(tags[i].replace(/#/,''));          
            }
            return ret;
     },
             
     _callNextLogic: function (endIndex)
     {
           if ((endIndex <= 0))
           {
               //lleva siguiente tarea del contexto
               this.tag.getContext().next();
               //desbloquea el envio de contenido
               this.waitForContent = false;
           }
     },
             
     reset: function()
     {
        this.sessionTags = [];
        this.sessionTagsSave = [];
        this.waitForContent = false;
     },
         
     sendContent: function ()
     {
          var that = this
          ,   options = this.tag.options
          ,   contentText = $.trim($(options.input_control_message).val())
          ,   tags = contentText.match(/#\w+/g);
              
              if ((tags !==null) && (tags.length > 0))
              {
                //tags unicas
                tags = this._removeHash(tags);
                // y que no estan en la lista de 
                //session tags
                var uniqueTags = tags.filter(function(elem, pos) {
                    
                    return ((tags.indexOf(elem) === pos) && ($.inArray( elem, options.sessionTags) < 0));
                });
                
                if (uniqueTags.length > 0)
                {
                    for (var i = 0,
                     formData,
                     count = uniqueTags.length,
                     endIndex = uniqueTags.length;(i < count); i++) 
                  {
                    contentText = uniqueTags[i];
            
                    that.sessionTags.push(contentText);
            
                    if (arquematics.crypt)
                    {
                        $(options.input_control_tag_name).val(arquematics.crypt.encryptMultipleKeys(contentText));
                    }
                    else
                    {
                        $(options.input_control_tag_name).val(contentText);     
                    }
                    
                    $(options.input_control_tag_hash).val(arquematics.utils.sha256(contentText));
                    
                    formData = $(options.form).find('input, select, textarea').serialize();     
                      
                    $.ajaxQueue({
                        type: "POST",
                        url: $(options.form).attr('action'),
                        datatype: "json",
                        data: formData,
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status === 200)
                            {
                                //tags salvados en la sesion
                                that.sessionTagsSave.push(
                                  { id: dataJSON.values.id,
                                    tag_url: dataJSON.values.tag_url,
                                    count: dataJSON.values.count,
                                    hash: dataJSON.values.hash,
                                    encrypt_text: dataJSON.values.encrypt_text,
                                    name: dataJSON.values.name});
                            }   
                            
                            endIndex--;
                            
                            that._callNextLogic(endIndex);
                            
                        },
                        statusCode: {
                            404: function() {
                                endIndex--;
                            
                                that._callNextLogic(endIndex);
                            },
                            500: function() {
                                endIndex--;
                            
                                that._callNextLogic(endIndex);
                            }
                        },
                        error: function(dataJSON)
                        {
                             endIndex--;
                            
                            that._callNextLogic(endIndex);
                        }
                    });      
                  }     
                }
                else
                {
                  that._callNextLogic(0);      
                }
              }
              else
              {
                 that._callNextLogic(0);     
              }
        }
};
/**
 * acción enviar tags de evento
 */
arquematics.tag.sendTagContentEvent = function () 
{
    this.name = 'sendTagContentEvent';
    
    this.go = function ()
    {  
        
        if (!arquematics.wallEvent.tag.sendManager.waitForContent)
        {
            arquematics.wallEvent.tag.sendManager.waitForContent = true;

            arquematics.wallEvent.tag.sendManager.sendContent(); 
        }
        else
        {
          //espera 1/2 segundo y vuelve a intentar
          setTimeout($.proxy(function() {
            this.go();
          }, this), 500);    
        }
    };
};
/**
 * acción enviar tags del blog
 */
arquematics.tag.sendTagContentBlog = function () 
{
    this.name = 'sendTagContentBlog';
    
    this.go = function ()
    {  
        if (!arquematics.wallBlog.tag.sendManager.waitForContent)
        {
            arquematics.wallBlog.tag.sendManager.waitForContent = true;

            arquematics.wallBlog.tag.sendManager.sendContent(); 
        }
        else
        {
          //espera 1/2 segundo y vuelve a intentar
          setTimeout($.proxy(function() {
            this.go();
          }, this), 500);    
        }
    };
};

/**
 * acción enviar tags muro
 */
arquematics.tag.sendTagContent = function () 
{
    this.name = 'sendTagContent';
    
    this.go = function (params)
    {  
        if (!arquematics.tag.sendManager.waitForContent)
        {
            arquematics.tag.sendManager.waitForContent = true;
            
            arquematics.tag.sendManager.sendContent(); 
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