/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  - arquematics.infinite.js
 *  - timeago:  http://timeago.yarp.com/
 *  - autosize: https://github.com/jackmoore/autosize.git
 *  
 * 
 * @param {jQuery} $
 * @param {arquematics} arquematics
 */
(function($, arquematics) {
  
arquematics.wall = {
        options: {
            elemTab:                    '#arWall',
            content:                    '#content',
            form_wall:                  '#wall_send_content',
            
            update_button:              '#cmd-update-button-arWall',
            
            input_control_select_group: '#wallMessage_groups',
            input_control_message:      '#wallMessage_message',
            input_control_pass:         '#wallMessage_pass',
            
            template_comment_form:      '#comment-form-template',
            
            tools: []
	},
        
        reset: function(e, that) 
        { 
            var options = this.options;/*,
                $btn = $(options.update_button);   
                //activa el boton
                $btn.button('reset');*/
                
                var $control = $(options.input_control_message);
                $control.parent().removeClass('error');
                
                //genera un nuevo pass
                this.randomAdmin.generate();
                //contenido texto en blanco y gana el foco
                $control.val('');
                $control.focus(); 
        },
            
        resetError: function(e, that) 
        {
             var options = this.options;/*,
                 $btn = $(options.update_button); 
               //resetea el boton
               $btn.button('reset');*/
            
               var $control = $(options.input_control_message);
               $control.parent().addClass('error');
      
               $control.focus();
        },
             
        _jQueryExtensions : function() {
            // jQuery extensions
            $.fn.extend({
                scrollToMe: function () {
                    var x = $(this).offset().top - 100;
                    $('html,body').animate({scrollTop: x}, 500);
            }});	
	},
            
        changeContent: function() {
                          
        },
        getLock: function()
        {
            return arquematics.wall.context.lock;
        },
        lock: function()
        {
           arquematics.wall.context.lock = true;
        },
        unlock: function()
        {
           arquematics.wall.context.lock = false;
        },
        
        hasContent: function()
        {
          var textControl = $.trim($(this.options.input_control_message).val());
          
          return (textControl.length > 0);   
        },
        
        init: function(options)
        {
            if (!options){options = {};}
            
            this.options = $.extend(options, this.options,options);
           
            this.context = new arquematics.context(new arquematics.wall.sendContent());
                
            this.randomAdmin = new arquematics.randomGenerator();
                
            this._jQueryExtensions();
            this._initControlHandlers();
            this._initDOM(this.getContentNode());
	},
         
        update: function(enableTabFuntions)
        {
            $.buttonControlStatus($(this.options.update_button),enableTabFuntions);
        },
        
        _initControlHandlers: function () {
           
             var that = this;
             var options = this.options;
            
            $(options.input_control_message).autosize();
            
            $(options.update_button).on("click", function (e) 
            {
               e.preventDefault();
               //si no esta bloqueado
               if (!that.context.lock 
                   && that.hasToolsContent())
               {
                 //bloquea todos los controles antes de nada
                 $('body').trigger('changeTabStatus', [false] );
                 //sin parametros de momento
                 that.context.setParams(false);
                    
                 that.context.start();
               }
            });
            
            $('body').bind('changeScrollContent', function (e, $node)
            {
               that._initDOM($node);
            });
            
            $('body').bind('resetControls', function (e)
            {
                var message = new arquematics.wall.message(null);
                    message.setState(arquematics.wall.messageStatus.reset);
                    
               that.notify(message);
            });
        },
           
        addCommentHandler: function ($node)
        {
            $node.find('.cmd-comment-button').on("click",function(e)
            {
                e.preventDefault();
                
                var $button = $(e.currentTarget),
                    $form = $button.parents('.form-message-comment'),
                    $inputControl = $form.find('.form-control'),
                    comment = $.trim($inputControl.val());
                    
                if(comment.length > 0)
                {
                   
                    $button.button('loading');
                    
                    var callBack = function (formData) { 
                                             
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
                                        var $contenNode =  $(dataJSON.HTML),
                                            $nodeComments = $button.parents('.message-comments')
                                                        .find('.comment');
                                        // desencripta el contenido
                                        $('body').trigger('changeControlContent', [$contenNode] );
                                
                                        //segun si ya tenemos comentarios
                                        //o no
                                        if ($nodeComments.length > 0)
                                        {
                                            $nodeComments.last()
                                                 .after($contenNode); 
                                        }
                                        else
                                        {
                                          $button.parents('.message-comments')
                                            .prepend($contenNode);      
                                        }                               
                                        
                                        arquematics.wall.addNodeHandlers($contenNode);
                                
                                        $inputControl.val('');
                                    }
                           
                                    
                                    $inputControl.focus();
                                    $button.button('reset');
                                },
                            statusCode: {
                                404: function() {
                                    $inputControl.focus();
                                    $button.button('reset'); 
                                },
                                500: function() {
                                    $inputControl.focus();
                                    $button.button('reset'); 
                                }
                            },
                            error: function(dataJSON)
                            {
                                $inputControl.focus();
                                $button.button('reset');
                            }
                          });
                        };
                        
                    if (arquematics.crypt)
                    {
                       arquematics.utils.encryptFormAndSend(
                        $form,
                        callBack,
                        $inputControl);      
                    }
                    else
                    {
                       arquematics.utils.prepareFormAndSend(
                        $form,
                        callBack);     
                    }
                }
                
            });
        },
        
        addNodeHandlers: function ($node)
        {
            var that = this,
                options = this.options;
            
            $node.find("span.mytime").timeago();
            
            // borra un message    
            $node.find('span.cmd-message-delete').on("click",function(e)
            {
                e.preventDefault();
                
                var elem = $(e.currentTarget);
                var ID = elem.data('message-id');
                
                elem.unbind('click');
                
                $.ajax({
                    type: "POST",
                    datatype: "json",
                    url: options.url_delete_message  + ID,
                    cache: false,
       
                    beforeSend: function(){
                        $("#message-"+ID).find('.messages-text').animate({'backgroundColor':'#fb6c6c'},300);
                    },
                    success: function(dataJson)
                    {
                        if(dataJson.status === 200)
                        {
                            var $contenNode =  $("#message-"+ID)
                            ,   message = new arquematics.wall.message($contenNode);
                            
                            message.setState(arquematics.wall.messageStatus.del);
                            
                            arquematics.wall.notify(message);
                            
                             $("#message-"+ID)
                                .find('.messages-text')
                                .fadeOut(300,function(){
                                        $("#message-"+ID).parent().remove();
                                 });
                        }
                    },
                    statusCode: {
                            404: function() 
                            {
                               $("#message-"+ID).animate({'backgroundColor':'#ff0000'},300);
                            },
                            500: function() 
                            {
                               $("#message-"+ID).animate({'backgroundColor':'#ff0000'},300);
                            }
                        },
                    error: function(dataJSON)
                    {
                      $("#message-"+ID).animate({'backgroundColor':'#ff0000'},300);
                    }
                });
            });
            
            $node.find('span.cmd-comment-delete').on("click",function(e)
            {
                e.preventDefault();
                
                var $elem = $(e.currentTarget),
                    ID = $elem.data('comment-id');
                
                 $.ajax({
                    type: "POST",
                    datatype: "json",
                    url: options.url_delete_comment  + ID,
                    cache: false,
       
                    beforeSend: function()
                    {
                        $("#comment-"+ID).animate({'backgroundColor':'#fb6c6c'},300);
                    },
                    success: function(dataJson)
                    {
                        if(dataJson.status === 200)
                        {
                            $("#comment-"+ID).fadeOut(300,function(){$("#comment-"+ID).remove();});
                        }
                    },
                    statusCode: {
                            404: function() 
                            {
                               $("#comment-"+ID).animate({'backgroundColor':'#ff0000'},300);
                            },
                            500: function() 
                            {
                               $("#comment-"+ID).animate({'backgroundColor':'#ff0000'},300);
                            }
                        },
                    error: function(dataJSON)
                    {
                       $("#comment-"+ID).animate({'backgroundColor':'#ff0000'},300);
                    }
                });
                 
            });
            
            
            
            $node.find(".cmd-view-comments").on("click",function(e)
            {
               e.preventDefault();
               
               var $message = $(this).parents('.message'),
                   $mensageControls = $message.find('.controls-message-controls'),
                   $commentsContainer = $message.parent().find('.message-comments');
               
               $(this).hide();
               
               //quita el punto de separación
               $mensageControls
                    .contents()
                    .filter(function() {
                        return this.nodeType === 3; //Node.TEXT_NODE
                }).remove();
               
               $commentsContainer.find('.comment').removeClass('hide');
               $commentsContainer.find('.comment').show();
            });
            
            $node.find(".cmd-comment-link").on("click",function(e)
            {
                e.preventDefault();
                var $message = $(this).parents('.message'),
                    $commentsContainer = $message.parent().find('.message-comments'),
                    $inputControl = $commentsContainer.find('.form-control');
                
                if ($inputControl.length === 0)
                {
                    //adornos
                    $message.removeClass('message-close');
                    $message.addClass('message-open');
                    
                    var $form = $(options.template_comment_form).tmpl({id: $message.data('message_id')});
                    
                    $commentsContainer.removeClass('hide');
                    $commentsContainer.append($form);
                    $commentsContainer.slideDown();
                    
                    that.addCommentHandler($commentsContainer); 
                }
                
                $inputControl = $commentsContainer.find('.form-control');
                
                $inputControl.autosize();
                $inputControl.scrollToMe();
                $inputControl.focus();
                
            });
                
        },
        
        hasToolsContent: function(){
            var observerCount = this.options.tools.length
            , ret = this.hasContent();
            
            for(var i = 0; (i < observerCount) && (!ret); i++)
            {
                if (typeof this.options.tools[i].hasContent === 'function')
                {
                   ret  = this.options.tools[i].hasContent(); 
                }
            }
            
            return ret;
        },
        
        notify: function($contextNode){
            var observerCount = this.options.tools.length;
            
            for(var i = 0; i < observerCount; i++){
                this.options.tools[i].update($contextNode);
            }
        },
        
        /**
         * agrega una herramienta 
         * @param {type} tool
         */
        subscribeTool: function(tool) 
        {
            if (tool)
            {
              this.options.tools.push(tool);
              
              var statusList = tool.getAvailableToolStatus();
           
              if (statusList && (statusList.length > 0))
              {
                for (var i = 0; i < statusList.length; i++) 
                {
                    this.context.add(statusList[i]);  
                }  
              }
              //si necesita el servicio y es una funcion lo envia
              if(typeof tool.addRandomGenerator === 'function')
              {
                 tool.addRandomGenerator(this.randomAdmin);
              }
            }
	},
        
        encryptForm: function ($form, $encField, $passField)
        {
            var  d = $.Deferred()
            , encNameFields = [$passField.attr("name"), $encField.attr("name")]
            , formData = ''
            ,  formDataArr = $form.find('input, select, textarea').serializeArray()
            ,  textToEncode;
        
            textToEncode = $encField.val().replace(/^\s+|\s+$/gm,'');
            arquematics.crypt.encryptAsynMultipleKeys(textToEncode,
                function (textEncode)
                {
                    textEncode= textEncode
                        .replace(/\"$/, '')
                        .replace(/^\"/, '')
                        .replace(/\\/g, '')
                        .replace(/\s+/g, '');
                        
                     formData += '&' + $encField.attr("name") + '=' + textEncode;       
                
                     var passEncode = $passField.val().replace(/^\s+|\s+$/gm,'');
                     
                     arquematics.crypt.encryptAsynMultipleKeys(passEncode,
                        function (textEncode)
                        {
                            formData += '&' + $passField.attr("name") + '=' + textEncode;
                            
                            for (var i = 0, count = formDataArr.length; i < count; i++)
                            {
                                if ($.inArray( formDataArr[i].name, encNameFields ) < 0)
                                {
                                    formData += '&' + formDataArr[i].name + '=' + formDataArr[i].value.replace(/^\s+|\s+$/gm,'');        
                                }
                            }
                            
                            d.resolve(formData );
                        });
                }
            );
            
            return d;
        },
        
        getElement: function ()
        {
          return $(this.options.elemTab);  
        },
                
        controlName: function()
        {
          return 'wall';
        },
       
        getContentNode: function()
        {
            return $(this.options.content + ' div:first');
        },
       
        _initDOM: function($node) 
        {
           var that = this;
           
           $node.children().each(function() {
                var $child = $(this);
               
                that.addNodeHandlers($child);
           });
	}
    };
    
    arquematics.wall.messageStatus =
    {
        ready: 0, // listo  
        del: 1, // borrandose del DOM
        reset: 2 // reseteando tools al estado inicial
    };
    
    arquematics.wall.message = function ($domContent)
    {
        if(!(this instanceof arquematics.wall.message))
        {
           throw new arquematis.exception.invalid("arquematics.wall.message:Constructor called as a function");
        }
        
        this.state = arquematics.wall.messageStatus.ready;
        
        this.$domContent = $domContent;
    };
    
    arquematics.wall.message.prototype = {
        setContent: function($domContent)
        {
           this.$domContent = $domContent;  
        },
        getContent: function()
        {
            return this.$domContent;
        },
        getState: function()
        {
            return this.state;
        },
        setState: function(state)
        {
            this.state = state;
        }
    };
    
   /**
    * sendContent es un estado especial que se activa siempre 
    * al terminar con las acciones del contexto arquematics.wall.context 
    */
   arquematics.wall.sendContent = function () 
   {
       var options = arquematics.wall.options;
       
       this.name = 'sendContent';

       this.go = function (params)
       {
           var $form = $(options.form_wall)
            , passKey = params
            , $inputControl = $(options.input_control_message)
            , $inputControlPass = $(options.input_control_pass);
           
           var callBack = function (formData, pass) {
               
               pass = false || pass;
               
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
                                    var $contenNode = $(dataJSON.HTML);
                                    
                                    if ($(options.content + ' div:first.page').length <= 0)
                                    {
                                      $(options.content).append('<div class="page"></div>');
                                    }
                                    
                                    $(options.content + ' div:first.page').prepend($contenNode);          
                                  
                                    // desencripta el contenido
                                    $('body').trigger('changeControlContent', [$contenNode] );
                        
                                    arquematics.wall.addNodeHandlers($contenNode);
                        
                                    $contenNode.removeClass('hide');
                                    $contenNode.show();
                                    $contenNode.find('.message').animate({'backgroundColor':'#ffff'},200);
                                    //se ha agregado contenido a wall
                                    //y lo notifica
                                    arquematics.wall.notify(new arquematics.wall.message($contenNode));
                        
                                    arquematics.wall.reset();
                        
                                    arquematics.wall.unlock();
                                    //desbloqueo de controles
                                    $('body').trigger('changeTabStatus', [true] );
                                }
                                else
                                {
                                    arquematics.wall.resetError();
                       
                                    arquematics.wall.unlock();
                                    //desbloqueo de controles
                                    $('body').trigger('changeTabStatus', [true] );
                                }
                    
                    },
                    statusCode: {
                        404: function() {
                            arquematics.wall.resetError();
                       
                            arquematics.wall.unlock();
                            //desbloqueo de controles
                            $('body').trigger('changeTabStatus', [true] );
                       
                        },
                        500: function() {
                            arquematics.wall.resetError();
                       
                            arquematics.wall.unlock();
                             //desbloqueo de controles
                            $('body').trigger('changeTabStatus', [true] );
                        }
                    },
                    error: function(dataJSON)
                    {
                        arquematics.wall.resetError();
                  
                        arquematics.wall.unlock();
                        //desbloqueo de controles
                        $('body').trigger('changeTabStatus', [true] );
                    }
                }); 
           };
           
           if (arquematics.crypt)
           { 
               $inputControlPass.val(passKey);
               
               $.when(arquematics.wall.encryptForm($form, $inputControl, $inputControlPass)) 
                  .then(function (formData){
                      callBack(formData, passKey);
              }); 
           }
           else
           {
                arquematics.utils.prepareFormAndSend($form, callBack);    
           }
       };
   };
   
   
   
}(jQuery, arquematics));