/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  - bootstrap-datetimepicker.js
 */
$.widget( "arquematics.datetimer", {
	options: {
           
           onChangeContent: function (content, isScheduled, textInfo)
           {

           },
           resetControl: function(e, that) 
           {
               var $control = $(that.element).find('.ui-control-text-input');
               $control.parent().removeClass('error');
              
               $control.focus();
           },
           resetControlError: function(e, that) 
           {
               var $control = $(that.element).find('.ui-control-text-input');
              
               $control.parent().addClass('error');
              
               $control.focus();
           }
	},
        lastControlData: '',
        lastControlTimeStamp: false,
        
        _create: function() {
           this._initEventHandlers();
	},
        _update: function() {
           this._changeStatusText($(this.element)
                                    .find('.ui-control-text-input').val());
	},
        setOption: function( key, value ) {
		this.options[ key ] = value;
		this._update();
	},
        
        _initEventHandlers: function () {
            var elem = this.element;
            var that = this;
           
            //cmd cambia el contenido
            $(elem).find('.ui-control-text-input').on("change", function (e) 
            {
                e.stopPropagation();      
                that._changeContent();
            });
            
             //cmd click elemento
            $(elem).find('.ui-control-text').on("click", function (e) 
            {
                e.stopPropagation();
                $(elem).find('.ui-control-text-form')
                .removeClass('hide')
                .show();
            });
            
            //cmd aceptar
            $(elem).find('.send').on("click", function (e) 
            {
                e.stopPropagation();
                $(elem).find('.ui-control-text-form').hide();
            });
            //cmd cancelar
            $(elem).find('.cancel').on("click", function (e) 
            {
                e.stopPropagation();
                
                var $controlItem = $(elem).find('.ui-control-text-input');
                                                   
                $controlItem.val(that.lastControlData);
                
                that._changeContent();
           
                $(elem).find('.ui-control-text-form').hide();
                
                $controlItem.data('datetimepicker').hide();
                
            });
        },
        
         open: function (e)
         {
             var elem = this.element;
             $(elem).find('.ui-control-text-form')
             .removeClass('hide')
             .show();
         },
         close: function (e)
         {
             var elem = this.element;
             $(elem).find('.ui-control-text-form').hide();
         },
         /**
          * return <boolean>: true si la fecha es en el futuro
          */
         _changeStatusText: function (newTimeDataInputText)
         {
           var options = this.options;
           var $infoText = $(this.element).find('.status-text');
           
           var isScheduled = this.isScheduled();
           
           if ((!options.is_save) && (options.is_draft) && (this.lastControlData === newTimeDataInputText))
           {
              $infoText.text(options.text_status_now);      
           }
           else if (isScheduled)
           {
              $infoText.text(options.text_status_scheduled + ' ' + newTimeDataInputText);    
           }
           else
           {
              $infoText.text(options.text_status_published + ' ' + newTimeDataInputText);
           }
           
           return isScheduled;
         },
         _changeContent: function ()
         {
           var elem = this.element;
           var options = this.options;
           
           var $controlItem = $(elem).find('.ui-control-text-input');

           try
           {
             var isScheduled = this._changeStatusText($controlItem.val());
             
             var changeValLong = moment($controlItem.val(), options.format).format(options.format_long);
             
             this._trigger('resetControl', null, this);
             
             this.options.onChangeContent(changeValLong, isScheduled, this.getInfoText());
           }
           catch(err)
           {
               //ha habido un error en el texto, el control, no tiene fecha valida
              this._trigger('resetControlError', null, this);
           }
           
         },
         isScheduled: function ()
         {
             var changeVal = $(this.element).find('.ui-control-text-input').val();
             var currenTimestamp = new Date().getTime();
             
             try
             {
               var newTimestamp = moment(changeVal, this.options.format ).toDate().getTime();
               return (currenTimestamp < newTimestamp );
             }
             catch(err)
             {
                //Handle errors here
                return false;
             }
         },
         getInfoText: function ()
         {
             var options = this.options;
             
             var ret = '';
           
             var $controlPublicationOption = $(options.input_control_publication + ' option:selected');
           
             if ($controlPublicationOption && ($controlPublicationOption.length > 0))
             {
              if ($controlPublicationOption.val() === 'published')
              {
                 ret = (this.isScheduled())? options.text_info_scheduled:options.text_info_published;                
              }
              else
              {
                 ret = options.text_info_draft;       
              }
               
            }
            return ret;
         },
         _init: function ()
         {
             var elem = this.element;
             var $controlItem = $(elem).find('.ui-control-text-input');
             this.lastControlData = $controlItem.data('now');
             this.lastControlTimeStamp = moment(this.lastControlData, this.options.format).toDate().getTime();
         }
});