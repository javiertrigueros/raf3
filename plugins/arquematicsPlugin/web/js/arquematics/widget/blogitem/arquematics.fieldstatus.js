/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 */
$.widget( "arquematics.fieldstatus", {
	options: {
            text_enabled: 'Enabled', 
            text_disabled: 'Disabled',
            onChangeContent: function (content)
            {

            }
	},
        cancelVal: false,
        controlType: 'text',
        _create: function() {
           this._loadDOM();
           this._initEventHandlers();
	},
        
        _initEventHandlers: function () {
            var elem = this.element;
            var that = this;
          
            this.$uiControlText.on("click", function (e) 
            {
                e.preventDefault();    
     
                that.open();
            });
            
            this.$uiControlInput.on("change", function (e) 
            {
                e.preventDefault(); 
                that._changeContent();
            });
            
            $(elem).find('.send').on("click", function (e) 
            {
                e.preventDefault(); 
                
                that.close();
            });
            
            $(elem).find('.cancel').on("click", function (e) 
            {
                e.preventDefault(); 
                
                if (that.controlType === 'select')
                {
                   that._selectValue(that.cancelVal); 
                }
                else if (that.controlType === 'checkbox')
                {
                    that.$uiControlInput.prop('checked', that.cancelVal);
                }
                else
                {
                   that.$uiControlInput.val(that.cancelVal);     
                }
                
                that._changeContent();
                
                that.close();
            });
            
        },
        
         open: function (e)
         {
             this.$uiControlTextForm
                    .removeClass('hide')
                    .show();
         },
         close: function (e)
         {
             this.$uiControlTextForm.hide();
         },
         /**
          * pone un nuevo valor en el select
          * @param newValue
          */
         _selectValue: function (newValue)
         {   
             this.$uiControlInput.find('.ui-control-text-input option')
                    .removeAttr('selected').prop('selected', false);
                    
             var $option = $('option', this.$uiControlInput).filter(function () { return $(this).val() === newValue; });
             
             $option.attr('selected', 'selected').prop('selected', true);
         },
         _changeSelect: function ()
         {
             var $option = this.$uiControlInput.find('option:selected');
             
             var ret = $option.text();
             
             this.$uiControlInput.find('option')
                    .removeAttr('selected').prop('selected', false);
                    
             $option.attr('selected', 'selected').prop('selected', true);
              
             return ret;
         },
         _changeContent: function ()
         {
             var textData = "";
             
             if (this.controlType === 'select')
             {
               textData = this._changeSelect();      
             }
             else if (this.controlType === 'checkbox')
             {
                if (this.$uiControlInput.is(':checked'))
                {
                   textData = this.options.text_enabled;     
                }
                else
                {
                  textData = this.options.text_disabled;  
                }  
             }
             else
             {
               textData = this.$uiControlInput.val();   
             }
 
             this.$statusText.text(textData);
             this.options.onChangeContent(textData); 
         },
         /**
          * tipo de un control html 
          * 
          * @param <$nodeItem>
          * @return <string>: text|select
          */
         _getType: function($nodeItem){ 
             return $nodeItem[0].tagName === "INPUT" ? $($nodeItem[0]).attr("type").toLowerCase() : $nodeItem[0].tagName.toLowerCase(); 
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
         },
         //inicializa variables
         _init: function ()
         {
             this.controlType = this._getType(this.$uiControlInput);
             
             if (this.controlType === 'select')
             {
                this.cancelVal = this.$uiControlInput.find('option:selected').val();    
             }
             else if (this.controlType === 'checkbox')
             {
                
               this.cancelVal = this.$uiControlInput.is(':checked');
             }
             else
             {
                this.cancelVal = this.$uiControlInput.val();         
             }
             
             
         }
});