/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  -arquematics.geomap
 */

/**
 * 
 * @param {jQuery} $
 * @param {arquematics} arquematics
 * @returns 
 */
(function($, arquematics) {

$.widget( "arquematics.userlocate", {
	options: {
            //cualquier texto
            regex:                                  '^(.*)+$',
            hideTextControl:                        true,
            resetInput:                             false,
            //cierra el control cuando envia con exito
            sendClose:                            true,
            //url para enviar los datos
            sendURL:                              false,
            //formulario que se va a enviar
            form:                                  false,
            
            input_control_hash:                   'locate[hash]',
            
            container_map:                        '.map-item',
            
            
            resetControl: function(e, that) 
            {
              var $btn = $(e.currentTarget);
              $btn.button('reset');
              
              that.$uiControlTextForm.removeClass('error');

              if (that.options.resetInput)
              {
                that.$uiControlInput.val('');       
              }
              
              that.$uiControlInput.focus();       
            },
            
            resetControlError: function(e, that) 
            {
              var $btn = $(e.currentTarget);
              $btn.button('reset');
              
              that.$uiControlTextForm.addClass('error');
              that.$uiControlInput.focus();
            },
            onAddContent: function (e, dataJSON)
            {
                
            }
	},
        
        _create: function() {
            this._loadDOM();
            this._initEventHandlers();
	},
        
        _initEventHandlers: function () {
            var elem = this.element;
            var that = this;
                                
            var $controlInput = this.$uiControlInput;
            
            $controlInput.on('keypress paste', that, function(e){
                return that._filter(e, $controlInput);
            });
            
            arquematics.geomap.geolocateControl($controlInput, this.$map );
            
            this.$uiControlText.on("click", function (e) 
            {
                e.preventDefault();
                that.open();
            });
            
            $(elem).find('.cancel').on("click", function (e) 
            {
                e.stopPropagation();
                e.preventDefault();
                
                that.close();
            });
            
            $(elem).find('.send').on("click", function (e) 
            {
                e.preventDefault();
                var $btn = $(e.currentTarget);
                $btn.button('loading');
                
                that.sendContent(e);
            });
            
        },
        _filter: function(event, inputTextControl) {
            var that = this;   
            var controlInputString = '';
            var regex = new RegExp(this.options.regex);
           
            if (event.type==='keypress') 
            {
              var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;

               //  8 = backspace, 9 = tab, 13 = enter, 35 = end, 36 = home, 37 = left, 39 = right, 46 = delete & .
              if (  key === 8 || key === 9 || key === 13 || key === 35 || key === 36|| key === 37 || key === 39 || key === 46) 
              {
                 //mirar 35 = #, 39 = ', 37 = %, 36 = $, 
                if (event.charCode === 0 && event.keyCode === key) {
                  return true;                                             
                }
              }
              
              controlInputString = inputTextControl.val() + String.fromCharCode(key);
            } 
            else if (event.type === 'paste') 
            {
              inputTextControl.data('value_before_paste', inputTextControl.val());
              setTimeout(function(){
                that._filter({type:'after_paste'},inputTextControl);
              }, 1);
              return true;
            } 
            else if (event.type === 'after_paste') 
            {
              controlInputString = inputTextControl.val();
            } 
            else 
            {
              return false;
            }
           
            if (regex.test(controlInputString)) {
              return true;
            }
            
            if (event.type === 'after_paste')
            {
                 inputTextControl.val(inputTextControl.data('value_before_paste'));    
            }
            return false;
          },
         
         _setInfoText: function (text)
         {
              this.$statusText.empty();
              this.$statusText.text(text);
         },
         
         open: function (e)
         {
            if (this.options.hideTextControl)
            {
                this.$uiControlText.hide();  
            }
            else
            {
              this.$uiControlText.removeClass('hide');  
              this.$uiControlText.show();   
            }
            
            this.$uiControlTextForm.removeClass('hide');    
            this.$uiControlTextForm.show();
            this.showProfileMap();
            this.$uiControlInput.focus();
         },
         close: function (e)
         {
             this.$map.hide();
             
             this.$uiControlTextForm.hide();
             if (this.options.hideTextControl)
             {
               this.$uiControlText.removeClass('hide');   
               this.$uiControlText.show();
             }
         },
                 
         showProfileMap: function ()
         {
            this.$map.show();
             
            if ($.trim(this.$map.data('content')).length > 0)
            {
                 //mapa inicial
                arquematics.geomap.renderActiveMap(this.$map);
            }
         },
        
         sendContent: function (e)
         {
            var that = this,
                options = this.options,
                form = (options.form === false)?this.$uiControlForm:$(options.form);
            
            var formData = '';
            
            if (arquematics.crypt)
            {
                var formDataArr = form.find('input, select, textarea').serializeArray(),
                        textToEncode = '';
               
                $.each(formDataArr, function (index, obj)
                {
                    if (obj.name === that.$uiControlInput.attr("name"))
                    {
                      textToEncode = $.trim(obj.value);
                      formData += '&' + obj.name + '=' + arquematics.crypt.encryptMultipleKeys(textToEncode);      
                    }
                    //no codifica options.input_control_hash
                    else if (obj.name !== options.input_control_hash)
                    {
                      formData += '&' + obj.name + '=' + obj.value;        
                    }   
                 });
                 //sacamos el hash sha256 del texto
                 formData += '&' + options.input_control_hash + '=' + arquematics.utils.sha256(textToEncode);
               
            }
            else
            {
               formData = form.find('input, select, textarea').serialize();          
            }
             
            $.ajax({
                type: "POST",
                url: (!that.options.sendURL)?form.attr('action'):that.options.sendURL,
                datatype: "json",
                data: formData,
                cache: false,
                success: function(dataJSON)
                {
                    if ($.isPlainObject(dataJSON) 
                        && (dataJSON.status >= 200)
                        && (dataJSON.status < 300))
                    {
                        
                      if (arquematics.crypt)
                      {
                        that._setInfoText(that.$uiControlInput.val());
                      }
                      else
                      {
                        that._setInfoText(dataJSON.HTML);      
                      }
                     
                      if (options.sendClose)
                      {
                        that.close();
                      }
                      
                      that._trigger('resetControl',e, that);
                      that._trigger('onAddContent', e,  dataJSON);
                    }
                    else
                    {
                      that._trigger('resetControlError',e, that);
                      that._trigger('onAddContent', e,  dataJSON);
                    }
                },
                statusCode: {
                    404: function() {
                       that._trigger('resetControlError',e, that);
                       that._trigger('onAddContent', e, false);
                    },
                    500: function() {
                       that._trigger('resetControlError',e, that);
                       that._trigger('onAddContent', e, false);
                    }
                },
                error: function(dataJSON)
                {
                  that._trigger('resetControlError',e, that);
                  that._trigger('onAddContent', e,  false);
                }
            });
            
        },
        _loadDOM: function()
        {
            //div que se activa al pulsar uiControlText
            this.$uiControlTextForm = $(this.element).find('.ui-control-text-form');
            //div que activa el $uiControlTextForm 
            this.$uiControlText = $(this.element).find('.ui-control-text');
            //control de datos del formulario
            this.$uiControlInput = $(this.element).find('.ui-control-text-input');
            //formulario que que se envia
            this.$uiControlForm = $(this.element).find('.ui-control-form');
            //texto en el que se reflejan los cambios
            this.$statusText = $(this.element).find('.status-text');
            //div contenedor del mapa (estatico o dinamico)
            this.$map = $(this.element).find(this.options.container_map);
        }
});

}(jQuery, arquematics));