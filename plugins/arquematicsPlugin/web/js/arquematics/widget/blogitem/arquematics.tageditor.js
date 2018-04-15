/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 */
$.widget( "arquematics.tagdeditor", {
	options: {
                         
            tags_list:                         '#a_blog_post_tags_list',
            
            form_search_tag:                   '',
            input_control_has_to_search:       '#a_blog_tag_search_tag',
            
            cmd_send_tag:                      '#cmd-send-tag',
            
            onChangeContent: function (data)
            {
                
            },
            resetControl: function(e, that) 
            {
              var btn = $(e.currentTarget);
              btn.button('reset');
              
              $(that.element).find('.ui-control-text-form').removeClass('error');
              
              if (that.options.resetInput)
              {
                $(that.element).find('.ui-control-text-input').val('');       
              }
              
            },
            
            resetControlError: function(e, that) 
            {
              var btn = $(e.currentTarget);
              btn.button('reset');
              
              $(that.element).find('.ui-control-text-form').addClass('error');
            },
            onAddContent: function (e, dataJSON)
            {
                
            }
	},
        
        _create: function() {
                this._initEventHandlers();
	},
        
        _initEventHandlers: function () {
          
            var that = this;
            var options = this.options;
            
             $(this.element).autocomplete({
                    source: function(request, response) {
                        that._autocomplete( {'term': request.term }, function(results, status) {
                            response($.map(results, function(item) {
                                return {
                                    value: item.name,
                                    id:    item.id
                                };
                            }));
                        });
                    },
                   
                    select: function(event, ui) {
                       
                       $(that.element).val(ui.item.value);
                       
                       $(options.input_control_has_to_search).val(false);
                       
                       $(options.cmd_send_tag).click();
                    }
               });
               
        },
        
        _autocomplete: function (term, callBack)
        {
            var options = this.options;
            var searchVal = $.trim($(this.element).val());
            
            //hace las busquedas a partir de 2 caracteres
            if (searchVal.length > 2)
            {
                $(options.input_control_has_to_search).val(true);
            
                var formData = $(options.form_search_tag).find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: $(options.form_search_tag).attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    success: function(dataJSON)
                    {
                        if (dataJSON.status === 200)
                        {
                            $(options.input_control_has_to_search).val(false);
                            callBack.call(this, dataJSON.values ,200);
                        }
                        else
                        {
                            $(options.input_control_has_to_search).val(false);
                            callBack.call(this, [],500);      
                        }
                    },
                    statusCode: {
                        302: function(){
                            $(options.input_control_has_to_search).val(false);
                            callBack.call(this, [], 302);  
                        },
                        404: function() {
                            $(options.input_control_has_to_search).val(false);
                            callBack.call(this, [],404);
                        },
                        500: function() {
                            $(options.input_control_has_to_search).val(false);
                            callBack.call(this, [],500);
                        }
                    },
                    error: function(dataJSON)
                    {
                        $(options.input_control_has_to_search).val(false);
                        callBack.call(this, [],501);
                    }
                }); 
            }
        },
        _init: function ()
        {
         
        }
});