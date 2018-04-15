/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 * 
 */

$.widget( "arquematics.pageEditor", {
    
	
        options : 
                {
                    menuItemDepthPerLevel : 30, // Do not use directly. Use depthToPx and pxToDepth instead.
                    globalMaxDepth : 11
		},  
		menuList : undefined,	// 
		menusChanged : false,
                isRTL: false,
                //esta enviando contenido
                hasSendContent: false,
		
		// Functions that run on init.
		_init : function() {
			this.menuList = $('#menu-to-edit');
                   
			this.jQueryExtensions();
                        
                        this.addHanderControls();

                        this.updateInfoMenus();
                        
                        if (this.menuList.length)
                        {
                           this.initSortables();
                        }
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
				}
			});
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
                                over: function(event, ui) {
                                   // console.log( ui);
                                },
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
					parent = ( ui.item.next()[0] === ui.placeholder[0] ) ? ui.item.next() : ui.item;
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
					var children, 
                                            depthChange = currentDepth - originalDepth;
                                    
                                        if (!(( prev.data('depth') < currentDepth)
                                            && ((prev.data('menu-type') === 'blog')
                                                || (prev.data('menu-type') === 'events'))))
                                        {
                                          // Return child elements to the list
                                          children = transport.children().insertAfter(ui.item);

                                          // Add "sub menu" description
                                          var subMenuTitle = ui.item.find( '.item-title .is-submenu' );
                                          if ( 0 < currentDepth )
                                          {
                                              subMenuTitle.show();  
                                          }	
                                          else
                                          {
                                              subMenuTitle.hide();  
                                          }
						
                                          // Update depth classes
                                          if( depthChange !== 0 ) 
                                          {
						ui.item.updateDepthClass( currentDepth );
						children.shiftDepthClass( depthChange );
						updateMenuMaxDepth( depthChange );
                                          }
					
					
                                          // address sortable's incorrectly-calculated top in opera
                                          ui.item[0].style.top = 0;

                                          // handle drop placement for rtl orientation
                                          if ( that.isRTL ) {
						ui.item[0].style.left = 'auto';
						ui.item[0].style.right = 0;
                                          }
                                        
                                          // Register a change
                                          that.registerChange();  
                                          
                                          nodeOrderChange(ui.item, depthChange);
                                        }
                                       
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
                                            negateIfRTL = (that.isRTL)? -1 : 1,
                                            edge = that.isRTL ? offset.left + ui.helper.width() : offset.left,
                                            depth = negateIfRTL * that.pxToDepth( edge - menuEdge );
					// Check and correct if depth is not within range.
					// Also, if the dragged element is dragged upwards over
					// an item, shift the placeholder to a child position.
					if ( depth > maxDepth || offset.top < prevBottom ) depth = maxDepth;
					else if ( depth < minDepth ) depth = minDepth;
                                     
                              
					if( depth !== currentDepth )
                                        {
                                           updateCurrentDepth(ui, depth); 
                                        }
						
					// If we overlap the next element, manually shift downwards
					if( nextThreshold 
                                            && (offset.top + helperHeight > nextThreshold )) 
                                        {
                                            next.after( ui.placeholder );
                                            updateSharedVars( ui );
                                            $(this).sortable( "refreshPositions" );
					}
                                        
                                        if (( prev.data('depth') < depth)
                                            && ((prev.data('menu-type') === 'blog')
                                            || (prev.data('menu-type') === 'events')))
                                        {
                                          ui.placeholder.addClass('sortable-placeholder-error');      
                                        }
                                        else 
                                        {
                                          ui.placeholder.removeClass('sortable-placeholder-error');       
                                        }
                                       
				}
			});

			function updateSharedVars(ui) {
				var depth;
                               
				prev = ui.placeholder.prev();
				next = ui.placeholder.next();
                                
                                

				// Make sure we don't select the moving item.
				if( prev[0] === ui.item[0] ) prev = prev.prev();
				if( next[0] === ui.item[0] ) next = next.next();

				prevBottom = (prev.length) ? prev.offset().top + prev.height() : 0;
				nextThreshold = (next.length) ? next.offset().top + next.height() / 3 : 0;
				minDepth = (next.length) ? next.menuItemDepth() : 0;

				if( prev.length )
                                {
                                    maxDepth = ( (depth = prev.menuItemDepth() + 1) > that.options.globalMaxDepth ) ? that.options.globalMaxDepth : depth;  
                                }
				else
                                {
                                   maxDepth = 0;     
                                }
                              
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
                        
                 /**
                 * se ha cambiado el orden del nodo
                 * @param {Jquery} $node :
                 * @param {int} depthChange : cambio de nivel
                 */
                function nodeOrderChange($node, depthChange)
                {
                    var $next =  (next instanceof $)? next : false,
                        $prev =  (prev instanceof $)? prev: false;
                   

                    if (depthChange === 0)
                    {
                       if ($next && $next.data('id'))
                       {
                           var options = {
                                "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $next.data('id') + "&type=before",
                                "type":"post"       
                           };
                           $.ajax(options);     
                       }
                       else if ($prev && $prev.data('id'))
                       {
                           var options = {
                                "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prev.data('id') + "&type=after",
                                "type":"post"       
                           };
                           $.ajax(options);    
                       }
                    }
                    else if (depthChange > 0)
                    {
                       if ($prev && $prev.data('id'))
                       {
                          
                           if (($node.data('depth') >= 0)
                               && ($prev.data('depth') >= 0)
                               && ($node.data('depth') > $prev.data('depth')))
                           {
                               var options = {
                                    "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prev.data('id') + "&type=inside",
                                    "type":"post"       
                                    };
                               $.ajax(options);       
                           }
                           else
                           {
                               var options = {
                                    "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prev.data('id') + "&type=after",
                                    "type":"post"       
                                    };
                               $.ajax(options);      
                           }
                       }
                       else if ($next && $next.data('id'))
                       {  
                            var options = {
                                    "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prev.data('id') + "&type=before",
                                    "type":"post"       
                                    };
                                    
                            $.ajax(options);   
                       }
                    }
                    else if (depthChange < 0)
                    {
                       if ($prev && $prev.data('id'))
                       {
                           var $prevItem = $prev.prev();
                           var find = false;
                           //buscar el nodo al mismo nivel
                           while ($prevItem.hasClass('menu-item') && (!find))
                           {
                                    //estan al mismo nivel
                                    find = ($prevItem.data('depth') ===  $node.data('depth'));
                                    if (!find)
                                    {
                                      $prevItem = $prevItem.prev();         
                                    }
                           }
                                
                           if (find)
                           {
                                var options = {
                                    "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prevItem.data('id') + "&type=after",
                                    "type":"post"       
                                    };
                                $.ajax(options);
                           }
                       }
                       else if ($next && $next.data('id'))
                       {
                            var options = {
                                    "url" : "/admin/a/treeMove?id=" + $node.data('id') + "&refId=" + $prev.data('id') + "&type=before",
                                    "type":"post"       
                                    };
                                    
                            $.ajax(options);
                           
                       }
                    }
                   }
		},

		attachListeners : function()
                {
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
                       
                        $("li label").bind('click', function(e) {
                             e.preventDefault();
                            
                             $(e.target).parent().find('input').click();
                            
                        });
                        
			$('#update-nav-menu').bind('click', function(e) {
                           
                                if ( e.target && e.target.className ) 
                                {
                                        
					if ( -1 !== e.target.className.indexOf('item-edit') )
                                        {
                                                e.preventDefault();
						return that.eventOnClickEditLink(e.target);
					}
                                        else if ( -1 !== e.target.className.indexOf('add-to-menu') )
                                        {
                                               
                                                e.preventDefault();
						return that.eventOnClickSaveMenu(e.target);
					}
                                        else if ( -1 !== e.target.className.indexOf('update-page') )
                                        {
						e.preventDefault();
                                                return that.eventOnClickUpdatePage(e.target);
					}
                                        else if ( -1 !== e.target.className.indexOf('item-delete') )
                                        {
                                                e.preventDefault();
						return that.eventOnClickMenuItemDelete(e.target);
					}   
				}
			});
                        
                        $("#cmd-delete-page-accept").bind('click', function(e) {
                            e.preventDefault();
                            
                            that.eventOnClickDeletePage(e);
                            
                        });
                       
                        
			$('#add-custom-links input[type="text"]').keypress(function(e){
				
                            if ( e.keyCode === 13 ) {
					e.preventDefault();
					$("#submit-customlinkdiv").click();
				}
			});
                          
		},
                addHanderControls: function()
                {
                    var that = this;
                    
                    this.attachListeners();
                    
                    this.addHanderNode($("#add-page"));
                    
                    $("#add-page h3").click(function(e){
                    
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    });
                    
                    $.each(this.menuList.children(), function (index, value) {
                        that.addHanderNode($(value));
                        that.addHanderInput($(value));
                    });
                },
                        
                addHanderNode: function ($node)
                {
                    
                    $node.find("input[name='" + $node.find('#settings_view_options').attr('name') + "']").change(function(e)
                    {
                        e.preventDefault();
                        
                        var $nodeLi = $(this).parents('li');
                        // var v = $(stem + ':checked').val();
                        var v = $(this).val();
                        if (v === 'login')
                        {
                            $nodeLi.find('.a-page-permissions-view-extended').show();
                        }
                        else
                        {
                            $nodeLi.find('.a-page-permissions-view-extended').hide();
                        }
                    });
                    
                    var $nodeControlInput = $node.find('#settings_view_options');
                    
                    $node.find("input[name='" + $node.find('#settings_view_options').attr('name') + "'][value='" + $nodeControlInput.data('default-choice') + "']").change();
                    
                    $node.find("input[name='settings[edit_admin_lock]']").change(function(e)
                    {
                        e.preventDefault();
                         
                        var $nodeLi = $(this).parents('li');
                        if ($(this).attr('checked'))
                        {
                            $nodeLi.find('.a-page-permissions-edit-extended').hide();
                        }
                        else
                        {
                            $nodeLi.find('.a-page-permissions-edit-extended').show();
                        }
                    });
    
                    $node.find("input[name='settings[edit_admin_lock]']").change();
                    
                    //hide and show controls
                    $node.find('.a-permissions-heading').click(function(e) {
                        e.preventDefault();
                        
                        $(this).parent().children('.accordion-content').toggle();
                    });
                    
                    $node.find('.a-tag-heading').click(function(e) {
                        e.preventDefault();
                        
                        $(this).parent().children('.accordion-content').toggle();
                    });
                    
                    $node.find('.a-more-options-btn').click(function(e) {
                        e.preventDefault();
                        
                        $(this).parent().children('.a-options-more').toggle();
                    });
                    
                    $node.find('.a-permissions-heading').click();
                    
                    $node.find('.a-tag-heading').click();
                    
                    $node.find('.a-more-options-btn').click();
                    
                },
                        
                /**
                 * comportamiento de un control input
                 * 
                 * @param {$node} $node : nodo li del menu
                 * 
                 */ 
                addHanderInput: function($node)
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
                            rightArrowKey   =	39,
                            
                            $controlInput = $node.find("#a-edit-page-title"),
                            $labelNode = $node.find("#menu-title-label"),
                            $dataNode = $node;
                    
                     
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
                                       $dataNode.data('name', dataInput);
                                       
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
                                       $dataNode.data('name', dataInput); 
                                       
                                       
                                       
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
                                       $dataNode.data('name', dataInput);  
                                       
                                       
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
                             
                             $dataNode.data('name', dataInput);  
                             
                        });
                    
                },
               
		addMenuItemToTop : function( menuMarkup) {
			$(menuMarkup).prependTo( this.menuList );
		},
               
		registerChange : function() {
			this.menusChanged = true;
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
               
                controlWait: function()
                {
                    var $btn = $('#control-save-menu-btn');
              
                    $btn.button('loading');
                    
                    // Show the ajax spinner
                    $('.customlinkdiv .spinner').show();
                    
                    $('#control-save-menu-main').addClass('wait-save-and-exit');
                    
                },
                controlReset: function()
                {
                     var $btn = $('#control-save-menu-btn');
                   
                    $btn.button('reset');
                    
                    $('#control-save-menu-main').removeClass('wait-save-and-exit');
            
                },
                

                renderNewPageItem: function(dataJson)
                {
                    
                     var dataHTML = $('#template-page').tmpl( 
                            { "formHTML":dataJson.HTML,
                              "id": dataJson.values.id,
                              "slug": dataJson.values.slug,
                              "title":dataJson.values.title });
                          
                        
                     var $node = $(dataHTML);
                     
                     $node.find('#menu-item-settings-' + dataJson.values.id).prepend(dataJson.HTML);
                     
                     return $node;
                      
                },
                      
                showDeleteModal: function($node, callBack)
                {
                    var $nodeLi = $node.parents('li');
                    
                    var markup = $('#modal-body-content').data('warm-text');
            
                    $.template( "textTemplate", markup );
            
                    var $node = $.tmpl( "textTemplate", {pagename:$nodeLi.data('name')} );
                    $('#modal-body-content').text($node.text());
                    $('#page_delete_id').val($nodeLi.data('id'));
                    $('#delete-page-modal').modal('show');
                },
                        
                /**
                 * envia el contenido del formulario
                 * 
                 * @param {JQuery obj} $node : nodo del formulario en el que esta el boton
                 *                              que ha enviado el formulario
                 */       
                sendContent: function($node)
                {
                    var that = this
                    , options = this.options
                    , view = $node.find("#settings_view_options:radio:checked").val()
                        $form = $('#update-nav-menu');
                    
                    if (!this.hasSendContent)
                    {
                        this.hasSendContent = true;
                        
                        this.controlWait();
                 
                   /*
                    * JSON.stringify(dataJson)
                           [id] => 
                           [view_individuals] => [
                                {"id":"1","name":"Normal Admin (admin)","selected":false,"applyToSubpages":false},
                                {"id":"4","name":"Test1 User (test1)","selected":false,"applyToSubpages":false},
                                {"id":"5","name":"Test2 User (test2)","selected":false,"applyToSubpages":false}
                                ] 
                           [view_groups] => 
                               [
                               {"id":"editors_and_guests","name":"Editors y Guests","selected":true,"applyToSubpages":false},
                               {"id":"1","name":"admin","selected":true,"applyToSubpages":false},
                               {"id":"3","name":"basic","selected":false,"applyToSubpages":false},
                               {"id":"4","name":"editor","selected":true,"applyToSubpages":false},
                               {"id":"2","name":"guest","selected":false,"applyToSubpages":false}
                                ] 
                           [edit_individuals] => [
                               {"id":"4","name":"Test1 User (test1)","selected":true,"extra":false,"applyToSubpages":false},
                               {"id":"5","name":"Test2 User (test2)","selected":false,"extra":false,"applyToSubpages":false}
                                ] 
                           [edit_groups] => [
                               {"id":"3","name":"basic","selected":true,"extra":false,"applyToSubpages":false},
                               {"id":"4","name":"editor","selected":false,"extra":false,"applyToSubpages":false}
                            ] 
                           [_csrf_token] => 4a026a6e0e4b37203d1f9c5b159e2b4c
                           [realtitle] => dfg sdf 
                           [slug] => /gsdfgsdfg d gsd 
                           [joinedtemplate] => a:home 
                           [archived] => 0 
                           [tags] => gogogo, masver 
                           [real_meta_description] => df gsdfg sdf 
                           [view_options] => login 
                           [edit_admin_lock] => on
                       ) */
                   
                   //var adminLock = '';
                  
                  /*
                   var formData = {
                     id: $node.find('#a_settings_settings_id').val(),
                     view_individuals: $node.find("input[name='settings[view_individuals]']").val(),
                     view_groups: $node.find("input[name='settings[view_groups]']").val(),
                     edit_individuals: $node.find("input[name='settings[edit_individuals]']").val(),
                     edit_groups: $node.find("input[name='settings[edit_groups]']").val(),
                     _csrf_token: $node.find('#a_settings_settings__csrf_token').val(),
                     realtitle: $node.find('#a-edit-page-title').val(),
                     slug: $node.find('#a_settings_settings_slug').val(),
                     joinedtemplate: $node.find('#a_settings_settings_joinedtemplate').val(),
                     archived: archived,
                     simple_status: $node.find('#a_settings_settings_simple_status').val(),
                     tags: $node.find('#a_settings_settings_tags').val(),
                     real_meta_description: $node.find('#a_settings_settings_real_meta_description').val(),
                     view_options: view,
                     edit_admin_lock: $node.find('#a_settings_settings_edit_admin_lock').val()
                   };*/
                        
                     var formData = {
                     id: $node.find('#a_settings_settings_id').val(),
                     view_individuals: $node.find("input[name='settings[view_individuals]']").val(),
                     view_groups: $node.find("input[name='settings[view_groups]']").val(),
                     edit_individuals: $node.find("input[name='settings[edit_individuals]']").val(),
                     edit_groups: $node.find("input[name='settings[edit_groups]']").val(),
                     _csrf_token: $node.find('#a_settings_settings__csrf_token').val(),
                     realtitle: $node.find('#a-edit-page-title').val(),
                     slug: $node.find('#a_settings_settings_slug').val(),
                     joinedtemplate: $node.find('#a_settings_settings_joinedtemplate').val(),
                     archived: $node.find("#settings_archived:radio:checked").val(),
                     simple_status: $node.find('#a_settings_settings_simple_status').val(),
                     tags: $node.find('#a_settings_settings_tags').val(),
                     real_meta_description: $node.find('#a_settings_settings_real_meta_description').val(),
                     view_options: view,
                     edit_admin_lock: $node.find('#a_settings_settings_edit_admin_lock').val()
                   };
                   
                   
                   $('body').addClass('loading');
                   
                    $.ajax({
                        type: "POST",
                        url: $form.attr('action'),
                        datatype: "json",
                        data: { settings: formData},
                        cache: false,
                        success: function(retJSON)
                            {
                               if (retJSON.status === 200)
                               {
                                   var $nodeLi = that.renderNewPageItem(retJSON);
                             
                                   that.addMenuItemToTop($nodeLi);
                                   
                                   that.addHanderNode($nodeLi);
                                   that.addHanderInput($nodeLi);
                         
                                   //actualiza los mensajes de ayuda
                                   that.updateInfoMenus();
                                   
                                  window.location = options.url_reload;
                               }
                               
                               that.controlReset();
                               that.hasSendContent = false;
                               // Remove the ajax spinner
                               $('.customlinkdiv .spinner').hide();
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
                        
                eventOnClickUpdatePage: function(clickedEl)
                {
                     var $btn = $(clickedEl)
                        , $node = $btn.parents('li')
                        , $form = $('#update-nav-menu')
                        , archived = $node.find("#settings_archived:radio:checked").val()
                        , view = $node.find("#settings_view_options:radio:checked").val()
                        , that = this
                        , options = this.options;               
                      
                     if (!this.hasSendContent)
                     {
                       $btn.button('load');
                       this.hasSendContent = true;
                       
                       var formData = {
                            id: $node.find('#a_settings_settings_id').val(),
                            view_individuals: $node.find("input[name='settings[view_individuals]']").val(),
                            view_groups: $node.find("input[name='settings[view_groups]']").val(),
                            edit_individuals: $node.find("input[name='settings[edit_individuals]']").val(),
                            edit_groups: $node.find("input[name='settings[edit_groups]']").val(),
                            _csrf_token: $node.find('#a_settings_settings__csrf_token').val(),
                            realtitle: $node.find('#a-edit-page-title').val(),
                            slug: $node.find('#a_settings_settings_slug').val(),
                            joinedtemplate: $node.find('#a_settings_settings_joinedtemplate').val(),
                            archived: archived,
                            simple_status: $node.find('#a_settings_settings_simple_status').val(),
                            tags: $node.find('#a_settings_settings_tags').val(),
                            real_meta_description: $node.find('#a_settings_settings_real_meta_description').val(),
                            view_options: view,
                            edit_admin_lock: $node.find('#a_settings_settings_edit_admin_lock').val()
                        };
                        
                        $('body').addClass('loading');
                   
                        $.ajax({
                            type: "POST",
                            url: $form.attr('action'),
                            datatype: "json",
                            data: { settings: formData},
                            cache: false,
                            success: function(retJSON)
                            {
                               $btn.button('reset');
                               that.hasSendContent = false;
                               
                               window.location = options.url_reload;
                            },
                            statusCode: {
                                    404: function() {
                                       
                                        $btn.button('reset');
                                        that.hasSendContent = false;
                                    },
                                    500: function() {
                                       
                                        $btn.button('reset');
                                        that.hasSendContent = false;
                                    }
                            },
                        error: function(dataJSON)
                        {
                           
                            $btn.button('reset');
                            that.hasSendContent = false;
                        }
                      });   
                   }
                
             
                    return false;
                },

		eventOnClickSaveMenu: function(clickedEl) {
                        
                     this.sendContent($(clickedEl).parents('#add-page'));
		},

               
                eventOnClickDeletePage: function (event)
                {
                    var $btn = $('#cmd-delete-page-accept'),
                        $nodeToDelete = $('#menu-item-' + $('#page_delete_id').val()),
                        $form = $('#delete-page-form')
                        , that = this
                        , options = this.options;
                    
                   //asegurarse de que no esta enviando contenidos
                   if (!this.hasSendContent)
                   {
                       $btn.button('load');
                       this.hasSendContent = true;
                       
                        var formData = $form
                                .find('input, select, textarea')
                                .serialize();

                        $('body').addClass('loading');
                   
                        $.ajax({
                            type: "POST",
                            url: $form.attr('action'),
                            datatype: "json",
                            data: formData,
                            cache: false,
                            success: function(retJSON)
                            {
                               if (retJSON.status === 200)
                               {
                                  that.removeMenuItem( $nodeToDelete);
                                  that.registerChange();
                         
                                  //actualiza los mensajes de ayuda
                                  that.updateInfoMenus();
                               }
                               
                               $('#delete-page-modal').modal('hide');
                               
                               $btn.button('reset');
                               that.hasSendContent = false;
                               
                               window.location = options.url_reload;
                               
                            },
                            statusCode: {
                                    404: function() {
                                        $('#delete-page-modal').modal('hide');
                                        $btn.button('reset');
                                        that.hasSendContent = false;
                                    },
                                    500: function() {
                                        $('#delete-page-modal').modal('hide');
                                        $btn.button('reset');
                                        that.hasSendContent = false;
                                    }
                            },
                        error: function(dataJSON)
                        {
                            $('#delete-page-modal').modal('hide');
                            $btn.button('reset');
                            that.hasSendContent = false;
                        }
                      });   
                   }
                },

		eventOnClickMenuItemDelete : function(clickedEl) {
			var $node = $(clickedEl),
                            that = this;
                        if ($node instanceof $)
                        {
                          var $menuItem = $node.parents('.menu-item');
                          
                          this.showDeleteModal($node, 
                                    function() {
                                        that.removeMenuItem( $menuItem );
                                        that.registerChange();
                                    });
                          
                          
                        }
                         
			return false;
		},
                

		
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