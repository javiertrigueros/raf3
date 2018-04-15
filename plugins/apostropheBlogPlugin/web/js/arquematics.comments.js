/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 */
$.widget( "arquematics.comments", {
	options: {
            cmd_comment: '#cmd-comment-submit',
            
            comment: '#ar_comment_comment',
            comment_author: '#ar_comment_comment_author',
            comment_author_email: '#ar_comment_comment_author_email',
            comment_author_url: '#ar_comment_comment_author_url',
            parent_comment: '#ar_comment_parent',
            
            error_comment_author: '#error-comment_author',
            error_comment_author_email: '#error-comment_author_email',
            error_comment_author_url: '#error-comment_author_url',
            error_comment: '#error-comment',
           
            form_comment: '#form-comment',
            
            comment_list: '#comment-list',
            respond:    '#respond',
            comments_counter: '#comments-counter',
            
            resetControl: function(e, that) 
            { 
                that.isSendContent = false;
                
                that.reset();
                
                that._focusAtControl();
            },
            
            resetControlError: function(e, that) 
            {
               var options = that.options;
               //resetea el boton
               var $btn = $(that.options.cmd_comment);
               $btn.button('reset');
               
               that.isSendContent = false;
               
               $(options.comment_author).val('');
               $(options.comment_author_email).val('');
               $(options.comment_author_url).val('');
               $(options.comment).val('');
               $(options.parent_comment).val('');
               
              
               that._focusAtControl();
            }
	},
        //se esta enviando contenido
        isSendContent: false,
        //nodo al que va el foco
        $focusNode: false,
        //nodo en el que se cuenta el número de comentarios
        $counterNode: false,
        _setOption: function( key, value ) {
		this.options[ key ] = value;
		this._update();
	},
        _create: function() {
                this._super();
                this._initHandlers();
                
                this._trigger( "comments-complete", null, { value: 100 } );
	},
        
        _initHandlers: function () 
        {
            var that = this;
            
            that._addHandlers($(this.options.form_comment));   
           
            $(this.options.comment_list).children().each(function(){
                 that._addHandlers($(this));   
            });
            
        },
        _disableAllLinks: function ()
        {
            $('.active-link').each(function( index, element ) {
                    // element == this
                   var $currentNode = $(element);
                   $currentNode.removeClass('active-link');
                   $currentNode.data('active','false');
                   $currentNode.text($currentNode.data('text_reply'));

                 });
    
        },
        
        _activateLink: function ($currentNode)
        { 
           $currentNode.addClass('active-link');
           $currentNode.data('active','true');  
           $currentNode.text($currentNode.data('text_reply_cancel'));    
        },
        _moveForm: function ($moveToNode, parentId){
            var options = this.options;
            
             //resetea el boton
             var $btn = $(this.options.cmd_comment);
             $btn.button('reset');
            //cambio los valores  antes de
            // hacer una copia y borrar en nodo del DOM
            $(options.parent_comment).val(parentId);
            
            var $form = $(options.form_comment).clone();
            $(options.form_comment).remove();
              
            this._addHandlers($form);
              
            $form.appendTo($moveToNode);
        },
        
        _addHandlers: function ($node) 
        {
           var that = this;
           var options = this.options;
           
           $node.find('.comment-reply-link').on("click",function(e)  
           {
              e.preventDefault();
              
              var $currentNodeLink = $(this);
              
              var isActive = ($currentNodeLink.data('active') === 'true');
              
              if (!isActive)
              {
                  that._disableAllLinks();
                  
                  that._activateLink($currentNodeLink);
                  
                  var $posibleNodes = $($currentNodeLink.attr('href')).find('.comment-body');

                  if ($posibleNodes instanceof $)
                  {
                    that._moveForm($posibleNodes[0], $currentNodeLink.data('parent_id') );
                    //oculta el nodo dejar un comentario
                    $(options.respond).hide();
                  
                    that._focusAtControl();
                  }
              }
              else
              {
                 that.reset();     
              }
              
           });
           
           
           if ($node.is( "form" ) )
           {
               $node.submit(function(e) {
                    e.preventDefault();
                
                    that._sendControl(e);
                    return false;
                });
                   
           }
           
           
        },
        
        _incrementCounter: function(){
    
            var countComments = parseInt(this.$counterNode.data('counter'),10) + 1;
            
            this.$counterNode.data('counter',countComments);
            this.$counterNode.text(countComments);
        },
        
        getElement: function ()
        {
          return this.element;  
        },
                
        controlName: function()
        {
          return 'comments';
        },
        
                   
        _sendControl:function(e)
        {
            if (!this.isSendContent)
            {
              this.isSendContent = true;
              //desactiva el botton
              var $btn = $(this.options.cmd_comment);
              
              $btn.button('loading');
              /*
              $btn.addClass('disabled')
              .prop('disabled',true);*/
              
              this.sendContent();      
            }
             
        },
                
             
        sendContent: function ()
        {
            var that = this;
            var options = this.options;
            
            var formData = $(options.form_comment).find('input, select, textarea').serialize();
          
            $.ajax({
                type: "POST",
                url: $(options.form_comment).attr('action'),
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                        if (dataJSON.values.parent === null)
                        {
                            $(options.comment_list).append(dataJSON.HTML);
                        }
                        else
                        {
                            var $nodeParent = $('#li-comment-' + dataJSON.values.parent);
                            var $placeNode = $nodeParent.find('ul');

                            if ($placeNode && $placeNode.is('ul'))
                            {
                                $placeNode.append(dataJSON.HTML);    
                            }
                            else
                            {
                                $nodeParent.append('<ul class="children">' + dataJSON.HTML  + '</ul>');    
                            }
                                
                        }
                        
                        var $contenNode = $('#li-comment-' + dataJSON.values.id);
                        
                        $contenNode.slideDown();
                        $contenNode.animate({'backgroundColor':'#ffff'},200);
                        
                        that._addHandlers($contenNode);
                        
                        that._incrementCounter();
                        
                         
                        that._trigger('resetControl', null, that);
                       
                    }
                    else
                    {
                        $.each(dataJSON.errors, function( index, value ) {
                           
                            $(options[index]).parent().addClass('error');
                            
                            $('#error-' + index).text(value);
                            
                        });
                        
                       
                       that._trigger('resetControlError',null, that);     
                    }
                },
                statusCode: {
                    404: function() {
                       that._trigger('resetControlError',null, that); 
                    },
                    500: function() {
                       that._trigger('resetControlError',null, that);  
                    }
                },
                error: function(dataJSON)
                {
                  that._trigger('resetControlError',null, that);  
                }
            });
            
        },
                
        reset: function() 
        {
          var options = this.options;
                
          $(options.comment).val('');
          $(options.comment_author).val('');
          $(options.comment_author_email).val('');
          $(options.comment_author_url).val('');
          $(options.parent_comment).val('');
                
          $(options.error_comment_author).text('');
          $(options.error_comment_author_email).text('');
          $(options.error_comment_author_url).text('');
          $(options.error_comment).text('');
                
          var $inputControls = $('.form-control');
                
          $inputControls.each(function(){
             $(this).parent().removeClass('error');
          });
                
          this._disableAllLinks();
                
          this._moveForm($(options.respond), '' );
              
               
          //muestra el nodo dejar un comentario
          $(options.respond).show();
                
          //activa el boton
          var $btn = $(options.cmd_comment);
          $btn.button('reset');
          
          //arreglo del bug de firefox
          setTimeout(function () {
            $btn.removeClass('disabled');
            $btn.removeAttr('disabled');
          }, 1);
	},
        
        _focusAtControl: function()
        {
           var $inputControls = $('.focus-control');
           if ($inputControls 
              && ($inputControls instanceof $))
           {
              this.$focusNode = $inputControls[0];
              
              this.$focusNode.focus();
           }
            
        },
        _init: function() 
        {
           this.$counterNode = $(this.options.comments_counter);              
	}
});

