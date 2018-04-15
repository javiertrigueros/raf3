    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 *  
 */

$.widget( "arquematics.friendselector", {
	options: {
            modal_accept: '#modal-accept',
            modal_request:  '#modal-request',
            modal_friend_request: '#modal-friend-request',
            
            activeClass: 'active-group',
            
            cmd_friend_request:     '#cmd-friend-request',
            cmd_cancel_friend:      '#cmd-cancel-friend, #cmd-cancel-friend-secondary',
            cmd_add_friend:         '#cmd-add-friend',
            cmd_ff_request_btn:     '#ff-request-btn',
            cmd_cancel_request:     '#cmd-cancel-request',
            
            cmd_friend_request_cancel: '#cmd-friend-request-cancel, #cmd-cancel-friend-secondary',
            
            content_container:              '#modal-accept-content',
            content_modal_accept_footer:    '#modal-accept-footer',
            content_group_control_content:  '#group-control-content',
            content_requests_counter:       '#requests-counter',
            
            
            //formulario aceptar la peticion
            form_add_friend:                       '#add_friend',
            
            input_control_accept__csrf_token:      '#accept__csrf_token',
            input_control_profile_id:              '#accept_profile_id',
            input_control_profile_list_id:         '#accept_profile_list_id',
            input_control_accept_is_accept:        '#accept_is_accept',
              
            //prefijo de las solicitudes de usuarios
            user_item:                              '#men',
            user_item_class:                        '.user-item',
            
            wall_url:               '/wall',
            
            
            controlError: function(e, that) 
            {
               var options = that.options;
               
               $.buttonControlStatus($(options.cmd_add_friend), true);
            
               var controlGroup = $(options.input_control_list_edit_name).parent();
               controlGroup.addClass('error');
               
               $(options.modal_accept).modal('hide');
            },
            
            controlErrorIgnore: function(e, that) 
            {
               $.buttonControlStatus($(e.currentTarget), true);
            },
            
            // Is triggered on change of the selected options.
            onChange: function(option, checked) {
             
               
            },
            buttonClass: 'btn btn-default',
            buttonWidth: 'auto',
          
            buttonContainer:'<div id="group-control" class="btn-group wall-group-btn" />',
       
            buttonText: function(options) {
				if (options.length === 0) {
					return 'Groups <b class="caret"></b>';
				}
				else if (options.length > 3) {
					return options.length + ' selected <b class="caret"></b>';
				}
				else {
					var selected = '';
					options.each(function() {
						selected += $(this).text() + ', ';
					});
					return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
				}
            }
	},
       

        _create: function() {
                this._initControlContents();
                this._resetControl();
                this._initEventHandlers();
	},
        
        controlName: function()
        {
          return 'friendselector';
        },
        
        _initEventHandlers: function () 
        {
           var that = this;
           var options = this.options;

           $(options.cmd_friend_request_cancel).on("click", function (e)
           {
                e.preventDefault();
                
                $(options.modal_accept).modal('hide');
                $(options.modal_request).modal('hide');
                $(options.modal_friend_request).modal('hide');
           });
           
           $(options.cmd_friend_request).on("click", function (e)
           {
                e.preventDefault();
                
                $(options.modal_accept).modal('hide');
                $(options.modal_request).modal('hide');
                
                $(options.modal_friend_request)
                    .modal()
                    .show({
                        backdrop: true,
                        keyboard:true
                    });
           });
           
           $(options.cmd_ff_request_btn).on("click", function (e)
           {
                e.preventDefault();
                
                $(options.modal_friend_request).modal('hide');
                $(options.modal_accept).modal('hide');
                $(options.modal_request).modal('show');
                
           });
           
           $(options.cmd_cancel_request).on("click", function (e)
           {
                e.preventDefault();
                
                $(options.modal_friend_request).modal('hide');
                $(options.modal_accept).modal('hide');
                $(options.modal_request).modal('hide');
                
           });
           
           
           $(options.cmd_cancel_friend).on("click", function (e) 
           {
                e.preventDefault();
                
                $(options.modal_friend_request).modal('hide');
                $(options.modal_accept).modal('hide');
                //$(options.modal_request).css('z-index',1050);
                $(options.modal_request).modal('show');
           });
           
           $(options.cmd_add_friend).on("click", function (e) 
           {
                e.preventDefault();
                
                var $btn = $(e.currentTarget)
                , $form = $(options.form_add_friend)
                , formData = {};

                $.buttonControlStatus($btn, false);
                //
                var listString = '';
                $(options.input_control_profile_list_id + ' :selected').each(function(i, selected) {
                    listString += $.trim($(selected).val()) + ' ';
                });
                 
                listString = $.trim(listString);
                
                if (listString.length > 0)
                {
                  formData = {
                    'accept[_csrf_token]': $(options.input_control_accept__csrf_token).val(),
                    'accept[is_accept]': $(options.input_control_accept_is_accept).val(),
                    'accept[profile_id]': $(options.input_control_profile_id).val(),
                    'accept[profile_list_id]':listString};      
                }
                else
                {
                   formData = {
                    'accept[_csrf_token]': $(options.input_control_accept__csrf_token).val(),
                    'accept[is_accept]': $(options.input_control_accept_is_accept).val(),
                    'accept[profile_id]': $(options.input_control_profile_id).val()};
                }
        
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
                           var userNode = $(options.user_item + $(options.input_control_profile_id).val()).parent();
                           
                           userNode.next("divider").remove();
                           userNode.remove();
                           
                           //pone el contador de peticiones
                           var requestCounter = $(options.user_item_class).length;
                           if (requestCounter > 0)
                           {
                             $(options.content_requests_counter).text($(options.user_item_class).length);      
                           }
                           else
                           {
                              $(options.content_requests_counter).hide();
                              $(options.content_requests_counter).text('');
                              
                              //desconecta el boton
                              $(options.cmd_friend_request).off();
                           }
                          
                            $.buttonControlStatus($btn, true);
                            
                            $(options.modal_accept).modal('hide');
                            
                            //los controles especiales que guardan el estado
                            //vuelven al etado inicial
                            $('body').trigger('resetControls'); 
                             //ha aceptado un nuevo usuario y es necesrio
                            //recargar el contenio del muro
                            //$.event.trigger('resetWallContent', options.wall_url );
                            $('body').trigger('resetWallContent', options.wall_url ); 
                           
                        }
                        else 
                        {
                          that._trigger('controlError',null, that);   
                        }
                    },
                    statusCode: {
                        404: function() {
                            that._trigger('controlError',null, that); 
                        },
                        500: function() {
                            that._trigger('controlError',null, that);  
                        }
                    },
                    error: function(dataJSON)
                    {
                        that._trigger('controlError',null, that);  
                    }
                });
                    
                
           });
           
           
           
          
            $('li a', options.content_group_control_content).on('click', function (event)
            {
                event.stopPropagation();
                
                var groupItem = $(event.target).parents('li');
                var groupStatus = groupItem.find('i.fa');
                        
                if (groupStatus.hasClass('hide')) 
                {
                    groupStatus.removeClass('hide');
                    groupItem.addClass(options.activeClass);
                    groupItem.removeClass('disable-group');
                }
		else 
                {
                    groupStatus.addClass('hide');
                    groupItem.removeClass(options.activeClass);
                    groupItem.addClass('disable-group');           
                }
                
                
                var option = $('option[value="' + groupItem.attr('data-id') + '"]', $(options.input_control_profile_list_id));
			
                var checked = (!groupStatus.hasClass('hide'));
		if (checked) {
                    option.attr('selected', 'selected');
                    option.prop('selected', 'selected');
		}
		else {
                    option.removeAttr('selected');
		}
			
                        
		var optionsSelected = $('option:selected', $(options.input_control_profile_list_id));
		
                that.button.html(options.buttonText(optionsSelected));

            });
           
            
        },
        _resetControl: function()
        {
            var options = this.options;
            
            var optionsSelected = $('option:selected', $(options.input_control_profile_list_id));
            
            optionsSelected.each(function() {
               $(this).removeAttr('selected'); 
            });
            
            this.button.html(options.buttonText(new Array()));
            
           
            $(options.content_group_control_content).children("li").each(function(){
               
                var groupStatus = $(this).find('i.fa');
                var groupItem = $(this);
                
                groupStatus.addClass('hide');
                groupItem.removeClass(options.activeClass);
                groupItem.addClass('disable-group'); 
            });
            
        },
        _addIgnoreNodeHandlers: function (node)
        {
            var that = this;
            var options = this.options;
            
            node.on("click", function (e) 
            {
                e.preventDefault();
                
                var btn = $(e.currentTarget);
                btn.button('loading');
                
                $(options.input_control_profile_id).val(btn.attr('data-friend-id'));
                $(options.input_control_accept_is_accept).val(false);
                   
                var form = $(options.form_add_friend);
                var formData = form.find('input, select, textarea').serialize();
          
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    success: function(dataJSON)
                    {
                        if (dataJSON.status === 200)
                        {
                           var userNode = $(options.user_item + $(options.input_control_profile_id).val()).parent();
                           
                           userNode.next("divider").remove();
                           userNode.remove();
                           
                           //pone el contador de peticiones
                           var requestCounter = $(options.user_item_class).length;
                           if (requestCounter > 0)
                           {
                             $(options.content_requests_counter).text( $(options.user_item_class).length );      
                           }
                           else 
                           {
                              $(options.content_requests_counter).text('');
                               //desconecta el boton
                              $(options.cmd_ff_request_btn).off();
                              //oculta el modal
                              $(options.modal_accept).modal('hide');
                           }
                        }
                        else 
                        {
                          that._trigger('controlErrorIgnore', e, that);   
                        }
                    },
                    statusCode: {
                        404: function() {
                            that._trigger('controlErrorIgnore', e, that); 
                        },
                        500: function() {
                            that._trigger('controlErrorIgnore', e, that);  
                        }
                    },
                    error: function(dataJSON)
                    {
                        that._trigger('controlErrorIgnore', e, that);  
                    }
                });
                
            });
        },
        
        _addAcceptNodeHandlers: function (nodes)
        {
            var that = this;
            var options = this.options;
            
            nodes.on("click", function (e) 
            {
                e.preventDefault();
                var btn = $(e.currentTarget);
                var userNode = btn.parents('.user-item').find('.user-item-content').clone();
                
                 
                $(options.input_control_profile_id).val(btn.attr('data-friend-id'));
                $(options.input_control_accept_is_accept).val(true);
                
                that._resetControl();
                
                $(options.content_container).empty();
                
                $(options.content_container).append(userNode);
                
                $(options.modal_friend_request).modal('hide');
                $(options.modal_request).modal('hide');
                $(options.modal_accept).modal('show');
                
            });
        },
        /**
         * añade contenidos, con sus Handlers al DOM de la página
         */
        _initControlContents: function()
        {
            //var that = this;
            var options = this.options;
         
            this.container = $(options.content_modal_accept_footer)
                                .prepend('<span class="button-friend pull-left"></span>');
           
            this.container = this.container.find('.button-friend');
            this.button = $('<button type="button" style="width:' + options.buttonWidth + '" class="dropdown-toggle ' + options.buttonClass + '" data-toggle="dropdown">' + options.buttonText($('option:selected', $(options.input_control_profile_list_id))) + '</button>');
            
            this.container.prepend(this.button);
            var itemOptions = $('option', options.input_control_profile_list_id);
            
            if (itemOptions.length > 0)
            {
                this.container.prepend('<ul id="' + options.content_group_control_content.replace('#','') + '" class="dropdown-menu inner selectpicker"></ul>');
                                
                var containerContent = this.container.find('ul');
            
                itemOptions.each(function() {
                    var curentOptionText = $(this).text();
                    var curentOptionVal = $(this).attr('value');
               
                    containerContent.append('<li class="group-item disable-group" data-id="' + curentOptionVal + '"><a href="#"> <span class="group-text">' + curentOptionText + '</span> <i class="fa fa-check hide icon-ok check-mark"></i> </a></li>');
                });  
            }
            
           this._addAcceptNodeHandlers($(this.element).find('.accept'));
           this._addIgnoreNodeHandlers($(this.element).find('.ignore'));
        },
        destroy: function() {

            // In jQuery UI 1.8, you must invoke the destroy method from the base widget

            $.Widget.prototype.destroy.call( this );
            // In jQuery UI 1.9 and above, you would define _destroy instead of destroy and not call the base method
        }
        
});