/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 */
$.widget( "arquematics.blogpublisher", {
	options: {
          
            form:                         '#form-update',
            
            cmd_update_button:            '#update-button',
            
            takeBackUrl:                    '',
       
            is_event:                        false,
            node_date_range:                '#ui-date-range',
            fieldsToSend:                   [],
            
           
            resetControl: function(e, that) 
            {
                //activa el boton
                var btn = $(that.options.cmd_update_button);
                btn.button('reset');
                
                this._hasSendData = false;
                
            },
            
            resetControlError: function(e, that) 
            {
                 //activa el boton
                var btn = $(that.options.cmd_update_button);
                btn.button('reset');
                
                this._hasSendData = false;
                
            }
	},
        //flag cuando esta enviando datos
        _hasSendData: false,
        
        _create: function() {
                this._initEventHandlers();
                
                this._trigger("complete", null, { value: 100 });
	},
        _initEventHandlers: function () {
            var that = this;
            var options = this.options;

            $(options.cmd_update_button).on("click", function (e) 
            {
               that._sendControl(e); 
            });
        },
        
        _sendControl: function(e)
        {
            e.preventDefault();
            
            
            var options = this.options;
            
            var btn = $(options.cmd_update_button);
            btn.button('loading');
            
            if (!this._hasSendData)
            {
               this._hasSendData = true;
               
               this.sendContent(e);     
            }
        },
      
        
        sendContent: function (e)
        {
            var that = this;
            var options = this.options;
            
            var formData = {};
            var data = [];
            if (options.is_event)
            {
                $.each(that.options.fieldsToSend, function(index, node) {
                   if ($(node).is('select'))
                   {
                       if ($(node).val() !== null)
                       {
                         data[$(node).attr("name")] = $(node).val();       
                       }  
                   }
                   else if ($(node).is(':checkbox'))
                   {
                       
                         data[$(node).attr("name")] =  $(node).is(':checked')?1:0;
                   }
                   else
                   {
                     data[$(node).attr("name")] = $(node).val();      
                   }
                 
                   
                });
                
                var $controlsDateRange =  $(options.node_date_range).data('arquematics-datetimerange').getInputControls();

                $.each($controlsDateRange, function(index, $nodeControl) {
                    
                    data[$nodeControl.attr("name")] = $nodeControl.val();
                });
            }
            else
            {
               var $controls = $(options.form).find('input, select, textarea');
               
                $.each($controls, function(index, node)
                {
                    if ($(node).is('select'))
                    {
                      if ($(node).val() !== null)
                      {
                         data[$(node).attr("name")] = $(node).val();       
                      }  
                            
                    }
                    else if ($(node).is(':checkbox'))
                    {
                         data[$(node).attr("name")] =  $(node).is(':checked')?1:0;
                    }
                    else
                    {
                      data[$(node).attr("name")] = $(node).val();      
                    }
                    
                });
            }
           
           formData = $.extend({}, data, formData);
         
            $.ajax({
                type: "POST",
                url: $(options.form).attr('action'),
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if (dataJSON.status === 200)
                    {
                       
                       setTimeout(function(){
                           window.location = options.takeBackUrl;
                       },20);
                         
                    }
                    else
                    {
                       that._trigger('resetControlError',e, that);      
                    }
                },
                statusCode: {
                    404: function() {
                       that._trigger('resetControlError',e, that); 
                    },
                    500: function() {
                       that._trigger('resetControlError',e, that);  
                    }
                },
                error: function(dataJSON)
                {
                  that._trigger('resetControlError',e, that);  
                }
            });
            
        }
});