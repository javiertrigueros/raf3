/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  - momentjs http://momentjs.com/docs/
 */

!function( $ ) {

	// Timepicker object

	var Timepicker = function(element, options) {
		var that = this;

		this.element = $(element);
		this.language = options.language || this.element.data('date-language') || "en";
		this.language = this.language in dates ? this.language : "en";
		
                
                this.format =  options.format || this.element.data('format');
               
		this.isVisible = false;
		this.isInput = this.element.is('input');
		this.component = this.element.is('.date') ? this.element.find('.add-on .icon-th, .add-on .icon-time, .add-on .icon-calendar').parent() : false;
		this.componentReset = this.element.is('.date') ? this.element.find('.add-on .icon-remove').parent() : false;
		this.hasInput = this.component && this.element.find('input').length;
		if (this.component && this.component.length === 0) {
			this.component = false;
		}

                this.minuteStep = options.minuteStep || this.element.data('minute-step') || 5;
		this.pickerPosition = options.pickerPosition || this.element.data('picker-position') || 'bottom-right';
		this.showMeridian = options.showMeridian || this.element.data('show-meridian') || false;
		this.initialDate = options.initialDate || new Date();
                

		this._attachEvents();
		this.formatViewType = "datetime";
                
		if ('formatViewType' in options) {
                    this.formatViewType = options.formatViewType;
		} else if ('formatViewType' in this.element.data()) {
                    this.formatViewType = this.element.data('formatViewType');
		}

		this.minView = 0;
		this.maxView = DPGlobal.modes.length-1;
		
		this.startViewMode = 1;
		this.viewMode = this.startViewMode;

		this.viewSelect = this.minView;
		        
		this.forceParse = true;
		if ('forceParse' in options) {
			this.forceParse = options.forceParse;
		} else if ('dateForceParse' in this.element.data()) {
			this.forceParse = this.element.data('date-force-parse');
		}

		this.picker = $(DPGlobal.template)
                        .appendTo('body')
				.on({
					click: $.proxy(this.click, this),
					mousedown: $.proxy(this.mousedown, this)
				});
               
                this.picker.addClass('timepicker-dropdown-' + this.pickerPosition + ' dropdown-menu');
            
		
		$(document).on('mousedown', function (e) {
			// Clicked outside the timepicker, hide it
			if ($(e.target).closest('.timepicker').length === 0) {
				that.hide();
			}
		});

		this.autoclose = false;
		if ('autoclose' in options) {
			this.autoclose = options.autoclose;
		} else if ('autoclose' in this.element.data()) {
                        this.autoclose = this.element.data('autoclose');
		}

                
		this.keyboardNavigation = true;
		if ('keyboardNavigation' in options) {
			this.keyboardNavigation = options.keyboardNavigation;
		} else if ('dateKeyboardNavigation' in this.element.data()) {
			this.keyboardNavigation = this.element.data('date-keyboard-navigation');
		}

		this.todayBtn = (options.todayBtn || this.element.data('date-today-btn') || false);
		this.todayHighlight = (options.todayHighlight || this.element.data('date-today-highlight') || false);

		this.weekStart = ((options.weekStart || this.element.data('date-weekstart') || dates[this.language].weekStart || 0) % 7);
		this.weekEnd = ((this.weekStart + 6) % 7);
		this.startDate = -Infinity;
		this.endDate = Infinity;
		this.daysOfWeekDisabled = [];
		this.setStartDate(options.startDate || this.element.data('date-startdate'));
		this.setEndDate(options.endDate || this.element.data('date-enddate'));
		this.setDaysOfWeekDisabled(options.daysOfWeekDisabled || this.element.data('date-days-of-week-disabled'));
		
		this.update();
		this.setMode();
	};

	Timepicker.prototype = {
		constructor: Timepicker,

		_events: [],
		_attachEvents: function(){
			this._detachEvents();
			if (this.isInput) { // single input
				this._events = [
					[this.element, {
						focus: $.proxy(this.show, this),
						keyup: $.proxy(this.update, this),
						keydown: $.proxy(this.keydown, this)
					}]
				];
			}
			else if (this.component && this.hasInput){ // component: input + button
				this._events = [
					// For components that are not readonly, allow keyboard nav
					[this.element.find('input'), {
						focus: $.proxy(this.show, this),
						keyup: $.proxy(this.update, this),
						keydown: $.proxy(this.keydown, this)
					}],
					[this.component, {
						click: $.proxy(this.show, this)
					}]
				];
				if (this.componentReset) {
					this._events.push([
						this.componentReset,
						{click: $.proxy(this.reset, this)}
					]);
				}
			}
			else {
				this._events = [
					[this.element, {
						click: $.proxy(this.show, this)
					}]
				];
			}
			for (var i=0, el, ev; i<this._events.length; i++){
				el = this._events[i][0];
				ev = this._events[i][1];
				el.on(ev);
			}
		},
		
		_detachEvents: function(){
			for (var i=0, el, ev; i<this._events.length; i++){
				el = this._events[i][0];
				ev = this._events[i][1];
				el.off(ev);
			}
			this._events = [];
		},

		show: function(e) {
                        this.viewMode = this.startViewMode;
                        
                        this.setMode();
			this.picker.show();
			this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
			if (this.forceParse) {
                            this.update();
			}
			this.place();
			$(window).on('resize', $.proxy(this.place, this));
			if (e) {
				e.stopPropagation();
				e.preventDefault();
			}
			this.isVisible = true;
			this.element.trigger({
				type: 'show',
				date: this.date
			});
		},

		hide: function(e){
			if(!this.isVisible) return;
			this.picker.hide();
			$(window).off('resize', this.place);
			this.viewMode = this.startViewMode;                 
                        
			if (!this.isInput) {
				$(document).off('mousedown', this.hide);
			}

			if (this.forceParse &&
				(
					this.isInput && this.element.val()  || 
					this.hasInput && this.element.find('input').val()
				)
                            ){
                                this.setValue();
                            }
                      
                            this.isVisible = false;
                            this.element.trigger({
				type: 'hide',
				date: this.date
			});
                        
		},

		remove: function() {
			this._detachEvents();
			this.picker.remove();
			delete this.element.data().timepicker;
		},

		getDate: function() {
			return this.date;
		},

		
		setDate: function(d) {
			if (d >= this.startDate && d <= this.endDate) {
				this.date = d;
				this.setValue();
				this.viewDate = this.date;
				this.fill();
			} else {
				this.element.trigger({
					type: 'outOfRange',
					date: d,
					startDate: this.startDate,
					endDate: this.endDate
				});
			}
		},

		setValue: function() {
			var formatted = this.getFormattedDate();
			if (!this.isInput) {
				if (this.component){
					this.element.find('input').val(formatted);
				}
				this.element.data('date', formatted);
			} else {
				this.element.val(formatted);
			}
                        
		},

		getFormattedDate: function() {
                   
                    return moment(this.date).format(this.format);
		},
                
		setStartDate: function(startDate){
			this.startDate = startDate || -Infinity;

                        if ((this.startDate !== -Infinity)
                            && (!(this.startDate 
                                && this.startDate.getMonth 
                                && this.startDate.getMonth.call)))
                        {
                                this.startDate = moment(this.startDate, this.format).toDate();
			}
                       
			this.update();
		},
                
                
		setEndDate: function(endDate){
			this.endDate = endDate || Infinity;
			if ((this.endDate !== Infinity)
                            && (!(this.endDate 
                                && this.endDate.getMonth 
                                && this.endDate.getMonth.call)))
                        {
                                this.endDate = moment(this.endDate, this.format).toDate();
			}
			this.update();
		},
                
                

		setDaysOfWeekDisabled: function(daysOfWeekDisabled){
			this.daysOfWeekDisabled = daysOfWeekDisabled || [];
			if (!$.isArray(this.daysOfWeekDisabled)) {
				this.daysOfWeekDisabled = this.daysOfWeekDisabled.split(/,\s*/);
			}
			this.daysOfWeekDisabled = $.map(this.daysOfWeekDisabled, function (d) {
				return parseInt(d, 10);
			});
			this.update();
		},

		place: function(){
			var zIndex = parseInt(this.element.parents().filter(function() {
				return $(this).css('z-index') != 'auto';
			}).first().css('z-index'))+10;
			var offset, top, left;
                        
			if (this.component) {
				offset = this.component.offset();
				left = offset.left;
				if (this.pickerPosition == 'bottom-left' || this.pickerPosition == 'top-left') {
					left += this.component.outerWidth() - this.picker.outerWidth();
				}
			} else {
                           
				offset = this.element.offset();
				left = offset.left + this.element.outerWidth() - this.picker.outerWidth();
			}
                        
			if (this.pickerPosition == 'top-left' || this.pickerPosition == 'top-right') {
				top = offset.top - this.picker.outerHeight();
			} else {
				top = offset.top + this.height;
			}
			this.picker.css({
				top: top,
				left: left,
				zIndex: zIndex
			});
		},

		update: function(){
			var date, fromArgs = false;
			if(arguments && arguments.length && (typeof arguments[0] === 'string' || arguments[0] instanceof Date)) {
				date = arguments[0];
				fromArgs = true;
			} else {
				date = (this.isInput ? this.element.val() : this.element.data('date') || this.element.find('input').val()) || this.initialDate;
			}

			if (!date) {
				date = new Date();
				fromArgs = false;
			}
                       
                        this.date = this.parseDate(date, this.format, this.language);
			
			if (fromArgs) this.setValue();
                            
			if (this.date < this.startDate) {
				this.viewDate = new Date(this.startDate);
			} else if (this.date > this.endDate) {
				this.viewDate = new Date(this.endDate);
			} else {
				this.viewDate = new Date(this.date);
			}
                        
			this.fill();
                        
		},
                

		fill: function() 
                {
			if (this.date == null || this.viewDate == null) {
				return;
			}
                        
                        var d = this.viewDate,
                            year = d.getFullYear(),
                            month = d.getMonth(),
                            dayMonth = d.getDate(),
                            hourNow = this.date.getHours(),
                            hours = d.getHours(),
                            minutes = d.getMinutes();
                        
                      
			var html = [];
			var txt = '', 
                            meridian = '', 
                            meridianOld = '',
                            actual = new Date();
			for (var i=0; (i < 24) ;i++) {
                                
                                actual = new Date(year, month, dayMonth, i, 0, 0, 0);
				clsName = '';
				// We want the previous hour for the startDate
				if ((actual.valueOf() + 3600000) <= this.startDate || actual.valueOf() > this.endDate) {
					clsName += ' disabled';
				} else if (hourNow == i) {
					clsName += ' active';
				}
                                
				if (this.showMeridian && dates[this.language].meridiem.length == 2) 
                                {
                                    meridian = (i<12?dates[this.language].meridiem[0]:dates[this.language].meridiem[1]);
                                    if (meridian != meridianOld) {
					if (meridianOld != '') {
                                            html.push('</fieldset>');
					}
					html.push('<fieldset class="hour"><legend>'+meridian.toUpperCase()+'</legend>');
                                    }
                                    meridianOld = meridian;
                                    txt = (i%12?i%12:12);
                                    html.push('<span class="hour'+clsName+' hour_'+(i<12?'am':'pm')+'">'+txt+'</span>');
                                    if (i == 23) {
                                        html.push('</fieldset>');
                                    }
				} else {
                                    txt = i+':00';
                                    html.push('<span class="hour'+clsName+'">'+txt+'</span>');
				}
			}
			this.picker.find('.timepicker-hours td').html(html.join(''));

			html = [];
                        txt = '', meridian = '', meridianOld = '';
			for(var i=0; (i < 60); i+=this.minuteStep) {
				actual = new Date(year, month, dayMonth, hours, i, 0, 0);
                               
				clsName = '';
				if (actual.valueOf() < this.startDate || actual.valueOf() > this.endDate) {
					clsName += ' disabled';
				} else if (Math.floor(minutes/this.minuteStep) == Math.floor(i/this.minuteStep)) {
					clsName += ' active';
				}
				if (this.showMeridian && dates[this.language].meridiem.length == 2) 
                                {
                                    meridian = (hours<12?dates[this.language].meridiem[0]:dates[this.language].meridiem[1]);
                                    if (meridian != meridianOld) {
					if (meridianOld != '') {
                                            html.push('</fieldset>');
					}
					html.push('<fieldset class="minute"><legend>'+meridian.toUpperCase()+'</legend>');
                                    }
                                    meridianOld = meridian;
                                    txt = (hours%12?hours%12:12);
                                    //html.push('<span class="minute'+clsName+' minute_'+(hours<12?'am':'pm')+'">'+txt+'</span>');
                                    html.push('<span class="minute'+clsName+'">'+txt+':'+(i<10?'0'+i:i)+'</span>');
                                    if (i == 59) {
					html.push('</fieldset>');
                                    }
				} else {
                                    txt = i+':00';
                                    //html.push('<span class="hour'+clsName+'">'+txt+'</span>');
                                    html.push('<span class="minute'+clsName+'">'+hours+':'+(i<10?'0'+i:i)+'</span>');
                                }
			}
			this.picker.find('.timepicker-minutes td').html(html.join(''));

			this.place(); 
		},
               
		click: function(e) {
			e.stopPropagation();
			e.preventDefault();
			var target = $(e.target).closest('span, td, th, legend');
			if (target.length == 1) 
                        {
				if (target.is('.disabled')) 
                                {
					this.element.trigger({
						type: 'outOfRange',
						date: this.viewDate,
						startDate: this.startDate,
						endDate: this.endDate
					});
					return;
				}
                                
                                if ((!target.is('.disabled'))
                                    && (target[0].nodeName.toLowerCase() === 'span'))
                                {
                                      var hours   = this.viewDate.getHours(),
                                          minutes = this.viewDate.getMinutes();
                                                         
                                      if (target.is('.hour'))
                                      {
                                        hours = parseInt(target.text(), 10) || 0;
					if (target.hasClass('hour_am') || target.hasClass('hour_pm')) 
                                        {
                                            if (hours == 12 && target.hasClass('hour_am')) {
                                                hours = 0;
                                             } else if (hours != 12 && target.hasClass('hour_pm')) {
                                                hours += 12;
                                             }
					}
					this.viewDate.setHours(hours);
					this.element.trigger({
                                            type: 'changeHour',
                                            date: this.viewDate
					});
                                        if (this.viewSelect >= 1) {
                                            this._setDate(this.viewDate);
                                        }                          
                                      } 
                                      else if (target.is('.minute'))
                                      {
					minutes = parseInt(target.text().substr(target.text().indexOf(':')+1), 10) || 0;
					this.viewDate.setMinutes(minutes);
					this.element.trigger({
                                            type: 'changeMinute',
                                            date: this.viewDate
                                        });
					if (this.viewSelect >= 0) {
                                            this._setDate(this.viewDate);
					}                         
                                      }
                                                        
                                      this.setMode(-1);
                                      this.fill();  
                                }
			}
		},

		_setDate: function(date, which){
			if (!which || which == 'date')
				this.date = date;
			if (!which || which  == 'view')
				this.viewDate = date;
			this.fill();
			this.setValue();
			var element;
			if (this.isInput) {
				element = this.element;
			} else if (this.component){
				element = this.element.find('input');
			}
			if (element) {
				element.change();
				if (this.autoclose && (!which || which == 'date')) {
                                    this.hide();
				}
			}
			this.element.trigger({
				type: 'changeDate',
				date: this.date
			});
		},

		moveMinute: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf());
			//dir = dir > 0 ? 1 : -1;
			new_date.setUTCMinutes(new_date.getUTCMinutes() + (dir * this.minuteStep));
			return new_date;
		},

		moveHour: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf());
			//dir = dir > 0 ? 1 : -1;
                        //new_date.setUTCHours(new_date.getHours() + dir);
			new_date.setUTCHours(new_date.getUTCHours() + dir);
			return new_date;
		},
              
		dateWithinRange: function(date){
			return date >= this.startDate && date <= this.endDate;
		},

		keydown: function(e){
			if (this.picker.is(':not(:visible)')){
				if (e.keyCode == 27) // allow escape to hide and re-show picker
					this.show();
				return;
			}
			var dateChanged = false,
				dir, day, month,
				newDate, newViewDate;
			switch(e.keyCode){
				case 27: // escape
					this.hide();
					e.preventDefault();
					break;
				case 37: // left
				case 39: // right
					if (!this.keyboardNavigation) break;
					dir = e.keyCode == 37 ? -1 : 1;
					viewMode = this.viewMode;
					if (e.ctrlKey) {
                                            viewMode += 2;
					} else if (e.shiftKey) {
                                            viewMode += 1;
					}
					
                                       
                                        if (viewMode == 1) {
						newDate = this.moveHour(this.date, dir);
						newViewDate = this.moveHour(this.viewDate, dir);
					} else if (viewMode == 0) {
						newDate = this.moveMinute(this.date, dir);
						newViewDate = this.moveMinute(this.viewDate, dir);
					}
                                        
					if (this.dateWithinRange(newDate)){
						this.date = newDate;
						this.viewDate = newViewDate;
						this.setValue();
						this.update();
						e.preventDefault();
						dateChanged = true;
					}
					break;
				case 38: // up
				case 40: // down
					if (!this.keyboardNavigation) break;
					dir = e.keyCode == 38 ? -1 : 1;
					viewMode = this.viewMode;
					if (e.ctrlKey) {
                                            viewMode += 2;
					} else if (e.shiftKey) {
                                            viewMode += 1;
					}
                                        
					
                                        if (viewMode == 1) {
                                            if (this.showMeridian) {
						newDate = this.moveHour(this.date, dir * 6);
						newViewDate = this.moveHour(this.viewDate, dir * 6);
                                            } else {
						newDate = this.moveHour(this.date, dir * 4);
						newViewDate = this.moveHour(this.viewDate, dir * 4);
                                            }
					} else if (viewMode == 0) {
						newDate = this.moveMinute(this.date, dir * 4);
						newViewDate = this.moveMinute(this.viewDate, dir * 4);
					}
					if (this.dateWithinRange(newDate)){
						this.date = newDate;
						this.viewDate = newViewDate;
						this.setValue();
						this.update();
						e.preventDefault();
						dateChanged = true;
					}
					break;
				case 13: // enter
					if (this.viewMode != 0) {
                                            var oldViewMode = this.viewMode;
                                            
                                            this.setMode(-1);
                                            this.fill();
                                            if (oldViewMode == this.viewMode && this.autoclose) {
                                                this.hide();
                                            }
					} else {
                                            this.fill();
                                            if (this.autoclose) {
                                                this.hide();
                                            }
                                        }
					e.preventDefault();
					break;
				case 9: // tab
					this.hide();
					break;
			}
			if (dateChanged){
				var element;
				if (this.isInput) {
					element = this.element;
				} else if (this.component){
					element = this.element.find('input');
				}
				if (element) {
					element.change();
				}
				this.element.trigger({
					type: 'changeDate',
					date: this.date
				});
			}
		},

		setMode: function(dir) {
			if (dir) {
				var newViewMode = Math.max(0, Math.min(DPGlobal.modes.length - 1, this.viewMode + dir));
				if (newViewMode >= this.minView && newViewMode <= this.maxView) {
					this.viewMode = newViewMode;
				}
			}
			/*
				vitalets: fixing bug of very special conditions:
				jquery 1.7.1 + webkit + show inline timepicker in bootstrap popover.
				Method show() does not set display css correctly and timepicker is not shown.
				Changed to .css('display', 'block') solve the problem.
				See https://github.com/vitalets/x-editable/issues/37

				In jquery 1.7.2+ everything works fine.
			*/
			//this.picker.find('>div').hide().filter('.timepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
			
                        
                        this.picker.find('>div').hide().filter('.timepicker-'+DPGlobal.modes[this.viewMode].clsName).css('display', 'block');
			//this.updateNavArrows();
		},
              
                nonpunctuation: /[^ -\/:-@\[-`{-~\t\n\rTZ]+/g,
               
		parseFormat: function(format, type){
			// IE treats \0 as a string end in inputs (truncating the value),
			// so it's a bad format delimiter, anyway
			var separators = format.replace(/hh?|HH?|p|P|ii?|ss?|dd?|DD?|mm?|MM?|yy(?:yy)?/g, '\0').split('\0');
                        var parts = format.match(/hh?|HH?|p|P|ii?|ss?|dd?|DD?|mm?|MM?|yy(?:yy)?/g);
                        
			if (!separators || !separators.length || !parts || parts.length == 0){
				throw new Error("Invalid date format.");
			}
			return {separators: separators, parts: parts};
		},
                
                parseDate: function(date, format, language) {
                  
			if (date instanceof Date) {
                            return date;
			}
                        
                        format = this.parseFormat(format);
			
			var parts = date && date.match(this.nonpunctuation) || [],
				date = new Date(0, 0, 0, 0, 0, 0, 0),
				parsed = {},
				setters_order = ['hh', 'h', 'ii', 'i', 'ss', 's', 'yyyy', 'yy', 'M', 'MM', 'm', 'mm', 'D', 'DD', 'd', 'dd', 'H', 'HH', 'p', 'P'],
				setters_map = {
					hh: function(d,v){ return d.setHours(v); },
					h:  function(d,v){ return d.setHours(v); },
					HH: function(d,v){ return d.setHours(v); },
					H:  function(d,v){ return d.setHours(v); },
			
					ss: function(d,v){ return d.setUTCSeconds(v); },
					s:  function(d,v){ return d.setUTCSeconds(v); },
					
					m: function(d,v){return d.setMinutes(v);},
                                        mm: function(d,v){return d.setMinutes(v);},
                                        
					a: function(d,v){ return d.getHours(); },
					A: function(d,v){ return d.setHours( (v > 12)?d.getHours()+12:v); }
				},
				val, filtered, part;
                                setters_map['M'] = setters_map['MM'] = setters_map['mm'] = setters_map['m'];
                                setters_map['dd'] = setters_map['d'];
                                setters_map['P'] = setters_map['p'];
                               
			//date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
			
                        if (parts.length == format.parts.length) {
				for (var i=0, cnt = format.parts.length; i < cnt; i++) {
					val = parseInt(parts[i], 10);
					part = format.parts[i];
					if (isNaN(val)) {
						switch(part) {
							case 'MM':
								filtered = $(dates[language].months).filter(function(){
									var m = this.slice(0, parts[i].length),
										p = parts[i].slice(0, m.length);
									return m == p;
								});
								val = $.inArray(filtered[0], dates[language].months) + 1;
								break;
							case 'M':
								filtered = $(dates[language].monthsShort).filter(function(){
									var m = this.slice(0, parts[i].length),
										p = parts[i].slice(0, m.length);
									return m == p;
								});
								val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
								break;
								case 'p':
								case 'P':
										val = $.inArray(parts[i].toLowerCase(), dates[language].meridiem);
										break;
						}
					}
					parsed[part] = val;
				}

				for (var i=0, s; i<setters_order.length; i++){
					s = setters_order[i];
					if (s in parsed && !isNaN(parsed[s])){
                                           setters_map[s](date, parsed[s]);
                                          
                                        }
						
				}
                               
                                
			}
			return date;
		},
		
		reset: function(e) {
			this._setDate(null, 'date');
		}
	};

	$.fn.timepicker = function ( option ) {
		var args = Array.apply(null, arguments);
		args.shift();
		return this.each(function () {
			var $this = $(this),
				data = $this.data('timepicker'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('timepicker', (data = new Timepicker(this, $.extend({}, $.fn.timepicker.defaults,options))));
			}
			if (typeof option == 'string' && typeof data[option] == 'function') {
				data[option].apply(data, args);
			}
		});
	};

	$.fn.timepicker.defaults = {
	};
	$.fn.timepicker.Constructor = Timepicker;
	var dates = $.fn.timepicker.dates = {
		en: {
			days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			meridiem: ["am", "pm"],
			suffix: ["st", "nd", "rd", "th"],
			today: "Today"
		},
                es: {
                        
			days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],   
                        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			meridiem: ["am", "pm"],
			suffix: ["st", "nd", "rd", "th"],
			today: "Ahora"
		}
	};

	var DPGlobal = {
		modes: [
			{
				clsName: 'minutes',
				navFnc: 'Hours',
				navStep: 1
			},
			{
				clsName: 'hours',
				navFnc: 'Date',
				navStep: 1
			},
			{
				clsName: 'days',
				navFnc: 'Month',
				navStep: 1
			}],
		isLeapYear: function (year) {
			return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
		},
		getDaysInMonth: function (year, month) {
			return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
		},
               
		contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
		footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
	};
	DPGlobal.template = '<div class="timepicker">'+
							'<div class="timepicker-minutes">'+
								'<table class=" table-condensed">'+
									//DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="timepicker-hours">'+
								'<table class=" table-condensed">'+
									//DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="timepicker-days">'+
								'<table class=" table-condensed">'+
									//DPGlobal.headTemplate+
									'<tbody></tbody>'+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="timepicker-months">'+
								'<table class="table-condensed">'+
									//DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="timepicker-years">'+
								'<table class="table-condensed">'+
									//DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
						'</div>';

	$.fn.timepicker.DPGlobal = DPGlobal;

}( window.jQuery );