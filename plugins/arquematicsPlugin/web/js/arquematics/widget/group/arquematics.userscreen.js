/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * 
 */

$.fn.selectRange = function(start, end) {
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};

//usage
//----------------------------------------------------------------
//Other useful cursor functions:
//cursor functions
 
//set cursor position
$.fn.setCursorPosition = function(position){
    if(this.length == 0) return this;
    return $(this).setSelection(position, position);
};
 
//set selection range
$.fn.setSelection = function(selectionStart, selectionEnd) {
    if(this.length == 0) return this;
    input = this[0];
 
    if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
    } else if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
    }
 
    return this;
};


//Set focus to beginning of input:
$.fn.focusEnd = function(){
    this.setCursorPosition(this.val().length);
};


//Set focus to end of input:
$.fn.focusStart = function(){
    this.setCursorPosition(0);
};

/**
* devuelve largo real de un objeto jquery DOM
* 
* @param {jquery} $node
* 
* @return int
*/
$.getNodeHeight = function ($node)
{
    if($.browser.msie)
    {
        var $temp = $("<div>")
                            .css("position","absolute")
                            .css("left","-10000px")
                            .append($node.html());

        $("body").append($temp);
        var h = $temp.height();
        $temp.remove();
            
        return h;
    }
    else return $node[0].scrollHeight;      
};

 
 $.nodeArrOp = {
        fix: function ($listNode)
        {
            var dataItemsJson = $listNode.data('items');
            if (!(dataItemsJson instanceof Array)) 
            {
                   $listNode.data('items',[]);
                   $listNode.data('items')
                            .push(parseInt(dataItemsJson.toString()
                               .replace(/\]/g,'')
                               .replace(/\[/g,'')));
                                                            
                   dataItemsJson = $listNode.data('items');                                         
                   
            }
            return dataItemsJson;
        },
        find: function ($userNode,$listNode)
        {
           return $.inArray($userNode.data('id'), this.fix($listNode))
        },
        /**
         * quita de la lista el id del usuario 
         * 
         * @param {jquery $userNode}
         * @param {jquery $listNode}
         * 
         * @return array of int 
         */
        remove: function ($userNode, $listNode)
        {
            var index = this.find($userNode, $listNode)
            , dataItemsJson = this.fix($listNode);
            
            if (index >= 0)
            {
               if (dataItemsJson.length > 1)
               {
                     dataItemsJson.splice(index, 1);      
               }
               else
               {
                     dataItemsJson = [];     
               }      
            }
            
            return dataItemsJson;
        },
        add: function ($userNode, $listNode)
        {
             var index = this.find($userNode, $listNode)
            , dataItemsJson = this.fix($listNode);
            
            if (index < 0)
            {
                dataItemsJson.push(parseInt($userNode.data('id')));
            }
            return dataItemsJson;
        }
  };
        
$.widget( "arquematics.userscreen", {
	options: {
            //fomulario busqueda
            form:                   '#form_user_search',
            send_button:            '#cmd-search',
            input_control:          '#search_search',
            input_control_page:     '#search_page',
            
            autocomplete_url:       '',
            
          
            container:              '#content-container',
            content_groups_all:     '#content-groups-all',
            container_groups:       '#content-groups',
            content_active_list:    '#content-active-list',
            content_list:           '#content-list',

            container_item:                     '.members',
            container_header:                   '#header',
            container_insert:                   '#members-loader',
            
            container_insert_list_node:         '#members-list',
            container_insert_users_node:        '#members-minus',
            
            trigger :               80,  //disparador en %
            document:               null,
            scrolling:              false,
            counter:                1,
            
            group:                  '#group',
            group_editable:         '.editable',
            
            
            isScrollActive:         true,
            bindToDocument:         true
            //maximo número de listas
            //max_list_items:               6
        },
        controlContent: '',
        _create: function() 
        {            
            this._initEventHandlers();
            
            this.bindScrollControl(true);
            
            this.repaint();
	},
        
        repaint: function()
        {
            var that = this;
            var options = this.options;
            
            
            if (options.bindToDocument)
            {
               $(options.content_list).css('overflow-y','visible');
               $(options.container).css('overflow-y','visible');
                
               $(options.content_active_list).hide();
               
               $(options.content_groups_all)
                  .removeClass('hide')
                  .show();

               $(options.container).css('margin-top', $.getNodeHeight($(options.container_header)) + 'px');
               
               $(options.container).height('auto');
               
               $(options.container).removeClass('hide');
               $(options.container).show();
               
               //muestra todos los usuarios que se han ocultado
               $(options.container + ' ' + options.container_item)
                  .removeClass('hide')
                  .show();
               
               $(options.container_insert_users_node).hide();
               
               this.bodyHeight = $(document).height();
               
               that.resetControl();
            }
            else
            {
                $(options.content_groups_all).hide();
                
                var windowHeight = $(window).height() - $(options.container_header).height() ;
                
                $(options.content_list).height( windowHeight * 34 / 100);
                $(options.container).height(windowHeight * 54 / 100);
                
                $(options.content_list).css('overflow-y','auto');
                $(options.container).css('overflow-y','auto');
                
                $(options.content_list).animate({scrollTop: 0}, 10);
                $(options.container).animate({scrollTop: 0}, 10);
               
                    
                $(options.content_active_list).css('margin-top', $(options.container_header).height() + 'px');
                
                $(options.content_active_list).removeClass('hide');
                $(options.content_active_list).show();
                
                $(options.container).css('margin-top','0px');
                
                $(options.container)
                    .removeClass('hide')
                    .show();

                $(options.container_insert_users_node)
                    .removeClass('hide')
                    .show();
                
                this.bodyHeight = $.getNodeHeight($(options.container));
                
                that.resetControl();
            }
         
        },
        
        _changeContent: function() 
        {
               var options = this.options;
               
               this.controlContent = $.trim($(this.options.input_control).val());
               
               options.counter = 1;
               $(options.container)
                    .children('.members')
                    .remove();

               this.sendContent();
               
        },
        _initEventHandlers: function () 
        {
           var that = this;
           var options = this.options;
           
            $(options.input_control).autocomplete({
                  
                    source: function(request, response) {
                        that._autocomplete( {'term': request.term }, function(results, status) {
                            response($.map(results, function(item) {
                                return {
                                    value: item.first_last,
                                    id:    item.id
                                }
                            }));
                        })
                    },
                   
                    select: function(event, ui) {
                          $(options.input_control).val(ui.item.value);
                          that._changeContent();
                    }
               });
               
             $('body').bind('changeScrollContent', function (e, $node)
             {
              
               if ($node instanceof jQuery)
               {
                   that._removeSameUserNodes($node);
                  //continua cargando el documento hasta 
                  //que termina la lista o aumenta el largo de la pagina
                  if (options.bindToDocument 
                      && ($(document).height() <= 2 * that.bodyHeight))
                  {
                     that._onScreenChange();     
                  }
                  else if ((!options.bindToDocument) 
                            && ($.getNodeHeight($(options.container)) <= 2 * that.bodyHeight))
                  {
                    that._onScreenChange();       
                  }
               }
             });
             
            $(options.input_control).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                
                if (keycode == '13') {
                    that._sendControl(ev);
                }
            })
            
            $(options.send_button).bind('click', function (e){
                that._sendControl(e);
            });
        },
        bindScrollControl: function(toDocument)
        {
          
            var that = this;
            var options = this.options;
            
            options.bindToDocument = toDocument;
            
            if (toDocument)
            {
                $(options.content_list).unbind('scroll');
                
                $(options.container).unbind('scroll');
                //enlaza el documento completo al evento scroll
                $(this.element).scroll(function() {
                    var wintop     = $(window).scrollTop();
                    var docheight  = $(this).height();
                    var winheight  = $(window).height();
                
                    if ((Math.round( wintop * 100 / ( docheight - winheight )) > options.trigger ) 
                    &&  !(options.scrolling))
                    {
                        that.sendContent();
                    }
                
                });    
            }
            else {
                
                 $(this.element).unbind('scroll');
                 
                    
                 $(options.container).scroll(function() {
                    var wintop     = $(options.container).scrollTop();
                    var docheight  = $(this)[0].scrollHeight;
                    var controlHeight  = $(options.container).height();
                   
                    if ((Math.round( wintop * 100 / ( docheight - controlHeight )  ) > (options.trigger) ) 
                         &&  !(options.scrolling))
                    {
                        that.sendContent();
                    }
                });    
                
            }
           
            
        },
        _autocomplete: function (term, callBack)
        {
            
            var options = this.options;
            
            var formData = $(options.form).find('input, select, textarea').serialize();
            
            $.ajax({
                type: "POST",
                url: options.autocomplete_url,
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    callBack.call(this, dataJSON ,200);
                    
                    /*
                    if (dataJSON.status == 200)
                    {
                       callBack.call(this, dataJSON.values ,200);
                    }
                    else
                    {
                      callBack.call(this, [],500);      
                    }*/
                },
                statusCode: {
                    302: function(){
                      callBack.call(this, [], 302);  
                    },
                    404: function() {
                       callBack.call(this, [],404);
                    },
                    500: function() {
                       callBack.call(this, [],500);
                    }
                },
                error: function(dataJSON)
                {
                  
                  callBack.call(this, [],501);
                }
            });
        },
       
        /*
        _countNumberOfList: function()
        {
            return $(this.options.container_groups).children(this.options.group_editable).length;
        },*/
        
        _removeSameUserNodes: function($userNodesNodes)
        {
           var options = this.options;
           //TODO hacer una lista de elementos
           //que pueden estar repetidos
           $userNodesNodes.each(function() {
                //borra los nodos iguales
                if ($(options.container)
                     .find('#' + $(this).attr('id')).lenght > 0)
                {
                   $(this).remove();
                }  
           });
            
        },
        _sendControl: function(e)
        {
            e.preventDefault();
            var that = this;
            var options = this.options;
            
            //desactiva el botton
            var btn = $(e.currentTarget);
            //btn.button('loading');
            
            if (($.trim(options.input_control) != this.controlContent)
                        && !(options.scrolling))
            {
                this._changeContent();     
            }
            else if (($.trim(options.input_control) != this.controlContent)
                    && (options.scrolling))
            {
                setTimeout(function(){
                     that._sendControl(e);
                 }, 1500); //1.5 seg
            }
          
        },
        
            
            //resetea el contenido del control y lo activa para usar
            resetControl: function() 
            {
                var options = this.options;
                //resetea el boton
                $(options.send_button).button('reset');
                
                var controlGroup = $(options.tool_focus).parent();
                controlGroup.removeClass('error');
                
                $(options.container_insert).hide();
                
                $(options.input_control).focus();
            },
            
            resetControlError: function() 
            {
               var options = this.options;
               //resetea el boton
               $(options.send_button).button('reset');
               
               var controlGroup = $(options.input_control).parent();
               controlGroup.addClass('error');
               
               $(options.container_insert).hide();
               
               $(options.input_control).focus();
            },
            
        sendContent: function()
        {
            var that = this;
            var options = this.options;
            
            $(options.input_control_page).val(options.counter);
            
            options.scrolling = true;
            
            var formData = $(options.form).find('input, select, textarea').serialize();
          
            $(options.container_insert)
                .removeClass('hide')
                .show();
            
            $.ajax({
                type: "POST",
                url: $(options.form).attr('action'),
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status == 200)
                    {
                         
                         var $contentNode = $($.trim(dataJSON.HTML));
                         $(options.container_insert).before($contentNode);
                         $(options.container).data('is_last_page', dataJSON.values.isLastPage);
                         
                         $('body').trigger('changeScrollContent', [$contentNode] );      
                         
                         if (dataJSON.values.isLastPage)
                         {
                            $(options.container).unbind('scroll');
                            that.element.unbind('scroll');
                            options.isScrollActive = false;
                         }
                         else if (!options.isScrollActive)
                         {
                            options.isScrollActive = true;
                            that.bindScrollControl(options.bindToDocument);             
                         }
                        
                    }
                    options.scrolling = false;
                    options.counter++;
                    
                    that.resetControl();
                },
                statusCode: {
                    404: function() {
                        that.resetControlError();
                      
                    },
                    500: function() {
                        that.resetControlError();
                       
                    }
                },
                error: function(dataJSON)
                {
                  that.resetControlError();
                  
                }
            });
            
            
        },
        bodyHeight: 0,
        _onScreenChange: function()
        {
          var that = this
          , options = this.options;
          
          if (!$(options.container).data('is_last_page'))
          {
            if (options.scrolling)
            {
                setTimeout(function(){
                     that._onScreenChange();
                 }, 100); //0.1 seg
            }
            else
            {
              this.sendContent();      
            } 
          }
        },
        isListControlOpen: function()
        {
          return !this.options.bindToDocument;   
        },
        _init: function()
        {
          this.bodyHeight = $(document).height();
          this._onScreenChange();
        }
});