    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 *  
 */

$.widget( "arquematics.groupselector", {
	options: {
            active_control: '',
            activeClass: 'active-group',
            buttonClass: 'btn',
            buttonWidth: 'auto',
            buttonContainer: '<div class="btn-group" />',
            // Maximum height of thet dropdown menu.
            // If maximum height is exceeded a scrollbar will be displayed.
            maxHeight: 600,
            dropdownMenuClass: 'span6',
            
            cmd_configure:  '#cmd-configure',
            
            content_container:  '#group-control-content',
            group_control:      '#group-control',
            
            text_comments: 'Comments',
            text_edit: 'Edit',
            
            // Is triggered on change of the selected options.
            onChange: function(option, checked) {
             
               
            },
            buttonText: function(options) {
				if (options.length === 0) {
					return 'None selected <b class="caret"></b>';
				}
				else if (options.length > 3) {
					return options.length + ' selected <b class="caret"></b>';
				}
				else {
					var selected = '';
					options.each(function() {
						selected += $(this).text() + ', ';
					});
					return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
				}
            }
	},
        showExtendedControl: false,
        _update: function() {
            this.reset();
            this.init();
            this._initEventHandlers();
	},
        setOption: function( key, value ) {
		this.options[ key ] = value;
		this._update();
	},
        
        _create: function() {
            this.init();
            this._initEventHandlers();
            this._trigger( "complete", null, { value: 100 } );
	},
        _changeGroupExtendAttribute: function (iconGroup, groupItemNode)
        {
            var dataItems = $.parseJSON(groupItemNode.attr("data-id"));
            var option = $('option[value="' + groupItemNode.attr('data-id') + '"]', this.select);
            
            if (iconGroup.hasClass('icon-remove'))
            {
                iconGroup.removeClass('icon-remove');
                iconGroup.addClass('icon-ok');
                            
                iconGroup.removeClass('group-icon-cancel');
                iconGroup.addClass('group-icon-ok');         
            }
            else
            {
                iconGroup.addClass('icon-remove');
                iconGroup.removeClass('icon-ok');
                            
                iconGroup.addClass('group-icon-cancel');
                iconGroup.removeClass('group-icon-ok');
            }
            
            
            
            if (iconGroup.hasClass('cmd-comments'))
            {
              dataItems[1] = (iconGroup.hasClass('icon-ok'))?1:0;      
            }
            
            if (iconGroup.hasClass('cmd-edit-adds'))
            {
              dataItems[2] = (iconGroup.hasClass('icon-ok'))?1:0;      
            }
            
            groupItemNode.attr("data-id", '[' + dataItems.toString() + ']' );
            option.val(groupItemNode.attr('data-id'));
            
           
            this.options.onChange(option, iconGroup.hasClass('icon-ok'));
        },
        /**
         * pone a 0 todos los atributos extra de una lista
         */
        _resetGroupExtendAttribute: function (groupItemNode)
        {
            var iconControls = groupItemNode.find('.group-container-input i');
            
            iconControls.each(function() {
                var iconControl = $(this);
                
                iconControl.addClass('icon-remove');
                iconControl.removeClass('icon-ok');
                            
                iconControl.addClass('group-icon-cancel');
                iconControl.removeClass('group-icon-ok');
                
            });
            
            var dataItems = $.parseJSON(groupItemNode.attr("data-id"));
            var option = $('option[value="' + groupItemNode.attr('data-id') + '"]', this.select);
            
            dataItems[1] = 0;
            dataItems[2] = 0;
            
            groupItemNode.attr("data-id", '[' + dataItems.toString() + ']' );
            option.val(groupItemNode.attr('data-id'));
            
        },
        _initEventHandlers: function () 
        {
                $('ul li a', this.container).on('click', $.proxy(function(event) {
			event.stopPropagation();
                        
                        var groupItem = $(event.target).parents('li');
                        
                       
                        var groupStatus = groupItem.find('i');
                        
                        
			if (groupStatus.hasClass('hide')) {
                                groupStatus.removeClass('hide');
				groupItem.addClass(this.options.activeClass);
                                groupItem.removeClass('disable-group');
			}
			else {
                                groupStatus.addClass('hide');
				groupItem.removeClass(this.options.activeClass);
                                groupItem.addClass('disable-group');
                                
                                this._resetGroupExtendAttribute(groupItem);
			}
			
			var option = $('option[value="' + groupItem.attr('data-id') + '"]', this.select);
			
                        var checked = (!groupStatus.hasClass('hide'));
			if (checked) {
				option.attr('selected', 'selected');
				option.prop('selected', 'selected');
			}
			else {
				option.removeAttr('selected');
			}
			
			var options = $('option:selected', this.select);
			$('button', this.container).html(this.options.buttonText(options));
			
			this.options.onChange(option, checked);
		}, this));
                
		 $('ul li a span.group-container-input span.group-edit-adds', this.container).on('click', $.proxy(function(event) {
			event.stopPropagation();
                        
                        var groupItem = $(event.target).parents('li');
                        
                        if (groupItem.hasClass(this.options.activeClass))
                        {
                            var iconGroup = groupItem.find('.group-edit-adds i');
                       
                            this._changeGroupExtendAttribute(iconGroup, groupItem);    
                        }
                        
                       
		}, this));
                
                 $('ul li a span.group-container-input span.group-comments', this.container).on('click', $.proxy(function(event) {
			event.stopPropagation();
                        
                        var groupItem = $(event.target).parents('li');
                        
                        if (groupItem.hasClass(this.options.activeClass))
                        {
                            var iconGroup = groupItem.find('.group-comments i');
                        
                            this._changeGroupExtendAttribute(iconGroup, groupItem);
                        }
		}, this));
                
                
                
                $(this.options.cmd_configure).on('click', $.proxy(function(e) {
                    e.preventDefault();
                    
                    var that = this;
                    
                    this.showExtendedControl = (!this.showExtendedControl);
                    
                    if (this.showExtendedControl)
                    {
                        this.container.find('ul').addClass(this.options.dropdownMenuExtendedClass);
                        this.container.find('ul').removeClass(this.options.dropdownMenuNormalClass);
                        
                        this.container.find('.group-container-input').show();     
                    }
                    else
                    {
                        this.container.find('ul').removeClass(this.options.dropdownMenuExtendedClass);
                        this.container.find('ul').addClass(this.options.dropdownMenuNormalClass);
                        
                        this.container.find('.group-container-input').hide();    
                    }
                    
                    //resetea  los controles
                    this.container.find('li').each(function() {
                        that._resetGroupExtendAttribute($(this));
                    });
                  
		}, this));
                
        },
       
        controlName: function()
        {
          return 'groupselector';
        },
        reset: function()
        {
           $(this.options.active_control).val([]);
           this.container.remove();
        },
        init: function()
        {
            var options = this.options;
            
            $(options.active_control).val([]);
            this.select = $(options.active_control);
            
            if ($('option', this.select).length > 0)
            {
                // Set max height of dropdown menu to activate auto scrollbar.
                if (this.options.maxHeight) {
                    $('ul', this.container).css({
				'max-height': this.options.maxHeight + 'px',
				'overflow-y': 'auto',
				'overflow-x': 'hidden'
			});
                        
                }
                
                this.container = $(this.options.buttonContainer)
				.append('<button type="button" style="width:' + this.options.buttonWidth + '" class="dropdown-toggle ' + this.options.buttonClass + '" data-toggle="dropdown">' + this.options.buttonText($('option:selected', this.select)) + '</button>')
				.append('<ul id="group-control-content" class="dropdown-menu"></ul>')
                                .append('<span id="cmd-configure" class="glyphicon glyphicon-cog"></span>');
                                //.append('<i id="cmd-configure" class="icon-cog"></i>');
		
            
            
                $('ul', this.container).addClass(options.dropdownMenuNormalClass);
            
            
                var containerContent = this.container.find('ul');
            
                $('option', this.select).each(function() {
                       var curentOptionText = $(this).text();
                       var curentOptionVal = $(this).attr('value');
                       
                       containerContent.append('<li class="group-item disable-group" data-id="' + curentOptionVal + '"><a href="#"><span class="group-text"><i class="icon-ok hide group-icon-ok"></i>' + curentOptionText + '</span><span class="group-container-input hide"><span class="group-input group-comments"><i class="cmd-comments icon-remove group-icon-cancel"></i>' + options.text_comments + '</span>&nbsp;<span class="group-input group-edit-adds"><i class="cmd-edit-adds icon-remove group-icon-cancel"></i>' + options.text_edit + '</span></span></a></li>');
                });
            
                $(this.element).after(this.container);
                
                
            }
            else
            {
                  this.container = $(this.options.buttonContainer)
				.append('<button type="button" style="width:' + this.options.buttonWidth + '" class="dropdown-toggle ' + this.options.buttonClass + '" data-toggle="dropdown">' + this.options.buttonText($('option:selected', this.select)) + '</button>')
                                .append('<span id="cmd-configure" class="glyphicon glyphicon-cog"></span>');
                                //.append('<i id="cmd-configure" class="icon-cog"></i>'); 
                  
                  $(this.element).after(this.container);
            }
        }
        
});