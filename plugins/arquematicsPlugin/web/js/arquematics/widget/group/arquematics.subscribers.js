/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias:
 *  - arquematics.listeditor
 * 
 */


 $.lastPosition =  {
                data: {left: 0, top: 0},
                setPos: function(node)
                {
                   var position = node.position();
                   
                   this.data.left = position.left;
                   this.data.top = position.top;
                },
                getPos: function(){
                    return this.data;
                }
            };
             
$.widget( "arquematics.subscribers", {
	options: {
            list_modal:                            '#list-modal',
            
            cmd_add_user:                           '.add-user',
            cmd_remove_request:                     '.remove-request',
            cmd_remove_suscriptor:                  '.remove-suscriptor',
            
            input_control_list_add_id:             '#list_add_id',
            input_control_list_add_profile_id:     '#list_add_profile_id',
            
            //formulario agregar id a lista
            form_add_list:                         '#form_add_list',
            //formulario creación de lista
            form_new_list:                         '#form_new_list',
            //formulario agregar peticion amistad de usuario
            form_friend_no_list:                   '#form_friend_request_no_list',

            send_button:                '#cmd-create',
            list_id:                    '#list_id',
            
            input_control_list_name:    '#list_name',
            input_control_list:         '#list_users_list',
            input_control_list_owner:   '#list_edit_list_owner_id',
            input_control_add_friend:   '#add_friend_friend_id',
            
            container:                      '#content-container',
            container_groups:               '#content-groups',
            content_groups_all:             '#content-groups-all',
            content_new_list_name_help:     '#new_list_name_help',
            content_list:                   '#content-list',
            content_list_head:              '#content-list-head',
            
            droppable:              '.group',
            draggable:              '.members',
            
            group:                  '#group',
            group_editable:         '.editable',
            
            
            control_list_warm_add_error:        '#list-warm-add-error-main',
            
            //template_new_list:      '#template-new-list',
            //template_create_list:   '#template-create-list',
            //template_user:          '#template-user',
            
            //userscreen:             null,
            
            //resetea el contenido del control y lo activa para usar
            resetControl: function(e, that) 
            {
                var options = that.options;
                
                //resetea el boton
                $(options.send_button).button('reset');
                
                $(options.list_modal).modal('hide');
                 
                $(options.list_id).val('');
                $(options.input_control_list_name).val('');
                $(options.input_control_list).val('');
                
                $(options.input_control_list_name)
                        .parent()
                        .removeClass('has-error');
                
                $(options.content_new_list_name_help).text('');
                
                $(options.input_control_list_name).focus();
            },
            
            resetControlError: function(e, that) 
            {
               var options = that.options;
               //resetea el boton
               $(options.send_button).button('reset');
            
               $(options.input_control_list_name)
                        .parent()
                        .addClass('has-error');
               
               $(options.input_control_list_name).focus();
            }
         
        },
        
        
        _create: function() 
        {
            this._initEventHandlers();
	},
        
        _initEventHandlers: function () 
        {
           var that = this;
           var options = this.options;
           
           $(options.group + '0').on('click', function (e){
                e.preventDefault();
                
                that._trigger('resetControl',null, that);
               
                $(options.list_modal)
                    .modal()
                    .show({
                        backdrop: true,
                        keyboard:true
                    });
                    
                $(options.input_control_list_name ).focus();
                 //$(options.input_control_list_name ).setCursorPosition(0);
           });
           
           $('body').bind('changeScrollContent', function (e, $node)
           {
                that._addNodeHandlers($node); 
           });
           
           $(options.group + '0').bind({
                mouseover: function (e) {
                    e.preventDefault();
                    var $item = $(e.currentTarget).children('button');
                    var oldText = $item.text();
                    
                    var newText = $item.attr('data-mouseover');
                    
                    $item.text(newText);
                    $item.attr('data-mouseover', oldText);
                },
                mouseout: function (e) {
                    e.preventDefault();
                    var item = $(e.currentTarget).children('button');
                    var oldText = item.text();
                    
                    var newText = item.attr('data-mouseover');
                    
                    item.text(newText);
                    item.attr('data-mouseover', oldText);
                }
            });
           
           
           
           $(options.list_modal).find('.close-modal').on("click",function(e)  
           {
               e.preventDefault();
               $(options.list_modal).modal('hide');
           });
           
          
           $(options.send_button).off();
           
           $(options.send_button).on("click", function (e) 
           {
                e.preventDefault();
                
                var $form = $(options.form_new_list);
               
                var formData = $form.find('input, select, textarea').serialize();
       
                var btn = $(e.currentTarget);
                //btn.button('loading');
               
                $.ajax({
                        type: "POST",
                        url: $form.attr('action'),
                        datatype: "json",
                        data: formData,
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status == 200)
                            {     
                                $(options.group + '0').before(dataJSON.HTML);
                                
                                var contenNode = $(options.group + dataJSON.values.id);
                       
                                //contenNode.slideDown();
                                contenNode.removeClass('hide');
                                //contenNode.animate({'backgroundColor':'#ffff'},200);
                                
                                that._addListHandlers(contenNode);
                                
                                //oculta el elemento para agregar listas
                                if (dataJSON.values.isLasListToAdd)
                                {
                                  $(options.group + '0')
                                        .hide()
                                        .removeClass('group-active');
                                }
          
                                that._trigger('resetControl',null, that);
                            }
                            else if ((dataJSON.status == 500) 
                                &&(dataJSON.errors.hasOwnProperty('name')))
                            {
                              $(options.content_new_list_name_help)
                                .text(dataJSON.errors.name);
                                
                              that._trigger('resetControlError',null, that);
                            }
                            else
                            {
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
            });
           
           $(options.draggable).live("mouseenter", function(){
        
                var $this = $(this);
                if(!$this.is(':data(draggable)'))
                {
                    $this.draggable({
                        revert: false,
                        helper: "clone",
                        start: function(event, ui) {
                            ui.helper.removeClass('hand-open');
                            ui.helper.addClass('hand-close');
                            
                            if (ui.helper.hasClass('members'))
                            {
                              ui.helper.prepend($("<span class='draggable draggable-plus'></span>"));  
                            }
                        },
                        drag: function(event, ui){
                         
                          $.lastPosition.setPos(ui.helper);
                        },
                        stop:function(evt,ui){

                            ui.helper.removeClass('hand-close');
                            ui.helper.addClass('hand-open');
                       
                            ui.helper.find('.draggable').remove();
                            
                        },
                        containment: "document",
                        cursor: "move"
                    });
                }
            });
            
            
            $(options.droppable).livequery(function(){
                 $(this).droppable({
                    //clases activas marcadas
                    activeClass: "state-highlight",
                    drop: function( event, ui ) 
                    {
                           
                        var $userNode = $(ui.draggable)
                        ,   $listNode = $(this);
                        
                        if ( $listNode.data('id') == 0)
                        {
                            that._trigger('resetControl',null, that);
                            
                            $(options.list_modal)
                                    .modal()
                                    .show({
                                        backdrop: true,
                                        keyboard:true
                                    });
                                    
                            $(options.input_control_list).val($userNode.data('id'));
                            
                            $(options.input_control_list_name ).focus();                   
                        }
                        else if ($.nodeArrOp.find($userNode,$listNode) < 0)
                        {
                             $.nodeArrOp.add($userNode, $listNode);
                             that.sendAddListUser($userNode, $listNode);
                        }
                        else
                        {
                                $(options.control_list_warm_add_error).removeClass('hide');
                                $(options.control_list_warm_add_error).fadeIn(200);
                           
                                setTimeout(function() {
                                    $(options.control_list_warm_add_error).fadeOut('fast');
                                }, 1500);     
                        }
                        
                        
                    },
                    over: function(event, ui) {
                        var $userNode = $(ui.draggable)
                        ,   $listNode = $(this);
                        
                        if ($listNode.data('id') == 0)
                        {
                          $listNode.children('button').removeClass('btn-success');
                          $listNode.children('button').addClass('btn-warning');   
                        }
                        else if ($.nodeArrOp.find($userNode,$listNode) >= 0)
                        {
                          $listNode.children('button').addClass('btn-danger');
                        }
                        else
                        {
                         $listNode.children('button').addClass('btn-warning');       
                        }
                        
                    },
                    out: function(event, ui) 
                    {
                        var groupNode = $(this);
                        
                        if (groupNode.attr("data-id") == 0)
                        {
                          groupNode.children('button').removeClass('btn-warning');
                          groupNode.children('button').addClass('btn-success');   
                        }
                        else
                        {
                          groupNode.children('button').removeClass('btn-danger');
                          groupNode.children('button').removeClass('btn-warning');      
                        }
                    },
                    
                    deactivate: function (event, ui) { 
                        var groupNode = $(this);                  
                        
                        if (groupNode.attr("data-id") == 0)
                        {
                          groupNode.children('button').removeClass('btn-warning');
                          groupNode.children('button').addClass('btn-success');   
                        }
                        else
                        {
                          groupNode.children('button').removeClass('btn-danger');
                          groupNode.children('button').removeClass('btn-warning');      
                        }
                        
                    }
                });
            });
            
            
        },
       
        _addListHandlers: function ($node)
        {
            var listEditor = $node.listeditor().data('listeditor');
           
            $node.on('click', function (e)
            {
                e.preventDefault();
                
                listEditor.getUsersList(true);
             });
        },
        /**
         * nuestra o oculta los bottones para añadir 
         * un usuario 
         * 
         * @param {$node}: jquery node del usuario
         * @param {object}: botones a mostrar
         */
        _renderUserNodeCMD: function ($node, values)
        {
            var options = this.options
            , $nodeRemoveSuscriptor = $node.find(options.cmd_remove_suscriptor)
            , $nodeRemoveRequest = $node.find(options. cmd_remove_request)
            , $nodeAddUser = $node.find(options.cmd_add_user);
            //oculta todo
            $nodeAddUser.hide();
            $nodeRemoveRequest.hide();
            $nodeRemoveSuscriptor.hide();
            
            if (values.add_user)
            {
              $nodeAddUser.removeClass('hide');
              $nodeAddUser.show();      
            }
            else if (values.remove_request)
            {
              $nodeRemoveRequest.removeClass('hide');
              $nodeRemoveRequest.show();       
            }
            else if (values.remove_suscriptor)
            {
               $nodeRemoveSuscriptor.removeClass('hide');
              $nodeRemoveSuscriptor.show();      
            }
        },
        /**
         * @param {object}: estado de los botones
         * 
         * @return {boolean} : tipo de actualización true = remove, false = add
         */
        _updateType: function (values)
        {
            return (values.add_user 
                    && !values.remove_request
                    && !values.remove_suscriptor)?true:false;
        },
        
        _removeUserFromList: function ($userNode, $listNode)
        {
            var $userN = $userNode || false
            , $listN = $listNode || false;
            
            if ($userN && $listN && this._updateUserFromList($userN, $listN))
            {
                $userN.fadeOut('fast');     
            }
        },
        
        _updateUserFromList: function ($userNode, $listNode)
        {
                var arrayPosIndex = $.nodeArrOp.find($userNode, $listNode)
                , ret = (arrayPosIndex >= 0);
                
                if (ret)
                {
                   var dataItemsJson = $.nodeArrOp.remove($userNode, $listNode);
                   
                   $listNode.data('items', dataItemsJson );
                   
                   $listNode.data('count', dataItemsJson.length );
                   $listNode.find('.count').text('(' + $listNode.data('count') + ')');
                   
                    var $addDiv = $listNode.find('.animate')
                                    .addClass('animate-minus')
                                    .css('margin-top', 0)
                                    .removeClass('hide')
                                    .show();
            
                    setTimeout(function() {
                        $addDiv.animate({"margin-top": "-=35px"}, "fast");
                        $addDiv.fadeOut("slow");
                     }, 300);
                }
                
                return ret;
        },
        _updateUserFromAllList: function ($userNode)
        {
            var options = this.options
            , that   = this;
             
            $(options.group_editable ).each(function() {
                that._updateUserFromList($userNode, $(this));
            });
        },
        
        _fixActiveListCounter: function ()
        {
           var options = this.options
           , dataItemsJson = $.nodeArrOp.fix($(options.content_list));
           
           $(options.content_list_head).find('.count').text('(' + dataItemsJson.length + ')');
        },
        
        _addNodeHandlers: function ($node)
        {
           var that = this
           , options = this.options;
           
           $node.find(options.cmd_add_user + ',' + options.cmd_remove_suscriptor + ',' + options. cmd_remove_request).each(function() {
                $(this).on('click', function (e){
                    e.preventDefault();
                    
                     var $nodeMember = $(e.currentTarget).parent(options.members)
                      , $form = $(options.form_friend_no_list);
 
                    $(options.input_control_add_friend).val($nodeMember.data('id'));
                    
                    var formData = $form.find('input, select, textarea').serialize();
                    
                    $.ajax({
                        type: "POST",
                        url: $form.attr('action'),
                        datatype: "json",
                        data: formData,
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status == 200)
                            {
                               that._renderUserNodeCMD($nodeMember,dataJSON.values);
                               
                               if (that._updateType(dataJSON.values))
                               {
                                 that._updateUserFromAllList($nodeMember);      
                               }
                             
                               if (that.userscreen.isListControlOpen())
                               {
  
                                 that._removeUserFromList(
                                            $(options.content_list).find('#' + $nodeMember.attr('id') ),
                                            $(options.content_list));
                                 
                                 that._fixActiveListCounter();
                                  
                               }
                            }
                        },
                        statusCode: {
                            404: function() {
                              
                            },
                            500: function() {
                             
                            }
                        },
                        error: function(dataJSON)
                        {
                           
                        }
                    });
                 });
           });
        },
        
        sendAddListUser: function( $userNode, $groupNode)
        {
            var that = this;
            var options = this.options
            , listId = $groupNode.data('id')
            , userId = $userNode.data('id');
            
            var form = $(options.form_add_list);
             
            $(options.input_control_list_add_id).val(listId);
            $(options.input_control_list_add_profile_id).val(userId);
           
            var formData = form.find('input, select, textarea').serialize();
       
            var $addDiv = $groupNode.find('.animate');
            
            $addDiv.removeClass('animate-minus');
            $addDiv.css('margin-top', 0)
                .removeClass('hide')
                .show();
                                          
            $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        datatype: "json",
                        data: formData,
                        cache: false,
                        success: function(dataJSON)
                        {
                            $addDiv.animate({"margin-top": "-=35px"}, "fast");
                            $addDiv.fadeOut("slow");
                                
                            if (dataJSON.status == 200)
                            {
                                $groupNode.find('.count').text('(' + $groupNode.data('items').length + ')'); 
                                
                                that._renderUserNodeCMD($userNode, dataJSON.values);
                            }
                            
                        },
                        statusCode: {
                            404: function() {
                               $addDiv.fadeOut("slow");
                            },
                            500: function() {
                              $addDiv.fadeOut("slow");
                            }
                        },
                        error: function(dataJSON)
                        {
                           $addDiv.fadeOut("slow");
                        }
                    });
        },
        /**
         * el control tiene contenido esperando ser procesado
         * 
         * @return <boolean>: true tiene contenido
         */
        _hasContent: function (item) {
           return (item && (item.length > 0));
        },
        /**
         * el control tiene contenido que puede ser válido
         * @return <boolean>: true tiene contenido valido
         */
        validate: function()
        {
            var item = $.trim(this.element.val());
            
            return (this._hasContent(item));
        },
        userscreen: false,
        _init: function()
        {
            var that = this
            , options = this.options;
            
            this.userscreen = $(document).data('userscreen');
            
            $(options.container_groups)
                    .children(options.group_editable)
                    .each(function() {
                
                that._addListHandlers($(this));
            });
            
            that._addNodeHandlers($(options.container));
        }
});