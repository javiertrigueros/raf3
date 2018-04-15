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

$.widget( "arquematics.friends", {
	options: {
          
           //formulario cancelar o agregar suscripcion
           form_add_list:                          '#add_friend',
           input_control_accept_profile_list_id:   '#accept_profile_list_id',
           input_control_accept_profile_id:        '#accept_profile_id',
           input_control_accept_is_accept:         '#accept_is_accept',
            
           container_friends:                      '#content-friends',
           container_ignore:                       '#content-ignore',
          
           showOnLoad:             '',
           
           cmd_ignore:                              '.ignore',
           cmd_accept:                              '.accept',
            
           resetControl: function(e, that) 
           {
                var options = that.options;
                
           },
            
           resetControlError: function(e, that) 
           {
               var options = that.options;
               
               var btn = $(e.currentTarget);
               btn.button('reset');
           }
         
        },
        _create: function() 
        {
 
	},       
        addIgnoreHandlers: function (node)
        {
            var that = this;
            var options = this.options;
           
            
            node.find(options.cmd_accept).on("click", function (e) 
            {
                e.preventDefault();
                
                var $btn = $(e.currentTarget);
                 //desactiva el botton
                $.buttonControlStatus($btn, false);
                
                var userNode = $btn.parents('.user');
                
                var userID = parseInt($btn.data("friend-id"));
                
                $(options.input_control_accept_profile_list_id)
                $(options.input_control_accept_profile_id).val(userID);
                $(options.input_control_accept_is_accept).val(true);
                
                var form = $(options.form_add_list);
                var formData = form.find('input, select, textarea').serialize();
                
                 $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    beforeSend: function(){
                        userNode.find('.user-content').animate({'backgroundColor':'#fb6c6c'},300);
                    },
                    success: function(dataJSON)
                    {
                        
                        if (dataJSON.status === 200)
                        {
                            userNode.fadeOut(300,function(){
                                $.buttonControlStatus($btn, true);
                                userNode.remove();
                            });
                            
                            var node = $(dataJSON.HTML);
                            $(options.container_friends).prepend(node);
                            
                            that.addFriendsHandlers(node);
                        }
                        else 
                        {
                          $.buttonControlStatus($btn, true);
                          that._trigger('controlError',e, that);   
                        }
                    },
                    statusCode: {
                        404: function() {
                            $.buttonControlStatus($btn, true);
                            that._trigger('controlError',e, that); 
                        },
                        500: function() {
                            $.buttonControlStatus($btn, true);
                            that._trigger('controlError',e, that);  
                        }
                    },
                    error: function(dataJSON)
                    {
                        $.buttonControlStatus($btn, true);
                        that._trigger('controlError',e, that);  
                    }
                });
                    
            });
            
        },
        addFriendsHandlers: function (node)
        {
            var that = this;
            var options = this.options;
           
            
            node.find(options.cmd_ignore).on("click", function (e) 
            {
                e.preventDefault();
                
                var $btn = $(e.currentTarget);
                //desactiva el botton
                $.buttonControlStatus($btn, false);
                
                var userNode = $btn.parents('.user');
                
                var userID = $btn.data("friend-id");
                
                $(options.input_control_accept_profile_list_id)
                $(options.input_control_accept_profile_id).val(userID);
                $(options.input_control_accept_is_accept).val(false);
                
                var form = $(options.form_add_list);
                var formData = form.find('input, select, textarea').serialize();
                
                 $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    beforeSend: function(){
                        userNode.find('.user-content').animate({'backgroundColor':'#fb6c6c'},300);
                    },
                    success: function(dataJSON)
                    {
                        
                        if (dataJSON.status == 200)
                        {
                            userNode.fadeOut(300,function(){
                                $.buttonControlStatus($btn, true);
                                userNode.remove();
                            });
                            
                            var node = $(dataJSON.HTML);
                            
                            $(options.container_ignore).prepend(node);
                            
                            that.addIgnoreHandlers(node);
                        }
                        else 
                        {
                          $.buttonControlStatus($btn, true);
                          that._trigger('controlError',e, that);   
                        }
                    },
                    statusCode: {
                        404: function() {
                            $.buttonControlStatus($btn, true);
                            that._trigger('controlError',e, that); 
                        },
                        500: function() {
                            $.buttonControlStatus($btn, true);
                            that._trigger('controlError',e, that);  
                        }
                    },
                    error: function(dataJSON)
                    {
                        $.buttonControlStatus($btn, true);
                        that._trigger('controlError',e, that);  
                    }
                });
                    
            });
            
        },
        resetButtoms: function()
        {
          var options = this.options;
          
          $(options.cmd_ignore).each(function() {
              $.buttonControlStatus($(this), true);
          });

          $(options.cmd_accept).each(function() {
              $.buttonControlStatus($(this), true);
          });  
        },
         
        _init: function()
        {
            var options = this.options;
            
            this.addFriendsHandlers($(options.container_friends));
            
            this.addIgnoreHandlers($(options.container_ignore));
        }
});