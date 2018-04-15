/**
 * Copyright (c) 2006
 * Martin Czuchra, Nicolas Peters, Daniel Polak, Willi Tscheschner
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 **/

if(!ORYX.Plugins) 
	ORYX.Plugins = new Object();

ORYX.Plugins.DragDropResize = ORYX.Plugins.AbstractPlugin.extend({

	/**
	 *	Constructor
	 *	@param {Object} Facade: The Facade of the Editor
	 */
	construct: function(facade) {
		this.facade = facade;

		// Initialize variables
		this.currentShapes 		= [];			// Current selected Shapes
		//this.pluginsData 		= [];			// Available Plugins
		this.toMoveShapes 		= [];			// Shapes there will be moved
		this.distPoints 		= [];			// Distance Points for Snap on Grid
		this.isResizing 		= false;		// Flag: If there was currently resized
		this.dragEnable 		= false;		// Flag: If Dragging is enabled
		this.dragIntialized 	= false;		// Flag: If the Dragging is initialized
		this.edgesMovable		= true;			// Flag: If an edge is docked it is not movable
		this.offSetPosition 	= {x: 0, y: 0};	// Offset of the Dragging
		this.faktorXY 			= {x: 1, y: 1};	// The Current Zoom-Faktor
		this.containmentParentNode;				// the current future parent node for the dragged shapes
		this.isAddingAllowed 	= false;		// flag, if adding current selected shapes to containmentParentNode is allowed
		this.isAttachingAllowed = false;		// flag, if attaching to the current shape is allowed
		
		this.callbackMouseMove	= this.handleMouseMove.bind(this);
		this.callbackMouseUp	= this.handleMouseUp.bind(this);
		
		// Get the SVG-Containernode 
		var containerNode = this.facade.getCanvas().getSvgContainer();
		
		// Create the Selected Rectangle in the SVG
		this.selectedRect = new ORYX.Plugins.SelectedRect(containerNode);
		
		// Show grid line if enabled
		if (ORYX.CONFIG.SHOW_GRIDLINE) {
			this.vLine = new ORYX.Plugins.GridLine(containerNode, ORYX.Plugins.GridLine.DIR_VERTICAL);
			this.hLine = new ORYX.Plugins.GridLine(containerNode, ORYX.Plugins.GridLine.DIR_HORIZONTAL);
		}
		
		// Get a HTML-ContainerNode
		containerNode = this.facade.getCanvas().getHTMLContainer();
		
		this.scrollNode = this.facade.getCanvas().rootNode.parentNode.parentNode;
		
		// Create the southeastern button for resizing
		this.resizerSE = new ORYX.Plugins.Resizer(containerNode, "southeast", this.facade);
		this.resizerSE.registerOnResize(this.onResize.bind(this)); // register the resize callback
		this.resizerSE.registerOnResizeEnd(this.onResizeEnd.bind(this)); // register the resize end callback
		this.resizerSE.registerOnResizeStart(this.onResizeStart.bind(this)); // register the resize start callback
		
		// Create the northwestern button for resizing
		this.resizerNW = new ORYX.Plugins.Resizer(containerNode, "northwest", this.facade);
		this.resizerNW.registerOnResize(this.onResize.bind(this)); // register the resize callback
		this.resizerNW.registerOnResizeEnd(this.onResizeEnd.bind(this)); // register the resize end callback
		this.resizerNW.registerOnResizeStart(this.onResizeStart.bind(this)); // register the resize start callback
		
		// For the Drag and Drop
		// Register on MouseDown-Event on a Shape
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this));
	},

	/**
	 * On Mouse Down
	 *
	 */
	handleMouseDown: function(event, uiObj) {
		// If the selection Bounds not intialized and the uiObj is not member of current selectio
		// then return
		if(!this.dragBounds || !this.currentShapes.member(uiObj) || !this.toMoveShapes.length) {return};
		
		// Start Dragging
		this.dragEnable = true;
		this.dragIntialized = true;
		this.edgesMovable = true;

		// Calculate the current zoom factor
		var a = this.facade.getCanvas().node.getScreenCTM();
		this.faktorXY.x = a.a;
		this.faktorXY.y = a.d;

		// Set the offset position of dragging
		var upL = this.dragBounds.upperLeft();
		this.offSetPosition =  {
			x: Event.pointerX(event) - (upL.x * this.faktorXY.x),
			y: Event.pointerY(event) - (upL.y * this.faktorXY.y)};
		
		this.offsetScroll	= {x:this.scrollNode.scrollLeft,y:this.scrollNode.scrollTop};
			
		// Register on Global Mouse-MOVE Event
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.callbackMouseMove, false);	
		// Register on Global Mouse-UP Event
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.callbackMouseUp, true);			

		return;
	},

	/**
	 * On Key Mouse Up
	 *
	 */
	handleMouseUp: function(event) {
		
		//disable containment highlighting
		this.facade.raiseEvent({
									type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE,
									highlightId:"dragdropresize.contain"
								});
								
		this.facade.raiseEvent({
									type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE,
									highlightId:"dragdropresize.attached"
								});

		// If Dragging is finished
		if(this.dragEnable) {
		
			// and update the current selection
			if(!this.dragIntialized) {
				
				// Do Method after Dragging
				this.afterDrag();	
				
				// Check if the Shape is allowed to dock to the other Shape						
				if ( 	this.isAttachingAllowed &&
						this.toMoveShapes.length == 1 && this.toMoveShapes[0] instanceof ORYX.Core.Node  &&
						this.toMoveShapes[0].dockers.length > 0) {
					
					// Get the position and the docker					
					var position 	= this.facade.eventCoordinates( event );	
					var docker 		= this.toMoveShapes[0].dockers[0];


			
					//Command-Pattern for dragging several Shapes
					var dockCommand = ORYX.Core.Command.extend({
						construct: function(docker, position, newDockedShape, facade){
							this.docker 		= docker;
							this.newPosition	= position;
							this.newDockedShape = newDockedShape;
							this.newParent 		= newDockedShape.parent || facade.getCanvas();
							this.oldPosition	= docker.parent.bounds.center();
							this.oldDockedShape	= docker.getDockedShape();
							this.oldParent 		= docker.parent.parent || facade.getCanvas();
							this.facade			= facade;
							
							if( this.oldDockedShape ){
								this.oldPosition = docker.parent.absoluteBounds().center();
							}
							
						},			
						execute: function(){
							this.dock( this.newDockedShape, this.newParent,  this.newPosition );
							
							// Raise Event for having the docked shape on top of the other shape
							this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_ARRANGEMENT_TOP, excludeCommand: true})									
						},
						rollback: function(){
							this.dock( this.oldDockedShape, this.oldParent, this.oldPosition );
						},
						dock:function( toDockShape, parent, pos ){
							// Add to the same parent Shape
							parent.add( this.docker.parent )
							
							
							// Set the Docker to the new Shape
							this.docker.setDockedShape( undefined );
							this.docker.bounds.centerMoveTo( pos )				
							this.docker.setDockedShape( toDockShape );	
							//this.docker.update();
							
							this.facade.setSelection( [this.docker.parent] );	
							this.facade.getCanvas().update();
							this.facade.updateSelection();
																												
											
						}
					});
			
					// Instanziate the dockCommand
					var commands = [new dockCommand(docker, position, this.containmentParentNode, this.facade)];
					this.facade.executeCommands(commands);	
						
					
				// Check if adding is allowed to the other Shape	
				} else if( this.isAddingAllowed ) {
					
				
					// Refresh all Shapes --> Set the new Bounds
					this.refreshSelectedShapes();
					
				}
				
				this.facade.updateSelection();
							
				//this.currentShapes.each(function(shape) {shape.update()})
				// Raise Event: Dragging is finished
				this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_DRAGDROP_END});
			}	

			if (this.vLine)
				this.vLine.hide();
			if (this.hLine)
				this.hLine.hide();
		}

		// Disable 
		this.dragEnable = false;	
		

		// UnRegister on Global Mouse-UP/-Move Event
		document.documentElement.removeEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.callbackMouseUp, true);	
		document.documentElement.removeEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.callbackMouseMove, false);				
			
		return;
	},

	/**
	* On Key Mouse Move
	*
	*/
	handleMouseMove: function(event) {
		// If dragging is not enabled, go return
		if(!this.dragEnable) { return };
		// If Dragging is initialized
		if(this.dragIntialized) {
			// Raise Event: Drag will be started
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_DRAGDROP_START});
			this.dragIntialized = false;
			
			// And hide the resizers and the highlighting
			this.resizerSE.hide();
			this.resizerNW.hide();
			
			// if only edges are selected, containmentParentNode must be the canvas
			this._onlyEdges = this.currentShapes.all(function(currentShape) {
				return (currentShape instanceof ORYX.Core.Edge);
			});
			
//			/* If only edges are selected, check if they are movable. An Edge is
//			 * movable in case it is not docked
//			 */
//			if(this._onlyEdges) {
//				this.currentShapes.each(function(edge) {
//					if(edge.isDocked()) {
//						this.edgesMovable = false;
//						throw $break;
//					}
//				}.bind(this));
//			}
			
			// Do method before Drag
			this.beforeDrag();
			
			this._currentUnderlyingNodes = [];
			
		}

			
		// Calculate the new position
		var position = {
			x: Event.pointerX(event) - this.offSetPosition.x,
			y: Event.pointerY(event) - this.offSetPosition.y}

		position.x 	-= this.offsetScroll.x - this.scrollNode.scrollLeft; 
		position.y 	-= this.offsetScroll.y - this.scrollNode.scrollTop;
		
		// If not the Control-Key are pressed
		var modifierKeyPressed = event.shiftKey || event.ctrlKey;
		if(ORYX.CONFIG.GRID_ENABLED && !modifierKeyPressed) {
			// Snap the current position to the nearest Snap-Point
			position = this.snapToGrid(position);
		} else {
			if (this.vLine)
				this.vLine.hide();
			if (this.hLine)
				this.hLine.hide();
		}

		// Adjust the point by the zoom faktor 
		position.x /= this.faktorXY.x;
		position.y /= this.faktorXY.y;

		// Set that the position is not lower than zero
		position.x = Math.max( 0 , position.x)
		position.y = Math.max( 0 , position.y)

		// Set that the position is not bigger than the canvas
		var c = this.facade.getCanvas();
		position.x = Math.min( c.bounds.width() - this.dragBounds.width(), 		position.x)
		position.y = Math.min( c.bounds.height() - this.dragBounds.height(), 	position.y)	
						

		// Drag this bounds
		this.dragBounds.moveTo(position);

		// Update all selected shapes and the selection rectangle
		//this.refreshSelectedShapes();
		this.resizeRectangle(this.dragBounds);

		this.isAttachingAllowed = false;

		//check, if a node can be added to the underlying node
		var underlyingNodes = $A(this.facade.getCanvas().getAbstractShapesAtPosition(this.facade.eventCoordinates(event)));
		
		var checkIfAttachable = this.toMoveShapes.length == 1 && this.toMoveShapes[0] instanceof ORYX.Core.Node && this.toMoveShapes[0].dockers.length > 0
		checkIfAttachable	= checkIfAttachable && underlyingNodes.length != 1
		
			
		if(		!checkIfAttachable &&
				underlyingNodes.length === this._currentUnderlyingNodes.length  &&
				underlyingNodes.all(function(node, index){return this._currentUnderlyingNodes[index] === node}.bind(this))) {
					
			return;
			
		} else if(this._onlyEdges) {
			
			this.isAddingAllowed = true;
			this.containmentParentNode = this.facade.getCanvas();
			
		} else {
		
			/* Check the containment and connection rules */
			var options = {
				event : event,
				underlyingNodes : underlyingNodes,
				checkIfAttachable : checkIfAttachable
			};
			this.checkRules(options);
							
		}
		
		this._currentUnderlyingNodes = underlyingNodes.reverse();
		
		//visualize the containment result
		if( this.isAttachingAllowed ) {
			
			this.facade.raiseEvent({
									type: 			ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW,
									highlightId: 	"dragdropresize.attached",
									elements: 		[this.containmentParentNode],
									style: 			ORYX.CONFIG.SELECTION_HIGHLIGHT_STYLE_RECTANGLE,
									color: 			ORYX.CONFIG.SELECTION_VALID_COLOR
								});
								
		} else {
			
			this.facade.raiseEvent({
									type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE,
									highlightId:"dragdropresize.attached"
								});
		}
		
		if( !this.isAttachingAllowed ){
			if( this.isAddingAllowed ) {

				this.facade.raiseEvent({
										type:ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW,
										highlightId:"dragdropresize.contain",
										elements:[this.containmentParentNode],
										color: ORYX.CONFIG.SELECTION_VALID_COLOR
									});

			} else {

				this.facade.raiseEvent({
										type:ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW,
										highlightId:"dragdropresize.contain",
										elements:[this.containmentParentNode],
										color: ORYX.CONFIG.SELECTION_INVALID_COLOR
									});

			}
		} else {
			this.facade.raiseEvent({
									type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE,
									highlightId:"dragdropresize.contain"
								});			
		}	

		// Stop the Event
		//Event.stop(event);
		return;
	},
	
//	/**
//	 * Rollbacks the docked shape of an edge, if the edge is not movable.
//	 */
//	redockEdges: function() {
//		this._undockedEdgesCommand.dockers.each(function(el){
//			el.docker.setDockedShape(el.dockedShape);
//			el.docker.setReferencePoint(el.refPoint);
//		})
//	},
	
	/**
	 *  Checks the containment and connection rules for the selected shapes.
	 */
	checkRules : function(options) {
		var event = options.event;
		var underlyingNodes = options.underlyingNodes;
		var checkIfAttachable = options.checkIfAttachable;
		var noEdges = options.noEdges;
		
		//get underlying node that is not the same than one of the currently selected shapes or
		// a child of one of the selected shapes with the highest z Order.
		// The result is a shape or the canvas
		this.containmentParentNode = underlyingNodes.reverse().find((function(node) {
			return (node instanceof ORYX.Core.Canvas) || 
					(((node instanceof ORYX.Core.Node) || ((node instanceof ORYX.Core.Edge) && !noEdges)) 
					&& (!(this.currentShapes.member(node) || 
							this.currentShapes.any(function(shape) {
								return (shape.children.length > 0 && shape.getChildNodes(true).member(node));
							}))));
		}).bind(this));
								
		if( checkIfAttachable &&  this.containmentParentNode){
				
			this.isAttachingAllowed	= this.facade.getRules().canConnect({
												sourceShape:	this.containmentParentNode, 
												edgeShape:		this.toMoveShapes[0], 
												targetShape:	this.toMoveShapes[0]
												});						
			
			if ( this.isAttachingAllowed	) {
				var point = this.facade.eventCoordinates(event);
				this.isAttachingAllowed	= this.containmentParentNode.isPointOverOffset( point.x, point.y );
			}						
		}
		
		if( !this.isAttachingAllowed ){
			//check all selected shapes, if they can be added to containmentParentNode
			this.isAddingAllowed = this.toMoveShapes.all((function(currentShape) {
				if(currentShape instanceof ORYX.Core.Edge ||
					currentShape instanceof ORYX.Core.Controls.Docker ||
					this.containmentParentNode === currentShape.parent) {
					return true;
				} else if(this.containmentParentNode !== currentShape) {
					
					if(!(this.containmentParentNode instanceof ORYX.Core.Edge) || !noEdges) {
					
						if(this.facade.getRules().canContain({containingShape:this.containmentParentNode,
															  containedShape:currentShape})) {	  	
							return true;
						}
					}
				}
				return false;
			}).bind(this));				
		}
		
		if(!this.isAttachingAllowed && !this.isAddingAllowed && 
				(this.containmentParentNode instanceof ORYX.Core.Edge)) {
			options.noEdges = true;
			options.underlyingNodes.reverse();
			this.checkRules(options);			
		}
	},
	
	/**
	 * Redraw the selected Shapes.
	 *
	 */
	refreshSelectedShapes: function() {
		// If the selection bounds not initialized, return
		if(!this.dragBounds) {return}

		// Calculate the offset between the bounds and the old bounds
		var upL = this.dragBounds.upperLeft();
		var oldUpL = this.oldDragBounds.upperLeft();
		var offset = {
			x: upL.x - oldUpL.x,
			y: upL.y - oldUpL.y };

		// Instanciate the dragCommand
		var commands = [new ORYX.Core.Command.Move(this.toMoveShapes, offset, this.containmentParentNode, this.currentShapes, this)];
		// If the undocked edges command is setted, add this command
		if( this._undockedEdgesCommand instanceof ORYX.Core.Command ){
			commands.unshift( this._undockedEdgesCommand );
		}
		// Execute the commands			
		this.facade.executeCommands( commands );	

		// copy the bounds to the old bounds
		if( this.dragBounds )
			this.oldDragBounds = this.dragBounds.clone();

	},
	
	/**
	 * Callback for Resize
	 *
	 */
	onResize: function(bounds) {
		// If the selection bounds not initialized, return
		if(!this.dragBounds) {return;}
		
		this.dragBounds = bounds;
		this.isResizing = true;

		// Update the rectangle 
		this.resizeRectangle(this.dragBounds);
	},
	
	onResizeStart: function() {
		this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_RESIZE_START});
	},

	onResizeEnd: function() {
		
		if (!(this.currentShapes instanceof Array)||this.currentShapes.length<=0) {
			return;
		}
		
		// If Resizing finished, the Shapes will be resize
		if(this.isResizing) {
			
			var commandClass = ORYX.Core.Command.extend({
				construct: function(shape, newBounds, plugin){
					this.shape = shape;
					this.oldBounds = shape.bounds.clone();
					this.newBounds = newBounds;
					this.plugin = plugin;
				},			
				execute: function(){
					this.shape.bounds.set(this.newBounds.a, this.newBounds.b);
					this.update(this.getOffset(this.oldBounds, this.newBounds));
					
				},
				rollback: function(){
					this.shape.bounds.set(this.oldBounds.a, this.oldBounds.b);
					this.update(this.getOffset(this.newBounds, this.oldBounds))
				},
				
				getOffset:function(b1, b2){
					return {
						x: b2.a.x - b1.a.x,
						y: b2.a.y - b1.a.y,
						xs: b2.width()/b1.width(),
						ys: b2.height()/b1.height()
					}
				},
				update:function(offset){
					this.shape.getLabels().each(function(label) {
						label.changed();
					});
					
					var allEdges = [].concat(this.shape.getIncomingShapes())
						.concat(this.shape.getOutgoingShapes())
						// Remove all edges which are included in the selection from the list
						.findAll(function(r){ return r instanceof ORYX.Core.Edge }.bind(this))
												
					this.plugin.layoutEdges(this.shape, allEdges, offset);

					this.plugin.facade.setSelection([this.shape]);
					this.plugin.facade.getCanvas().update();
					this.plugin.facade.updateSelection();
				}
			});
			
			var bounds = this.dragBounds.clone();
			var shape = this.currentShapes[0];
			
			if(shape.parent) {
				var parentPosition = shape.parent.absoluteXY();
				bounds.moveBy(-parentPosition.x, -parentPosition.y);
			}
				
			var command = new commandClass(shape, bounds, this);
			
			this.facade.executeCommands([command]);
			
			this.isResizing = false;
			
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_RESIZE_END});
		}
	},
	

	/**
	 * Prepare the Dragging
	 *
	 */
	beforeDrag: function(){

		var undockEdgeCommand = ORYX.Core.Command.extend({
			construct: function(moveShapes){
				this.dockers = moveShapes.collect(function(shape){ return shape instanceof ORYX.Core.Controls.Docker ? {docker:shape, dockedShape:shape.getDockedShape(), refPoint:shape.referencePoint} : undefined }).compact();
			},			
			execute: function(){
				this.dockers.each(function(el){
					el.docker.setDockedShape(undefined);
				})
			},
			rollback: function(){
				this.dockers.each(function(el){
					el.docker.setDockedShape(el.dockedShape);
					el.docker.setReferencePoint(el.refPoint);
					//el.docker.update();
				})
			}
		});
		
		this._undockedEdgesCommand = new undockEdgeCommand( this.toMoveShapes );
		this._undockedEdgesCommand.execute();	
		
	},

	hideAllLabels: function(shape) {
			
			// Hide all labels from the shape
			shape.getLabels().each(function(label) {
				label.hide();
			});
			// Hide all labels from docked shapes
			shape.getAllDockedShapes().each(function(dockedShape) {
				var labels = dockedShape.getLabels();
				if(labels.length > 0) {
					labels.each(function(label) {
						label.hide();
					});
				}
			});

			// Do this recursive for all child shapes
			// EXP-NICO use getShapes
			shape.getChildren().each((function(value) {
				if(value instanceof ORYX.Core.Shape)
					this.hideAllLabels(value);
			}).bind(this));
	},

	/**
	 * Finished the Dragging
	 *
	 */
	afterDrag: function(){
				
	},

	/**
	 * Show all Labels at these shape
	 * 
	 */
	showAllLabels: function(shape) {

			// Show the label of these shape
			//shape.getLabels().each(function(label) {
			for(var i=0; i<shape.length ;i++){
				var label = shape[i];
				label.show();
			}//);
			// Show all labels at docked shapes
			//shape.getAllDockedShapes().each(function(dockedShape) {
			var allDockedShapes = shape.getAllDockedShapes()
			for(var i=0; i<allDockedShapes.length ;i++){
				var dockedShape = allDockedShapes[i];				
				var labels = dockedShape.getLabels();
				if(labels.length > 0) {
					labels.each(function(label) {
						label.show();
					});
				}
			}//);

			// Do this recursive
			//shape.children.each((function(value) {
			for(var i=0; i<shape.children.length ;i++){
				var value = shape.children[i];	
				if(value instanceof ORYX.Core.Shape)
					this.showAllLabels(value);
			}//).bind(this));
	},

	/**
	 * Intialize Method, if there are new Plugins
	 *
	 */
	/*registryChanged: function(pluginsData) {
		// Save all new Plugin, sorted by group and index
		this.pluginsData = pluginsData.sortBy( function(value) {
			return (value.group + "" + value.index);
		});
	},*/

	/**
	 * On the Selection-Changed
	 *
	 */
	onSelectionChanged: function(event) {
		var elements = event.elements;
		
		// Reset the drag-variables
		this.dragEnable = false;
		this.dragIntialized = false;
		this.resizerSE.hide();
		this.resizerNW.hide();

		// If there is no elements
		if(!elements || elements.length == 0) {
			// Hide all things and reset all variables
			this.selectedRect.hide();
			this.currentShapes = [];
			this.toMoveShapes = [];
			this.dragBounds = undefined;
			this.oldDragBounds = undefined;
		} else {

			// Set the current Shapes
			this.currentShapes = elements;

			// Get all shapes with the highest parent in object hierarchy (canvas is the top most parent)
			var topLevelElements = this.facade.getCanvas().getShapesWithSharedParent(elements);
			this.toMoveShapes = topLevelElements;
			
			this.toMoveShapes = this.toMoveShapes.findAll( function(shape) { return shape instanceof ORYX.Core.Node && 
																			(shape.dockers.length === 0 || !elements.member(shape.dockers.first().getDockedShape()))});		
																			
			elements.each((function(shape){
				if(!(shape instanceof ORYX.Core.Edge)) {return;}
				
				var dks = shape.getDockers();
								
				var hasF = elements.member(dks.first().getDockedShape());
				var hasL = elements.member(dks.last().getDockedShape());	
						
//				if(!hasL) {
//					this.toMoveShapes.push(dks.last());
//				}
//				if(!hasF){
//					this.toMoveShapes.push(dks.first())
//				} 
				/* Enable movement of undocked edges */
				if(!hasF && !hasL) {
					var isUndocked = !dks.first().getDockedShape() && !dks.last().getDockedShape()
					if(isUndocked) {
						this.toMoveShapes = this.toMoveShapes.concat(dks);
					}
				}
				
				if( shape.dockers.length > 2 && hasF && hasL){
					this.toMoveShapes = this.toMoveShapes.concat(dks.findAll(function(el,index){ return index > 0 && index < dks.length-1}))
				}
				
			}).bind(this));
			
			// Calculate the new area-bounds of the selection
			var newBounds = undefined;
			this.toMoveShapes.each(function(value) {
				var shape = value;
				if(value instanceof ORYX.Core.Controls.Docker) {
					/* Get the Shape */
					shape = value.parent;
				}
				
				if(!newBounds){
					newBounds = shape.absoluteBounds();
				}
				else {
					newBounds.include(shape.absoluteBounds());
				}
			}.bind(this));
			
			if(!newBounds){
				elements.each(function(value){
					if(!newBounds) {
						newBounds = value.absoluteBounds();
					} else {
						newBounds.include(value.absoluteBounds());
					}
				});
			}
			
			// Set the new bounds
			this.dragBounds = newBounds;
			this.oldDragBounds = newBounds.clone();

			// Update and show the rectangle
			this.resizeRectangle(newBounds);
			this.selectedRect.show();
			
			// Show the resize button, if there is only one element and this is resizeable
			if(elements.length == 1 && elements[0].isResizable) {
				var aspectRatio = elements[0].getStencil().fixedAspectRatio() ? elements[0].bounds.width() / elements[0].bounds.height() : undefined;
				this.resizerSE.setBounds(this.dragBounds, elements[0].minimumSize, elements[0].maximumSize, aspectRatio);
				this.resizerSE.show();
				this.resizerNW.setBounds(this.dragBounds, elements[0].minimumSize, elements[0].maximumSize, aspectRatio);
				this.resizerNW.show();
			} else {
				this.resizerSE.setBounds(undefined);
				this.resizerNW.setBounds(undefined);
			}

			// If Snap-To-Grid is enabled, the Snap-Point will be calculate
			if(ORYX.CONFIG.GRID_ENABLED) {

				// Reset all points
				this.distPoints = [];

				if (this.distPointTimeout)
					window.clearTimeout(this.distPointTimeout)
				
				this.distPointTimeout = window.setTimeout(function(){
					// Get all the shapes, there will consider at snapping
					// Consider only those elements who shares the same parent element
					var distShapes = this.facade.getCanvas().getChildShapes(true).findAll(function(value){
						var parentShape = value.parent;
						while(parentShape){
							if(elements.member(parentShape)) return false;
							parentShape = parentShape.parent
						}
						return true;
					})
					
					// The current selection will delete from this array
					//elements.each(function(shape) {
					//	distShapes = distShapes.without(shape);
					//});

					// For all these shapes
					distShapes.each((function(value) {
						if(!(value instanceof ORYX.Core.Edge)) {
							var ul = value.absoluteXY();
							var width = value.bounds.width();
							var height = value.bounds.height();

							// Add the upperLeft, center and lowerRight - Point to the distancePoints
							this.distPoints.push({
								ul: {
									x: ul.x,
									y: ul.y
								},
								c: {
									x: ul.x + (width / 2),
									y: ul.y + (height / 2)
								},
								lr: {
									x: ul.x + width,
									y: ul.y + height
								}
							});
						}
					}).bind(this));
					
				}.bind(this), 10)


			}
		}
	},

	/**
	 * Adjust an Point to the Snap Points
	 *
	 */
	snapToGrid: function(position) {

		// Get the current Bounds
		var bounds = this.dragBounds;
		
		var point = {};

		var ulThres = 6;
		var cThres = 10;
		var lrThres = 6;

		var scale = this.vLine ? this.vLine.getScale() : 1;
		
		var ul = { x: (position.x/scale), y: (position.y/scale)};
		var c = { x: (position.x/scale) + (bounds.width()/2), y: (position.y/scale) + (bounds.height()/2)};
		var lr = { x: (position.x/scale) + (bounds.width()), y: (position.y/scale) + (bounds.height())};

		var offsetX, offsetY;
		var gridX, gridY;
		
		// For each distant point
		this.distPoints.each(function(value) {

			var x, y, gx, gy;
			if (Math.abs(value.c.x-c.x) < cThres){
				x = value.c.x-c.x;
				gx = value.c.x;
			}/* else if (Math.abs(value.ul.x-ul.x) < ulThres){
				x = value.ul.x-ul.x;
				gx = value.ul.x;
			} else if (Math.abs(value.lr.x-lr.x) < lrThres){
				x = value.lr.x-lr.x;
				gx = value.lr.x;
			} */
			

			if (Math.abs(value.c.y-c.y) < cThres){
				y = value.c.y-c.y;
				gy = value.c.y;
			}/* else if (Math.abs(value.ul.y-ul.y) < ulThres){
				y = value.ul.y-ul.y;
				gy = value.ul.y;
			} else if (Math.abs(value.lr.y-lr.y) < lrThres){
				y = value.lr.y-lr.y;
				gy = value.lr.y;
			} */

			if (x !== undefined) {
				offsetX = offsetX === undefined ? x : (Math.abs(x) < Math.abs(offsetX) ? x : offsetX);
				if (offsetX === x)
					gridX = gx;
			}

			if (y !== undefined) {
				offsetY = offsetY === undefined ? y : (Math.abs(y) < Math.abs(offsetY) ? y : offsetY);
				if (offsetY === y)
					gridY = gy;
			}
		});
		
		
		if (offsetX !== undefined) {
			ul.x += offsetX;	
			ul.x *= scale;
			if (this.vLine&&gridX)
				this.vLine.update(gridX);
		} else {
			ul.x = (position.x - (position.x % (ORYX.CONFIG.GRID_DISTANCE/2)));
			if (this.vLine)
				this.vLine.hide()
		}
		
		if (offsetY !== undefined) {	
			ul.y += offsetY;
			ul.y *= scale;
			if (this.hLine&&gridY)
				this.hLine.update(gridY);
		} else {
			ul.y = (position.y - (position.y % (ORYX.CONFIG.GRID_DISTANCE/2)));
			if (this.hLine)
				this.hLine.hide();
		}
		
		return ul;
	},
	
	showGridLine: function(){
		
	},


	/**
	 * Redraw of the Rectangle of the SelectedArea
	 * @param {Object} bounds
	 */
	resizeRectangle: function(bounds) {
		// Resize the Rectangle
		this.selectedRect.resize(bounds);
	}

});


ORYX.Plugins.SelectedRect = Clazz.extend({

	construct: function(parentId) {

		this.parentId = parentId;

		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", $(parentId),
					['g']);

		this.dashedArea = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node,
			['rect', {x: 0, y: 0,
				'stroke-width': 1, stroke: '#777777', fill: 'none',
				'stroke-dasharray': '2,2',
				'pointer-events': 'none'}]);

		this.hide();

	},

	hide: function() {
		this.node.setAttributeNS(null, 'display', 'none');
	},

	show: function() {
		this.node.setAttributeNS(null, 'display', '');
	},

	resize: function(bounds) {
		var upL = bounds.upperLeft();

		var padding = ORYX.CONFIG.SELECTED_AREA_PADDING;

		this.dashedArea.setAttributeNS(null, 'width', bounds.width() + 2*padding);
		this.dashedArea.setAttributeNS(null, 'height', bounds.height() + 2*padding);
		this.node.setAttributeNS(null, 'transform', "translate("+ (upL.x - padding) +", "+ (upL.y - padding) +")");
	}


});



ORYX.Plugins.GridLine = Clazz.extend({
	
	construct: function(parentId, direction) {

		if (ORYX.Plugins.GridLine.DIR_HORIZONTAL !== direction && ORYX.Plugins.GridLine.DIR_VERTICAL !== direction) {
			direction = ORYX.Plugins.GridLine.DIR_HORIZONTAL;
		}
		
	
		this.parent = $(parentId);
		this.direction = direction;
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.parent,
					['g']);

		this.line = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node,
			['path', {
				'stroke-width': 1, stroke: 'silver', fill: 'none',
				'stroke-dasharray': '5,5',
				'pointer-events': 'none'}]);

		this.hide();

	},

	hide: function() {
		this.node.setAttributeNS(null, 'display', 'none');
	},

	show: function() {
		this.node.setAttributeNS(null, 'display', '');
	},

	getScale: function(){
		try {
			return this.parent.parentNode.transform.baseVal.getItem(0).matrix.a;
		} catch(e) {
			return 1;
		}
	},
	
	update: function(pos) {
		
		if (this.direction === ORYX.Plugins.GridLine.DIR_HORIZONTAL) {
			var y = pos instanceof Object ? pos.y : pos; 
			var cWidth = this.parent.parentNode.parentNode.width.baseVal.value/this.getScale();
			this.line.setAttributeNS(null, 'd', 'M 0 '+y+ ' L '+cWidth+' '+y);
		} else {
			var x = pos instanceof Object ? pos.x : pos; 
			var cHeight = this.parent.parentNode.parentNode.height.baseVal.value/this.getScale();
			this.line.setAttributeNS(null, 'd', 'M'+x+ ' 0 L '+x+' '+cHeight);
		}
		
		this.show();
	}


});

ORYX.Plugins.GridLine.DIR_HORIZONTAL = "hor";
ORYX.Plugins.GridLine.DIR_VERTICAL = "ver";

ORYX.Plugins.Resizer = Clazz.extend({

	construct: function(parentId, orientation, facade) {

		this.parentId 		= parentId;
		this.orientation	= orientation;
		this.facade			= facade;
		this.node = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", $(this.parentId),
			['div', {'class': 'resizer_'+ this.orientation, style:'left:0px; top:0px;'}]);

		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this), true);
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, 	this.handleMouseUp.bind(this), 		true);
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, 	this.handleMouseMove.bind(this), 	false);

		this.dragEnable = false;
		this.offSetPosition = {x: 0, y: 0};
		this.bounds = undefined;

		this.canvasNode = this.facade.getCanvas().node;

		this.minSize = undefined;
		this.maxSize = undefined;
		
		this.aspectRatio = undefined;

		this.resizeCallbacks 		= [];
		this.resizeStartCallbacks 	= [];
		this.resizeEndCallbacks 	= [];
		this.hide();
		
		// Calculate the Offset
		this.scrollNode = this.node.parentNode.parentNode.parentNode;


	},

	handleMouseDown: function(event) {
		this.dragEnable = true;

		this.offsetScroll	= {x:this.scrollNode.scrollLeft,y:this.scrollNode.scrollTop};
			
		this.offSetPosition =  {
			x: Event.pointerX(event) - this.position.x,
			y: Event.pointerY(event) - this.position.y};
		
		this.resizeStartCallbacks.each((function(value) {
			value(this.bounds);
		}).bind(this));

	},

	handleMouseUp: function(event) {
		this.dragEnable = false;
		this.containmentParentNode = null;
		this.resizeEndCallbacks.each((function(value) {
			value(this.bounds);
		}).bind(this));
				
	},

	handleMouseMove: function(event) {
		if(!this.dragEnable) { return; }
		
		if(event.shiftKey || event.ctrlKey) {
			this.aspectRatio = this.bounds.width() / this.bounds.height();
		} else {
			this.aspectRatio = undefined;
		}

		var position = {
			x: Event.pointerX(event) - this.offSetPosition.x,
			y: Event.pointerY(event) - this.offSetPosition.y}


		position.x 	-= this.offsetScroll.x - this.scrollNode.scrollLeft; 
		position.y 	-= this.offsetScroll.y - this.scrollNode.scrollTop;
		
		position.x  = Math.min( position.x, this.facade.getCanvas().bounds.width())
		position.y  = Math.min( position.y, this.facade.getCanvas().bounds.height())
		
		var offset = {
			x: position.x - this.position.x,
			y: position.y - this.position.y
		}
		
		if(this.aspectRatio) {
			// fixed aspect ratio
			newAspectRatio = (this.bounds.width()+offset.x) / (this.bounds.height()+offset.y);
			if(newAspectRatio>this.aspectRatio) {
				offset.x = this.aspectRatio * (this.bounds.height()+offset.y) - this.bounds.width();
			} else if(newAspectRatio<this.aspectRatio) {
				offset.y = (this.bounds.width()+offset.x) / this.aspectRatio - this.bounds.height();
			}
		}
		
		// respect minimum and maximum sizes of stencil
		if(this.orientation==="northwest") {
			if(this.bounds.width()-offset.x > this.maxSize.width) {
				offset.x = -(this.maxSize.width - this.bounds.width());
				if(this.aspectRatio)
					offset.y = this.aspectRatio * offset.x;
			}
			if(this.bounds.width()-offset.x < this.minSize.width) {
				offset.x = -(this.minSize.width - this.bounds.width());
				if(this.aspectRatio)
					offset.y = this.aspectRatio * offset.x;
			}
			if(this.bounds.height()-offset.y > this.maxSize.height) {
				offset.y = -(this.maxSize.height - this.bounds.height());
				if(this.aspectRatio)
					offset.x = offset.y / this.aspectRatio;
			}
			if(this.bounds.height()-offset.y < this.minSize.height) {
				offset.y = -(this.minSize.height - this.bounds.height());
				if(this.aspectRatio)
					offset.x = offset.y / this.aspectRatio;
			}
		} else { // defaults to southeast
			if(this.bounds.width()+offset.x > this.maxSize.width) {
				offset.x = this.maxSize.width - this.bounds.width();
				if(this.aspectRatio)
					offset.y = this.aspectRatio * offset.x;
			}
			if(this.bounds.width()+offset.x < this.minSize.width) {
				offset.x = this.minSize.width - this.bounds.width();
				if(this.aspectRatio)
					offset.y = this.aspectRatio * offset.x;
			}
			if(this.bounds.height()+offset.y > this.maxSize.height) {
				offset.y = this.maxSize.height - this.bounds.height();
				if(this.aspectRatio)
					offset.x = offset.y / this.aspectRatio;
			}
			if(this.bounds.height()+offset.y < this.minSize.height) {
				offset.y = this.minSize.height - this.bounds.height();
				if(this.aspectRatio)
					offset.x = offset.y / this.aspectRatio;
			}
		}

		if(this.orientation==="northwest") {
			var oldLR = {x: this.bounds.lowerRight().x, y: this.bounds.lowerRight().y};
			this.bounds.extend({x:-offset.x, y:-offset.y});
			this.bounds.moveBy(offset);
		} else { // defaults to southeast
			this.bounds.extend(offset);
		}

		this.update();

		this.resizeCallbacks.each((function(value) {
			value(this.bounds);
		}).bind(this));

		Event.stop(event);

	},
	
	registerOnResizeStart: function(callback) {
		if(!this.resizeStartCallbacks.member(callback)) {
			this.resizeStartCallbacks.push(callback);
		}
	},
	
	unregisterOnResizeStart: function(callback) {
		if(this.resizeStartCallbacks.member(callback)) {
			this.resizeStartCallbacks = this.resizeStartCallbacks.without(callback);
		}
	},

	registerOnResizeEnd: function(callback) {
		if(!this.resizeEndCallbacks.member(callback)) {
			this.resizeEndCallbacks.push(callback);
		}
	},
	
	unregisterOnResizeEnd: function(callback) {
		if(this.resizeEndCallbacks.member(callback)) {
			this.resizeEndCallbacks = this.resizeEndCallbacks.without(callback);
		}
	},
		
	registerOnResize: function(callback) {
		if(!this.resizeCallbacks.member(callback)) {
			this.resizeCallbacks.push(callback);
		}
	},

	unregisterOnResize: function(callback) {
		if(this.resizeCallbacks.member(callback)) {
			this.resizeCallbacks = this.resizeCallbacks.without(callback);
		}
	},

	hide: function() {
		this.node.style.display = "none";
	},

	show: function() {
		if(this.bounds)
			this.node.style.display = "";
	},

	setBounds: function(bounds, min, max, aspectRatio) {
		this.bounds = bounds;

		if(!min)
			min = {width: ORYX.CONFIG.MINIMUM_SIZE, height: ORYX.CONFIG.MINIMUM_SIZE};

		if(!max)
			max = {width: ORYX.CONFIG.MAXIMUM_SIZE, height: ORYX.CONFIG.MAXIMUM_SIZE};

		this.minSize = min;
		this.maxSize = max;
		
		this.aspectRatio = aspectRatio;

		this.update();
	},

	update: function() {
		if(!this.bounds) { return; }

		var upL = this.bounds.upperLeft();

		if(this.bounds.width() < this.minSize.width)	{ this.bounds.set(upL.x, upL.y, upL.x + this.minSize.width, upL.y + this.bounds.height());};
		if(this.bounds.height() < this.minSize.height)	{ this.bounds.set(upL.x, upL.y, upL.x + this.bounds.width(), upL.y + this.minSize.height);};
		if(this.bounds.width() > this.maxSize.width)	{ this.bounds.set(upL.x, upL.y, upL.x + this.maxSize.width, upL.y + this.bounds.height());};
		if(this.bounds.height() > this.maxSize.height)	{ this.bounds.set(upL.x, upL.y, upL.x + this.bounds.width(), upL.y + this.maxSize.height);};

		var a = this.canvasNode.getScreenCTM();
		
		upL.x *= a.a;
		upL.y *= a.d;
		
		if(this.orientation==="northwest") {
			upL.x -= 13;
			upL.y -= 26;
		} else { // defaults to southeast
			upL.x +=  (a.a * this.bounds.width()) + 3 ;
			upL.y +=  (a.d * this.bounds.height())  + 3;
		}
		
		this.position = upL;

		this.node.style.left = this.position.x + "px";
		this.node.style.top = this.position.y + "px";
	}
});



/**
 * Implements a Command to move shapes
 * 
 */ 
ORYX.Core.Command.Move = ORYX.Core.Command.extend({
	construct: function(moveShapes, offset, parent, selectedShapes, plugin){
		this.moveShapes = moveShapes;
		this.selectedShapes = selectedShapes;
		this.offset 	= offset;
		this.plugin		= plugin;
		// Defines the old/new parents for the particular shape
		this.newParents	= moveShapes.collect(function(t){ return parent || t.parent });
		this.oldParents	= moveShapes.collect(function(shape){ return shape.parent });
		this.dockedNodes= moveShapes.findAll(function(shape){ return shape instanceof ORYX.Core.Node && shape.dockers.length == 1}).collect(function(shape){ return {docker:shape.dockers[0], dockedShape:shape.dockers[0].getDockedShape(), refPoint:shape.dockers[0].referencePoint} });
	},			
	execute: function(){
		this.dockAllShapes()				
		// Moves by the offset
		this.move( this.offset);
		// Addes to the new parents
		this.addShapeToParent( this.newParents ); 
		// Set the selection to the current selection
		this.selectCurrentShapes();
		this.plugin.facade.getCanvas().update();
		this.plugin.facade.updateSelection();
	},
	rollback: function(){
		// Moves by the inverted offset
		var offset = { x:-this.offset.x, y:-this.offset.y };
		this.move( offset );
		// Addes to the old parents
		this.addShapeToParent( this.oldParents ); 
		this.dockAllShapes(true)	
		
		// Set the selection to the current selection
		this.selectCurrentShapes();
		this.plugin.facade.getCanvas().update();
		this.plugin.facade.updateSelection();
		
	},
	move:function(offset, doLayout){
		
		// Move all Shapes by these offset
		for(var i=0; i<this.moveShapes.length ;i++){
			var value = this.moveShapes[i];					
			value.bounds.moveBy(offset);
			
			if (value instanceof ORYX.Core.Node) {
				
				(value.dockers||[]).each(function(d){
					d.bounds.moveBy(offset);
				})
				
				// Update all Dockers of Child shapes
				/*var childShapesNodes = value.getChildShapes(true).findAll(function(shape){ return shape instanceof ORYX.Core.Node });							
				var childDockedShapes = childShapesNodes.collect(function(shape){ return shape.getAllDockedShapes() }).flatten().uniq();							
				var childDockedEdge = childDockedShapes.findAll(function(shape){ return shape instanceof ORYX.Core.Edge });							
				childDockedEdge = childDockedEdge.findAll(function(shape){ return shape.getAllDockedShapes().all(function(dsh){ return childShapesNodes.include(dsh) }) });							
				var childDockedDockers = childDockedEdge.collect(function(shape){ return shape.dockers }).flatten();
				
				for (var j = 0; j < childDockedDockers.length; j++) {
					var docker = childDockedDockers[j];
					if (!docker.getDockedShape() && !this.moveShapes.include(docker)) {
						//docker.bounds.moveBy(offset);
						//docker.update();
					}
				}*/
				
				
				var allEdges = [].concat(value.getIncomingShapes())
					.concat(value.getOutgoingShapes())
					// Remove all edges which are included in the selection from the list
					.findAll(function(r){ return	r instanceof ORYX.Core.Edge && !this.moveShapes.any(function(d){ return d == r || (d instanceof ORYX.Core.Controls.Docker && d.parent == r)}) }.bind(this))
					// Remove all edges which are between the node and a node contained in the selection from the list
					.findAll(function(r){ return 	(r.dockers.first().getDockedShape() == value || !this.moveShapes.include(r.dockers.first().getDockedShape())) &&  
													(r.dockers.last().getDockedShape() == value || !this.moveShapes.include(r.dockers.last().getDockedShape()))}.bind(this))
													
				// Layout all outgoing/incoming edges
				this.plugin.layoutEdges(value, allEdges, offset);
				
				
				var allSameEdges = [].concat(value.getIncomingShapes())
					.concat(value.getOutgoingShapes())
					// Remove all edges which are included in the selection from the list
					.findAll(function(r){ return r instanceof ORYX.Core.Edge && r.dockers.first().isDocked() && r.dockers.last().isDocked() && !this.moveShapes.include(r) && !this.moveShapes.any(function(d){ return d == r || (d instanceof ORYX.Core.Controls.Docker && d.parent == r)}) }.bind(this))
					// Remove all edges which are included in the selection from the list
					.findAll(function(r){ return this.moveShapes.indexOf(r.dockers.first().getDockedShape()) > i ||  this.moveShapes.indexOf(r.dockers.last().getDockedShape()) > i}.bind(this))

				for (var j = 0; j < allSameEdges.length; j++) {
					for (var k = 1; k < allSameEdges[j].dockers.length-1; k++) {
						var docker = allSameEdges[j].dockers[k];
						if (!docker.getDockedShape() && !this.moveShapes.include(docker)) {
							docker.bounds.moveBy(offset);
						}
					}
				}	
				
				/*var i=-1;
				var nodes = value.getChildShapes(true);
				var allEdges = [];
				while(++i<nodes.length){
					var edges = [].concat(nodes[i].getIncomingShapes())
						.concat(nodes[i].getOutgoingShapes())
						// Remove all edges which are included in the selection from the list
						.findAll(function(r){ return r instanceof ORYX.Core.Edge && !allEdges.include(r) && r.dockers.any(function(d){ return !value.bounds.isIncluded(d.bounds.center)})})
					allEdges = allEdges.concat(edges);
					if (edges.length <= 0){ continue }
					//this.plugin.layoutEdges(nodes[i], edges, offset);
				}*/
			}
		}
										
	},
	dockAllShapes: function(shouldDocked){
		// Undock all Nodes
		for (var i = 0; i < this.dockedNodes.length; i++) {
			var docker = this.dockedNodes[i].docker;
			
			docker.setDockedShape( shouldDocked ? this.dockedNodes[i].dockedShape : undefined )
			if (docker.getDockedShape()) {
				docker.setReferencePoint(this.dockedNodes[i].refPoint);
				//docker.update();
			}
		}
	},
	
	addShapeToParent:function( parents ){
		
		// For every Shape, add this and reset the position		
		for(var i=0; i<this.moveShapes.length ;i++){
			var currentShape = this.moveShapes[i];
			if(currentShape instanceof ORYX.Core.Node &&
			   currentShape.parent !== parents[i]) {
				
				// Calc the new position
				var unul = parents[i].absoluteXY();
				var csul = currentShape.absoluteXY();
				var x = csul.x - unul.x;
				var y = csul.y - unul.y;

				// Add the shape to the new contained shape
				parents[i].add(currentShape);
				// Add all attached shapes as well
				currentShape.getOutgoingShapes((function(shape) {
					if(shape instanceof ORYX.Core.Node && !this.moveShapes.member(shape)) {
						parents[i].add(shape);
					}
				}).bind(this));

				// Set the new position
				if(currentShape instanceof ORYX.Core.Node && currentShape.dockers.length == 1){
					var b = currentShape.bounds;
					x += b.width()/2;y += b.height()/2
					currentShape.dockers.first().bounds.centerMoveTo(x, y);
				} else {
					currentShape.bounds.moveTo(x, y);
				}
				
			} 
			
			// Update the shape
			//currentShape.update();
			
		}
	},
	selectCurrentShapes:function(){
		this.plugin.facade.setSelection( this.selectedShapes );
	}
});

/**
 * Copyright (c) 2008
 * Willi Tscheschner
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 **/
if (!ORYX.Plugins) 
    ORYX.Plugins = new Object();


ORYX.Plugins.RenameShapes = Clazz.extend({

    facade: undefined,
    
    construct: function(facade){
    
        this.facade = facade;

		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DBLCLICK, this.actOnDBLClick.bind(this));
        this.facade.offer({
		 keyCodes: [{
				keyCode: 113, // F2-Key
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.renamePerF2.bind(this)
         });
		
		
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEDOWN, this.hide.bind(this), true );
		
		// Added in 2011 by Matthias Kunze and Tobias Pfeiffer
		// Register on the event for the template plugins (see the method registerTemplate for more information)
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_REGISTER_LABEL_TEMPLATE, this.registerTemplate.bind(this));
		// raise the event once so we have the initialized property this.label_templates
		this.facade.raiseEvent({
			type: ORYX.CONFIG.EVENT_REGISTER_LABEL_TEMPLATE,
			empty: true // enforces basic template (unity)
		});		
    },
    
    /**
     * Handle the registration of a plugin for templatization.
     * This is part of a change made by Matthias Kunze and Tobias Pfeiffer in 2011 
     * The options are 2 functions (edit_template and render_template) that handle the templatization.
     * 
     * edit_template is called with the oldValue of a property to be edited and changes it's appearance a bit
     * so the user sees something slightly different in his editwindow, for details refer to the UMLState plugin.
     * 
     * render_template is called with the result of the editing by the user, the template connected things are removed
     * from it so it gets saved in its pure form. Again for more info please refer to the UMLState plugin.
     * 
     * multiple templating methods are saved and are executed one after another, as one may want to use many of them.
     * 
     * @param options, the options of the template function. It should be the edit_template and the render_template functions.
     */
    registerTemplate: function(options) {

        // initialization
        this.label_templates = this.label_templates || [];
     
        // push the new template onto our list so it gets executed in the next renaming process
        this.label_templates.push({
            edit: "function" == typeof(options.edit_template) ? options.edit_template : function(a){return a;},
            render: "function" == typeof(options.render_template) ? options.render_template : function(a){return a;}
        });
     },

    
	/**
	 * This method handles the "F2" key down event. The selected shape are looked
	 * up and the editing of title/name of it gets started.
	 */
	renamePerF2 : function renamePerF2() {
		var selectedShapes = this.facade.getSelection();
		this.actOnDBLClick(undefined, selectedShapes.first());
	},
	
	getEditableProperties: function getEditableProperties(shape) {
	    // Get all properties which where at least one ref to view is set
		var props = shape.getStencil().properties().findAll(function(item){ 
			return (item.refToView() 
					&&  item.refToView().length > 0
					&&	item.directlyEditable()); 
		});
		
		// from these, get all properties where write access are and the type is String
	    return props.findAll(function(item){ return !item.readonly() &&  item.type() == ORYX.CONFIG.TYPE_STRING });
	},
	
	getPropertyForLabel: function getPropertyForLabel(properties, shape, label) {
	    return properties.find(function(item){ return item.refToView().any(function(toView){ return label.id == shape.id + toView })});
	},
	
	actOnDBLClick: function actOnDBLClick(evt, shape){
		if( !(shape instanceof ORYX.Core.Shape) ){ return }
		
		// Destroys the old input, if there is one
		this.destroy();

		var props = this.getEditableProperties(shape);
		
		// Get all ref ids
		var allRefToViews	= props.collect(function(prop){ return prop.refToView() }).flatten().compact();
		// Get all labels from the shape with the ref ids
		var labels			= shape.getLabels().findAll(function(label){ return allRefToViews.any(function(toView){ return label.id.endsWith(toView) }); })
		
		// If there are no referenced labels --> return
		if( labels.length == 0 ){ return } 
		
		// Define the nearest label
		var nearestLabel 	= labels.length == 1 ? labels[0] : null;	
		if( !nearestLabel ){
		    nearestLabel = labels.find(function(label){ return label.node == evt.target || label.node == evt.target.parentNode })
	        if( !nearestLabel ){
		        var evtCoord 	= this.facade.eventCoordinates(evt);

		        var trans		= this.facade.getCanvas().rootNode.lastChild.getScreenCTM();
		        evtCoord.x		*= trans.a;
		        evtCoord.y		*= trans.d;
			    if (!shape instanceof ORYX.Core.Node) {

			        var diff = labels.collect(function(label){

						        var center 	= this.getCenterPosition( label.node ); 
						        var len 	= Math.sqrt( Math.pow(center.x - evtCoord.x, 2) + Math.pow(center.y - evtCoord.y, 2));
						        return {diff: len, label: label} 
					        }.bind(this));
			
			        diff.sort(function(a, b){ return a.diff > b.diff })	
			
			        nearestLabel = 	diff[0].label;
                } else {

			        var diff = labels.collect(function(label){

						        var center 	= this.getDifferenceCenterForNode( label.node ); 
						        var len 	= Math.sqrt( Math.pow(center.x - evtCoord.x, 2) + Math.pow(center.y - evtCoord.y, 2));
						        return {diff: len, label: label} 
					        }.bind(this));
			
			        diff.sort(function(a, b){ return a.diff > b.diff })	
			
			        nearestLabel = 	diff[0].label;
                }
            }
		}

		// Get the particular property for the label
		var prop = this.getPropertyForLabel(props, shape, nearestLabel);

        this.showTextField(shape, prop, nearestLabel);
	},
	
	showTextField: function showTextField(shape, prop, label) {
		// Set all particular config values
		var htmlCont 	= this.facade.getCanvas().getHTMLContainer().id;
	    
	    // Get the center position from the nearest label
		var width;
		if(!(shape instanceof ORYX.Core.Node)) {
		    var bounds = label.node.getBoundingClientRect();
			width = Math.max(150, bounds.width);
		} else {
			width = shape.bounds.width();
		}
		if (!shape instanceof ORYX.Core.Node) {
		    var center 		= this.getCenterPosition( label.node );
		    center.x		-= (width/2);
        } else {
            var center = shape.absoluteBounds().center();
		    center.x		-= (width/2);
        }
		var propId		= prop.prefix() + "-" + prop.id();

		// Set the config values for the TextField/Area
		var config 		= 	{
								renderTo	: htmlCont,
								// Part of the change by Matthias Kunze and Tobias Pfeiffer in order for templates to work
								// give the value to each registered templating function and let the function modify it, then return it
								value		: (function(value, propId, shape){
									this.label_templates.forEach(function(tpl){
										// Make sure bad templating functions don't break everything
										try {
											value = tpl.edit(value, propId, shape);
										} catch(err) {
											ORYX.Log.error("Unable to render label template", err, tpl.edit);
										}
									});
									return value;
								}.bind(this))(shape.properties[propId], propId, shape),
								x			: (center.x < 10) ? 10 : center.x,
								y			: center.y,
								width		: Math.max(100, width),
								style		: 'position:absolute', 
								allowBlank	: prop.optional(), 
								maxLength	: prop.length(),
								emptyText	: prop.title(),
								cls			: 'x_form_text_set_absolute',
                                listeners   : {specialkey: this._specialKeyPressed.bind(this)}
							};
		
		// Depending on the property, generate 
		// either an TextArea or TextField
		if(prop.wrapLines()) {
			config.y 		-= 30;
			config['grow']	= true;
			this.shownTextField = new Ext.form.TextArea(config);
		} else {
			config.y -= 16;
			
			this.shownTextField = new Ext.form.TextField(config);
		}
		
		//focus
		this.shownTextField.focus();
		
		// Define event handler
		//	Blur 	-> Destroy
		//	Change 	-> Set new values					
		this.shownTextField.on( 'blur', 	this.destroy.bind(this) )
		this.shownTextField.on( 'change', 	function(node, value){
			var currentEl 	= shape;
			var oldValue	= currentEl.properties[propId]; 
			// Part of the change by Matthias Kunze and Tobias Pfeiffer in order for templates to work
			// give the value to each registered templating function and modify it, then return it
			var newValue	= (function(value, propId, shape){
				this.label_templates.forEach(function(tpl){
					// Make sure bad templating functions don't break everything
					try {
						value = tpl.render(value, propId, shape);
					} catch(err) {
						ORYX.Log.error("Unable to render label template", err, tpl.render);
					}
				})
				return value;
			}.bind(this))(value, propId, shape);
			var facade		= this.facade;
			
			if (oldValue != newValue) {
				// Implement the specific command for property change
				var commandClass = ORYX.Core.Command.extend({
					construct: function(){
						this.el = currentEl;
						this.propId = propId;
						this.oldValue = oldValue;
						this.newValue = newValue;
						this.facade = facade;
					},
					execute: function(){
						this.el.setProperty(this.propId, this.newValue);
						//this.el.update();
						this.facade.setSelection([this.el]);
						this.facade.getCanvas().update();
						this.facade.updateSelection();
					},
					rollback: function(){
						this.el.setProperty(this.propId, this.oldValue);
						//this.el.update();
						this.facade.setSelection([this.el]);
						this.facade.getCanvas().update();
						this.facade.updateSelection();
					}
				})
				// Instanciated the class
				var command = new commandClass();
				
				// Execute the command
				this.facade.executeCommands([command]);
			}
		}.bind(this) )

		// Diable the keydown in the editor (that when hitting the delete button, the shapes not get deleted)
		this.facade.disableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
	},
    
    _specialKeyPressed: function _specialKeyPressed(field, e) {
        // Enter or Ctrl+Enter pressed
        var keyCode = e.getKey();
        if (keyCode == 13  && (e.shiftKey || !field.initialConfig.grow)) {
            field.fireEvent("change", null, field.getValue());
            field.fireEvent("blur");
        } else if (keyCode == e.ESC) {
            field.fireEvent("blur");
        }
    },
	
	getCenterPosition: function(svgNode){
		
		var center 		= {x: 0, y:0 };
		// transformation to the coordinate origin of the canvas
		var trans 		= svgNode.getTransformToElement(this.facade.getCanvas().rootNode.lastChild);
		var scale 		= this.facade.getCanvas().rootNode.lastChild.getScreenCTM();
		var transLocal 	= svgNode.getTransformToElement(svgNode.parentNode);
		var bounds = undefined;
		
		center.x 	= trans.e - transLocal.e;
		center.y 	= trans.f - transLocal.f;
		
		
		try {
			bounds = svgNode.getBBox();
		} catch (e) {}

		// Firefox often fails to calculate the correct bounding box
		// in this case we fall back to the upper left corner of the shape
		if (bounds === null || typeof bounds === "undefined" || bounds.width == 0 || bounds.height == 0) {
			bounds = {
				x: Number(svgNode.getAttribute('x')),
				y: Number(svgNode.getAttribute('y')),
				width: 0,
				height: 0
			};
		}
		
		center.x += bounds.x;
		center.y += bounds.y;
		
		center.x += bounds.width/2;
		center.y += bounds.height/2;
		
		center.x *= scale.a;
		center.y *= scale.d;		
		return center;
		
	},

	getDifferenceCenterForNode: function getDifferenceCenterForNode(svgNode){
        //for shapes that do not have multiple lables on the x-line, only the vertical difference matters
        var center  = this.getCenterPosition(svgNode);
        center.x = 0;
        center.y = center.y + 10;
        return center;
    },
	
	hide: function(e){
		if (this.shownTextField && (!e || !this.shownTextField.el || e.target !== this.shownTextField.el.dom)) {
			this.shownTextField.onBlur();
		}
	},
	
	destroy: function(e){
		if( this.shownTextField ){
			this.shownTextField.destroy(); 
			delete this.shownTextField; 
			
			this.facade.enableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
		}
	}
});

   
/**
 * Copyright (c) 2008
 * Willi Tscheschner
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 **/


/**
 * This plugin offer the functionality of undo/redo
 * Therewith the command pattern is used.
 * 
 * A Plugin which want that the changes could get undo/redo has 
 * to implement a command-class (which implements the method .execute(), .rollback()).
 * Those instance of class must be execute thru the facade.executeCommands(). If so,
 * those command get stored here in the undo/redo stack and can get reset/restore.
 *
 **/

if (!ORYX.Plugins) 
    ORYX.Plugins = new Object();

ORYX.Plugins.Undo = Clazz.extend({
	
	// Defines the facade
        facade		: undefined,
    
	// Defines the undo/redo Stack
	undoStack	: [],
	redoStack	: [],
   

	
	/**
	 * Stores all executed commands in a stack
	 * 
	 * @param {Object} evt
	 */
	handleExecuteCommands: function( evt ){
		
		// If the event has commands
		if( !evt.commands ){ return }
		
		// Add the commands to a undo stack ...
		this.undoStack.push( evt.commands );
		// ...and delete the redo stack
		this.redoStack = [];
		
	},
	
	/**
	 * Does the undo
	 * 
	 */
	doUndo: function(){
		// Get the last commands
		var lastCommands = this.undoStack.pop();
		
		if( lastCommands ){
			// Add the commands to the redo stack
			this.redoStack.push( lastCommands );
			
			// Rollback every command
			lastCommands.each(function(command){
				command.rollback();
			});
		}
		
		// Update and refresh the canvas
		//this.facade.getCanvas().update();
		//this.facade.updateSelection();
		this.facade.raiseEvent({
			type 	: ORYX.CONFIG.EVENT_UNDO_ROLLBACK, 
			commands: lastCommands
		});
	},
	
	/**
	 * Does the redo
	 * 
	 */
	doRedo: function(){
		
		// Get the last commands from the redo stack
		var lastCommands = this.redoStack.pop();
		
		if( lastCommands ){
			// Add this commands to the undo stack
			this.undoStack.push( lastCommands );
			
			// Execute those commands
			lastCommands.each(function(command){
				command.execute();
			});
		}

		// Update and refresh the canvas		
		//this.facade.getCanvas().update();
		//this.facade.updateSelection();
		this.facade.raiseEvent({
			type 	: ORYX.CONFIG.EVENT_UNDO_EXECUTE, 
			commands: lastCommands
		});
	},
        	// Constructor 
    construct: function(facade){
    
        this.facade = facade;
        
        var plugin = this;
        
        //posibilidad de disparar this.undoStack.length > 0
        $('editor_undo').observe('click', function(event) {
              plugin.doUndo();
        });
        
        //posibilidad de disparar this.redoStack.length > 0
        $('editor_redo').observe('click', function(event) {
              plugin.doRedo();
        });
        
        // Register on event for executing commands --> store all commands in a stack		 
	this.facade.registerOnEvent(ORYX.CONFIG.EVENT_EXECUTE_COMMANDS, this.handleExecuteCommands.bind(this) );

	
        // Offers the functionality of undo                
        this.facade.offer({
			name			: ORYX.I18N.Undo.undo,
			description		: ORYX.I18N.Undo.undoDesc,
			icon			: ORYX.PATH + "images/arrow_undo.png",
			keyCodes: [{
					metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
					keyCode: 90,
					keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
				}
		 	],
			functionality	: this.doUndo.bind(this),
			group			: ORYX.I18N.Undo.group,
			isEnabled		: function(){ return (this.undoStack.length > 0); }.bind(this),
			index			: 0
		}); 

		// Offers the functionality of redo
        this.facade.offer({
			name			: ORYX.I18N.Undo.redo,
			description		: ORYX.I18N.Undo.redoDesc,
			icon			: ORYX.PATH + "images/arrow_redo.png",
			keyCodes: [{
					metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
					keyCode: 89,
					keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
				}
		 	],
			functionality	: this.doRedo.bind(this),
			group			: ORYX.I18N.Undo.group,
			isEnabled		: function(){ return (this.redoStack.length > 0); }.bind(this),
			index			: 1
		}); 
		
		
    	
	}
	
});


/**
 * Copyright (c) 2006
 * Martin Czuchra, Nicolas Peters, Daniel Polak, Willi Tscheschner
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 **/

Array.prototype.insertFrom = function(from, to){
	to 			= Math.max(0, to);
	from 		= Math.min( Math.max(0, from), this.length-1 );
		
	var el 		= this[from];
	var old 	= this.without(el);
	var newA 	= old.slice(0, to);
	newA.push(el);
	if(old.length > to ){
		newA 	= newA.concat(old.slice(to));
	};
	return newA;
};

if(!ORYX.Plugins)
	ORYX.Plugins = new Object();

ORYX.Plugins.Arrangement = Clazz.extend({

	facade: undefined,

	construct: function(facade) {
		this.facade = facade;
                
                var plugin = this;
		// Z-Ordering
                
                $('move_front').observe('click', function(event) {
                    plugin.setZLevel( plugin.setToTop, false);
                });
                
                $('move_back').observe('click', function(event) {
                    plugin.setZLevel(plugin.setToBack, false);
                });
                
                $('move_forwards').observe('click', function(event) {
                    plugin.setZLevel(plugin.setForward, false);
                });
                
                
                $('move_backwards').observe('click', function(event) {
                    plugin.setZLevel(plugin.setBackward, false);
                });
                
                $('aling_bottom').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_BOTTOM]);
                });
                
                $('aling_middle').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_MIDDLE]);
                });
                
                $('aling_top').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_TOP]);
                });
                
                $('aling_left').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_LEFT]);
                });
                
                $('aling_center').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_CENTER]);
                });
                
                $('aling_right').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_RIGHT]);
                });
                
                $('aling_size').observe('click', function(event) {
                    plugin.alignShapes([ORYX.CONFIG.EDITOR_ALIGN_SIZE]);
                });
                
                this.facade.registerOnEvent(ORYX.CONFIG.EVENT_ARRANGEMENT_TOP,          this.setZLevel.bind(this, this.setToTop)	);
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_ARRANGEMENT_BACK, 	this.setZLevel.bind(this, this.setToBack)	);
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_ARRANGEMENT_FORWARD, 	this.setZLevel.bind(this, this.setForward)	);
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_ARRANGEMENT_BACKWARD, 	this.setZLevel.bind(this, this.setBackward)	);	
                
                // Z-Ordering
                /*go
		this.facade.offer({
			'name':ORYX.I18N.Arrangement.btf,
			'functionality': this.setZLevel.bind(this, this.setToTop),
			'group': ORYX.I18N.Arrangement.groupZ,
			'icon': ORYX.PATH + "images/shape_move_front.png",
			'description': ORYX.I18N.Arrangement.btfDesc,
			'index': 1,
			'minShape': 1});
			
		this.facade.offer({
			'name':ORYX.I18N.Arrangement.btb,
			'functionality': this.setZLevel.bind(this, this.setToBack),
			'group': ORYX.I18N.Arrangement.groupZ,
			'icon': ORYX.PATH + "images/shape_move_back.png",
			'description': ORYX.I18N.Arrangement.btbDesc,
			'index': 2,
			'minShape': 1});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.bf,
			'functionality': this.setZLevel.bind(this, this.setForward),
			'group': ORYX.I18N.Arrangement.groupZ,
			'icon': ORYX.PATH + "images/shape_move_forwards.png",
			'description': ORYX.I18N.Arrangement.bfDesc,
			'index': 3,
			'minShape': 1});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.bb,
			'functionality': this.setZLevel.bind(this, this.setBackward),
			'group': ORYX.I18N.Arrangement.groupZ,
			'icon': ORYX.PATH + "images/shape_move_backwards.png",
			'description': ORYX.I18N.Arrangement.bbDesc,
			'index': 4,
			'minShape': 1});

		// Aligment
		this.facade.offer({
			'name':ORYX.I18N.Arrangement.ab,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_BOTTOM]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_bottom.png",
			'description': ORYX.I18N.Arrangement.abDesc,
			'index': 1,
			'minShape': 2});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.am,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_MIDDLE]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_middle.png",
			'description': ORYX.I18N.Arrangement.amDesc,
			'index': 2,
			'minShape': 2});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.at,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_TOP]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_top.png",
			'description': ORYX.I18N.Arrangement.atDesc,
			'index': 3,
			'minShape': 2});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.al,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_LEFT]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_left.png",
			'description': ORYX.I18N.Arrangement.alDesc,
			'index': 4,
			'minShape': 2});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.ac,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_CENTER]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_center.png",
			'description': ORYX.I18N.Arrangement.acDesc,
			'index': 5,
			'minShape': 2});

		this.facade.offer({
			'name':ORYX.I18N.Arrangement.ar,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_RIGHT]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_right.png",
			'description': ORYX.I18N.Arrangement.arDesc,
			'index': 6,
			'minShape': 2});
			
		this.facade.offer({
			'name':ORYX.I18N.Arrangement.as,
			'functionality': this.alignShapes.bind(this, [ORYX.CONFIG.EDITOR_ALIGN_SIZE]),
			'group': ORYX.I18N.Arrangement.groupA,
			'icon': ORYX.PATH + "images/shape_align_size.png",
			'description': ORYX.I18N.Arrangement.asDesc,
			'index': 7,
			'minShape': 2});
			
                */
	},
	
	setZLevel:function(callback, event){
			
		//Command-Pattern for dragging one docker
		var zLevelCommand = ORYX.Core.Command.extend({
			construct: function(callback, elements, facade){
				this.callback 	= callback;
				this.elements 	= elements;
				// For redo, the previous elements get stored
				this.elAndIndex	= elements.map(function(el){ return {el:el, previous:el.parent.children[el.parent.children.indexOf(el)-1]} })
				this.facade		= facade;
			},			
			execute: function(){
				
				// Call the defined z-order callback with the elements
				this.callback( this.elements )			
				this.facade.setSelection( this.elements )
			},
			rollback: function(){
				
				// Sort all elements on the index of there containment
				var sortedEl =	this.elAndIndex.sortBy( function( el ) {
									var value 	= el.el;
									var t 		= $A(value.node.parentNode.childNodes);
									return t.indexOf(value.node);
								}); 
				
				// Every element get setted back bevor the old previous element
				for(var i=0; i<sortedEl.length; i++){
					var el			= sortedEl[i].el;
					var p 			= el.parent;			
					var oldIndex 	= p.children.indexOf(el);
					var newIndex 	= p.children.indexOf(sortedEl[i].previous);
					newIndex		= newIndex || 0
					p.children 	= p.children.insertFrom(oldIndex, newIndex)			
					el.node.parentNode.insertBefore(el.node, el.node.parentNode.childNodes[newIndex+1]);
				}

				// Reset the selection
				this.facade.setSelection( this.elements )
			}
		});
	
		// Instanziate the dockCommand
		var command = new zLevelCommand(callback, this.facade.getSelection(), this.facade);
		if( event.excludeCommand ){
			command.execute();
		} else {
			this.facade.executeCommands( [command] );	
		}
		
	},

	setToTop: function(elements) {

		// Sortieren des Arrays nach dem Index des SVGKnotens im Bezug auf dem Elternknoten.
		var tmpElem =  elements.sortBy( function(value, index) {
			var t = $A(value.node.parentNode.childNodes);
			return t.indexOf(value.node);
		});
		// Sortiertes Array wird nach oben verschoben.
		tmpElem.each( function(value) {
			var p = value.parent;

			p.children = p.children.without(value);
			p.children.push( value );
			value.node.parentNode.appendChild(value.node);			
		});
	},

	setToBack: function(elements) {
		// Sortieren des Arrays nach dem Index des SVGKnotens im Bezug auf dem Elternknoten.
		var tmpElem =  elements.sortBy( function(value, index) {
			var t = $A(value.node.parentNode.childNodes);
			return t.indexOf(value.node);
		});

		tmpElem = tmpElem.reverse();

		// Sortiertes Array wird nach unten verschoben.
		tmpElem.each( function(value) {
			var p = value.parent
			p.children = p.children.without( value )
			p.children.unshift( value );
			value.node.parentNode.insertBefore(value.node, value.node.parentNode.firstChild);
		});
		
		
	},

	setBackward: function(elements) {
		// Sortieren des Arrays nach dem Index des SVGKnotens im Bezug auf dem Elternknoten.
		var tmpElem =  elements.sortBy( function(value, index) {
			var t = $A(value.node.parentNode.childNodes);
			return t.indexOf(value.node);
		});

		// Reverse the elements
		tmpElem = tmpElem.reverse();
		
		// Delete all Nodes who are the next Node in the nodes-Array
		var compactElem = tmpElem.findAll(function(el) {return !tmpElem.some(function(checkedEl){ return checkedEl.node == el.node.previousSibling})});
		
		// Sortiertes Array wird nach eine Ebene nach oben verschoben.
		compactElem.each( function(el) {
			if(el.node.previousSibling === null) { return; }
			var p 		= el.parent;			
			var index 	= p.children.indexOf(el);
			p.children 	= p.children.insertFrom(index, index-1)			
			el.node.parentNode.insertBefore(el.node, el.node.previousSibling);
		});
		
		
	},

	setForward: function(elements) {
		// Sortieren des Arrays nach dem Index des SVGKnotens im Bezug auf dem Elternknoten.
		var tmpElem =  elements.sortBy( function(value, index) {
			var t = $A(value.node.parentNode.childNodes);
			return t.indexOf(value.node);
		});


		// Delete all Nodes who are the next Node in the nodes-Array
		var compactElem = tmpElem.findAll(function(el) {return !tmpElem.some(function(checkedEl){ return checkedEl.node == el.node.nextSibling})});
	
			
		// Sortiertes Array wird eine Ebene nach unten verschoben.
		compactElem.each( function(el) {
			var nextNode = el.node.nextSibling		
			if(nextNode === null) { return; }
			var index 	= el.parent.children.indexOf(el);
			var p 		= el.parent;
			p.children 	= p.children.insertFrom(index, index+1)			
			el.node.parentNode.insertBefore(nextNode, el.node);
		});
	},


	alignShapes: function(way) {

		var elements = this.facade.getSelection();

		// Set the elements to all Top-Level elements
		elements = this.facade.getCanvas().getShapesWithSharedParent(elements);
		// Get only nodes
		elements = elements.findAll(function(value) {
			return (value instanceof ORYX.Core.Node)
		});
		// Delete all attached intermediate events from the array
		elements = elements.findAll(function(value) {
			var d = value.getIncomingShapes()
			return d.length == 0 || !elements.include(d[0])
		});
		if(elements.length < 2) { return; }

		// get bounds of all shapes.
		var bounds = elements[0].absoluteBounds().clone();
		elements.each(function(shape) {
		        bounds.include(shape.absoluteBounds().clone());
		});
		
		// get biggest width and heigth
		var maxWidth = 0;
		var maxHeight = 0;
		elements.each(function(shape){
			maxWidth = Math.max(shape.bounds.width(), maxWidth);
			maxHeight = Math.max(shape.bounds.height(), maxHeight);
		});

		var commandClass = ORYX.Core.Command.extend({
			construct: function(elements, bounds, maxHeight, maxWidth, way, facade){
				this.elements = elements;
				this.bounds = bounds;
				this.maxHeight = maxHeight;
				this.maxWidth = maxWidth;
				this.way = way;
				this.facade = facade;
				this.orgPos = [];
			},
			setBounds: function(shape, maxSize) {
				if(!maxSize)
					maxSize = {width: ORYX.CONFIG.MAXIMUM_SIZE, height: ORYX.CONFIG.MAXIMUM_SIZE};

				if(!shape.bounds) { throw "Bounds not definined." }
				
				var newBounds = {
                    a: {x: shape.bounds.upperLeft().x - (this.maxWidth - shape.bounds.width())/2,
                        y: shape.bounds.upperLeft().y - (this.maxHeight - shape.bounds.height())/2},
                    b: {x: shape.bounds.lowerRight().x + (this.maxWidth - shape.bounds.width())/2,
                        y: shape.bounds.lowerRight().y + (this.maxHeight - shape.bounds.height())/2}
	            }
				
				/* If the new width of shape exceeds the maximum width, set width value to maximum. */
				if(this.maxWidth > maxSize.width) {
					newBounds.a.x = shape.bounds.upperLeft().x - 
									(maxSize.width - shape.bounds.width())/2;
					
					newBounds.b.x =	shape.bounds.lowerRight().x + (maxSize.width - shape.bounds.width())/2
				}
				
				/* If the new height of shape exceeds the maximum height, set height value to maximum. */
				if(this.maxHeight > maxSize.height) {
					newBounds.a.y = shape.bounds.upperLeft().y - 
									(maxSize.height - shape.bounds.height())/2;
					
					newBounds.b.y =	shape.bounds.lowerRight().y + (maxSize.height - shape.bounds.height())/2
				}
				
				/* set bounds of shape */
				shape.bounds.set(newBounds);
				
			},			
			execute: function(){
				// align each shape according to the way that was specified.
				this.elements.each(function(shape, index) {
					this.orgPos[index] = shape.bounds.upperLeft();
					
					var relBounds = this.bounds.clone();
					if (shape.parent && !(shape.parent instanceof ORYX.Core.Canvas) ) {
						var upL = shape.parent.absoluteBounds().upperLeft();
						relBounds.moveBy(-upL.x, -upL.y);
					}
					
					switch (this.way) {
						// align the shapes in the requested way.
						case ORYX.CONFIG.EDITOR_ALIGN_BOTTOM:
			                shape.bounds.moveTo({
								x: shape.bounds.upperLeft().x,
								y: relBounds.b.y - shape.bounds.height()
							}); break;
		
				        case ORYX.CONFIG.EDITOR_ALIGN_MIDDLE:
			                shape.bounds.moveTo({
								x: shape.bounds.upperLeft().x,
								y: (relBounds.a.y + relBounds.b.y - shape.bounds.height()) / 2
							}); break;
		
				        case ORYX.CONFIG.EDITOR_ALIGN_TOP:
			                shape.bounds.moveTo({
								x: shape.bounds.upperLeft().x,
								y: relBounds.a.y
							}); break;
		
				        case ORYX.CONFIG.EDITOR_ALIGN_LEFT:
			                shape.bounds.moveTo({
								x: relBounds.a.x,
								y: shape.bounds.upperLeft().y
							}); break;
		
				        case ORYX.CONFIG.EDITOR_ALIGN_CENTER:
			                shape.bounds.moveTo({
								x: (relBounds.a.x + relBounds.b.x - shape.bounds.width()) / 2,
								y: shape.bounds.upperLeft().y
							}); break;
		
				        case ORYX.CONFIG.EDITOR_ALIGN_RIGHT:
			                shape.bounds.moveTo({
								x: relBounds.b.x - shape.bounds.width(),
								y: shape.bounds.upperLeft().y
							}); break;
							
						case ORYX.CONFIG.EDITOR_ALIGN_SIZE:
							if(shape.isResizable) {
								this.orgPos[index] = {a: shape.bounds.upperLeft(), b: shape.bounds.lowerRight()};
								this.setBounds(shape, shape.maximumSize);
							}
							break;
					}
					//shape.update()
				}.bind(this));
		
				this.facade.getCanvas().update();
				
				this.facade.updateSelection();
			},
			rollback: function(){
				this.elements.each(function(shape, index) {
					if (this.way == ORYX.CONFIG.EDITOR_ALIGN_SIZE) {
						if(shape.isResizable) {shape.bounds.set(this.orgPos[index]);}
					} else {shape.bounds.moveTo(this.orgPos[index]);}
				}.bind(this));
				
				this.facade.getCanvas().update();
				
				this.facade.updateSelection();
			}
		})
		
		var command = new commandClass(elements, bounds, maxHeight, maxWidth, parseInt(way), this.facade);
		
		this.facade.executeCommands([command]);	
	}
});