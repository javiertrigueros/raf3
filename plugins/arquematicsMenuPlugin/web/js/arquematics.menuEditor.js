/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 * 
 */

$.widget( "arquematics.menuEditor", {
    
	
        options : {
			menuItemDepthPerLevel : 30, // Do not use directly. Use depthToPx and pxToDepth instead.
			globalMaxDepth : 11
		},  
		menuList : undefined,	// Set in init.
		targetList : undefined, // Set in init.
		menusChanged : false,
		isRTL: !! ( 'undefined' != typeof isRtl && isRtl ),
		negateIfRTL: ( 'undefined' != typeof isRtl && isRtl ) ? -1 : 1,

		// Functions that run on init.
		_init : function() {
			this.menuList = $('#menu-to-edit');
			this.targetList = this.menuList;

			this.jQueryExtensions();

			this.attachMenuEditListeners();

			this.setupInputWithDefaultTitle();
			//this.attachQuickSearchListeners();
			//this.attachThemeLocationsListeners();

			this.attachTabsPanelListeners();

			this.attachUnsavedChangesListener();

                         /*
			if ( this.options.menus.oneThemeLocationNoMenus )
				$( '#posttype-page' ).addSelectedToMenu( this.addMenuItemToBottom );
                        */
			this.initManageLocations();

			this.initAccessibility();
                        
			this.initToggles();
                        
                        
                        this.updateInfoMenus();
                        
                        if ( this.menuList.length )
				this.initSortables();
                            
                        
		},

		jQueryExtensions : function() {
                        var api = this;
			// jQuery extensions
			$.fn.extend({
				menuItemDepth : function() {
					var margin = api.isRTL ? this.eq(0).css('margin-right') : this.eq(0).css('margin-left');
					return api.pxToDepth( margin && -1 !== margin.indexOf('px') ? margin.slice(0, -2) : 0 );
				},
				updateDepthClass : function(current, prev) {
					return this.each(function(){
						var t = $(this);
						prev = prev || t.menuItemDepth();
						$(this).removeClass('menu-item-depth-'+ prev )
							.addClass('menu-item-depth-'+ current );
                                                
                                                $(this).data('depth', current);
                                                $(this).attr('data-depth', current);                                                
					});
				},
				shiftDepthClass : function(change) {
					return this.each(function(){
						var t = $(this),
							depth = t.menuItemDepth();
						$(this).removeClass('menu-item-depth-'+ depth )
							.addClass('menu-item-depth-'+ (depth + change) );
                                                
                                                $(this).data('depth',(depth + change));
                                                $(this).attr('data-depth', (depth + change));                                             
					});
				},
				childMenuItems : function() {
					var result = $();
					this.each(function(){
						var t = $(this), depth = t.menuItemDepth(), next = t.next();
						while( next.length && next.menuItemDepth() > depth ) {
							result = result.add( next );
							next = next.next();
						}
					});
					return result;
				},
				shiftHorizontally : function( dir ) {
					return this.each(function(){
						var t = $(this),
							depth = t.menuItemDepth(),
							newDepth = depth + dir;

						// Change .menu-item-depth-n class
						t.moveHorizontally( newDepth, depth );
					});
				},
				moveHorizontally : function( newDepth, depth ) {
					return this.each(function(){
						var t = $(this),
							children = t.childMenuItems(),
							diff = newDepth - depth,
							subItemText = t.find('.is-submenu');

						// Change .menu-item-depth-n class
						//t.updateDepthClass( newDepth, depth ).updateParentMenuItemDBId();
                                                t.updateDepthClass( newDepth, depth );

						// If it has children, move those too
						if ( children ) {
							children.each(function( index ) {
								var t = $(this),
									thisDepth = t.menuItemDepth(),
									newDepth = thisDepth + diff;
								//t.updateDepthClass(newDepth, thisDepth).updateParentMenuItemDBId();
                                                                t.updateDepthClass(newDepth, thisDepth);
							});
						}

						// Show "Sub item" helper text
						if (0 === newDepth)
							subItemText.hide();
						else
							subItemText.show();
					});
				},
				hideAdvancedMenuItemFields : function() {
					return this.each(function(){
						var that = $(this);
						$('.hide-column-tog').not(':checked').each(function(){
							that.find('.field-' + $(this).val() ).addClass('hidden-field');
						});
					});
				},
				
				getItemData : function( itemType, id ) {
					itemType = itemType || 'menu-item';

					var itemData = {}, i,
					fields = [
						'menu-item-db-id',
						'menu-item-object-id',
						'menu-item-object',
						'menu-item-parent-id',
						'menu-item-position',
						'menu-item-type',
						'menu-item-title',
						'menu-item-url',
						'menu-item-description',
						'menu-item-attr-title',
						'menu-item-target',
						'menu-item-classes',
						'menu-item-xfn'
					];

					if( !id && itemType == 'menu-item' ) {
						id = this.find('.menu-item-data-db-id').val();
					}

					if( !id ) return itemData;

					this.find('input').each(function() {
						var field;
						i = fields.length;
						while ( i-- ) {
							if( itemType == 'menu-item' )
								field = fields[i] + '[' + id + ']';
							else if( itemType == 'add-menu-item' )
								field = 'menu-item[' + id + '][' + fields[i] + ']';

							if (
								this.name &&
								field == this.name
							) {
								itemData[fields[i]] = this.value;
							}
						}
					});

					return itemData;
				},
				setItemData : function( itemData, itemType, id ) { // Can take a type, such as 'menu-item', or an id.
					itemType = itemType || 'menu-item';

					if( !id && itemType == 'menu-item' ) {
						id = $('.menu-item-data-db-id', this).val();
					}

					if( !id ) return this;

					this.find('input').each(function() {
						var t = $(this), field;
						$.each( itemData, function( attr, val ) {
							if( itemType == 'menu-item' )
								field = attr + '[' + id + ']';
							else if( itemType == 'add-menu-item' )
								field = 'menu-item[' + id + '][' + attr + ']';

							if ( field == t.attr('name') ) {
								t.val( val );
							}
						});
					});
					return this;
				}
			});
		},

		countMenuItems : function( depth ) {
			return $( '.menu-item-depth-' + depth ).length;
		},

		moveMenuItem : function( $this, dir ) {

			var menuItems = $('#menu-to-edit li');
				menuItemsCount = menuItems.length,
				thisItem = $this.parents( 'li.menu-item' ),
				thisItemChildren = thisItem.childMenuItems(),
				thisItemData = thisItem.getItemData(),
				thisItemDepth = parseInt( thisItem.menuItemDepth() ),
				thisItemPosition = parseInt( thisItem.index() ),
				nextItem = thisItem.next(),
				nextItemChildren = nextItem.childMenuItems(),
				nextItemDepth = parseInt( nextItem.menuItemDepth() ) + 1,
				prevItem = thisItem.prev(),
				prevItemDepth = parseInt( prevItem.menuItemDepth() ),
				prevItemId = prevItem.getItemData()['menu-item-db-id'];

			switch ( dir ) {
			case 'up':
				var newItemPosition = thisItemPosition - 1;

				// Already at top
				if ( 0 === thisItemPosition )
					break;

				// If a sub item is moved to top, shift it to 0 depth
				if ( 0 === newItemPosition && 0 !== thisItemDepth )
					thisItem.moveHorizontally( 0, thisItemDepth );

				// If prev item is sub item, shift to match depth
				if ( 0 !== prevItemDepth )
					thisItem.moveHorizontally( prevItemDepth, thisItemDepth );

				// Does this item have sub items?
				if ( thisItemChildren ) {
					var items = thisItem.add( thisItemChildren );
					// Move the entire block
					//items.detach().insertBefore( menuItems.eq( newItemPosition ) ).updateParentMenuItemDBId();
                                        items.detach().insertBefore( menuItems.eq( newItemPosition ) );
				} else {
					//thisItem.detach().insertBefore( menuItems.eq( newItemPosition ) ).updateParentMenuItemDBId();
                                        thisItem.detach().insertBefore( menuItems.eq( newItemPosition ) );
				}
				break;
			case 'down':
				// Does this item have sub items?
				if ( thisItemChildren ) {
					var items = thisItem.add( thisItemChildren ),
						nextItem = menuItems.eq( items.length + thisItemPosition ),
						nextItemChildren = 0 !== nextItem.childMenuItems().length;

					if ( nextItemChildren ) {
						var newDepth = parseInt( nextItem.menuItemDepth() ) + 1;
						thisItem.moveHorizontally( newDepth, thisItemDepth );
					}

					// Have we reached the bottom?
					if ( menuItemsCount === thisItemPosition + items.length )
						break;

					//items.detach().insertAfter( menuItems.eq( thisItemPosition + items.length ) ).updateParentMenuItemDBId();
                                        items.detach().insertAfter( menuItems.eq( thisItemPosition + items.length ) );
				} else {
					// If next item has sub items, shift depth
					if ( 0 !== nextItemChildren.length )
						thisItem.moveHorizontally( nextItemDepth, thisItemDepth );

					// Have we reached the bottom
					if ( menuItemsCount === thisItemPosition + 1 )
						break;
                                        thisItem.detach().insertAfter( menuItems.eq( thisItemPosition + 1 ) );
					//thisItem.detach().insertAfter( menuItems.eq( thisItemPosition + 1 ) ).updateParentMenuItemDBId();
				}
				break;
			case 'top':
				// Already at top
				if ( 0 === thisItemPosition )
					break;
				// Does this item have sub items?
				if ( thisItemChildren ) {
					var items = thisItem.add( thisItemChildren );
					// Move the entire block
					//items.detach().insertBefore( menuItems.eq( 0 ) ).updateParentMenuItemDBId();
                                        items.detach().insertBefore( menuItems.eq( 0 ) );
				} else {
					//thisItem.detach().insertBefore( menuItems.eq( 0 ) ).updateParentMenuItemDBId();
                                        thisItem.detach().insertBefore( menuItems.eq( 0 ) );
				}
				break;
			case 'left':
				// As far left as possible
				if ( 0 === thisItemDepth )
					break;
				thisItem.shiftHorizontally( -1 );
				break;
			case 'right':
				// Can't be sub item at top
				if ( 0 === thisItemPosition )
					break;
				// Already sub item of prevItem
				if ( thisItemData['menu-item-parent-id'] === prevItemId )
					break;
				thisItem.shiftHorizontally( 1 );
				break;
			}
			$this.focus();
			this.registerChange();
			this.refreshKeyboardAccessibility();
			//this.refreshAdvancedAccessibility();
		},

		initAccessibility : function() {
                        var that = this;
			this.refreshKeyboardAccessibility();
			//this.refreshAdvancedAccessibility();

			// Events
                        /*
			$( '.menus-move-up' ).on( 'click', function ( e ) {
				that.moveMenuItem( $( this ).parents( 'li.menu-item' ).find( 'a.item-edit' ), 'up' );
				e.preventDefault();
			});
			$( '.menus-move-down' ).on( 'click', function ( e ) {
				that.moveMenuItem( $( this ).parents( 'li.menu-item' ).find( 'a.item-edit' ), 'down' );
				e.preventDefault();
			});
			$( '.menus-move-top' ).on( 'click', function ( e ) {
				that.moveMenuItem( $( this ).parents( 'li.menu-item' ).find( 'a.item-edit' ), 'top' );
				e.preventDefault();
			});
			$( '.menus-move-left' ).on( 'click', function ( e ) {
				that.moveMenuItem( $( this ).parents( 'li.menu-item' ).find( 'a.item-edit' ), 'left' );
				e.preventDefault();
			});
			$( '.menus-move-right' ).on( 'click', function ( e ) {
				that.moveMenuItem( $( this ).parents( 'li.menu-item' ).find( 'a.item-edit' ), 'right' );
				e.preventDefault();
			});*/
		},

		

		refreshKeyboardAccessibility : function() {
                        var that = this;
			$( '.item-edit' ).off( 'focus' ).on( 'focus', function(){
				$(this).off( 'keydown' ).on( 'keydown', function(e){

					var $this = $(this);

					// Bail if it's not an arrow key
					if ( 37 != e.which && 38 != e.which && 39 != e.which && 40 != e.which )
						return;

					// Avoid multiple keydown events
					$this.off('keydown');

					// Bail if there is only one menu item
					if ( 1 === $('#menu-to-edit li').length )
						return;

					// If RTL, swap left/right arrows
					var arrows = { '38' : 'up', '40' : 'down', '37' : 'left', '39' : 'right' };
					if ( $('body').hasClass('rtl') )
						arrows = { '38' : 'up', '40' : 'down', '39' : 'left', '37' : 'right' };

					switch ( arrows[e.which] ) {
					case 'up':
						that.moveMenuItem( $this, 'up' );
						break;
					case 'down':
						that.moveMenuItem( $this, 'down' );
						break;
					case 'left':
						that.moveMenuItem( $this, 'left' );
						break;
					case 'right':
						that.moveMenuItem( $this, 'right' );
						break;
					}
					// Put focus back on same menu item
					$( '#edit-' + thisItemData['menu-item-db-id'] ).focus();
					return false;
				});
			});
		},

		initToggles : function() {
			// init postboxes
			//postboxes.add_postbox_toggles('nav-menus');

			// adjust columns functions for menus UI
                        
                        //javier mirar lo de columms en commons.js
			/*
                        columns.useCheckboxesForHidden();
			columns.checked = function(field) {
				$('.field-' + field).removeClass('hidden-field');
			}
			columns.unchecked = function(field) {
				$('.field-' + field).addClass('hidden-field');
			}*/
			// hide fields
			this.menuList.hideAdvancedMenuItemFields();
                        /*
			$('.hide-postbox-tog').click(function () {
				var hidden = $( '.accordion-container li.accordion-section' ).filter(':hidden').map(function() { return this.id; }).get().join(',');
				$.post(ajaxurl, {
					action: 'closed-postboxes',
					hidden: hidden,
					closedpostboxesnonce: jQuery('#closedpostboxesnonce').val(),
					page: 'nav-menus'
				});
			});*/
		},
                updateInfoMenus: function()
                {
                     if( 0 === $( '#menu-to-edit li' ).length ) 
                        {
                            $( '.drag-instructions' ).hide();
                            $('#menu-instructions').show();
                        }
                        else
                        {
                          $( '.drag-instructions' ).show();
                          $('#menu-instructions').hide();
                        }
            
                },

		initSortables : function() {
                        var that = this;
			var currentDepth = 0, originalDepth, minDepth, maxDepth,
				prev, next, prevBottom, nextThreshold, helperHeight, transport,
				menuEdge = this.menuList.offset().left,
				body = $('body'), maxChildDepth,
				menuMaxDepth = initialMenuMaxDepth();

                     
                       

			// Use the right edge if RTL.
			menuEdge += this.isRTL ? this.menuList.width() : 0;

			this.menuList.sortable({
				handle: '.menu-item-handle',
				placeholder: 'sortable-placeholder',
				start: function(e, ui) {
					var height, width, parent, children, tempHolder;

					// handle placement for rtl orientation
					if ( that.isRTL )
						ui.item[0].style.right = 'auto';

					transport = ui.item.children('.menu-item-transport');

					// Set depths. currentDepth must be set before children are located.
					originalDepth = ui.item.menuItemDepth();
					updateCurrentDepth(ui, originalDepth);

					// Attach child elements to parent
					// Skip the placeholder
					parent = ( ui.item.next()[0] == ui.placeholder[0] ) ? ui.item.next() : ui.item;
					children = parent.childMenuItems();
					transport.append( children );

					// Update the height of the placeholder to match the moving item.
					height = transport.outerHeight();
					// If there are children, account for distance between top of children and parent
					height += ( height > 0 ) ? (ui.placeholder.css('margin-top').slice(0, -2) * 1) : 0;
					height += ui.helper.outerHeight();
					helperHeight = height;
					height -= 2; // Subtract 2 for borders
					ui.placeholder.height(height);

					// Update the width of the placeholder to match the moving item.
					maxChildDepth = originalDepth;
					children.each(function(){
						var depth = $(this).menuItemDepth();
						maxChildDepth = (depth > maxChildDepth) ? depth : maxChildDepth;
					});
					width = ui.helper.find('.menu-item-handle').outerWidth(); // Get original width
					width += that.depthToPx(maxChildDepth - originalDepth); // Account for children
					width -= 2; // Subtract 2 for borders
					ui.placeholder.width(width);

					// Update the list of menu items.
					tempHolder = ui.placeholder.next();
					tempHolder.css( 'margin-top', helperHeight + 'px' ); // Set the margin to absorb the placeholder
					ui.placeholder.detach(); // detach or jQuery UI will think the placeholder is a menu item
					$(this).sortable( "refresh" ); // The children aren't sortable. We should let jQ UI know.
					ui.item.after( ui.placeholder ); // reattach the placeholder.
					tempHolder.css('margin-top', 0); // reset the margin

					// Now that the element is complete, we can update...
					updateSharedVars(ui);
				},
				stop: function(e, ui) {
					var children, depthChange = currentDepth - originalDepth;

					// Return child elements to the list
					children = transport.children().insertAfter(ui.item);

					// Add "sub menu" description
					var subMenuTitle = ui.item.find( '.item-title .is-submenu' );
					if ( 0 < currentDepth )
						subMenuTitle.show();
					else
						subMenuTitle.hide();

					// Update depth classes
					if( depthChange != 0 ) {
						ui.item.updateDepthClass( currentDepth );
						children.shiftDepthClass( depthChange );
						updateMenuMaxDepth( depthChange );
					}
					// Register a change
					that.registerChange();
					// Update the item data.
					//ui.item.updateParentMenuItemDBId();

					// address sortable's incorrectly-calculated top in opera
					ui.item[0].style.top = 0;

					// handle drop placement for rtl orientation
					if ( that.isRTL ) {
						ui.item[0].style.left = 'auto';
						ui.item[0].style.right = 0;
					}

					that.refreshKeyboardAccessibility();
					//that.refreshAdvancedAccessibility();
				},
				change: function(e, ui) {
					// Make sure the placeholder is inside the menu.
					// Otherwise fix it, or we're in trouble.
					if( ! ui.placeholder.parent().hasClass('menu') )
						(prev.length) ? prev.after( ui.placeholder ) : api.menuList.prepend( ui.placeholder );

					updateSharedVars(ui);
				},
				sort: function(e, ui) {
					var offset = ui.helper.offset(),
						edge = that.isRTL ? offset.left + ui.helper.width() : offset.left,
						depth = that.negateIfRTL * that.pxToDepth( edge - menuEdge );
					// Check and correct if depth is not within range.
					// Also, if the dragged element is dragged upwards over
					// an item, shift the placeholder to a child position.
					if ( depth > maxDepth || offset.top < prevBottom ) depth = maxDepth;
					else if ( depth < minDepth ) depth = minDepth;

					if( depth != currentDepth )
						updateCurrentDepth(ui, depth);

					// If we overlap the next element, manually shift downwards
					if( nextThreshold && offset.top + helperHeight > nextThreshold ) {
						next.after( ui.placeholder );
						updateSharedVars( ui );
						$(this).sortable( "refreshPositions" );
					}
				}
			});

			function updateSharedVars(ui) {
				var depth;

				prev = ui.placeholder.prev();
				next = ui.placeholder.next();

				// Make sure we don't select the moving item.
				if( prev[0] == ui.item[0] ) prev = prev.prev();
				if( next[0] == ui.item[0] ) next = next.next();

				prevBottom = (prev.length) ? prev.offset().top + prev.height() : 0;
				nextThreshold = (next.length) ? next.offset().top + next.height() / 3 : 0;
				minDepth = (next.length) ? next.menuItemDepth() : 0;

				if( prev.length )
					maxDepth = ( (depth = prev.menuItemDepth() + 1) > that.options.globalMaxDepth ) ? that.options.globalMaxDepth : depth;
				else
					maxDepth = 0;
			}

			function updateCurrentDepth(ui, depth) {
				ui.placeholder.updateDepthClass( depth, currentDepth );
				currentDepth = depth;
			}

			function initialMenuMaxDepth() {
				if( ! body[0].className ) return 0;
				var match = body[0].className.match(/menu-max-depth-(\d+)/);
				return match && match[1] ? parseInt(match[1]) : 0;
			}

			function updateMenuMaxDepth( depthChange ) {
				var depth, newDepth = menuMaxDepth;
				if ( depthChange === 0 ) {
					return;
				} else if ( depthChange > 0 ) {
					depth = maxChildDepth + depthChange;
					if( depth > menuMaxDepth )
						newDepth = depth;
				} else if ( depthChange < 0 && maxChildDepth == menuMaxDepth ) {
					while( ! $('.menu-item-depth-' + newDepth, this.menuList).length && newDepth > 0 )
						newDepth--;
				}
				// Update the depth class.
				body.removeClass( 'menu-max-depth-' + menuMaxDepth ).addClass( 'menu-max-depth-' + newDepth );
				menuMaxDepth = newDepth;
			}
		},

		initManageLocations : function () {
			$('#menu-locations-wrap form').submit(function(){
				window.onbeforeunload = null;
			});
			$('.menu-location-menus select').on('change', function () {
				var editLink = $(this).closest('tr').find('.locations-edit-menu-link');
				if ($(this).find('option:selected').data('orig'))
					editLink.show();
				else
					editLink.hide();
			});
		},

		attachMenuEditListeners : function() {
			var that = this;
                     
                       $('.ui-control-text').bind('click', function(e) {
                           e.preventDefault();
                           
                           $('#control-menu-name div').toggle();
                           $('#arMenu_name').focus();
                       });
                       
                       $('.btn-cancel').bind('click', function(e) {
                           e.preventDefault();
               
                           var $node = $(e.currentTarget);
                           var $nodeTitle = $('#menu-name');
                           
                           $('#arMenu_name').val($node.data('name'));
                           
                           $nodeTitle.text($node.data('name'));
                           
                           $('#control-menu-name div').toggle();
                       });
                       
                       $('.btn-accept').bind('click', function(e) {
                           e.preventDefault();
                           
                           var $nodeCancel = $('.btn-cancel');
                           
                           
                           $nodeCancel.data('name', $('#arMenu_name').val());
                           
                           $('#control-menu-name div').toggle();
                       });
                       
                       
                        
			$('#update-nav-menu').bind('click', function(e) {
                           
                            //e.preventDefault();
                                if ( e.target && e.target.className ) 
                                {
                                    
					if ( -1 !== e.target.className.indexOf('item-edit') ) {
                                              
						return that.eventOnClickEditLink(e.target);
					} else if ( -1 !== e.target.className.indexOf('menu-save') ) {
                                                e.preventDefault();
                                                
						return that.eventOnClickMenuSave(e.target);
					} else if ( -1 !== e.target.className.indexOf('menu-delete') ) {
						
                                                return that.eventOnClickMenuDelete(e.target);
					} else if ( -1 !== e.target.className.indexOf('item-delete') ) {
                                                
						return that.eventOnClickMenuItemDelete(e.target);
					} else if ( -1 !== e.target.className.indexOf('item-cancel') ) {
						return that.eventOnClickCancelLink(e.target);
					}
				}
			});
                        
                        $('#control-save-menu-main').bind('click', function(e) {
                             e.preventDefault();
                             return that.eventOnClickMenuSave(e.target);
                        });
                        
			$('#add-custom-links input[type="text"]').keypress(function(e){
				
                            if ( e.keyCode === 13 ) {
					e.preventDefault();
					$("#submit-customlinkdiv").click();
				}
			});
                        
                        $('.nav-label').each(function()
                        {
                             that.addHanderChangeInputMenus($(this));  
                        });
                
                        this.addHanderInput($('#arMenu_name'),$('#menu-name'), false);
                          
		},
                /**
                 * comportamiento de un control input
                 * 
                 * @param <$controlInput>: Control de texto que queremos monitorizar
                 * @param <$labelNode>: Nodo que muestra el texto de $controlInput
                 * @param [$dataNode]:  Nodo en el que dejaremos los datos 
                 * 
                 */      
                addHanderInput: function($controlInput, $labelNode, $dataNode)
                {
                    
                     var ctrlDown = false;
                     var ctrlKey = 17, 
                            vKey = 86, // key v
                            VKey = 118, // key V
                            xKey = 88, // key x
                            XKey = 120, // key X
                            
                            backspaceKey = 8,
                               deleteKey = 46,
                               spaceKey  = 32,
                                                  
                            upArrowKey      =   38,
                            downArrowKey    =	40,
                            leftArrowKey    =	37,
                            rightArrowKey   =	39;
                    
                       var hasDataNodeParam = ($dataNode instanceof $);
                            
                   
                       $controlInput.keydown(function(e)
                            {
                                if (e.keyCode === ctrlKey) ctrlDown = true;
                            }).keyup(function(e)
                                {
                                    if (e.keyCode === ctrlKey) ctrlDown = false;
                                });
                        
                        $controlInput.keypress(function(e){ 
                            
                             var code = (e.keyCode ? e.keyCode : e.which);
                            
                             var dataInput = '';
                                                 
                             if (ctrlDown && ((code === vKey) || (code === VKey)))
                             {
                                if (e.clipboardData && e.clipboardData.getData) 
                                {
                                   dataInput = $controlInput.val() + e.clipboardData.getData('text/plain'); 
                                }
                                else
                                {
                                   setTimeout(function ()
                                   { 
                                       dataInput  = $controlInput.val();
                                       $labelNode.text(dataInput);
                                       
                                       if (hasDataNodeParam)
                                       {
                                          $dataNode.data('name', dataInput);     
                                       }
                                   }, 5);
                                   
                                   return true;
                                }
                             }
                             else if (ctrlDown && ((code === xKey) || (code === XKey)))
                             {
                                  setTimeout(function ()
                                   { 
                                       dataInput  = $controlInput.val();
                                       $labelNode.text(dataInput);
                                       
                                       if (hasDataNodeParam)
                                       {
                                          $dataNode.data('name', dataInput);     
                                       }
                                       
                                   }, 5);
                                   
                                   return true;
                             }
                             
                             else if ((code === backspaceKey) 
                                     || (code === deleteKey)
                                     || (code === spaceKey))
                             {
                                 setTimeout(function ()
                                   { 
                                       dataInput  = $controlInput.val();
                                       $labelNode.text(dataInput);
                                       
                                       if (hasDataNodeParam)
                                       {
                                          $dataNode.data('name', dataInput);     
                                       }
                                       
                                   }, 5);
                                   
                                   return true;
                                     
                             }                            
                             // si son las flechas de dirección no
                             // hace nada
                             else if (!((code === upArrowKey)
                                     || (code === downArrowKey)
                                     || (code === leftArrowKey)
                                     || (code === rightArrowKey)))
                             {
                               dataInput = ($controlInput.val()) + String.fromCharCode(code);       
                             }
                             else
                             {
                               dataInput = $controlInput.val();             
                             }
                             
                             $labelNode.text(dataInput);
                             
                             if (hasDataNodeParam)
                             {
                                $dataNode.data('name', dataInput);                
                             }
                        });
                    
                },
                
                addHanderChangeInputMenus: function($nodeInput)
                {
                    
                    var $nodeData = $nodeInput.parents('li');
                    var $nodeLabel = $nodeData.find('.menu-item-title');
                    
                    this.addHanderInput($nodeInput, $nodeLabel, $nodeData);
                },
               

		/**
		 * An interface for managing default values for input elements
		 * that is both JS and accessibility-friendly.
		 *
		 * Input elements that add the class 'input-with-default-title'
		 * will have their values set to the provided HTML title when empty.
		 */
		setupInputWithDefaultTitle : function() {
			var name = 'input-with-default-title';

			$('.' + name).each( function(){
				var $t = $(this), title = $t.attr('title'), val = $t.val();
				$t.data( name, title );

				if( '' == val ) $t.val( title );
				else if ( title == val ) return;
				else $t.removeClass( name );
			}).focus( function(){
				var $t = $(this);
				if( $t.val() == $t.data(name) )
					$t.val('').removeClass( name );
			}).blur( function(){
				var $t = $(this);
				if( '' == $t.val() )
					$t.addClass( name ).val( $t.data(name) );
			});

			$( '.blank-slate .input-with-default-title' ).focus();
		},

                /*
		attachThemeLocationsListeners : function() {
			var loc = $('#nav-menu-theme-locations'), params = {};
			params['action'] = 'menu-locations-save';
			params['menu-settings-column-nonce'] = $('#menu-settings-column-nonce').val();
			loc.find('input[type="submit"]').click(function() {
				loc.find('select').each(function() {
					params[this.name] = $(this).val();
				});
				loc.find('.spinner').show();
				$.post( ajaxurl, params, function(r) {
					loc.find('.spinner').hide();
				});
				return false;
			});
		},*/
                
                /*
		attachQuickSearchListeners : function() {
			var searchTimer;
                        var that = this;

			$('.quick-search').keypress(function(e){
				var t = $(this);

				if( 13 == e.which ) {
					that.updateQuickSearchResults( t );
					return false;
				}

				if( searchTimer ) clearTimeout(searchTimer);

				searchTimer = setTimeout(function(){
					that.updateQuickSearchResults( t );
				}, 400);
			}).attr('autocomplete','off');
		},

		updateQuickSearchResults : function(input) {
                    var that = this;
                    var panel, params,
			minSearchLength = 2,
			q = input.val();

			if( q.length < minSearchLength ) return;

			panel = input.parents('.tabs-panel');
			params = {
				'action': 'menu-quick-search',
				'response-format': 'markup',
				'menu': $('#menu').val(),
				'menu-settings-column-nonce': $('#menu-settings-column-nonce').val(),
				'q': q,
				'type': input.attr('name')
			};

			$('.spinner', panel).show();

			$.post( ajaxurl, params, function(menuMarkup) {
				that.processQuickSearchQueryResponse(menuMarkup, params, panel);
			});
		},*/
                
                /**
		* Adds selected menu items to the menu.
		*
		* @param processMethod jQuery metabox The metabox jQuery object.
		*/
		addCategoryToMenu : function(processMethod) 
                {
                    var menuItems = {},
                        that = this,
			$checkboxes = ( $('.tabs-panel-active .categorychecklist li input:checked').length > 0) 
                                        ? $('.tabs-panel-active .categorychecklist li input:checked') 
                                        : {};
 
                    if ($checkboxes.length > 0 )
                    {
                         // Show the ajax spinner
                        $('.add-to-menu .spinner').show();
                        
                        // Retrieve menu item data
                        $checkboxes.each(function()
                        {
                                var $checkboxNode = $(this);
                                
                                if ($checkboxNode.data("item-type") === 'post')
                                {
                                    var data = $('#template-menu-category-blog').tmpl( 
                                    { "id": $checkboxNode.data("id"),
                                      "url":$checkboxNode.data("url"),
                                      "name":$checkboxNode.data("name") });
                                }
                                else
                                {
                                    var data = $('#template-menu-category-event').tmpl( 
                                    { "id": $checkboxNode.data("id"),
                                      "url":$checkboxNode.data("url"),
                                      "name":$checkboxNode.data("name") });
                                }
                                
                                var $nodeLi = $(data);
                                that.addHanderChangeInputMenus($nodeLi.find('.nav-label'));
                                 
                                that.addMenuItemToTop($nodeLi);
                                      
                        });
                        
                        // Deselect the items and hide the ajax spinner
			$checkboxes.removeAttr('checked');
                        
                        // Remove the ajax spinner
			$('.add-to-menu .spinner').hide();
                        
                        //actualiza los mensajes de ayuda
                        this.updateInfoMenus();
                    }
                    return false;
		},
                        
                /**
		* Adds selected menu items to the menu.
		*
		* @param processMethod jQuery metabox The metabox jQuery object.
		*/
		addSelectedPagesToMenu : function(processMethod) 
                {
                    var menuItems = {},
                        that = this,
			$checkboxes = ( $('.tabs-panel-active .pagechecklist li input:checked').length > 0) 
                                        ? $('.tabs-panel-active .pagechecklist li input:checked') 
                                        : {};
 
                    if ($checkboxes.length > 0 )
                    {
                         // Show the ajax spinner
                        $('.add-to-menu .spinner').show();
                        
                        // Retrieve menu item data
                        $checkboxes.each(function()
                        {
                                var $checkboxNode = $(this);
                                
                                var data = $('#template-menu-page').tmpl( 
                                    { "id": $checkboxNode.data("id"),
                                      "url":$checkboxNode.data("url"),
                                      "name":$checkboxNode.data("name") });
                          
                                var $nodeLi = $(data);
                                that.addHanderChangeInputMenus($nodeLi.find('.nav-label'));
                                 
                                that.addMenuItemToTop($nodeLi);
                                      
                        });
                        
                        // Deselect the items and hide the ajax spinner
			$checkboxes.removeAttr('checked');
                        
                        // Remove the ajax spinner
			$('.add-to-menu .spinner').hide();
                        
                        //actualiza los mensajes de ayuda
                        this.updateInfoMenus();
                    }
                    return false;

                                     

                                        /*
					if ( 0 === $('#menu-to-edit').length ) {
						return false;
					}

					return this.each(function() {
						var t = $(this), menuItems = {},
							checkboxes = ( api.options.menus.oneThemeLocationNoMenus && 0 === t.find('.tabs-panel-active .categorychecklist li input:checked').length ) ? t.find('#page-all li input[type="checkbox"]') : t.find('.tabs-panel-active .categorychecklist li input:checked'),
                                                        //checkboxes = ( 0 === t.find('.tabs-panel-active .categorychecklist li input:checked').length ) ? t.find('#page-all li input[type="checkbox"]') : t.find('.tabs-panel-active .categorychecklist li input:checked'),
							re = new RegExp('menu-item\\[(\[^\\]\]*)');

						processMethod = processMethod || api.addMenuItemToBottom;

						// If no items are checked, bail.
						if ( !checkboxes.length )
							return false;

						// Show the ajax spinner
						t.find('.spinner').show();

						// Retrieve menu item data
						$(checkboxes).each(function(){
							var t = $(this),
								listItemDBIDMatch = re.exec( t.attr('name') ),
								listItemDBID = 'undefined' == typeof listItemDBIDMatch[1] ? 0 : parseInt(listItemDBIDMatch[1], 10);
							if ( this.className && -1 != this.className.indexOf('add-to-top') )
								processMethod = api.addMenuItemToTop;
							menuItems[listItemDBID] = t.closest('li').getItemData( 'add-menu-item', listItemDBID );
						});

						// Add the items
						api.addItemToMenu(menuItems, processMethod, function(){
							// Deselect the items and hide the ajax spinner
							checkboxes.removeAttr('checked');
							t.find('.spinner').hide();
						});
                                    
					});*/
		},

		addCustomLink : function( processMethod ) {
                        var that = this;
			var url = $('#custom-menu-item-url').val(),
				name = $('#custom-menu-item-name').val();

			processMethod = processMethod || that.addMenuItemToBottom;

			if ( '' === url || 'http://' === url )
                        {
                            return false;
                        }
				

			// Show the ajax spinner
			$('.customlinkdiv .spinner').show();
                        
                        var dataHTML = $('#template-menu-link').tmpl( 
                            { "url":url,
                              "name":name });
                          
                              //  .prependTo(this.menuList);
                         
                         var $nodeLi = $(dataHTML);
                         
                         that.addHanderChangeInputMenus($nodeLi.find('.nav-label'));
                            
                         this.addMenuItemToTop($nodeLi);
                         
                         
                          // Set custom link form back to defaults
                         $('#custom-menu-item-name').val('').blur();
                         $('#custom-menu-item-url').val('http://');
                         
                         this.refreshKeyboardAccessibility();
                         
                         //actualiza los mensajes de ayuda
                         this.updateInfoMenus();
                         
                         // Remove the ajax spinner
			 $('.customlinkdiv .spinner').hide();
                        
                         /*
                         this.refreshKeyboardAccessibility();
                         this.refreshAdvancedAccessibility();*/
                        
                        /*
			
                    this.addLinkToMenu( url, label, processMethod, function() {
				// Remove the ajax spinner
				$('.customlinkdiv .spinner').hide();
				// Set custom link form back to defaults
				$('#custom-menu-item-name').val('').blur();
				$('#custom-menu-item-url').val('http://');
			});*/
		},

		addLinkToMenu : function(url, label, processMethod, callback) {
			processMethod = processMethod || this.addMenuItemToBottom;
			callback = callback || function(){};

			this.addItemToMenu({
				'-1': {
					'menu-item-type': 'custom',
					'menu-item-url': url,
					'menu-item-title': label
				}
			}, processMethod, callback);
		},

		addItemToMenu : function(menuItem, processMethod, callback) {
			var menu = $('#menu').val(),
				nonce = $('#menu-settings-column-nonce').val();

			processMethod = processMethod || function(){};
			callback = callback || function(){};

			params = {
				'action': 'add-menu-item',
				'menu': menu,
				'menu-settings-column-nonce': nonce,
				'menu-item': menuItem
			};
                        
                        
                        var formData = $(options.add_page_menu).find('input, select, textarea').serialize();
                        

                        /*
                        var formData = $(options.form).find('input, select, textarea').serialize();
            
                        $.ajax({
                            type: "POST",
                            url: options.autocomplete_url,
                            datatype: "json",
                            data: formData,
                            cache: false,
                            success: function(dataJSON)
                            {
                                
                            },
                           statusCode: {
                                302: function(){
                                
                                },
                                404: function() {
                               
                                },
                                500: function() {
                       
                                }
                            },
                           error: function(dataJSON)
                            {
                  
                  
                            }
                        });*/
                        
                        /*
			$.post( ajaxurl, params, function(menuMarkup) {
				var ins = $('#menu-instructions');
				processMethod(menuMarkup, params);
				// Make it stand out a bit more visually, by adding a fadeIn
				$( 'li.pending' ).hide().fadeIn('slow');
				$( '.drag-instructions' ).show();
				if( ! ins.hasClass( 'menu-instructions-inactive' ) && ins.siblings().length )
					ins.addClass( 'menu-instructions-inactive' );
				callback();
			});*/
		},

		/**
		 * Process the add menu item request response into menu list item.
		 *
		 * @param string menuMarkup The text server response of menu item markup.
		 * @param object req The request arguments.
		 */
		addMenuItemToBottom : function( menuMarkup, req ) {
			$(menuMarkup).hideAdvancedMenuItemFields().appendTo( this.targetList );
			this.refreshKeyboardAccessibility();
			//this.refreshAdvancedAccessibility();
		},

		addMenuItemToTop : function( menuMarkup, req ) {
			$(menuMarkup).hideAdvancedMenuItemFields().prependTo( this.targetList );
			this.refreshKeyboardAccessibility();
			//this.refreshAdvancedAccessibility();
		},

		attachUnsavedChangesListener : function() {
                        var that = this;
			$('#menu-management input, #menu-management select, #menu-management, #menu-management textarea, .menu-location-menus select').change(function(){
				that.registerChange();
			});

			if ( 0 !== $('#menu-to-edit').length || 0 !== $('.menu-location-menus select').length ) {
				window.onbeforeunload = function(){
					if ( that.menusChanged )
						return navMenuL10n.saveAlert;
				};
			} else {
				// Make the post boxes read-only, as they can't be used yet
				$( '#menu-settings-column' ).find( 'input,select' ).end().find( 'a' ).attr( 'href', '#' ).unbind( 'click' );
			}
		},

		registerChange : function() {
			this.menusChanged = true;
		},

		attachTabsPanelListeners : function() {
                        var that = this;
			$('#menu-settings-column').bind('click', function(e) {
                           
				var selectAreaMatch, panelId, wrapper, items,
					target = $(e.target);

				if ( target.hasClass('nav-tab-link') ) {
                                        e.preventDefault();
                                        
					panelId = target.data( 'type' );
                                        
					wrapper = target.parents('.accordion-section-content').first();

					// upon changing tabs, we want to uncheck all checkboxes
					$('input', wrapper).removeAttr('checked');

					$('.tabs-panel-active', wrapper).removeClass('tabs-panel-active').addClass('tabs-panel-inactive');
					$('#' + panelId, wrapper).removeClass('tabs-panel-inactive').addClass('tabs-panel-active');

					$('.tabs', wrapper).removeClass('tabs');
					target.parent().addClass('tabs');

					// select the search bar
					$('.quick-search', wrapper).focus();

					
				} else if ( target.hasClass('select-all') ) {

                                        
                                        var $checkboxes = $('.tabs-panel-active .pagechecklist li input');
                                             
                                        if ($checkboxes.length > 0)
                                        {
                                            // Deselect the items 
                                            $checkboxes.attr('checked', 'checked');  
                                        }
                                            
                                        return false;
				} else if ( target.hasClass('submit-add-to-menu') ) {
                                    
                                
					that.registerChange();
                                        
                                        if ( e.target.id && 'submit-customlinkdiv' === e.target.id )	
                                        {
                                            that.addCustomLink( that.addMenuItemToBottom );
                                        }
                                        else if (e.target.id && 'submit-posttype-page' === e.target.id)
                                        {
                                            that.addSelectedPagesToMenu( that.addMenuItemToBottom );
                                        }
                                        else if (e.target.id && 'submit-posttype-category' === e.target.id)
                                        {
                                            that.addCategoryToMenu( that.addMenuItemToBottom );
                                            
                                        }
                                        
                                        /*
                                        

					if ( e.target.id && 'submit-customlinkdiv' == e.target.id )
						that.addCustomLink( that.addMenuItemToBottom );
					else if ( e.target.id && -1 != e.target.id.indexOf('submit-') )
						$('#' + e.target.id.replace(/submit-/, '')).addSelectedToMenu( that.addMenuItemToBottom );
                                        */
					return false;
				} else if ( target.hasClass('page-numbers') ) {
					$.post( ajaxurl, e.target.href.replace(/.*\?/, '').replace(/action=([^&]*)/, '') + '&action=menu-get-metabox',
						function( resp ) {
							if ( -1 == resp.indexOf('replace-id') )
								return;

							var metaBoxData = $.parseJSON(resp),
							toReplace = document.getElementById(metaBoxData['replace-id']),
							placeholder = document.createElement('div'),
							wrap = document.createElement('div');

							if ( ! metaBoxData['markup'] || ! toReplace )
								return;

							wrap.innerHTML = metaBoxData['markup'] ? metaBoxData['markup'] : '';

							toReplace.parentNode.insertBefore( placeholder, toReplace );
							placeholder.parentNode.removeChild( toReplace );

							placeholder.parentNode.insertBefore( wrap, placeholder );

							placeholder.parentNode.removeChild( placeholder );

						}
					);

					return false;
				}
			});
		},

		eventOnClickEditLink : function(clickedEl) {
                        
                       var $node = $(clickedEl);
                       
                       if ($node instanceof $)
                       {
                        var $menuItem = $node.parents('.menu-item');
                        var $settings = $menuItem.find('.menu-item-settings');
                      
                        if ($menuItem.hasClass('menu-item-edit-inactive'))
                        {
                           $settings.slideDown('fast');
                           $menuItem.removeClass('menu-item-edit-inactive')
				.addClass('menu-item-edit-active');
                               
                        }
                        else
                        {
                            $settings.slideUp('fast');
                            $menuItem.removeClass('menu-item-edit-active')
                                .addClass('menu-item-edit-inactive');
                        }       
                       }
                       
                       return false;
		},

		eventOnClickCancelLink : function(clickedEl) {
			var settings = $( clickedEl ).closest( '.menu-item-settings' ),
                            thisMenuItem = $( clickedEl ).closest( '.menu-item' );
                    
			thisMenuItem.removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
			settings.setItemData( settings.data('menu-item-data') ).hide();
			return false;
		},
                
                controlWait: function()
                {
                    var $btn = $('#control-save-menu-btn');
              
                    $btn.button('loading');
                    
                    $('#control-save-menu-main').addClass('wait-save-and-exit');
                    
                },
                controlReset: function()
                {
                     var $btn = $('#control-save-menu-btn');
                   
                    $btn.button('reset');
                    
                    $('#control-save-menu-main').removeClass('wait-save-and-exit');
            
                },
                
                hasSendContent: false,
                        
                sendContent: function(dataJson)
                {
                    var that = this;
                    var $form = $('#update-nav-menu');
                    
                    if (!this.hasSendContent)
                    {
                        this.hasSendContent = true;
                        
                        this.controlWait();
                        
                        $('body').addClass('loading');
                    
                    var formData = {
                        page_id: $('#arMenu_page_id').val(),
                        slot_name: $('#arMenu_slot_name').val(),
                        permid: $('#arMenu_permid').val(),
                        name: $('#arMenu_name').val(),
                        root_id: $('#arMenu_root_id').val(),
                        _csrf_token: $('#arMenu__csrf_token').val(),
                        dataJson: JSON.stringify(dataJson) };
          
                    $.ajax({
                        type: "POST",
                        url: $form.attr('action'),
                        datatype: "json",
                        data: { arMenu: formData},
                        cache: false,
                        success: function(retJSON)
                            {
                               window.location.href = $('#takeBack').attr('href');
                            },
                            statusCode: {
                                    404: function() {
                                        that.controlReset();
                                        that.hasSendContent = false;
                                    },
                                    500: function() {
                                        that.controlReset();
                                        that.hasSendContent = false;
                                    }
                            },
                        error: function(dataJSON)
                        {
                            that.controlReset();
                            that.hasSendContent = false;
                        }
                      });    
                    }
                },
                
                getParentMenuNodeId: function(nodeIndex, $menuNodes) 
                {
                    var retIndex = 0;
                    var find = false; 
                    
                    
                    if (($menuNodes instanceof $) 
                            && ($menuNodes.length > 0))
                    {
                        
                        var findLevelMin = $($menuNodes[nodeIndex]).data('depth');

                        for (var i = nodeIndex; 
                             (!find && (i >= 0));
                             --i)
                        {
                           find = ($($menuNodes[i]).data('depth') < findLevelMin); 
                           if (find)
                           {
                             retIndex = i; 
                           }
                        }
                    }
                    
                    
                    return retIndex;
                
                },

		eventOnClickMenuSave: function(clickedEl) {
                        
                        //var $menuNodes = $('#menu-to-edit').find('li.menu-item-depth-0');
                        var $menuNodes = $('#menu-to-edit').find('li');
                        
                        var dataJson = [];
                        var index = 0;
                        var currentLevel = 0;
                        var iParentNode = 0;
                        var that = this;
                        
                    
                        if (($menuNodes instanceof $) 
                            && ($menuNodes.length > 0))
                        {
                            $menuNodes.each(function(){
				var $node = $(this);
                                
                                if ($node.data('depth') > currentLevel)
                                {
                                    iParentNode = index;
  
                                    currentLevel = $node.data('depth');
                                    
                                }
                                else if ($node.data('depth') < currentLevel)
                                {
                                    //iParentNode = index - 1;
                                    
                                    iParentNode = that.getParentMenuNodeId(index , $menuNodes) +1;
                                    
                                    currentLevel = $node.data('depth');    
                                }
                               
                                 var objMenu = {
                                            index: index +1,
                                            parent: iParentNode,
                                            menu_type: $node.data('menu-type'),
                                            url: $node.data('url'),
                                            id: $node.data('id'),
                                            name: $node.data('name'),
                                            depth: $node.data('depth')
                                        };
                                        
                               
                                
                                dataJson.push(objMenu);
                                
                                index++;
                                
                            });
                            
                        }
                        //envia siempre, tambien borra completamente el menu
                        this.sendContent(dataJson);
                        
                        return false;
                        /*
            
			var locs = '',
			menuName = $('#menu-name'),
			menuNameVal = menuName.val();
			// Cancel and warn if invalid menu name
			if( !menuNameVal || menuNameVal == menuName.attr('title') || !menuNameVal.replace(/\s+/, '') ) {
				menuName.parent().addClass('form-invalid');
				return false;
			}
			// Copy menu theme locations
			$('#nav-menu-theme-locations select').each(function() {
				locs += '<input type="hidden" name="' + this.name + '" value="' + $(this).val() + '" />';
			});
			$('#update-nav-menu').append( locs );
			// Update menu item position data
			this.menuList.find('.menu-item-data-position').val( function(index) { return index + 1; } );
			window.onbeforeunload = null;*/
		},

		eventOnClickMenuDelete : function(clickedEl) {
			// Delete warning AYS
			if ( confirm( navMenuL10n.warnDeleteMenu ) ) {
				window.onbeforeunload = null;
				return true;
			}
			return false;
		},

		eventOnClickMenuItemDelete : function(clickedEl) {
			var $node = $(clickedEl);
                        if ($node instanceof $)
                        {
                          var $menuItem = $node.parents('.menu-item');
                          this.removeMenuItem( $menuItem );
                          this.registerChange();
                        }
                         
			return false;
		},

		/**
		 * Process the quick search response into a search result
		 *
		 * @param string resp The server response to the query.
		 * @param object req The request arguments.
		 * @param jQuery panel The tabs panel we're searching in.
		 */
        /*
		processQuickSearchQueryResponse : function(resp, req, panel) {
			var matched, newID,
			takenIDs = {},
			form = document.getElementById('nav-menu-meta'),
			pattern = new RegExp('menu-item\\[(\[^\\]\]*)', 'g'),
			$items = $('<div>').html(resp).find('li'),
			$item;

			if( ! $items.length ) {
				$('.categorychecklist', panel).html( '<li><p>' + navMenuL10n.noResultsFound + '</p></li>' );
				$('.spinner', panel).hide();
				return;
			}

			$items.each(function(){
				$item = $(this);

				// make a unique DB ID number
				matched = pattern.exec($item.html());

				if ( matched && matched[1] ) {
					newID = matched[1];
					while( form.elements['menu-item[' + newID + '][menu-item-type]'] || takenIDs[ newID ] ) {
						newID--;
					}

					takenIDs[newID] = true;
					if ( newID != matched[1] ) {
						$item.html( $item.html().replace(new RegExp(
							'menu-item\\[' + matched[1] + '\\]', 'g'),
							'menu-item[' + newID + ']'
						) );
					}
				}
			});

			$('.categorychecklist', panel).html( $items );
			$('.spinner', panel).hide();
		},
*/
		removeMenuItem : function(el) {
			var children = el.childMenuItems();
                        
                        var that = this;
                        
			el.addClass('deleting').animate({
					opacity : 0,
					height: 0
				}, 350, function() {
					var ins = $('#menu-instructions');
					el.remove();
					//children.shiftDepthClass( -1 ).updateParentMenuItemDBId();
                                        children.shiftDepthClass( -1 );
					
                                        that.updateInfoMenus();
                                        
				});
		},

		depthToPx : function(depth) {
			return depth * this.options.menuItemDepthPerLevel;
		},

		pxToDepth : function(px) {
			return Math.floor(px / this.options.menuItemDepthPerLevel);
		}
        
});