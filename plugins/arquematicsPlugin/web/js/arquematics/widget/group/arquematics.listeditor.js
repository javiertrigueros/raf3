/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias:
 *  - bootstrap-dropdown.js
 *  - bootstrap-alert.js
 */

$.widget( "arquematics.listeditor", {
	options: {
            list_modal_edit:                       '#list-modal-edit',
             
            //formulario usuarios en lista
            form:                                  '#form_get_list',
            input_control_get_list_id:             '#get_list_id',
            input_control_get_list_page:           '#get_list_page',
            
            //formulario editar nombre de lista
            form_edit_list:                         '#form_edit_list',
            input_control_list_edit_id:             '#list_edit_id',
            input_control_list_edit_name:           '#list_edit_name',
           
             //formulario agregar id a lista
            form_add_list:                         '#form_add_list',
            input_control_list_add_id:             '#list_add_id',
            input_control_list_add_profile_id:     '#list_add_profile_id',
            
            //formulario borrar profile de lista
            form_delete:                             '#form_list_delete',
            input_control_delete_list_id:            '#delete_list_id',
            input_control_delete_list_profile_id:    '#delete_list_profile_id',
            
            //formulario borrar lista
            form_delete_all:                         '#form_list_delete_all',
            input_control_delete_list_all_id:        '#delete_list_all_id',
            
   
            cmd_cancel:                              "#list-cancel",
            cmd_cancel_extra:                        "#menu-close-link",
            cmd_delete_user:                         ".close",
            cmd_delete_list:                         "#menu-list-delete",
            cmd_edit_list:                           "#menu-list-edit",
            cmd_edit_cancel:                         '#cmd-edit-cancel',
            cmd_edit:                                '#cmd-edit',
            cmd_delete_list_cancel:                  '#cmd-delete-list-cancel',
            cmd_delete_list_confirm:                 '#cmd-delete-list-confirm',
            
            
            container:                      '#content-container',
            container_insert:               '#list-loader',
            container_groups:               '#content-groups',
            content_groups_all:             '#content-groups-all',
            content_active_list:            '#content-active-list',
            content_list:                   '#content-list',
            content_edit_list_name_help:    '#edit_list_name_help',
            content_list_modal_delete:      '#list-modal-delete',
            
            content_group:                  '#group',
            content_group_text:             '.list-name-text',
            
            control_list_name:                  '#list-name',
            control_list_warm_add_error:        '#list-warm-add-error-main',
            
            content_list_text:                  '#list-text',
           
            content_header:                     '#header',
            container_insert_list_node:         '#members-list',
            container_insert_users_node:        '#members-minus',
           
            content_separation:             '#separation',
            
            template_list:                  '#template-list',
            template_remove_list_modal:     '#template-remove-list-modal',
            
            droppable:              '#content-container',
            draggable:              '.members-list',
            
            draggable_members:         '.members',
            draggable_members_list:    '.members-list',
            
            scrolling:              false,
            counter:                1,
            trigger :               40,  //disparador en %
            

            isInDataRequest:        false,
                 
            //resetea el contenido del control y lo activa para usar
            resetControl: function(e, that) 
            {
                var options = that.options;
                //resetea el boton
                $(options.cmd_edit).button('reset');
                
                $(options.input_control_list_edit_name)
                                    .parent()
                                    .removeClass('has-error');
                
                $(options.list_modal_edit).modal('hide');
                
                $(options.content_edit_list_name_help).text('');
                
                $(options.input_control_list_edit_name).focus();
            },
            
            resetControlError: function(e, that) 
            {
               var options = that.options;
               //resetea el boton
               $(options.cmd_edit).button('reset');
            
               $(options.input_control_list_edit_name)
                                    .parent()
                                    .addClass('has-error');
               
               $(options.input_control_list_edit_name).focus();
               
            }
         
        },
        _create: function() 
        {
           
	},
        
        _renderList: function(item) 
        {
             var options = this.options;
             
             var data = $(options.template_list).tmpl( item )
                                .appendTo(options.content_active_list);  
                  
             return data;
         },
         _renderModalDeleteList: function(item)
         {
             var options = this.options;
             
             var data = $(options.template_remove_list_modal).tmpl( item )
                                .appendTo(options.content_list_modal_delete);  
                  
             return data;
         },
       
        _initControlHandlers: function () 
        {
           var that = this;
           var options = this.options;
          
           $(options.control_list_name).dropdown();
           
           $(options.cmd_cancel + "," + options.cmd_cancel_extra).on('click', function (e){
                e.preventDefault();
                
                that.userscreen.bindScrollControl(true);
                that.userscreen.repaint();
           });
           
            $(options.content_list).unbind('scroll');
                 
            $(options.content_list).scroll(function(){
                     var wintop = $(options.content_list).scrollTop();
                     var docheight  = $(options.content_list)[0].scrollHeight;
                     //var controlHeight  = $(options.container).height();
                     var controlHeight  = $(options.content_list).height();
                     if (Math.round( wintop * 100 / ( docheight - controlHeight )) > options.trigger)
                     {
                       that.getUsersList(false);      
                     }
                     
             });
           
           $(options.cmd_edit_list).on('click', function (e){
                e.preventDefault();
                that._trigger('resetControl',null, that);
                
                var listName = $.trim(that.element.find(options.content_group_text).text());
                var listId = that.element.attr("data-id");
                
                $(options.input_control_list_edit_id).val(listId);
                $(options.input_control_list_edit_name).val(listName);
                
                $(options.list_modal_edit)
                                    .modal()
                                    .show({
                                        backdrop: true,
                                        keyboard:true
                                    });
                
                $(options.input_control_list_edit_name).focus();
           });
           
           $(options.cmd_edit).off();
           
           $(options.cmd_edit).on('click', function (e){
                e.preventDefault();
                
                var btn = $(e.currentTarget);
                btn.button('loading');
                
                var form = $(options.form_edit_list);
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
                              $(options.content_group + dataJSON.values.id).find(options.content_group_text).text(dataJSON.values.name);
                              
                              $(options.content_active_list).find(options.content_group_text).text(dataJSON.values.name);
                              
                              that._trigger('resetControl',null, that);
                            }
                            else if ((dataJSON.status === 500) 
                                &&(dataJSON.errors.hasOwnProperty('name')))
                            {
                              $(options.content_edit_list_name_help).text(dataJSON.errors.name);
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
           
           $(options.cmd_edit_cancel).off();
            
           $(options.cmd_edit_cancel).on("click",function(e)  
           {
               e.preventDefault();
               $(options.list_modal_edit).modal('hide');
               
               that.userscreen.repaint();
           });
           
           
           $(options.cmd_delete_list).on('click', function (e){
                e.preventDefault();
                
                var node = $(e.currentTarget);
                
                $(options.input_control_delete_list_all_id).val(node.attr("data-id"));
                
            
                $(options.content_list_modal_delete).empty();
                
                that._renderModalDeleteList({listname: node.attr("data-name")});
                
                $(options.content_list_modal_delete).modal('show');
                
                $(options.cmd_delete_list_cancel).off();
                $(options.cmd_delete_list_cancel).on('click', function (e){
                     e.preventDefault();
                     
                     $(options.content_list_modal_delete).modal('hide');
                     
                     that.userscreen.repaint();
                });
                
                $(options.cmd_delete_list_confirm).off();
                $(options.cmd_delete_list_confirm).on('click', function (e){
                     e.preventDefault();
                     
                     var btn = $(e.currentTarget);
                     btn.button('loading');
                     
                     that._sendDeleteAllList();
                });
                
                
           });
           
           
           $(options.draggable).live("mouseenter", function(e){
        
                var $this = $(this);
                if(!$this.is(':data(draggable)'))
                {
                    $this.draggable({
                        revert: false,
                        helper: "clone",
                        start: function(event, ui) {
                            ui.helper.removeClass('hand-open');
                            ui.helper.addClass('hand-close');
                            
                            if (ui.helper.hasClass('members-list'))
                            {
                              ui.helper.prepend($("<span class='draggable draggable-minus'></span>"));      
                            }
                        },
                        drag: function(event, ui){
                        
                          $.lastPosition.setPos(ui.helper);
                        },
                        stop:function(event, ui){
                            
                            ui.helper.removeClass('hand-close');
                            ui.helper.addClass('hand-open');
                            
                            ui.helper.find('.draggable').remove();
                           
                        },
                        containment: "document",
                        cursor: "move"
                    });
                }
            });
            
            //agrega usuario a la lista activa
            $(options.content_active_list).livequery(function(){
                 $(this).droppable({
                    //clases activas marcadas
                    activeClass: "state-highlight-add",
                    drop: function( event, ui ) 
                    {
                        if (ui.helper.hasClass('members'))
                        {
                            var $userNode = $(ui.draggable)
                            ,   $listNode = $(options.content_list);
                            
                            if ($.nodeArrOp.find($userNode,$listNode) < 0)
                            {
                                that._moveToNode(ui.helper, $(options.container_insert_list_node));

                                that._addUserToList($userNode, $listNode);      
                            }
                            else 
                            {
                                $(options.control_list_warm_add_error).removeClass('hide');
                                
                                $(options.control_list_warm_add_error).fadeIn(200);
                           
                                setTimeout(function() {
                                    $(options.control_list_warm_add_error).fadeOut('fast');
                                }, 1500);
                            }
                        }
                    },
                    over: function(event, ui) {
                        
                       if (ui.helper.hasClass('members'))
                       {
                         $(options.content_list).animate({scrollTop: 0}, 10);
                          
                         var $userNode = $(ui.draggable)
                         ,   $listNode = $(options.content_list);
                         
                         if ($.nodeArrOp.find($userNode,$listNode) >= 0)
                         {
                           //rojo
                           $(options.container_insert_list_node).addClass('node-insert-error');   
                         }
                         else
                         {
                           $(options.container_insert_list_node).addClass('node-insert-warning');  
                         } 
                       }
                    },
                    out: function(event, ui) 
                    {
                       //TODO: javier mirar esto
                       $(options.container_insert_list_node).removeClass('node-insert-error');
                       $(options.container_insert_users_node).removeClass('node-insert-error');

                    },
                    deactivate: function (event, ui) 
                    { 
                         //TODO: javier mirar esto
                         // mirar that._addUserToList si termina bien
                         $(options.container_insert_list_node).removeClass('node-insert-error');
                         $(options.container_insert_users_node).removeClass('node-insert-error');
                    }
                });
            });
            
            //borra usuario de la lista activa
            $(options.droppable).livequery(function(){
                 $(this).droppable({
                    //clases activas marcadas
                    activeClass: "state-highlight-remove",
                    drop: function( event, ui ) 
                    {
                       if (ui.helper.hasClass('members-list'))
                       {
                            var $userNode = $(ui.draggable)
                            ,   $listNode = $(options.content_list);
                         
                            if ($.nodeArrOp.find($userNode,$listNode) >= 0)
                            {
                               that._moveToNode(ui.helper,  $(options.container_insert_users_node));
                               that._removeUserAtList($userNode, $listNode);      
                            }
                       }
                       
                    },
                    over: function(event, ui) 
                    {
                       if (ui.helper.hasClass('members-list'))
                       {
                         $(options.container).animate({scrollTop: 0}, 10); 
                         
                         $(options.container_insert_users_node).addClass('node-insert-warning');
                       }
                    },
                    activate: function(event, ui) {
                      
                    },
                    out: function(event, ui) 
                    {
                       if (ui.helper.hasClass('members-list'))
                       {
                          $(options.container_insert_users_node).removeClass('node-insert-warning'); 
                       }
                    }
                });
            });
        },
        _sendDeleteAllList: function()
        {
            var that = this;
            var options = this.options;
           
            var form = $(options.form_delete_all);
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
                                $(that.element).remove();
                               
                                $(options.content_list_modal_delete).modal('hide');
                                
                                if (dataJSON.values.canAddList)
                                {
                                    $(options.content_group + '0')
                                        .removeClass('hide')
                                        .show()
                                        .addClass('group-active');
                                }
                                
                                that.userscreen.bindScrollControl(true);
                                that.userscreen.repaint();
                            }
                            else
                            {
                              $(options.content_list_modal_delete).modal('hide');
                            }
                        },
                        statusCode: {
                            404: function() {
                               $(options.content_list_modal_delete).modal('hide');
                            },
                            500: function() {
                               $(options.content_list_modal_delete).modal('hide');
                            }
                        },
                        error: function(dataJSON)
                        {
                          $(options.content_list_modal_delete).modal('hide');
                        }
            });
            
        },
        
        addUserNodeToGeneralList: function($userNode)
        {
            var options = this.options;
            
            $userNode.addClass('member-highlight');
            //busca un nodo similar y lo borra
            //si es necesario
            $(options.container)
                .find('#' + $userNode.attr('id'))
                .remove();
                                                       
            $(options.container_insert_users_node).after($userNode);

            $(options.container_insert_users_node).removeClass('node-insert-warning');
           
            this.subscribers._addNodeHandlers($userNode);
            
            setTimeout(function() {
                $userNode.removeClass('member-highlight');
            }, 400);
        },
        /**
         * Borra un profile de la lista
         */
        _sendDeleteList: function(listId, profileId)
        {
            var that = this;
            var options = this.options;
            
            $(options.input_control_delete_list_id).val(listId);
            $(options.input_control_delete_list_profile_id).val(profileId);
                
            var form = $(options.form_delete);
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
                               that.addUserNodeToGeneralList($(dataJSON.HTML));   
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
       
            
        },
        /**
         * es solo una animación
         * 
         */
        _moveToNode: function (node,  nodeToMove)
        {
            var nodeLastPosition = $.lastPosition.getPos();
            var x = node.clone();
            x.appendTo('body')
                .css({position: 'absolute',top: nodeLastPosition.top ,left: nodeLastPosition.left})
                .animate({
                    //height: (nodeToMove.height()),
                    width: (nodeToMove.width()),
                    padding: nodeToMove.css( "padding-top") -3,
                    top: nodeToMove.position().top ,
                    left: nodeToMove.position().left  },
                'fast',
                function(){
                    setTimeout(function(){  x.remove(); }, 500)
                }
            );
                
            x.find('.user-thumbnail').animate({
                    height: (x.find('.user-thumbnail').height()),
                    width: (x.find('.user-thumbnail').width()),
                    top: nodeToMove.position().top , left: nodeToMove.position().left +10 },
                'fast');
        },
        getUsersList: function(initHandlers)
        {
             var that = this;
             var options = this.options;
             
             if (!options.isInDataRequest)
             {
               options.isInDataRequest = true;
               
               $(options.input_control_get_list_id).val(parseInt(that.element.attr("data-id")));
              
               //reset counter
               options.counter = initHandlers?1:options.counter;
                   
               $(options.input_control_get_list_page).val(options.counter);
                
               var form = $(options.form);
               var formData = form.find('input, select, textarea').serialize();
               
               $(options.container_insert).removeClass('hide');
               $(options.container_insert).show();
               
               $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        datatype: "json",
                        data: formData,
                        cache: false,
                        success: function(dataJSON)
                        {
                            options.isInDataRequest = false;
                            
                            if (dataJSON.status === 200)
                            {
                              var $nodes = $(dataJSON.HTML);
                               
                              if (initHandlers)
                              {
                                  //borra el contenido antiguo
                                  $(options.content_active_list).empty();
                                  
                                  that._renderList({
                                    id: dataJSON.values.id, 
                                    name: dataJSON.values.name,
                                    count: dataJSON.values.count,
                                    items: dataJSON.values.items,
                                    owner_id: dataJSON.values.owner_id});
                              
                                  that._initControlHandlers();
                                  
                                  $(options.container_insert).before($nodes);
                                  that._addNodeHandlers($nodes);

                                  $(options.container_insert).hide();

                                  that.userscreen.bindScrollControl(false);
                                  that.userscreen.repaint();
                                  
                                  that.nodeContentListHeight = $.getNodeHeight($(options.content_list));
                              }
                              else
                              {
                                 $(options.container_insert).before($nodes);
                                 that._addNodeHandlers($nodes);
                                 $(options.container_insert).hide();
                              }
                              
                              that.totalListUsers = parseInt(dataJSON.values.count);
                          
                              if (dataJSON.values.isLastPage)
                              {
                                $(options.content_list).unbind('scroll');     
                              }
                              else
                              {
                                options.counter++;
                                
                                if ($.getNodeHeight($(options.content_list)) <= (2 * that.nodeContentListHeight))
                                {
                                  that._doForceLoad();      
                                }
                              }
                            }
                        },
                        statusCode: {
                            404: function() {
                               options.isInDataRequest = false;
                               $(options.container_insert).hide();
                            },
                            500: function() {
                               options.isInDataRequest = false;
                               $(options.container_insert).hide();
                            }
                        },
                        error: function(dataJSON)
                        {
                            options.isInDataRequest = false;
                            $(options.container_insert).hide();
                        }
                    });
               
             }
        },
        _removeUserAtList: function($userNode, $listNode)
        {
            if ($.nodeArrOp.find($userNode,$listNode) >= 0)
            {
                var options = this.options
                , dataItemsJson = $.nodeArrOp.remove($userNode, $listNode)
                , elem = $(this.element);
    
                elem.find('.count').text('(' + dataItemsJson.length + ')');
                elem.data('items', dataItemsJson);
                elem.data('count', dataItemsJson.length);
                
                $listNode.data('items', dataItemsJson);
                $listNode.data('count', dataItemsJson.length)

                $userNode.remove();

                $(options.content_active_list).find('.count').text('(' + dataItemsJson.length + ')');
                
                this._sendDeleteList($listNode.data('id'), $userNode.data('id'));
            }
            
        },
        _sendEditList: function (listId, userID)
        {
            var that = this;
            var options = this.options;
            
            var form = $(options.form_add_list);
             
            $(options.input_control_list_add_id).val(listId);
            $(options.input_control_list_add_profile_id).val(userID);
           
            
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
                               var $userNode = $(dataJSON.HTML)
                               , $generalListUserNode = $(options.container).find("[data-id='" + $userNode.data('id') + "']");
                        
                               $userNode.addClass('member-highlight');       
                               
                               $(options.container_insert_list_node).removeClass('node-insert-warning'); 
                               
                               $(options.container_insert_list_node).after($userNode);
                               
                               that._addNodeHandlers($userNode);
                               
                               $generalListUserNode.hide();
                               that.subscribers._renderUserNodeCMD($generalListUserNode,dataJSON.values);
                               
                               setTimeout(function() {
                                $userNode.removeClass('member-highlight');
                               }, 400);
                              
                            }
                            else
                            {
                                
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
            
        },
        _addUserToList: function($userNode, $listNode)
        {
            var  elem = $(this.element)
            , dataItemsJson = $.nodeArrOp.add($userNode, $listNode);
            
            elem.data('items', dataItemsJson);
            elem.data('count', dataItemsJson.length); 
            
            elem.find('.count').text('(' + dataItemsJson.length + ')');

            $(this.options.content_active_list).find('.count').text('(' + dataItemsJson.length + ')');
            
            this._sendEditList($listNode.data('id'), $userNode.data('id'));
        },

        _addNodeHandlers: function ($node)
        {
            var that = this;
            var options = this.options;
            
            $node.find(options.cmd_delete_user).on('click', function (e){
                e.preventDefault();
                
                var $userNode = $(e.currentTarget).parent();
                $userNode.fadeOut('fast');
                
                that._removeUserAtList($userNode, $(options.content_list));
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
        
        userscreen: null,
        subscribers: null,
        
        totalListUsers: 0,
        //tamaño real inicial del elemento
        nodeContentListHeight: 0,
        _doForceLoad: function()
        {
          var options = this.options
          ,  countViewUsers = $(options.content_list + ' ' + options.draggable_members_list).length;
          
          if (countViewUsers <= this.totalListUsers)
          {
            this.getUsersList(false);    
          }
        },
        _init: function()
        {   
            this.userscreen = $(document).data('userscreen');
            
            this.subscribers = $('#content-groups').data('subscribers');
        }
});