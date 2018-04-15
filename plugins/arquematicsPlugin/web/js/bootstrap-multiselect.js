/**
 * bootstrap-multiselect.js 1.0.0
 * https://github.com/davidstutz/bootstrap-multiselect
 *
 * Copyright 2012 David Stutz
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 *  mirar //https://github.com/damirfoy/iCheck/
 */
!function ($) {

	"use strict"; // jshint ;_;

	if(typeof ko != 'undefined' && ko.bindingHandlers && !ko.bindingHandlers.multiselect){
		ko.bindingHandlers.multiselect = {
		    init: function (element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
		    },
		    update: function (element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
		        var multiSelectData = valueAccessor();
		        var options = multiSelectData.options;
		        var optionsText = allBindingsAccessor().optionsText;
		        var optionsValue = allBindingsAccessor().optionsValue;

		        ko.applyBindingsToNode(element, { options: options, optionsValue: optionsValue, optionsText: optionsText }, viewModel);

		        var ms = $(element).data('multiselect');

		        if (ms) {
		            $(element).multiselect('rebuild');
		        } else {
		            $(element).multiselect(ko.utils.unwrapObservable(multiSelectData.initOptions));
		        }
		    }
		};
	}

	function Multiselect(select, options) {
		
		this.options = this.getOptions(options);
		this.$select = $(select);
		
		this.options.multiple = this.$select.attr('multiple') == "multiple";
		
                this.$button = $('<button type="button" class="multiselect dropdown-toggle ' + this.options.buttonClass + '" data-toggle="dropdown">' + this.options.buttonText(this.getSelected(), this.$select) + '</button>');
                
                
		this.$container = $(this.options.buttonContainer)
			.append(this.$button)
			.append('<ul class="dropdown-menu dropdown-menu-custom' + (this.options.dropRight ? ' pull-right' : '') + '"></ul>');

		if (this.options.buttonWidth) {
			$('button', this.$container).css({
				'width': this.options.buttonWidth
			});
		}

		// Set max height of dropdown menu to activate auto scrollbar.
		if (this.options.maxHeight) {
			$('ul', this.$container).css({
				'max-height': this.options.maxHeight + 'px',
				'overflow-y': 'auto',
				'overflow-x': 'hidden'
			});
		}
                
                this.createOptionAddItem();

		this.buildDropdown();
		
		this.updateButtonText();
                
                this.$select.hide();
                
                $(this.options.nodeAfterToAdd).after(this.$container);
	}

	Multiselect.prototype = {
		
		defaults: {
			// Default text function will either print 'None selected' in case no option is selected,
			// or a list of the selected options up to a length of 3 selected options.
			// If more than 3 options are selected, the number of selected options is printed.
			buttonText: function(options, select) {
				if (options.length == 0) {
					return 'None selected <b class="caret"></b>';
				}
				else if (options.length > 3) {
					return options.length + ' selected <b class="caret"></b>';
				}
				else {
					var selected = '';
					options.each(function() {
						var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).text();

						selected += label + ', ';
					});
					return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
				}
			},
			// Is triggered on change of the selected options.
			onChange: function(option, checked) {
				
			},
                        onCreateNewItem: function() {
                            
			},
                        onOpenMenu: function(e) {
                            
			},
                        newItemText: 'Add new category',
			buttonClass: 'btn',
			dropRight: false,
			selectedClass: 'active',
			buttonWidth: 'auto',
			buttonContainer: '<div class="btn-group" />',
			// Maximum height of the dropdown menu.
			// If maximum height is exceeded a scrollbar will be displayed.
			maxHeight: false,
			includeSelectAllOption: false,
			selectAllText: ' Select all'
		},

		constructor: Multiselect,
		
		// Will build an dropdown element for the given option.
		createOptionValue: function(element) {
                  
			if ($(element).is(':selected')) {
				$(element).attr('selected', 'selected').prop('selected', true);
			}
			
			// Support the label attribute on options.
			var label = $(element).attr('label') || $(element).text();
			var value = parseInt($(element).val());
                        
                        
                            var inputType = this.options.multiple ? "checkbox" : "radio";

                            //var $li = $('<li><a href="#" style="padding:0;"><label style="margin:0;padding:3px 20px 3px 20px;height:100%;cursor:pointer;"><input style="margin-bottom:5px;" type="' + inputType + '" /></label></a></li>');
                            var $li = $('<li><a href="#" class="cat-item"><input class="cat-item-control" type="' + inputType + '" /></a></li>');

                            var selected = $(element).prop('selected') || false;
                            var $checkbox = $('input', $li);
                            $checkbox.val(value);
                            
                            //$('label', $li).append(" " + label + "<i class='cmd-comments icon-remove group-icon-cancel hide'></i>");
                            $('a', $li).append("<span class='cat-item-control cat-item-label'>" + label + "</span><i class='icon-remove-circle delete-cat-icon '></i>");
                        
                            $('ul', this.$container).append($li);
                        
                            //$('ul', this.$container).append($li);

                            if ($(element).is(':disabled')) {
				$checkbox.attr('disabled', 'disabled').prop('disabled', true).parents('li').addClass('disabled');
                            }
			
                            $checkbox.prop('checked', selected);

                            if (selected && this.options.selectedClass) {
				$checkbox.parents('li').addClass(this.options.selectedClass);
                            }
			
		},
                
                createOptionAddItem: function() {
                    var labelText = this.options.newItemText;
                    
                    var $li = $("<li><a href='#' class='add-new-item'><i class='span1 icon-plus add-new-item-icon'></i></a></li>");
                    
                     $('a', $li).append("<strong class='span10'>" + labelText + "</strong>");
                        
                     $('ul', this.$container).append($li);
                                
                },

		// Build the dropdown and bind event handling.
		buildDropdown: function () {
                    var that = this;
                    var options = this.options;
                   
			this.$select.children().each($.proxy(function (index, element) {
				// Support optgroups and options without a group simultaneously.
				var tag = $(element).prop('tagName').toLowerCase();
				if (tag == 'optgroup') {
					var group = element;
					var groupName = $(group).prop('label');
					
					// Add a header for the group.
					var $li = $('<li><label style="margin:0;padding:3px 20px 3px 20px;height:100%;" class="multiselect-group"></label></li>');
					$('label', $li).text(groupName);
					$('ul', this.$container).append($li);
					
					// Add the options of the group.
					$('option', group).each($.proxy(function (index, element) {
						this.createOptionValue(element);
					}, this));
				}
				else if (tag == 'option') {
					this.createOptionValue(element);
				}
				else {
					// ignore illegal tags
				}
			}, this));
                        
                        $('ul li a input', this.$container).on('change', function(event) {
				event.stopPropagation();

                                var option = $(this).parent().find('input');
                                var $optionNode  = that._findOption(option.val());
                                
                                var checked = option.prop('checked') || false;

                                if (checked)
                                {
                                    that.select(option.val()); 
                                }
                                else
                                {
                                    that.deselect(option.val());   
                                }
                                
                                 var data = {
                                    id: $optionNode.val(),
                                    name: $optionNode.text(),
                                    selected: that.getSelected(),
                                    textAll: that.getText()
                                };
                                
                                options.onChange(data, checked);
			});
                        
                        $('ul li a.add-new-item', this.$container).on('click', function(event) {
				
                                
                                that.options.onCreateNewItem(event);
			});
                        
                        $('ul li a.cat-item', this.$container).on('click', function(event) {
				event.stopPropagation();
			});
                        
			$('ul li a span', this.$container).on('click', function(event) {
				event.stopPropagation();
                                var option = $(this).parent().find('input');
                                var $optionNode  = that._findOption(option.val());
                              
                                option.focus();
                                
                                var checked = option.prop('checked') || false;
                                if (!checked)
                                {
                                    that.select(option.val()); 
                                }
                                else
                                {
                                    that.deselect(option.val());   
                                }
                                
                                var data = {
                                    id: $optionNode.val(),
                                    name: $optionNode.text(),
                                    selected: that.getSelected(),
                                    textAll: that.getText()
                                };
                                
    
                                options.onChange(data , !(checked));
			});
                        //borrar un elemento
                        $('ul li a i', this.$container).on('click', function(event) {
                            event.stopPropagation();
                                
                            var $option = $(this).parent().find('input');
                            var ID = $option.val();
                
                            $(this).unbind('click');
                
                            $.ajax({
                                type: "POST",
                                datatype: "json",
                                url: options.deleteURL + ID,
                                cache: false,
                                success: function(dataJSON)
                                {
                                    if ($.isPlainObject(dataJSON) 
                                    && (dataJSON.status == 200))
                                    {
                                       var $node = that.$select.find('[value="' + dataJSON.values.id + '"]');
                                       $node.remove();
                                       that.rebuild();
                                       
                                       var data = {
                                            id: dataJSON.values.id,
                                            name: $node.text(),
                                            selected: that.getSelected(),
                                            textAll: that.getText()
                                        };
                                
                                        options.onChange(data , false);
                                       
                                    }
                                },
                                statusCode: {
                                    404: function() 
                                    {
                                       
                                    },
                                    500: function() 
                                    {
                                        
                                    }
                                },
                                error: function(dataJSON)
                                {
                                   
                                }
                            });
			});
                        
                        
                        this.$button.on('click', function(event) {
                            options.onOpenMenu(event);
                        });
                        
			
		},

		// Destroy - unbind - the plugin.
		destroy: function() {
			this.$container.remove();
			this.$select.show();
		},

		// Refreshs the checked options based on the current state of the select.
		refresh: function() {
			$('option', this.$select).each($.proxy(function(index, element) {
				var $input = $('ul li input', this.$container).filter(function () {
					return $(this).val() == $(element).val();
				});

				if ($(element).is(':selected')) {
					$input.prop('checked', true);

					if (this.options.selectedClass) {
						$input.parents('li').addClass(this.options.selectedClass);
					}
				}
				else {
					$input.prop('checked', false);

					if (this.options.selectedClass) {
						$input.parents('li').removeClass(this.options.selectedClass);
					}
				}
			}, this));
			
			this.updateButtonText();
		},
		
		// Select an option by its value.
		select: function(value) {
			var $option = this._findOption(value); 
			var $checkbox = $('ul li input', this.$container).filter(function () { return $(this).val() == value; });
			
			if (this.options.selectedClass) {
				$checkbox.parents('li').addClass(this.options.selectedClass);
			}

			$checkbox.prop('checked', true);
			
			$option.attr('selected', 'selected').prop('selected', true);
			
			this.updateButtonText();
		},
                _findOption: function (value)
                {
                    return $('option', this.$select).filter(
                            function () { 
                                return $(this).val() == value; 
                            });
                },
                /**
                 * quitar seleccion por su valor
                 * @param value
                 */
		deselect: function(value) {
                        var $option = this._findOption(value);
			var $checkbox = $('ul li input', this.$container).filter(function () { return $(this).val() == value; });

			if (this.options.selectedClass) {
				$checkbox.parents('li').removeClass(this.options.selectedClass);
			}

			$checkbox.prop('checked', false);
			
			$option.removeAttr('selected').prop('selected', false);
			
			this.updateButtonText();
		},
		// Rebuild the whole dropdown menu.
		rebuild: function() {
		    $('ul', this.$container).html('');
                    
                    this.createOptionAddItem();
                    
                    this.buildDropdown(this.$select, this.options);
                    this.updateButtonText();
		},

		// Get options by merging defaults and given options.
		getOptions: function(options) {
			return $.extend({}, this.defaults, options);
		},
		
		updateButtonText: function() {
			var options = this.getSelected();
			$('button', this.$container).html(this.options.buttonText(options, this.$select));
		},
                
                getText: function(){

                     var $elements = this.getSelected();
                     var i = $elements.length;
                     var ret = '';
                     $elements.each(function() {
                        i--;
                        if (i > 0)
                        {
                            ret += $(this).text() + ', ';   
                        }
                        else 
                        {
                            ret += $(this).text();  
                        }
                    });
                   
                    return ret;
                },
		
		getSelected: function() {
                   
			return $('option:selected', this.$select);
		}
	};

    $.fn.multiselect = function(option, parameter) {
        return this.each(function() {
            var data = $(this).data('multiselect'),
                options = typeof option == 'object' && option;

            // Initialize the multiselect.
            if (!data) {
                $(this).data('multiselect', (data = new Multiselect(this, options)));
            }

            // Call multiselect method.
            if (typeof option == 'string') {
               data[option](parameter);
            }
           
        });
    };
	
	$(function() {
		$("select[data-role=multiselect]").multiselect();
	});
}(window.jQuery);
