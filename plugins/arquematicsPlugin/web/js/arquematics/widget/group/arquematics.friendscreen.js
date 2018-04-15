/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias:
 *  
 * 
 */

$.widget( "arquematics.friendscreen", {
	options: {
          
          
           form_user_search:                          '#form_user_search',
           input_control_search_search:               '#search_search',
           input_control_search_page:                 '#search_page',
           input_control_search_is_subscriber:        '#search_is_subscriber',
            
           container_friends:                      '#content-friends',
           container_ignore:                       '#content-ignore',
          
           loader_ignore:                          '#ignore-loader',
           loader_friends:                         '#friends-loader',
          
           friends_tabs_menu:                      '#friends-tabs-menu a',
           
           cmd_search:                              '#cmd-search',
           
           trigger :               40,  //disparador en %
           scrolling:              false,
           counter:                1,
           
           showOnLoad:             '',
            
          
           resetControl: function(e, that) 
           {
                var options = that.options;
                //activa el botton
                $.buttonControlStatus($(options.cmd_search), true);
                
                $(options.input_control_search_search).focus();
           },
            
           resetControlError: function(e, that) 
           {
               var options = that.options;
               //activa el botton
               $.buttonControlStatus($(options.cmd_search), true);
           }
         
        },
        controlContent: '',
        _create: function() 
        {
            this._initEventHandlers();
            this._bindScrollControl();
	},

        _initEventHandlers: function () 
        {
           var that = this;
           var options = this.options;
           
           $('body').bind('changeScrollContent', function (e, $node)
             {
               if ($node instanceof jQuery)
               {
                  //continua cargando el documento hasta 
                  //que termina la lista o aumenta el largo de la pagina
                  if (($(document).height() <= 2 * that.bodyHeight))
                  {
                     that._onScreenChange();     
                  }
               }
           });
           
           $(options.friends_tabs_menu).click(function (e) {
                e.preventDefault();
               
                if (!options.scrolling)
                {
                   var $selectTab = $(this);
                   
                   var isSubscriber = ($selectTab.data("is-friend"))?1:0;
                
                   $(options.input_control_search_is_subscriber).val(isSubscriber);
                
                   $selectTab.tab('show');
                   
                   that._onScreenChange();
                   
                   $('#search_search').focus();     
                }
            });
            
            $(options.input_control_search_search).autocomplete({
                  
                    source: function(request, response) {
                        that._autocomplete( {'term': request.term }, function(results, status) {
                            response($.map(results, function(item) {
                                return {
                                    value: item.first_last,
                                    id:    item.id
                                };
                            }));
                        });
                    },
                   
                    select: function(event, ui) {
                          $(options.input_control_search_search).val(ui.item.value);
                          that._changeContent();
                    }
               });
               
                
                $(options.input_control_search_search).keypress(function (ev) {
                    var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                
                    if (keycode == '13') {
                        that._sendControl(ev);
                    }
                });
             
              
               $(options.cmd_search).on('click', function (e){
                    e.preventDefault();
                   
                    that._sendControl(e);
                });   
        },
        
        _autocomplete: function (term, callBack)
        {
           
            var options = this.options;
            
            var formData = $(options.form_user_search).find('input, select, textarea').serialize();
            
            $.ajax({
                type: "POST",
                url: options.autocomplete_url,
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                       callBack.call(this, dataJSON.values ,200);
                    }
                    else
                    {
                      callBack.call(this, [],500);      
                    }
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
        
        _changeContent: function() 
        {
               var options = this.options;
               
               this.controlContent = $.trim($(options.input_control_search_search).val());
               
               options.counter = 1;
               $(options.input_control_search_page).val(options.counter);
               
                var isSubscriber = ($(options.input_control_search_is_subscriber).val() == 1);
                
                if (isSubscriber)
                {
                    $(options.container_friends).find('.user').remove();
                }
                else
                {
                    $(options.container_ignore).find('.user').remove();       
                }
                
               this._bindScrollControl();
            
               this.sendContent();
        },
        _sendControl: function(e)
        {
            e.preventDefault();
            var that = this;
            var options = this.options;
            
            //desactiva el botton
            //$.buttonControlStatus($(e.currentTarget), false);
            
            if (($.trim(options.input_control_search_search) !== this.controlContent)
                        && !(options.scrolling))
            {
                
                this._changeContent();     
            }
            else if (($.trim(options.input_control_search_search) !== this.controlContent)
                    && (options.scrolling))
            {
                setTimeout(function(){
                    
                     that._sendControl(e);
                 }, 1500); //1.5 seg
            }
          
        },
        _bindScrollControl: function()
        {
            var that = this;
            var options = this.options;
            
            $(document).unbind('scroll');
            //enlaza el documento completo al evento scroll
            $(document).scroll(function() {
                    var wintop     = $(window).scrollTop();
                    var docheight  = $(this).height();
                    var winheight  = $(window).height();
                
                    if ((Math.round( wintop * 100 / ( docheight - winheight )) > options.trigger ) 
                    &&  !(options.scrolling))
                    {
                        that.sendContent();
                    }
             });    
        },
        sendContent: function()
        {
            var that = this
            , options = this.options
            , $form = $(options.form_user_search)
            , isSubscriber = ($(options.input_control_search_is_subscriber).val() == 1)
            , $loader = (isSubscriber)? $(options.loader_friends):$(options.loader_ignore);

            $(options.input_control_search_page).val(options.counter);
            options.scrolling = true;
            
            $loader.removeClass('hide');
            $loader.show();


            $.ajax({
                type: "POST",
                url: $form.attr('action'),
                datatype: "json",
                data: $form.find('input, select, textarea').serialize(),
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                         var $contentNode = $(dataJSON.HTML);
                         
                         $loader.before($contentNode);
                         
                         if (isSubscriber)
                         {
                                that.friends.addFriendsHandlers($contentNode);
                                
                                $(options.container_friends).data('is_last_page', dataJSON.values.isLastPage);
                         }
                         else
                         {
                                that.friends.addIgnoreHandlers($contentNode);
                                $(options.container_ignore).data('is_last_page',dataJSON.values.isLastPage);
                         }
                         
                         if (dataJSON.values.isLastPage)
                         {
                            $(document).unbind('scroll');
                         }
                         
                         $('body').trigger('changeScrollContent', [$contentNode] );
                         
                    }
                    
                    options.scrolling = false;
                    
                    options.counter++;
                    
                    $loader.hide();
                    
                    that._trigger('resetControl',null, that);
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
        friends: null,
        bodyHeight: 0,
        
        _onScreenChange: function()
        {
          var that = this
          , options = this.options
          , isSubscriber = ($(options.input_control_search_is_subscriber).val() == 1)
          , $containerNode = isSubscriber?$(options.container_friends):$(options.container_ignore);
          
          if (!$containerNode.data('is_last_page'))
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
        _init: function()
        {
            this.friends = $('#friends-tabs-menu').data('friends');
            this.bodyHeight = $(document).height();
            
            this.friends.resetButtoms();
            
            this._onScreenChange();
        }
});