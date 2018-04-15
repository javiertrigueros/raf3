/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *      - datetimepicker
 *      - state-machine https://github.com/jakesgordon/javascript-state-machine
 */
$.widget( "arquematics.datetimerange", {
	options: {
            //cualquier texto
            regex:                              '^(.*)+$',
            resetControl: function(e, that) 
            {
                /*
               var $control = $(that.element).find('.ui-control-text-input');
               $control.parent().removeClass('error');
              
               $control.focus();*/
            },
            resetControlError: function(e, that) 
            {
                /*
               var $control = $(that.element).find('.ui-control-text-input');
              
               $control.parent().addClass('error');
              
               $control.focus();*/
            }
           
	},
        componentStart: false,
        componentEnd: false,
        
        cancelDateTimeStart: '',
        cancelDateTimeEnd:  '',
        
        
        sm: StateMachine.create({
            events: [
                { name: 'startNoDates',     from: 'none',           to: 'no_date' },
                { name: 'startInitDate',    from: 'none',           to: 'init_date'},
                { name: 'startEndDate',     from: 'none',           to: 'end_date' },
                { name: 'addInitDate',      from: 'no_date',        to: 'init_date'},
                { name: 'addInitDate',      from: 'init_date',      to: 'init_date'},
                { name: 'addInitDate',      from: 'end_date',       to: 'end_date'},
                { name: 'addEndDate',       from: 'init_date',      to: 'end_date' },
                { name: 'addEndDate',       from: 'end_date',       to: 'end_date' },
                { name: 'delEndDate',       from: 'end_date',       to: 'init_date' },
                { name: 'delEndDate',       from: 'init_date',      to: 'init_date' },
                { name: 'cancel',           from: ['no_date', 'init_date', 'end_date'],  to: 'none' }
        ]}),
        
        cancelStatus: '',

       
        _create: function() {
            this._loadDOM();
            this._initEventHandlers();
	},
        
        _initEventHandlers: function () {
            var elem = this.element;
            var that = this;
            
            this.$uiControlStatusZone.on("click", function (e) 
            {
                e.preventDefault();
                       
                that.sm.addInitDate();
                
                that._setStatusText();
     
                that.open();
            });
            
            
            this.$uiControlAddDateEnd.on("click", function (e) 
            {
                e.preventDefault();
               
                that.$endDate.val(that.$startDate.val());
                that.$endTime.val(that.$startTime.val());
                
                that._changeContent(e);
                
                that._setStatusText();
                
                that.openEndDate();
              
            });
            
            this.$uiControlRemoveDateEnd.on("click", function (e) 
            {
                e.preventDefault();
                
                that.closeEndDate();
                
                that.$endDate.val(that.$startDate.val());
                that.$endTime.val(that.$startTime.val());
                
                that.sm.delEndDate();
                
                that._setStatusText();
               
            });
            
            this.$inputControls.on("changeDate", function (e) 
            {
                e.preventDefault();
                
                if (that._changeContent(e))
                {
                    var $nodeControl = $(e.currentTarget);
              
                    if ($nodeControl.is('input') 
                        && ($nodeControl.hasClass('end-date') || $nodeControl.hasClass('end-time')))
                    {
                        that.sm.addEndDate();
                        
                        that._setStatusText();
                    }     
                }
            });
            
            $(elem).find('.send').on("click", function (e) 
            {
                e.preventDefault();
                
                that.close();
            });
           
            $(elem).find('.cancel').on("click", function (e) 
            {
                e.preventDefault();
                
                //reinicializa la maquina de estados
                that.sm.cancel();
                that._initStateMachine(that.cancelStatus);
                
                that._setCancelData();
                that._setStatusText();
         
                that.close();
            });
            
                      
         },
         
         _initStateMachine: function(state)
         {
            if (state === 'no_date')
            {
               this.sm.startNoDates();         
            }
            else if (state === 'init_date')
            {
               this.sm.startInitDate();        
            }
            else if (state === 'end_date')
            {
              this.sm.startEndDate();           
            }
             
         },
        
         openEndDate: function()
         {
            this.$uiControlAddDateEnd.hide();
            this.$uiControlRemoveDateEnd
            .removeClass('hide')
            .show();
            
            this.$uiControlExtra
                .removeClass('hide')
                .show();
           
            this.$endDate
                .removeClass('hide')
                .show();
                
            this.$endTime
                .removeClass('hide')
                .show();
         },
         closeEndDate: function()
         {
            this.$uiControlAddDateEnd
                    .removeClass('hide')
                    .show();
                    
            this.$uiControlRemoveDateEnd.hide();
            
            this.$uiControlExtra.hide();
            
            this.$endDate.hide();
            this.$endTime.hide();
         },
         open: function ()
         {
            if ((this.sm.current === 'no_date')
            || (this.sm.current === 'init_date'))
            {
                 this.closeEndDate();  
            }
            
            this.$uiControlInputZone
                .removeClass('hide')
                .show();
            
         },
         close: function ()
         {
            if ((this.sm.current === 'no_date')
            || (this.sm.current === 'init_date'))
            {
                 this.closeEndDate();  
            }
             
             this.$uiControlInputZone.hide();
         },
         _setStatusText: function()
         {
             var options = this.options;
             
              if ((this.sm.current === 'end_date')
                || (this.sm.current === 'init_date'))
              {
                  try
                  {
                     var initDateTime = moment($.trim(this.$startDate.val()) + ' ' + $.trim(this.$startTime.val()), options.format);
                     var endDateTime = moment($.trim(this.$endDate.val()) + ' ' + $.trim(this.$endTime.val()), options.format);
                     this._setDataStatusText(initDateTime, endDateTime);    
                  }
                  catch(err)
                  {
                    //ha habido un error en el texto, el control, no tiene fecha valida
                    this._trigger('resetControlError', null, this);
                  }
                  
              }
              else if (this.sm.current === 'no_date')
              {
                  this.$statusText.text(options.text_info_undefined);
                  this.$statusTextExtra.text(options.text_info_undefined);
              }
         },
         _setCancelData: function()
         {
             this.$startDate.val(this.cancelStartDate);
             this.$endDate.val(this.cancelEndDate);
                
             this.$startTime.val(this.cancelStartTime);
             this.$endTime.val(this.cancelEndTime); 
         },
         /**
         * @param {e} event :event
         * @return {boolean} : true si ha cambiado el contenido
         */
         _changeContent: function (e)
         {
           try
           {
             var startDate = moment($.trim(this.$startDate.val()) + ' ' + $.trim(this.$startTime.val()), this.options.format).toDate(),
                 endDate = moment($.trim(this.$endDate.val()) + ' ' + $.trim(this.$endTime.val()), this.options.format).toDate();
          
             if (startDate.valueOf() > endDate.valueOf())
             {
                //pone nueva fecha inicial
                this.$startTime.val('0:00');
                
                this.$endDate.val(this.$startDate.val());
                this.$endTime.val(this.$startTime.val());
                
                //reinicializa la maquina de estados
                this.sm.cancel();
                this._initStateMachine('init_date');
                this._setStatusText();
             }
             
             if ($.trim(this.$startDate.val()) === $.trim(this.$endDate.val()))
             {
                this.widgetEndTime.setStartDate(startDate);
             }
             else
             {
               this.widgetEndTime.setStartDate(new Date(-Infinity));   
             }
             
             this.widgetEndDate.setStartDate(startDate);
             //this.widgetEndTime.setStartDate(startDate);
             
             this._trigger('resetControl', e, this);
             
             return true;
           }
           catch(err)
           {
               //ha habido un error en el texto, el control, no tiene fecha valida
              this._trigger('resetControlError', e, this);
              
              return false;
           }
          
           
         },
         _setDataStatusText: function(initDateTime, endDateTime)
         {
             this.$statusText.text(initDateTime.format(this.options.format) + ' ' + this.options.text_info_init_date);
             this.$statusTextExtra.text(endDateTime.format(this.options.format) + ' ' + this.options.text_info_end_date);
         },
         
         _loadDOM: function()
         {
             var $elem = $(this.element);
             
             this.$startDate = $elem.find('.start-date');
             this.$startTime = $elem.find('.start-time');
             
             this.$endDate = $elem.find('.end-date');
             this.$endTime = $elem.find('.end-time');
             
               
             this.widgetStartDate = this.$startDate.data('datepicker');
             this.widgetEndDate = this.$endDate.data('datepicker');
             
             
             this.widgetStartTime = this.$startTime.data('timepicker');
             this.widgetEndTime = this.$endTime.data('timepicker');
             
             this.$inputControls = this.element.find('.ui-control-text-input');
             
             this.widgetStartTime = this.$startTime.data('timepicker');
             this.widgetEndTime = this.$endTime.data('timepicker');
             
             this.$uiControlText = this.element.find('.ui-control-text');
             this.$uiControlExtra = this.element.find('.ui-control-text-extra');
             
             this.$statusTextExtra = this.element.find('.status-text-extra');
             this.$statusText = this.element.find('.status-text');
             
             
             this.$uiControlStatusZone = this.element.find('.ui-control-status-zone');
             this.$uiControlInputZone =  this.element.find('.ui-control-input-zone');
             
             this.$uiControlAddDateEnd = this.element.find('.ui-control-add-date-end');
             this.$uiControlRemoveDateEnd = this.element.find('.ui-control-remove-date-end');
               
         },
         /**
          * devuelve los controles activos
          */
         getInputControls: function()
         {
            var ret = [];
            var status = this.sm.current;
             
            if (!(status === 'no_date'))
            {
                 this.$inputControls.each(function(){
                     var $nodeControl = $(this);
                     
                     if ((status === 'init_date') 
                          && $nodeControl.is('input')
                          && ($nodeControl.hasClass('date-start') || $nodeControl.hasClass('start-time'))
                        )
                     {    
                       ret.push($nodeControl);     
                     }
                     else if ((status !== 'init_date'))
                     {
                        ret.push($nodeControl);      
                     }
                 });
            }
            
            return ret;
         },
         _init: function ()
         {
             
             this.cancelStartDate = this.$startDate.data('now');
             this.cancelEndDate = this.$endDate.data('now');
             
             this.cancelStartTime =  this.$startTime.data('now');
             this.cancelEndTime = this.$endTime.data('now');
            
             this.cancelStatus = this.options.status;
             
             this._initStateMachine(this.cancelStatus);
            
            try
            {
               
               var startDate = moment($.trim(this.$startDate.val()) + ' ' + $.trim(this.$startTime.val()), this.options.format).toDate();
               var endDate = moment($.trim(this.$endDate.val()) + ' ' + $.trim(this.$endTime.val()), this.options.format).toDate();
             
               this.widgetStartTime.update(startDate);
               this.widgetEndTime.update(endDate);
             }
             catch(err){}
         }
});