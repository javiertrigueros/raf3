
(function($j, $, arquematics, ORYX, document, window) {
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

ORYX.Plugins.Grouping = Clazz.extend({

	facade: undefined,

	construct: function(facade) {
		this.facade = facade;

                var plugin = this;
            
                $('shape_group').observe('click', function(event) {
                    plugin.createGroup();
                });
                
                $('shape_ungroup').observe('click', function(event) {
                    plugin.deleteGroup();
                });
                
                /*go
		this.facade.offer({
			'name':ORYX.I18N.Grouping.group,
			'functionality': this.createGroup.bind(this),
			'group': ORYX.I18N.Grouping.grouping,
			'icon': ORYX.PATH + "images/shape_group.png",
			'description': ORYX.I18N.Grouping.groupDesc,
			'index': 1,
			'minShape': 2,
			'isEnabled': this.isEnabled.bind(this, false)});

		this.facade.offer({
			'name':ORYX.I18N.Grouping.ungroup,
			'functionality': this.deleteGroup.bind(this),
			'group': ORYX.I18N.Grouping.grouping,
			'icon': ORYX.PATH + "images/shape_ungroup.png",
			'description': ORYX.I18N.Grouping.ungroupDesc,
			'index': 2,
			'minShape': 2,
			'isEnabled': this.isEnabled.bind(this, true)});
		
                go*/
                
		this.selectedElements = [];
		this.groups = [];
	},

	isEnabled: function(handles) {
		
		var selectedEl = this.selectedElements;

		return	handles === this.groups.any(function(group) {
					return 		group.length === selectedEl.length &&
								group.all(function(grEl) { return selectedEl.member(grEl)})
								});
	},

	onSelectionChanged: function(event) {

		// Get the new selection
		var newSelection = event.elements;
		
		// Find all groups with these selection
		this.selectedElements = this.groups.findAll(function(group) {
				return group.any(function(grEl) { return newSelection.member(grEl)})
		});
		
		// Add the selection to them
		this.selectedElements.push(newSelection)
		
		// Do all in one level and unique
		this.selectedElements = this.selectedElements.flatten().uniq();
		
		// If there are more element, set new selection in the editor
		if(this.selectedElements.length !== newSelection.length) {
			this.facade.setSelection(this.selectedElements);
		}
	},
	
	createGroup: function() {
	
		var selectedElements = this.facade.getSelection();
		
		var commandClass = ORYX.Core.Command.extend({
			construct: function(selectedElements, groups, setGroupsCB, facade){
				this.selectedElements = selectedElements;
				this.groups = groups;
				this.callback = setGroupsCB;
				this.facade = facade;
			},			
			execute: function(){
				var g = this.groups.findAll(function(group) {
					return !group.any(function(grEl) { return selectedElements.member(grEl)})
				});
				
				g.push(selectedElements);

				this.callback(g.clone());
				
				this.facade.setSelection(this.selectedElements);
			},
			rollback: function(){
				this.callback(this.groups.clone());
				
				this.facade.setSelection(this.selectedElements);
			}
		})
		
		var command = new commandClass(selectedElements, this.groups.clone(), this.setGroups.bind(this), this.facade);
		
		this.facade.executeCommands([command]);
	},
	
	deleteGroup: function() {
		
		var selectedElements = this.facade.getSelection();
		
		var commandClass = ORYX.Core.Command.extend({
			construct: function(selectedElements, groups, setGroupsCB, facade){
				this.selectedElements = selectedElements;
				this.groups = groups;
				this.callback = setGroupsCB;
				this.facade = facade;
			},			
			execute: function(){
				// Delete all groups where all these elements are member and where the elements length the same
				var groupPartition = this.groups.partition(function(group) {
						return 		group.length !== selectedElements.length ||
									!group.all(function(grEl) { return selectedElements.member(grEl)})
					});

				this.callback(groupPartition[0]);
				
				this.facade.setSelection(this.selectedElements);
			},
			rollback: function(){
				this.callback(this.groups.clone());
				
				this.facade.setSelection(this.selectedElements);
			}
		})
		
		var command = new commandClass(selectedElements, this.groups.clone(), this.setGroups.bind(this), this.facade);
		
		this.facade.executeCommands([command]);	
	},
	
	setGroups: function(groups) {
		this.groups = groups;
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

if(!ORYX.Plugins)
	ORYX.Plugins = new Object(); 

ORYX.Plugins.ShapeHighlighting = Clazz.extend({

	construct: function(facade) {
		
		this.parentNode = facade.getCanvas().getSvgContainer();
		
		// The parent Node
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.parentNode,
					['g']);

		this.highlightNodes = {};
		
		facade.registerOnEvent(ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, this.setHighlight.bind(this));
		facade.registerOnEvent(ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, this.hideHighlight.bind(this));		

	},

	setHighlight: function(options) {
		if(options && options.highlightId){
			var node = this.highlightNodes[options.highlightId];
			
			if(!node){
				node= ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node,
					['path', {
						"stroke-width": 2.0, "fill":"none"
						}]);	
			
				this.highlightNodes[options.highlightId] = node;
			}

			if(options.elements && options.elements.length > 0) {
				
				this.setAttributesByStyle( node, options );
				this.show(node);
			
			} else {
			
				this.hide(node);			
			
			}
			
		}
	},
	
	hideHighlight: function(options) {
		if(options && options.highlightId && this.highlightNodes[options.highlightId]){
			this.hide(this.highlightNodes[options.highlightId]);
		}		
	},
	
	hide: function(node) {
		node.setAttributeNS(null, 'display', 'none');
	},

	show: function(node) {
		node.setAttributeNS(null, 'display', '');
	},
	
	setAttributesByStyle: function( node, options ){
		
		// If the style say, that it should look like a rectangle
		if( options.style && options.style == ORYX.CONFIG.SELECTION_HIGHLIGHT_STYLE_RECTANGLE ){
			
			// Set like this
			var bo = options.elements[0].absoluteBounds();
			
			var strWidth = options.strokewidth ? options.strokewidth 	: ORYX.CONFIG.BORDER_OFFSET
			
			node.setAttributeNS(null, "d", this.getPathRectangle( bo.a, bo.b , strWidth ) );
			node.setAttributeNS(null, "stroke", 		options.color 		? options.color 		: ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity 	? options.opacity 		: 0.2);
			node.setAttributeNS(null, "stroke-width", 	strWidth);
						
		} else if(options.elements.length == 1 
					&& options.elements[0] instanceof ORYX.Core.Edge &&
					options.highlightId != "selection") {
			
			/* Highlight containment of edge's childs */
			node.setAttributeNS(null, "d", this.getPathEdge(options.elements[0].dockers));
			node.setAttributeNS(null, "stroke", options.color ? options.color : ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity ? options.opacity : 0.2);
			node.setAttributeNS(null, "stroke-width", 	ORYX.CONFIG.OFFSET_EDGE_BOUNDS);
			
		}else {
			// If not, set just the corners
			node.setAttributeNS(null, "d", this.getPathByElements(options.elements));
			node.setAttributeNS(null, "stroke", options.color ? options.color : ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity ? options.opacity : 1.0);
			node.setAttributeNS(null, "stroke-width", 	options.strokewidth ? options.strokewidth 	: 2.0);
						
		}
	},
	
	getPathByElements: function(elements){
		if(!elements || elements.length <= 0) {return undefined}
		
		// Get the padding and the size
		var padding = ORYX.CONFIG.SELECTED_AREA_PADDING;
		
		var path = ""
		
		// Get thru all Elements
		elements.each((function(element) {
			if(!element) {return}
			// Get the absolute Bounds and the two Points
			var bounds = element.absoluteBounds();
			bounds.widen(padding)
			var a = bounds.upperLeft();
			var b = bounds.lowerRight();
			
			path = path + this.getPath(a ,b);
												
		}).bind(this));

		return path;
		
	},

	getPath: function(a, b){
				
		return this.getPathCorners(a, b);
	
	},
			
	getPathCorners: function(a, b){

		var size = ORYX.CONFIG.SELECTION_HIGHLIGHT_SIZE;
				
		var path = ""

		// Set: Upper left 
		path = path + "M" + a.x + " " + (a.y + size) + " l0 -" + size + " l" + size + " 0 ";
		// Set: Lower left
		path = path + "M" + a.x + " " + (b.y - size) + " l0 " + size + " l" + size + " 0 ";
		// Set: Lower right
		path = path + "M" + b.x + " " + (b.y - size) + " l0 " + size + " l-" + size + " 0 ";
		// Set: Upper right
		path = path + "M" + b.x + " " + (a.y + size) + " l0 -" + size + " l-" + size + " 0 ";
		
		return path;
	},
	
	getPathRectangle: function(a, b, strokeWidth){

		var size = ORYX.CONFIG.SELECTION_HIGHLIGHT_SIZE;

		var path 	= ""
		var offset 	= strokeWidth / 2.0;
		 
		// Set: Upper left 
		path = path + "M" + (a.x + offset) + " " + (a.y);
		path = path + " L" + (a.x + offset) + " " + (b.y - offset);
		path = path + " L" + (b.x - offset) + " " + (b.y - offset);
		path = path + " L" + (b.x - offset) + " " + (a.y + offset);
		path = path + " L" + (a.x + offset) + " " + (a.y + offset);

		return path;
	},
	
	getPathEdge: function(edgeDockers) {
		var length = edgeDockers.length;
		var path = "M" + edgeDockers[0].bounds.center().x + " " 
					+  edgeDockers[0].bounds.center().y;
		
		for(i=1; i<length; i++) {
			var dockerPoint = edgeDockers[i].bounds.center();
			path = path + " L" + dockerPoint.x + " " +  dockerPoint.y;
		}
		
		return path;
	}
	
});

 
ORYX.Plugins.HighlightingSelectedShapes = Clazz.extend({

	construct: function(facade) {
		this.facade = facade;
		this.opacityFull = 0.9;
		this.opacityLow = 0.4;

		// Register on Dragging-Events for show/hide of ShapeMenu
		//this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_START, this.hide.bind(this));
		//this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_END,  this.show.bind(this));		
	},

	/**
	 * On the Selection-Changed
	 *
	 */
	onSelectionChanged: function(event) {
		if(event.elements && event.elements.length > 1) {
			this.facade.raiseEvent({
										type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
										highlightId:'selection',
										elements:	event.elements.without(event.subSelection),
										color:		ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR,
										opacity: 	!event.subSelection ? this.opacityFull : this.opacityLow
									});

			if(event.subSelection){
				this.facade.raiseEvent({
											type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
											highlightId:'subselection',
											elements:	[event.subSelection],
											color:		ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR,
											opacity: 	this.opacityFull
										});	
			} else {
				this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'subselection'});				
			}						
			
		} else {
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'selection'});
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'subselection'});
		}		
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

if(!ORYX.Plugins)
	ORYX.Plugins = new Object();

ORYX.Plugins.DragDocker = Clazz.extend({

	/**
	 *	Constructor
	 *	@param {Object} Facade: The Facade of the Editor
	 */
	construct: function(facade) {
		this.facade = facade;
		
		// Set the valid and invalid color
		this.VALIDCOLOR 	= ORYX.CONFIG.SELECTION_VALID_COLOR;
		this.INVALIDCOLOR 	= ORYX.CONFIG.SELECTION_INVALID_COLOR;
		
		// Define Variables 
		this.shapeSelection = undefined;
		this.docker 		= undefined;
		this.dockerParent   = undefined;
		this.dockerSource 	= undefined;
		this.dockerTarget 	= undefined;
		this.lastUIObj 		= undefined;
		this.isStartDocker 	= undefined;
		this.isEndDocker 	= undefined;
		this.undockTreshold	= 10;
		this.initialDockerPosition = undefined;
		this.outerDockerNotMoved = undefined;
		this.isValid 		= false;
		
		// For the Drag and Drop
		// Register on MouseDown-Event on a Docker
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DOCKERDRAG, this.handleDockerDrag.bind(this));

		
		// Register on over/out to show / hide a docker
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOVER, this.handleMouseOver.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOUT, this.handleMouseOut.bind(this));		
		
		
	},
	
	/**
	 * MouseOut Handler
	 *
	 */
	handleMouseOut: function(event, uiObj) {
		// If there is a Docker, hide this
		if(!this.docker && uiObj instanceof ORYX.Core.Controls.Docker) {
			uiObj.hide()	
		} else if(!this.docker && uiObj instanceof ORYX.Core.Edge) {
			uiObj.dockers.each(function(docker){
				docker.hide();
			})
		}
	},

	/**
	 * MouseOver Handler
	 *
	 */
	handleMouseOver: function(event, uiObj) {
		// If there is a Docker, show this		
		if(!this.docker && uiObj instanceof ORYX.Core.Controls.Docker) {
			uiObj.show()	
		} else if(!this.docker && uiObj instanceof ORYX.Core.Edge) {
			uiObj.dockers.each(function(docker){
				docker.show();
			})
		}
	},
	/**
	 * DockerDrag Handler
	 * delegates the uiEvent of the drag event to the mouseDown function
	 */
	handleDockerDrag: function(event, uiObj) {
		this.handleMouseDown(event.uiEvent, uiObj);
	},
	
	/**
	 * MouseDown Handler
	 *
	 */	
	handleMouseDown: function(event, uiObj) {
		// If there is a Docker
		if(uiObj instanceof ORYX.Core.Controls.Docker && uiObj.isMovable) {
			
			/* Buffering shape selection and clear selection*/
			this.shapeSelection = this.facade.getSelection();
			this.facade.setSelection();
			
			this.docker = uiObj;
			this.initialDockerPosition = this.docker.bounds.center();
			this.outerDockerNotMoved = false;			
			this.dockerParent = uiObj.parent;
			
			// Define command arguments
			this._commandArg = {docker:uiObj, dockedShape:uiObj.getDockedShape(), refPoint:uiObj.referencePoint || uiObj.bounds.center()};

			// Show the Docker
			this.docker.show();
			
			// If the Dockers Parent is an Edge, 
			//  and the Docker is either the first or last Docker of the Edge
			if(uiObj.parent instanceof ORYX.Core.Edge && 
			   	(uiObj.parent.dockers.first() == uiObj || uiObj.parent.dockers.last() == uiObj)) {
				
				// Get the Edge Source or Target
				if(uiObj.parent.dockers.first() == uiObj && uiObj.parent.dockers.last().getDockedShape()) {
					this.dockerTarget = uiObj.parent.dockers.last().getDockedShape()
				} else if(uiObj.parent.dockers.last() == uiObj && uiObj.parent.dockers.first().getDockedShape()) {
					this.dockerSource = uiObj.parent.dockers.first().getDockedShape()
				}
				
			} else {
				// If there parent is not an Edge, undefined the Source and Target
				this.dockerSource = undefined;
				this.dockerTarget = undefined;				
			}
		
			this.isStartDocker = this.docker.parent.dockers.first() === this.docker
			this.isEndDocker = this.docker.parent.dockers.last() === this.docker
					
			// add to canvas while dragging
			this.facade.getCanvas().add(this.docker.parent);
			
			// Hide all Labels from Docker
			this.docker.parent.getLabels().each(function(label) {
				label.hide();
			});
			
			// Undocked the Docker from current Shape
			if ((!this.isStartDocker && !this.isEndDocker) || !this.docker.isDocked()) {
				
				this.docker.setDockedShape(undefined)
				// Set the Docker to the center of the mouse pointer
				var evPos = this.facade.eventCoordinates(event);
				this.docker.bounds.centerMoveTo(evPos);
				//this.docker.update()
				//this.facade.getCanvas().update();
				this.dockerParent._update();
			} else {
				this.outerDockerNotMoved = true;
			}
			
			var option = {movedCallback: this.dockerMoved.bind(this), upCallback: this.dockerMovedFinished.bind(this)}
				
			// Enable the Docker for Drag'n'Drop, give the mouseMove and mouseUp-Callback with
			ORYX.Core.UIEnableDrag(event, uiObj, option);
		}
	},
	
	/**
	 * Docker MouseMove Handler
	 *
	 */
	dockerMoved: function(event) {
		this.outerDockerNotMoved = false;
		var snapToMagnet = undefined;
		
		if (this.docker.parent) {
			if (this.isStartDocker || this.isEndDocker) {
			
				// Get the EventPosition and all Shapes on these point
				var evPos = this.facade.eventCoordinates(event);
				
				if(this.docker.isDocked()) {
					/* Only consider start/end dockers if they are moved over a treshold */
					var distanceDockerPointer = 
						ORYX.Core.Math.getDistancePointToPoint(evPos, this.initialDockerPosition);
					if(distanceDockerPointer < this.undockTreshold) {
						this.outerDockerNotMoved = true;
						return;
					}
					
					/* Undock the docker */
					this.docker.setDockedShape(undefined)
					// Set the Docker to the center of the mouse pointer
					//this.docker.bounds.centerMoveTo(evPos);
					this.dockerParent._update();
				}
				
				var shapes = this.facade.getCanvas().getAbstractShapesAtPosition(evPos);
				
				// Get the top level Shape on these, but not the same as Dockers parent
				var uiObj = shapes.pop();
				if (this.docker.parent === uiObj) {
					uiObj = shapes.pop();
				}
				
				
				
				// If the top level Shape the same as the last Shape, then return
				if (this.lastUIObj == uiObj) {
				//return;
				
				// If the top level uiObj instance of Shape and this isn't the parent of the docker 
				}
				else 
					if (uiObj instanceof ORYX.Core.Shape) {
					
						// Get the StencilSet of the Edge
						var sset = this.docker.parent.getStencil().stencilSet();
						
						// Ask by the StencilSet if the source, the edge and the target valid connections.
						if (this.docker.parent instanceof ORYX.Core.Edge) {
							
							var highestParent = this.getHighestParentBeforeCanvas(uiObj);
							/* Ensure that the shape to dock is not a child shape 
							 * of the same edge.
							 */
							if(highestParent instanceof ORYX.Core.Edge 
									&& this.docker.parent === highestParent) {
								this.isValid = false;
								this.dockerParent._update();
								return;
							}
							this.isValid = false;
							var curObj = uiObj, orgObj = uiObj;
							while(!this.isValid && curObj && !(curObj instanceof ORYX.Core.Canvas)){
								uiObj = curObj;
								this.isValid = this.facade.getRules().canConnect({
											sourceShape: this.dockerSource ? // Is there a docked source 
															this.dockerSource : // than set this
															(this.isStartDocker ? // if not and if the Docker is the start docker
																uiObj : // take the last uiObj
																undefined), // if not set it to undefined;
											edgeShape: this.docker.parent,
											targetShape: this.dockerTarget ? // Is there a docked target 
											this.dockerTarget : // than set this
														(this.isEndDocker ? // if not and if the Docker is not the start docker
															uiObj : // take the last uiObj
															undefined) // if not set it to undefined;
										});
								curObj = curObj.parent;
							}
							
							// Reset uiObj if no 
							// valid parent is found
							if (!this.isValid){
								uiObj = orgObj;
							}

						}
						else {
							this.isValid = this.facade.getRules().canConnect({
								sourceShape: uiObj,
								edgeShape: this.docker.parent,
								targetShape: this.docker.parent
							});
						}
						
						// If there is a lastUIObj, hide the magnets
						if (this.lastUIObj) {
							this.hideMagnets(this.lastUIObj)
						}
						
						// If there is a valid connection, show the magnets
						if (this.isValid) {
							this.showMagnets(uiObj)
						}
						
						// Set the Highlight Rectangle by these value
						this.showHighlight(uiObj, this.isValid ? this.VALIDCOLOR : this.INVALIDCOLOR);
						
						// Buffer the current Shape
						this.lastUIObj = uiObj;
					}
					else {
						// If there is no top level Shape, then hide the highligting of the last Shape
						this.hideHighlight();
						this.lastUIObj ? this.hideMagnets(this.lastUIObj) : null;
						this.lastUIObj = undefined;
						this.isValid = false;
					}
				
				// Snap to the nearest Magnet
				if (this.lastUIObj && this.isValid && !(event.shiftKey || event.ctrlKey)) {
					snapToMagnet = this.lastUIObj.magnets.find(function(magnet){
						return magnet.absoluteBounds().isIncluded(evPos)
					});
					
					if (snapToMagnet) {
						this.docker.bounds.centerMoveTo(snapToMagnet.absoluteCenterXY());
					//this.docker.update()
					}
				}
			}
		}
		// Snap to on the nearest Docker of the same parent
		if(!(event.shiftKey || event.ctrlKey) && !snapToMagnet) {
			var minOffset = ORYX.CONFIG.DOCKER_SNAP_OFFSET;
			var nearestX = minOffset + 1
			var nearestY = minOffset + 1
			
			var dockerCenter = this.docker.bounds.center();
			
			if (this.docker.parent) {
				
				this.docker.parent.dockers.each((function(docker){
					if (this.docker == docker) {
						return
					};
					
					var center = docker.referencePoint ? docker.getAbsoluteReferencePoint() : docker.bounds.center();
					
					nearestX = Math.abs(nearestX) > Math.abs(center.x - dockerCenter.x) ? center.x - dockerCenter.x : nearestX;
					nearestY = Math.abs(nearestY) > Math.abs(center.y - dockerCenter.y) ? center.y - dockerCenter.y : nearestY;
					
					
				}).bind(this));
				
				if (Math.abs(nearestX) < minOffset || Math.abs(nearestY) < minOffset) {
					nearestX = Math.abs(nearestX) < minOffset ? nearestX : 0;
					nearestY = Math.abs(nearestY) < minOffset ? nearestY : 0;
					
					this.docker.bounds.centerMoveTo(dockerCenter.x + nearestX, dockerCenter.y + nearestY);
					//this.docker.update()
				} else {
					
					
					
					var previous = this.docker.parent.dockers[Math.max(this.docker.parent.dockers.indexOf(this.docker)-1, 0)]
					var next = this.docker.parent.dockers[Math.min(this.docker.parent.dockers.indexOf(this.docker)+1, this.docker.parent.dockers.length-1)]
					
					if (previous && next && previous !== this.docker && next !== this.docker){
						var cp = previous.bounds.center();
						var cn = next.bounds.center();
						var cd = this.docker.bounds.center();
						
						// Checks if the point is on the line between previous and next
						if (ORYX.Core.Math.isPointInLine(cd.x, cd.y, cp.x, cp.y, cn.x, cn.y, 10)) {
							// Get the rise
							var raise = (Number(cn.y)-Number(cp.y))/(Number(cn.x)-Number(cp.x));
							// Calculate the intersection point
							var intersecX = ((cp.y-(cp.x*raise))-(cd.y-(cd.x*(-Math.pow(raise,-1)))))/((-Math.pow(raise,-1))-raise);
							var intersecY = (cp.y-(cp.x*raise))+(raise*intersecX);
							
							if(isNaN(intersecX) || isNaN(intersecY)) {return;}
							
							this.docker.bounds.centerMoveTo(intersecX, intersecY);
						}
					}
					
				}
			}
		}
		//this.facade.getCanvas().update();
		this.dockerParent._update();
	},

	/**
	 * Docker MouseUp Handler
	 *
	 */
	dockerMovedFinished: function(event) {
		
		/* Reset to buffered shape selection */
		this.facade.setSelection(this.shapeSelection);
		
		// Hide the border
		this.hideHighlight();
		
		// Show all Labels from Docker
		this.dockerParent.getLabels().each(function(label){
			label.show();
			//label.update();
		});
	
		// If there is a last top level Shape
		if(this.lastUIObj && (this.isStartDocker || this.isEndDocker)){				
			// If there is a valid connection, the set as a docked Shape to them
			if(this.isValid) {
				
				this.docker.setDockedShape(this.lastUIObj);	
				
				this.facade.raiseEvent({
					type 	:ORYX.CONFIG.EVENT_DRAGDOCKER_DOCKED, 
					docker	: this.docker,
					parent	: this.docker.parent,
					target	: this.lastUIObj
				});
			}
			
			this.hideMagnets(this.lastUIObj)
		}
		
		// Hide the Docker
		this.docker.hide();
		
		if(this.outerDockerNotMoved) {
			// Get the EventPosition and all Shapes on these point
			var evPos = this.facade.eventCoordinates(event);
			var shapes = this.facade.getCanvas().getAbstractShapesAtPosition(evPos);
			
			/* Remove edges from selection */
			var shapeWithoutEdges = shapes.findAll(function(node) {
				return node instanceof ORYX.Core.Node;
			});
			shapes = shapeWithoutEdges.length ? shapeWithoutEdges : shapes;
			this.facade.setSelection(shapes);
		} else {
			//Command-Pattern for dragging one docker
			var dragDockerCommand = ORYX.Core.Command.extend({
				construct: function(docker, newPos, oldPos, newDockedShape, oldDockedShape, facade){
					this.docker 		= docker;
					this.index		= docker.parent.dockers.indexOf(docker);
					this.newPosition	= newPos;
					this.newDockedShape     = newDockedShape;
					this.oldPosition	= oldPos;
					this.oldDockedShape	= oldDockedShape;
					this.facade		= facade;
					this.index		= docker.parent.dockers.indexOf(docker);
					this.shape		= docker.parent;
					
				},			
				execute: function(){
					if (!this.docker.parent){
						this.docker = this.shape.dockers[this.index];
					}
					this.dock( this.newDockedShape, this.newPosition );
					this.removedDockers = this.shape.removeUnusedDockers();
					this.facade.updateSelection();
				},
				rollback: function(){
					this.dock( this.oldDockedShape, this.oldPosition );
					(this.removedDockers||$H({})).each(function(d){
						this.shape.add(d.value, Number(d.key));
						this.shape._update(true);
					}.bind(this))
					this.facade.updateSelection();
				},
				dock:function( toDockShape, pos ){			
					// Set the Docker to the new Shape
					this.docker.setDockedShape( undefined );
					if( toDockShape ){			
						this.docker.setDockedShape( toDockShape );	
						this.docker.setReferencePoint( pos );
						//this.docker.update();	
						//this.docker.parent._update();				
					} else {
						this.docker.bounds.centerMoveTo( pos );
					}
	
					this.facade.getCanvas().update();
					
												
								
				}
			});
			
			
			if (this.docker.parent){
				// Instanziate the dockCommand
				var command = new dragDockerCommand(this.docker, this.docker.getDockedShape() ? this.docker.referencePoint : this.docker.bounds.center(), this._commandArg.refPoint, this.docker.getDockedShape(), this._commandArg.dockedShape, this.facade);
				this.facade.executeCommands( [command] );	
			}
		}
		
	

		

		// Update all Shapes
		//this.facade.updateSelection();
			
		// Undefined all variables
		this.docker 		= undefined;
		this.dockerParent   = undefined;
		this.dockerSource 	= undefined;
		this.dockerTarget 	= undefined;	
		this.lastUIObj 		= undefined;		
	},
	
	/**
	 * Hide the highlighting
	 */
	hideHighlight: function() {
		this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'validDockedShape'});
	},

	/**
	 * Show the highlighting
	 *
	 */
	showHighlight: function(uiObj, color) {
		
		this.facade.raiseEvent({
					type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
					highlightId:'validDockedShape',
					elements:	[uiObj],
					color:		color
					});
	},
	
	showMagnets: function(uiObj){
		uiObj.magnets.each(function(magnet) {
			magnet.show();
		});
	},
	
	hideMagnets: function(uiObj){
		uiObj.magnets.each(function(magnet) {
			magnet.hide();
		});
	},
	
	getHighestParentBeforeCanvas: function(shape) {
		if(!(shape instanceof ORYX.Core.Shape)) {return undefined;}
		
		var parent = shape.parent;
		while(parent && !(parent.parent instanceof ORYX.Core.Canvas)) {
			parent = parent.parent;
		}	
		
		return parent;		
	}	

});



/**
 * Copyright (c) 2010
 * Robert Böhme, Philipp Berger
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

ORYX.Plugins.DockerCreation = Clazz.extend({
	
	construct: function( facade ){
		this.facade = facade;		
		this.active = false; //true-> a ghostdocker is shown; false->ghostdocker is hidden

		//visual representation of the Ghostdocker
		this.circle = ORYX.Editor.graft("http://www.w3.org/2000/svg", null ,
				['g', {"pointer-events":"none"},
					['circle', {cx: "8", cy: "8", r: "3", fill:"yellow"}]]); 	
		
		//Event registrations
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOVER, this.handleMouseOver.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOUT, this.handleMouseOut.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEMOVE, this.handleMouseMove.bind(this));
		/*
		 * Double click is reserved for label access, so abort action
		 */
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DBLCLICK,function(){window.clearTimeout(this.timer)}.bind(this));
		/*
		 * click is reserved for selecting, so abort action when mouse goes up
		 */
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEUP,function(){window.clearTimeout(this.timer)}.bind(this));

	},
	
	/**
	 * MouseOut Handler
	 * 
	 *hide the Ghostpoint when Leaving the mouse from an edge
	 */
	handleMouseOut: function(event, uiObj) {
		
		if (this.active) {		
			this.hideOverlay();
			this.active = false;
		}	
	},
	
	/**
	 * MouseOver Handler
	 * 
	 *show the Ghostpoint if the edge is selected
	 */
	handleMouseOver: function(event, uiObj) {
		//show the Ghostdocker on the edge
		if (uiObj instanceof ORYX.Core.Edge && this.isEdgeDocked(uiObj)){
			this.showOverlay(uiObj, this.facade.eventCoordinates(event));
		}
		//ghostdocker is active
		this.active = true;
		
	},
	
	/**
	 * MouseDown Handler
	 * 
	 *create a Docker when clicking on a selected edge
	 */
	handleMouseDown: function(event, uiObj) {	
		if (event.which==1 && uiObj instanceof ORYX.Core.Edge && this.isEdgeDocked(uiObj)){
			//Timer for Doubleclick to be able to create a label
			window.clearTimeout(this.timer);
			
			this.timer = window.setTimeout(function () {
				// Give the event to enable one click creation and drag
				this.addDockerCommand({
		            edge: uiObj,
					event: event,
		            position: this.facade.eventCoordinates(event)
		        });
	
			}.bind(this),200);
			this.hideOverlay();
	
		}
	},
	
	/**
	 * MouseMove Handler
	 * 
	 *refresh the ghostpoint when moving the mouse over an edge
	 */
	handleMouseMove: function(event, uiObj) {		
			if (uiObj instanceof ORYX.Core.Edge && this.isEdgeDocked(uiObj)){
				if (this.active) {	
					//refresh Ghostpoint
					this.hideOverlay();			
					this.showOverlay( uiObj, this.facade.eventCoordinates(event));
				}else{
					this.showOverlay( uiObj, this.facade.eventCoordinates(event));	
				}		
			}	
	},
	
	/**
	 * returns true if the edge is docked to at least one node
	 */
	isEdgeDocked: function(edge){
		return !!(edge.incoming.length || edge.outgoing.length);
	},
	
	
	/**
	 * Command for creating a new Docker
	 * 
	 * @param {Object} options
	 */
	addDockerCommand: function(options){
	    if(!options.edge)
	        return;
	    
	    var commandClass = ORYX.Core.Command.extend({
	        construct: function(edge, docker, pos, facade, options){            
	            this.edge = edge;
	            this.docker = docker;
	            this.pos = pos;
	            this.facade = facade;
				this.options= options;
	        },
	        execute: function(){
	            this.docker = this.edge.addDocker(this.pos, this.docker);
				this.index = this.edge.dockers.indexOf(this.docker);                                    
	            this.facade.getCanvas().update();
	            this.facade.updateSelection();
	            this.options.docker=this.docker;
	
	        },
	        rollback: function(){
	          
	             if (this.docker instanceof ORYX.Core.Controls.Docker) {
	                    this.edge.removeDocker(this.docker);
	             }             
	            this.facade.getCanvas().update();
	            this.facade.updateSelection(); 
	        }
	    });
	    var command = new commandClass(options.edge, options.docker, options.position, this.facade, options);    
	    this.facade.executeCommands([command]);
	
	    
		this.facade.raiseEvent({
			uiEvent:	options.event,
			type:		ORYX.CONFIG.EVENT_DOCKERDRAG}, options.docker );
	    
	},
	
	/**
	 *show the ghostpoint overlay
	 *
	 *@param {Shape} edge
	 *@param {Point} point
	 */
	showOverlay: function(edge, point){
		var best = point;
		var pair = [0,1];
		var min_distance = Infinity;
	
		// calculate the optimal point ON THE EDGE to display the docker
		for (var i=0, l=edge.dockers.length; i < l-1; i++) {
			var intersection_point = ORYX.Core.Math.getPointOfIntersectionPointLine(
				edge.dockers[i].bounds.center(),
				edge.dockers[i+1].bounds.center(),
				point,
				true // consider only the current segment instead of the whole line ("Strecke, statt Gerade") for distance calculation
			);
			
			
			if(!intersection_point) {
				continue;
			}
	
			var current_distance = ORYX.Core.Math.getDistancePointToPoint(point, intersection_point);
			if (min_distance > current_distance) {
				min_distance = current_distance;
				best = intersection_point;
			}
		}
	
		this.facade.raiseEvent({
				type: 			ORYX.CONFIG.EVENT_OVERLAY_SHOW,
				id: 			"ghostpoint",
				shapes: 		[edge],
				node:			this.circle,
				ghostPoint:		best,
				dontCloneNode:	true
			});			
	},
	
	/**
	 *hide the ghostpoint overlay
	 */
	hideOverlay: function() {
		
		this.facade.raiseEvent({
			type: ORYX.CONFIG.EVENT_OVERLAY_HIDE,
			id: "ghostpoint"
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

if(!ORYX.Plugins)
	ORYX.Plugins = new Object();

 ORYX.Plugins.SelectionFrame = Clazz.extend({

	construct: function(facade) {
		this.facade = facade;

		// Register on MouseEvents
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this));
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.handleMouseUp.bind(this), true);

		// Some initiale variables
		this.position 		= {x:0, y:0};
		this.size 			= {width:0, height:0};
		this.offsetPosition = {x: 0, y: 0};

		// (Un)Register Mouse-Move Event
		this.moveCallback 	= undefined;
		this.offsetScroll	= {x:0,y:0};
		// HTML-Node of Selection-Frame
		this.node = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", this.facade.getCanvas().getHTMLContainer(),
			['div', {'class':'Oryx_SelectionFrame'}]);

		this.hide();
	},

	handleMouseDown: function(event, uiObj) {
		// If there is the Canvas
		if( uiObj instanceof ORYX.Core.Canvas ) {
			// Calculate the Offset
			var scrollNode = uiObj.rootNode.parentNode.parentNode;
						
			var a = this.facade.getCanvas().node.getScreenCTM();
			this.offsetPosition = {
				x: a.e,
				y: a.f
			}

			// Set the new Position
			this.setPos({x: Event.pointerX(event)-this.offsetPosition.x, y:Event.pointerY(event)-this.offsetPosition.y});
			// Reset the size
			this.resize({width:0, height:0});
			this.moveCallback = this.handleMouseMove.bind(this);
		
			// Register Mouse-Move Event
			document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.moveCallback, false);

			this.offsetScroll		= {x:scrollNode.scrollLeft,y:scrollNode.scrollTop};
			
			// Show the Frame
			this.show();
		}

		Event.stop(event);
	},

	handleMouseUp: function(event) {
		// If there was an MouseMoving
		if(this.moveCallback) {
			// Hide the Frame
			this.hide();

			// Unregister Mouse-Move
			document.documentElement.removeEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.moveCallback, false);			
		
			this.moveCallback = undefined;

			var corrSVG = this.facade.getCanvas().node.getScreenCTM();

			// Calculate the positions of the Frame
			var a = {
				x: this.size.width > 0 ? this.position.x : this.position.x + this.size.width,
				y: this.size.height > 0 ? this.position.y : this.position.y + this.size.height
			}

			var b = {
				x: a.x + Math.abs(this.size.width),
				y: a.y + Math.abs(this.size.height)
			}

			// Fit to SVG-Coordinates
			a.x /= corrSVG.a; a.y /= corrSVG.d;
			b.x /= corrSVG.a; b.y /= corrSVG.d;


			// Calculate the elements from the childs of the canvas
			var elements = this.facade.getCanvas().getChildShapes(true).findAll(function(value) {
				var absBounds = value.absoluteBounds();
				var bA = absBounds.upperLeft();
				var bB = absBounds.lowerRight();
				if(bA.x > a.x && bA.y > a.y && bB.x < b.x && bB.y < b.y)
					return true;
				return false
			});

			// Set the selection
			this.facade.setSelection(elements);
		}
	},

	handleMouseMove: function(event) {
		// Calculate the size
		var size = {
			width	: Event.pointerX(event) - this.position.x - this.offsetPosition.x,
			height	: Event.pointerY(event) - this.position.y - this.offsetPosition.y
		}

		var scrollNode 	= this.facade.getCanvas().rootNode.parentNode.parentNode;
		size.width 		-= this.offsetScroll.x - scrollNode.scrollLeft; 
		size.height 	-= this.offsetScroll.y - scrollNode.scrollTop;
						
		// Set the size
		this.resize(size);

		Event.stop(event);
	},

	hide: function() {
		this.node.style.display = "none";
	},

	show: function() {
		this.node.style.display = "";
	},

	setPos: function(pos) {
		// Set the Position
		this.node.style.top = pos.y + "px";
		this.node.style.left = pos.x + "px";
		this.position = pos;
	},

	resize: function(size) {

		// Calculate the negative offset
		this.setPos(this.position);
		this.size = Object.clone(size);
		
		if(size.width < 0) {
			this.node.style.left = (this.position.x + size.width) + "px";
			size.width = - size.width;
		}
		if(size.height < 0) {
			this.node.style.top = (this.position.y + size.height) + "px";
			size.height = - size.height;
		}

		// Set the size
		this.node.style.width = size.width + "px";
		this.node.style.height = size.height + "px";
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

if(!ORYX.Plugins)
	ORYX.Plugins = new Object(); 

ORYX.Plugins.ShapeHighlighting = Clazz.extend({

	construct: function(facade) {
		
		this.parentNode = facade.getCanvas().getSvgContainer();
		
		// The parent Node
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.parentNode,
					['g']);

		this.highlightNodes = {};
		
		facade.registerOnEvent(ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, this.setHighlight.bind(this));
		facade.registerOnEvent(ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, this.hideHighlight.bind(this));		

	},

	setHighlight: function(options) {
		if(options && options.highlightId){
			var node = this.highlightNodes[options.highlightId];
			
			if(!node){
				node= ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node,
					['path', {
						"stroke-width": 2.0, "fill":"none"
						}]);	
			
				this.highlightNodes[options.highlightId] = node;
			}

			if(options.elements && options.elements.length > 0) {
				
				this.setAttributesByStyle( node, options );
				this.show(node);
			
			} else {
			
				this.hide(node);			
			
			}
			
		}
	},
	
	hideHighlight: function(options) {
		if(options && options.highlightId && this.highlightNodes[options.highlightId]){
			this.hide(this.highlightNodes[options.highlightId]);
		}		
	},
	
	hide: function(node) {
		node.setAttributeNS(null, 'display', 'none');
	},

	show: function(node) {
		node.setAttributeNS(null, 'display', '');
	},
	
	setAttributesByStyle: function( node, options ){
		
		// If the style say, that it should look like a rectangle
		if( options.style && options.style == ORYX.CONFIG.SELECTION_HIGHLIGHT_STYLE_RECTANGLE ){
			
			// Set like this
			var bo = options.elements[0].absoluteBounds();
			
			var strWidth = options.strokewidth ? options.strokewidth 	: ORYX.CONFIG.BORDER_OFFSET
			
			node.setAttributeNS(null, "d", this.getPathRectangle( bo.a, bo.b , strWidth ) );
			node.setAttributeNS(null, "stroke", 		options.color 		? options.color 		: ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity 	? options.opacity 		: 0.2);
			node.setAttributeNS(null, "stroke-width", 	strWidth);
						
		} else if(options.elements.length == 1 
					&& options.elements[0] instanceof ORYX.Core.Edge &&
					options.highlightId != "selection") {
			
			/* Highlight containment of edge's childs */
			node.setAttributeNS(null, "d", this.getPathEdge(options.elements[0].dockers));
			node.setAttributeNS(null, "stroke", options.color ? options.color : ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity ? options.opacity : 0.2);
			node.setAttributeNS(null, "stroke-width", 	ORYX.CONFIG.OFFSET_EDGE_BOUNDS);
			
		}else {
			// If not, set just the corners
			node.setAttributeNS(null, "d", this.getPathByElements(options.elements));
			node.setAttributeNS(null, "stroke", options.color ? options.color : ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR);
			node.setAttributeNS(null, "stroke-opacity", options.opacity ? options.opacity : 1.0);
			node.setAttributeNS(null, "stroke-width", 	options.strokewidth ? options.strokewidth 	: 2.0);
						
		}
	},
	
	getPathByElements: function(elements){
		if(!elements || elements.length <= 0) {return undefined}
		
		// Get the padding and the size
		var padding = ORYX.CONFIG.SELECTED_AREA_PADDING;
		
		var path = ""
		
		// Get thru all Elements
		elements.each((function(element) {
			if(!element) {return}
			// Get the absolute Bounds and the two Points
			var bounds = element.absoluteBounds();
			bounds.widen(padding)
			var a = bounds.upperLeft();
			var b = bounds.lowerRight();
			
			path = path + this.getPath(a ,b);
												
		}).bind(this));

		return path;
		
	},

	getPath: function(a, b){
				
		return this.getPathCorners(a, b);
	
	},
			
	getPathCorners: function(a, b){

		var size = ORYX.CONFIG.SELECTION_HIGHLIGHT_SIZE;
				
		var path = ""

		// Set: Upper left 
		path = path + "M" + a.x + " " + (a.y + size) + " l0 -" + size + " l" + size + " 0 ";
		// Set: Lower left
		path = path + "M" + a.x + " " + (b.y - size) + " l0 " + size + " l" + size + " 0 ";
		// Set: Lower right
		path = path + "M" + b.x + " " + (b.y - size) + " l0 " + size + " l-" + size + " 0 ";
		// Set: Upper right
		path = path + "M" + b.x + " " + (a.y + size) + " l0 -" + size + " l-" + size + " 0 ";
		
		return path;
	},
	
	getPathRectangle: function(a, b, strokeWidth){

		var size = ORYX.CONFIG.SELECTION_HIGHLIGHT_SIZE;

		var path 	= ""
		var offset 	= strokeWidth / 2.0;
		 
		// Set: Upper left 
		path = path + "M" + (a.x + offset) + " " + (a.y);
		path = path + " L" + (a.x + offset) + " " + (b.y - offset);
		path = path + " L" + (b.x - offset) + " " + (b.y - offset);
		path = path + " L" + (b.x - offset) + " " + (a.y + offset);
		path = path + " L" + (a.x + offset) + " " + (a.y + offset);

		return path;
	},
	
	getPathEdge: function(edgeDockers) {
		var length = edgeDockers.length;
		var path = "M" + edgeDockers[0].bounds.center().x + " " 
					+  edgeDockers[0].bounds.center().y;
		
		for(i=1; i<length; i++) {
			var dockerPoint = edgeDockers[i].bounds.center();
			path = path + " L" + dockerPoint.x + " " +  dockerPoint.y;
		}
		
		return path;
	}
	
});

 
ORYX.Plugins.HighlightingSelectedShapes = Clazz.extend({

	construct: function(facade) {
		this.facade = facade;
		this.opacityFull = 0.9;
		this.opacityLow = 0.4;

		// Register on Dragging-Events for show/hide of ShapeMenu
		//this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_START, this.hide.bind(this));
		//this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_END,  this.show.bind(this));		
	},

	/**
	 * On the Selection-Changed
	 *
	 */
	onSelectionChanged: function(event) {
		if(event.elements && event.elements.length > 1) {
			this.facade.raiseEvent({
										type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
										highlightId:'selection',
										elements:	event.elements.without(event.subSelection),
										color:		ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR,
										opacity: 	!event.subSelection ? this.opacityFull : this.opacityLow
									});

			if(event.subSelection){
				this.facade.raiseEvent({
											type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
											highlightId:'subselection',
											elements:	[event.subSelection],
											color:		ORYX.CONFIG.SELECTION_HIGHLIGHT_COLOR,
											opacity: 	this.opacityFull
										});	
			} else {
				this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'subselection'});				
			}						
			
		} else {
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'selection'});
			this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'subselection'});
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
 * 
 * HOW to USE the OVERLAY PLUGIN:
 * 	You can use it via the event mechanism from the editor
 * 	by using facade.raiseEvent( <option> )
 * 
 * 	As an example please have a look in the overlayexample.js
 * 
 * 	The option object should/have to have following attributes:
 * 
 * 	Key				Value-Type							Description
 * 	================================================================
 * 
 *	type 			ORYX.CONFIG.EVENT_OVERLAY_SHOW | ORYX.CONFIG.EVENT_OVERLAY_HIDE		This is the type of the event	
 *	id				<String>							You have to use an unified id for later on hiding this overlay
 *	shapes 			<ORYX.Core.Shape[]>					The Shapes where the attributes should be changed
 *	attributes 		<Object>							An object with svg-style attributes as key-value pair
 *	node			<SVGElement>						An SVG-Element could be specified for adding this to the Shape
 *	nodePosition	"N"|"NE"|"E"|"SE"|"S"|"SW"|"W"|"NW"|"START"|"END"	The position for the SVG-Element relative to the 
 *														specified Shape. "START" and "END" are just using for a Edges, then
 *														the relation is the start or ending Docker of this edge.
 *	
 * 
 **/
if (!ORYX.Plugins) 
    ORYX.Plugins = new Object();

ORYX.Plugins.Overlay = Clazz.extend({

    facade: undefined,
	
	styleNode: undefined,
    
    construct: function(facade){
		
        this.facade = facade;

		this.changes = [];

		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_OVERLAY_SHOW, this.show.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_OVERLAY_HIDE, this.hide.bind(this));	

		this.styleNode = document.createElement('style')
		this.styleNode.setAttributeNS(null, 'type', 'text/css')
		
		document.getElementsByTagName('head')[0].appendChild( this.styleNode )

    },
	
	/**
	 * Show the overlay for specific nodes
	 * @param {Object} options
	 * 
	 * 	String				options.id		- MUST - Define the id of the overlay (is needed for the hiding of this overlay)		
	 *	ORYX.Core.Shape[] 	options.shapes 	- MUST - Define the Shapes for the changes
	 * 	attr-name:value		options.changes	- Defines all the changes which should be shown
	 * 
	 * 
	 */
	show: function( options ){
		
		// Checks if all arguments are available
		if( 	!options || 
				!options.shapes || !options.shapes instanceof Array ||
				!options.id	|| !options.id instanceof String || options.id.length == 0) { 
				
					return
					
		}
		
		//if( this.changes[options.id]){
		//	this.hide( options )
		//}
			

		// Checked if attributes are setted
		if( options.attributes ){
			
			// FOR EACH - Shape
			options.shapes.each(function(el){
				
				// Checks if the node is a Shape
				if( !el instanceof ORYX.Core.Shape){ return }
				
				this.setAttributes( el.node , options.attributes )
				
			}.bind(this))

		}	
		
		var isSVG = true
		try {
			isSVG = options.node && options.node instanceof SVGElement;
		} catch(e){}
		
		// Checks if node is setted and if this is an SVGElement		
		if ( options.node && isSVG) {
			
			options["_temps"] = []
						
			// FOR EACH - Node
			options.shapes.each(function(el, index){
				
				// Checks if the node is a Shape
				if( !el instanceof ORYX.Core.Shape){ return }
				
				var _temp = {}
				_temp.svg = options.dontCloneNode ? options.node : options.node.cloneNode( true );
				
				// Add the svg node to the ORYX-Shape
				el.node.firstChild.appendChild( _temp.svg )		
				
				// If
				if (el instanceof ORYX.Core.Edge && !options.nodePosition) {
					options['nodePosition'] = "START"
				}
						
				// If the node position is setted, it has to be transformed
				if( options.nodePosition ){
					
					var b = el.bounds;
					var p = options.nodePosition.toUpperCase();
										
					// Check the values of START and END
					if( el instanceof ORYX.Core.Node && p == "START"){
						p = "NW";
					} else if(el instanceof ORYX.Core.Node && p == "END"){
						p = "SE";
					} else if(el instanceof ORYX.Core.Edge && p == "START"){
						b = el.getDockers().first().bounds
					} else if(el instanceof ORYX.Core.Edge && p == "END"){
						b = el.getDockers().last().bounds
					}

					// Create a callback for the changing the position 
					// depending on the position string
					_temp.callback = function(){
						
						var x = 0; var y = 0;
						
						if( p == "NW" ){
							// Do Nothing
						} else if( p == "N" ) {
							x = b.width() / 2;
						} else if( p == "NE" ) {
							x = b.width();
						} else if( p == "E" ) {
							x = b.width(); y = b.height() / 2;
						} else if( p == "SE" ) {
							x = b.width(); y = b.height();
						} else if( p == "S" ) {
							x = b.width() / 2; y = b.height();
						} else if( p == "SW" ) {
							y = b.height();
						} else if( p == "W" ) {
							y = b.height() / 2;
						} else if( p == "START" || p == "END") {
							x = b.width() / 2; y = b.height() / 2;
						}						
						else {
							return
						}
						
						if( el instanceof ORYX.Core.Edge){
							x  += b.upperLeft().x ; y  += b.upperLeft().y ;
						}
						
						_temp.svg.setAttributeNS(null, "transform", "translate(" + x + ", " + y + ")")
					
					}.bind(this)
					
					_temp.element = el;
					_temp.callback();
					
					b.registerCallback( _temp.callback );
					
				}
				
				// Show the ghostpoint
				if(options.ghostPoint){
					var point={x:0, y:0};
					point=options.ghostPoint;
					_temp.callback = function(){
						
						var x = 0; var y = 0;
						x = point.x -7;
						y = point.y -7;
						_temp.svg.setAttributeNS(null, "transform", "translate(" + x + ", " + y + ")")
						
					}.bind(this)
					
					_temp.element = el;
					_temp.callback();
					
					b.registerCallback( _temp.callback );
				}
				
				if(options.labelPoint){
					var point={x:0, y:0};
					point=options.labelPoint;
					_temp.callback = function(){
						
						var x = 0; var y = 0;
						x = point.x;
						y = point.y;
						_temp.svg.setAttributeNS(null, "transform", "translate(" + x + ", " + y + ")")
						
					}.bind(this)
					
					_temp.element = el;
					_temp.callback();
					
					b.registerCallback( _temp.callback );
				}
				
				
				options._temps.push( _temp )	
				
			}.bind(this))
			
			
			
		}		
	

		// Store the changes
		if( !this.changes[options.id] ){
			this.changes[options.id] = [];
		}
		
		this.changes[options.id].push( options );
				
	},
	
	/**
	 * Hide the overlay with the spefic id
	 * @param {Object} options
	 */
	hide: function( options ){
		
		// Checks if all arguments are available
		if( 	!options || 
				!options.id	|| !options.id instanceof String || options.id.length == 0 ||
				!this.changes[options.id]) { 
				
					return
					
		}		
		
		
		// Delete all added attributes
		// FOR EACH - Shape
		this.changes[options.id].each(function(option){
			
			option.shapes.each(function(el, index){
				
				// Checks if the node is a Shape
				if( !el instanceof ORYX.Core.Shape){ return }
				
				this.deleteAttributes( el.node )
							
			}.bind(this));

	
			if( option._temps ){
				
				option._temps.each(function(tmp){
					// Delete the added Node, if there is one
					if( tmp.svg && tmp.svg.parentNode ){
						tmp.svg.parentNode.removeChild( tmp.svg )
					}
		
					// If 
					if( tmp.callback && tmp.element){
						// It has to be unregistered from the edge
						tmp.element.bounds.unregisterCallback( tmp.callback )
					}
							
				}.bind(this))
				
			}
		
			
		}.bind(this));

		
		this.changes[options.id] = null;
		
		
	},
	
	
	/**
	 * Set the given css attributes to that node
	 * @param {HTMLElement} node
	 * @param {Object} attributes
	 */
	setAttributes: function( node, attributes ) {
		
		
		// Get all the childs from ME
		var childs = this.getAllChilds( node.firstChild.firstChild )
		
		var ids = []
		
		// Add all Attributes which have relation to another node in this document and concate the pure id out of it
		// This is for example important for the markers of a edge
		childs.each(function(e){ ids.push( $A(e.attributes).findAll(function(attr){ return attr.nodeValue.startsWith('url(#')}) )})
		ids = ids.flatten().compact();
		ids = ids.collect(function(s){return s.nodeValue}).uniq();
		ids = ids.collect(function(s){return s.slice(5, s.length-1)})
		
		// Add the node ID to the id
		ids.unshift( node.id + ' .me')
		
		var attr				= $H(attributes);
        var attrValue			= attr.toJSON().gsub(',', ';').gsub('"', '');
        var attrMarkerValue		= attributes.stroke ? attrValue.slice(0, attrValue.length-1) + "; fill:" + attributes.stroke + ";}" : attrValue;
        var attrTextValue;
        if( attributes.fill ){
            var copyAttr        = Object.clone(attributes);
        	copyAttr.fill		= "black";
        	attrTextValue		= $H(copyAttr).toJSON().gsub(',', ';').gsub('"', '');
        }
                	
        // Create the CSS-Tags Style out of the ids and the attributes
        csstags = ids.collect(function(s, i){return "#" + s + " * " + (!i? attrValue : attrMarkerValue) + "" + (attrTextValue ? " #" + s + " text * " + attrTextValue : "") })
		
		// Join all the tags
		var s = csstags.join(" ") + "\n" 
		
		// And add to the end of the style tag
		this.styleNode.appendChild(document.createTextNode(s));
		
		
	},
	
	/**
	 * Deletes all attributes which are
	 * added in a special style sheet for that node
	 * @param {HTMLElement} node 
	 */
	deleteAttributes: function( node ) {
				
		// Get all children which contains the node id		
		var delEl = $A(this.styleNode.childNodes)
					 .findAll(function(e){ return e.textContent.include( '#' + node.id ) });
		
		// Remove all of them
		delEl.each(function(el){
			el.parentNode.removeChild(el);
		});		
	},
	
	getAllChilds: function( node ){
		
		var childs = $A(node.childNodes)
		
		$A(node.childNodes).each(function( e ){ 
		        childs.push( this.getAllChilds( e ) )
		}.bind(this))

    	return childs.flatten();
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
if (!ORYX.Plugins) 
    ORYX.Plugins = new Object();

ORYX.Plugins.Edit = Clazz.extend({
    
    construct: function(facade){
    
        this.facade = facade;
        this.clipboard = new ORYX.Plugins.Edit.ClipBoard(facade);
        
        this.facade.registerOnEvent(ORYX.CONFIG.EVENT_KEYDOWN, this.keyHandler.bind(this));
        
        var plugin = this;
        
        $('edit_cut').observe('click', function(event) {
            plugin.editCut();
        });
        
        $('edit_copy').observe('click', function(event) {
            plugin.editCopy(true, false);
        });
        
        $('edit_paste').observe('click', function(event) {
            plugin.editPaste();
        });
       
        
        $('edit_delete').observe('click', function(event) {
            plugin.editDelete();
        });
        
        this.facade.offer({
            name: ORYX.I18N.Edit.del,
            description: ORYX.I18N.Edit.delDesc,
            icon: ORYX.PATH + "images/cross.png",
			keyCodes: [{
					metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
					keyCode: 8,
					keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
				},
				{	
					keyCode: 46,
					keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
				}
			],
            functionality: this.callEdit.bind(this, this.editDelete),
            group: ORYX.I18N.Edit.group,
            index: 4,
            minShape: 1
        });
        
        
        this.facade.offer({
         name: ORYX.I18N.Edit.cut,
         description: ORYX.I18N.Edit.cutDesc,
         icon: ORYX.PATH + "images/cut.png",
		 keyCodes: [{
				metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: 88,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
			}
		 ],
         functionality: this.callEdit.bind(this, this.editCut),
         group: ORYX.I18N.Edit.group,
         index: 1,
         minShape: 1
         });
         
        this.facade.offer({
         name: ORYX.I18N.Edit.copy,
         description: ORYX.I18N.Edit.copyDesc,
         icon: ORYX.PATH + "images/page_copy.png",
		 keyCodes: [{
				metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: 67,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
			}
		 ],
         functionality: this.callEdit.bind(this, this.editCopy, [true, false]),
         group: ORYX.I18N.Edit.group,
         index: 2,
         minShape: 1
         });
         
        this.facade.offer({
         name: ORYX.I18N.Edit.paste,
         description: ORYX.I18N.Edit.pasteDesc,
         icon: ORYX.PATH + "images/page_paste.png",
		 keyCodes: [{
				metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: 86,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN
			}
		 ],
         functionality: this.callEdit.bind(this, this.editPaste),
         isEnabled: this.clipboard.isOccupied.bind(this.clipboard),
         group: ORYX.I18N.Edit.group,
         index: 3,
         minShape: 0,
         maxShape: 0
         });
         
    },
	
	callEdit: function(fn, args){
		window.setTimeout(function(){
			fn.apply(this, (args instanceof Array ? args : []));
		}.bind(this), 1);
	},
	
	/**
	 * Handles the mouse down event and starts the copy-move-paste action, if
	 * control or meta key is pressed.
	 */
	handleMouseDown: function(event) {
		if(this._controlPressed) {
			this._controlPressed = false;
			this.editCopy();
			this.editPaste();
			event.forceExecution = true;
			this.facade.raiseEvent(event, this.clipboard.shapesAsJson());
		}
	},
    
    /**
     * The key handler for this plugin. Every action from the set of cut, copy,
     * paste and delete should be accessible trough simple keyboard shortcuts.
     * This method checks whether any event triggers one of those actions.
     *
     * @param {Object} event The keyboard event that should be analysed for
     *     triggering of this plugin.
     */
    // javier key error
    keyHandler: function(event){
        //TODO document what event.which is.
        
        ORYX.Log.debug("edit.js handles a keyEvent.");
        
        // assure we have the current event.
        if (!event) 
            event = window.event;
        
        
        // get the currently pressed key and state of control key.
        var pressedKey = event.which || event.keyCode;
        var ctrlPressed = event.ctrlKey;
        
        // if the object is to be deleted, do so, and return immediately.
        if ((pressedKey == ORYX.CONFIG.KEY_CODE_DELETE) ||
        ((pressedKey == ORYX.CONFIG.KEY_CODE_BACKSPACE) &&
        (event.metaKey || event.appleMetaKey))) {
        
            //ORYX.Log.debug("edit.js deletes the shape.");
            this.editDelete();
            return;
        }
        
         // if control key is not pressed, we're not interested anymore.
         if (!ctrlPressed)
            return;
         
         // when ctrl is pressed, switch trough the possibilities.
         switch (pressedKey) {
         
            // cut.
            case ORYX.CONFIG.KEY_CODE_X:
	         this.editCut();
            break;
	         
            // copy.
	    case ORYX.CONFIG.KEY_CODE_C:
	         this.editCopy();
	    break;
	         
            // paste.
            case ORYX.CONFIG.KEY_CODE_V:
	         this.editPaste();
            break;
         }
    },
    /**
     * Returns a list of shapes which should be considered while copying.
     * Besides the shapes of given ones, edges and attached nodes are added to the result set.
     * If one of the given shape is a child of another given shape, it is not put into the result. 
     */
    getAllShapesToConsider: function(shapes){
        var shapesToConsider = []; // only top-level shapes
        var childShapesToConsider = []; // all child shapes of top-level shapes
        
        shapes.each(function(shape){
            //Throw away these shapes which have a parent in given shapes
            isChildShapeOfAnother = shapes.any(function(s2){
                return s2.hasChildShape(shape);
            });
            if(isChildShapeOfAnother) return;
            
            // This shape should be considered
            shapesToConsider.push(shape);
            // Consider attached nodes (e.g. intermediate events)
            if (shape instanceof ORYX.Core.Node) {
				var attached = shape.getOutgoingNodes();
				attached = attached.findAll(function(a){ return !shapes.include(a) });
                shapesToConsider = shapesToConsider.concat(attached);
            }
            
            childShapesToConsider = childShapesToConsider.concat(shape.getChildShapes(true));
        }.bind(this));
        
        // All edges between considered child shapes should be considered
        // Look for these edges having incoming and outgoing in childShapesToConsider
        var edgesToConsider = this.facade.getCanvas().getChildEdges().select(function(edge){
            // Ignore if already added
            if(shapesToConsider.include(edge)) return false;
            // Ignore if there are no docked shapes
            if(edge.getAllDockedShapes().size() === 0) return false; 
            // True if all docked shapes are in considered child shapes
            return edge.getAllDockedShapes().all(function(shape){
                // Remember: Edges can have other edges on outgoing, that is why edges must not be included in childShapesToConsider
                return shape instanceof ORYX.Core.Edge || childShapesToConsider.include(shape);
            });
        });
        shapesToConsider = shapesToConsider.concat(edgesToConsider);
        
        return shapesToConsider;
    },
    
    /**
     * Performs the cut operation by first copy-ing and then deleting the
     * current selection.
     */
    editCut: function(){
        //TODO document why this returns false.
        //TODO document what the magic boolean parameters are supposed to do.
try {        
        this.editCopy(false, true);
        this.editDelete(true);
} catch(e){ORYX.Log.error(e)}
        return false;
    },
    
    /**
     * Performs the copy operation.
     * @param {Object} will_not_update ??
     */
    editCopy: function( will_update, useNoOffset ){
        var selection = this.facade.getSelection();
        
        //if the selection is empty, do not remove the previously copied elements
        if(selection.length == 0) return;
        
        this.clipboard.refresh(selection, this.getAllShapesToConsider(selection), this.facade.getCanvas().getStencil().stencilSet().namespace(), useNoOffset);

        if( will_update ) this.facade.updateSelection();
    },
    
    /**
     * Performs the paste operation.
     */
    editPaste: function(){
        // Create a new canvas with childShapes 
		//and stencilset namespace to be JSON Import conform
		var canvas = {
            childShapes: this.clipboard.shapesAsJson(),
			stencilset:{
				namespace:this.clipboard.SSnamespace
			}
        }
        // Apply json helper to iterate over json object
        Ext.apply(canvas, ORYX.Core.AbstractShape.JSONHelper);
        
        var childShapeResourceIds =  canvas.getChildShapes(true).pluck("resourceId");
        var outgoings = {};
        // Iterate over all shapes
        canvas.eachChild(function(shape, parent){
            // Throw away these references where referenced shape isn't copied
            shape.outgoing = shape.outgoing.select(function(out){
                return childShapeResourceIds.include(out.resourceId);
            });
			shape.outgoing.each(function(out){
				if (!outgoings[out.resourceId]){ outgoings[out.resourceId] = [] }
				outgoings[out.resourceId].push(shape)
			});
			
            return shape;
        }.bind(this), true, true);
        

        // Iterate over all shapes
        canvas.eachChild(function(shape, parent){
            
        	// Check if there has a valid target
            if(shape.target && !(childShapeResourceIds.include(shape.target.resourceId))){
                shape.target = undefined;
                shape.targetRemoved = true;
            }
    		
    		// Check if the first docker is removed
    		if(	shape.dockers && 
    			shape.dockers.length >= 1 && 
    			shape.dockers[0].getDocker &&
    			((shape.dockers[0].getDocker().getDockedShape() &&
    			!childShapeResourceIds.include(shape.dockers[0].getDocker().getDockedShape().resourceId)) || 
    			!shape.getShape().dockers[0].getDockedShape()&&!outgoings[shape.resourceId])) {
    				
    			shape.sourceRemoved = true;
    		}
			
            return shape;
        }.bind(this), true, true);

		
        // Iterate over top-level shapes
        canvas.eachChild(function(shape, parent){
            // All top-level shapes should get an offset in their bounds
            // Move the shape occording to COPY_MOVE_OFFSET
        	if (this.clipboard.useOffset) {
	            shape.bounds = {
	                lowerRight: {
	                    x: shape.bounds.lowerRight.x + ORYX.CONFIG.COPY_MOVE_OFFSET,
	                    y: shape.bounds.lowerRight.y + ORYX.CONFIG.COPY_MOVE_OFFSET
	                },
	                upperLeft: {
	                    x: shape.bounds.upperLeft.x + ORYX.CONFIG.COPY_MOVE_OFFSET,
	                    y: shape.bounds.upperLeft.y + ORYX.CONFIG.COPY_MOVE_OFFSET
	                }
	            };
        	}
            // Only apply offset to shapes with a target
            if (shape.dockers){
                shape.dockers = shape.dockers.map(function(docker, i){
                    // If shape had a target but the copied does not have anyone anymore,
                    // migrate the relative dockers to absolute ones.
                    if( (shape.targetRemoved === true && i == shape.dockers.length - 1&&docker.getDocker) ||
						(shape.sourceRemoved === true && i == 0&&docker.getDocker)){

                        docker = docker.getDocker().bounds.center();
                    }

					// If it is the first docker and it has a docked shape, 
					// just return the coordinates
				   	if ((i == 0 && docker.getDocker instanceof Function && 
				   		shape.sourceRemoved !== true && (docker.getDocker().getDockedShape() || ((outgoings[shape.resourceId]||[]).length > 0 && (!(shape.getShape() instanceof ORYX.Core.Node) || outgoings[shape.resourceId][0].getShape() instanceof ORYX.Core.Node)))) || 
						(i == shape.dockers.length - 1 && docker.getDocker instanceof Function && 
						shape.targetRemoved !== true && (docker.getDocker().getDockedShape() || shape.target))){
							
						return {
                        	x: docker.x, 
                        	y: docker.y,
                        	getDocker: docker.getDocker
						}
					} else if (this.clipboard.useOffset) {
	                    return {
		                        x: docker.x + ORYX.CONFIG.COPY_MOVE_OFFSET, 
		                        y: docker.y + ORYX.CONFIG.COPY_MOVE_OFFSET,
	                        	getDocker: docker.getDocker
		                    };
				   	} else {
				   		return {
                        	x: docker.x, 
                        	y: docker.y,
                        	getDocker: docker.getDocker
						};
				   	}
                }.bind(this));

            } else if (shape.getShape() instanceof ORYX.Core.Node && shape.dockers && shape.dockers.length > 0 && (!shape.dockers.first().getDocker || shape.sourceRemoved === true || !(shape.dockers.first().getDocker().getDockedShape() || outgoings[shape.resourceId]))){
            	
            	shape.dockers = shape.dockers.map(function(docker, i){
            		
                    if((shape.sourceRemoved === true && i == 0&&docker.getDocker)){
                    	docker = docker.getDocker().bounds.center();
                    }
                    
                    if (this.clipboard.useOffset) {
	            		return {
	                        x: docker.x + ORYX.CONFIG.COPY_MOVE_OFFSET, 
	                        y: docker.y + ORYX.CONFIG.COPY_MOVE_OFFSET,
	                    	getDocker: docker.getDocker
	                    };
                    } else {
	            		return {
	                        x: docker.x, 
	                        y: docker.y,
	                    	getDocker: docker.getDocker
	                    };
                    }
            	}.bind(this));
            }
            
            return shape;
        }.bind(this), false, true);

        this.clipboard.useOffset = true;
        this.facade.importJSON(canvas);
    },
    
    /**
     * Performs the delete operation. No more asking.
     */
    editDelete: function(){
        var selection = this.facade.getSelection();
        
		var shapes = this.getAllShapesToConsider(selection);
		var command = new ORYX.Plugins.Edit.DeleteCommand(shapes, this.facade);
                                       
		this.facade.executeCommands([command]);
    }
}); 

ORYX.Plugins.Edit.ClipBoard = Clazz.extend({
    construct: function(){
        this._shapesAsJson = [];
        this.selection = [];
		this.SSnamespace="";
		this.useOffset=true;
    },
    isOccupied: function(){
        return this.shapesAsJson().length > 0;
    },
    refresh: function(selection, shapes, namespace, useNoOffset){
        this.selection = selection;
        this.SSnamespace=namespace;
        // Store outgoings, targets and parents to restore them later on
        this.outgoings = {};
        this.parents = {};
        this.targets = {};
        this.useOffset = useNoOffset !== true;
        
        this._shapesAsJson = shapes.map(function(shape){
            var s = shape.toJSON();
            s.parent = {resourceId : shape.getParentShape().resourceId};
            s.parentIndex = shape.getParentShape().getChildShapes().indexOf(shape)
            return s;
        });
    },
	shapesAsJson: function() {
		return this._shapesAsJson;
	}
});

ORYX.Plugins.Edit.DeleteCommand = ORYX.Core.Command.extend({
    construct: function(shapes, facade){
	
try {
        this.shapesAsJson       = shapes;
        this.facade             = facade;
ORYX.Log.info("this.shapesAsJson", this.shapesAsJson);        
        // Store dockers of deleted shapes to restore connections
        this.dockers            = this.shapesAsJson.map(function(shape){
//            var shape = shapeAsJson.getShape();
            var incomingDockers = shape.getIncomingShapes().map(function(s){return s.getDockers().last()})
            var outgoingDockers = shape.getOutgoingShapes().map(function(s){return s.getDockers().first()})
            var dockers = shape.getDockers().concat(incomingDockers, outgoingDockers).compact().map(function(docker){
                return {
                    object: docker,
                    referencePoint: docker.referencePoint,
                    dockedShape: docker.getDockedShape()
                };
            });
            return dockers;
        }).flatten();
}catch(e){ORYX.Log.error(e)}

    },          
    execute: function(){
        this.shapesAsJson.each(function(shape){
            // Delete shape
            this.facade.deleteShape(shape); // AsJson.getShape()
        }.bind(this));
        
        this.facade.setSelection([]);
        this.facade.getCanvas().update();		
		this.facade.updateSelection();
        
    },
    rollback: function(){
        this.shapesAsJson.each(function(shape) {
    		var parent = ("undefined" != typeof(shape.parent) ?  this.facade.getCanvas().getChildShapeByResourceId(shape.parent.resourceId) : this.facade.getCanvas());            parent.add(shape, shape.parentIndex);
            parent.add(shape, shape.parentIndex);
        }.bind(this));
        
        //reconnect shapes
        this.dockers.each(function(d) {
            d.object.setDockedShape(d.dockedShape);
            d.object.setReferencePoint(d.referencePoint);
        }.bind(this));
        
        this.facade.setSelection(this.selectedShapes);
        this.facade.getCanvas().update();	
		this.facade.updateSelection();
        
    }
});
/**
 * Copyright (c) 2009
 * Jan-Felix Schwarz
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

ORYX.Plugins.KeysMove = ORYX.Plugins.AbstractPlugin.extend({

    facade: undefined,
    
    construct: function(facade){
    
        this.facade = facade;
        this.copyElements = [];
        
        this.facade.registerOnEvent(ORYX.CONFIG.EVENT_KEYDOWN, this.keyHandler.bind(this));

		// SELECT ALL
		this.facade.offer({
		keyCodes: [{
		 		metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: 65,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.selectAll.bind(this)
         });
		 
		// MOVE LEFT SMALL		
		this.facade.offer({
		keyCodes: [{
		 		metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: ORYX.CONFIG.KEY_CODE_LEFT,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_LEFT, false)
         });
		 
		 // MOVE LEFT
		 this.facade.offer({
		 keyCodes: [{
				keyCode: ORYX.CONFIG.KEY_CODE_LEFT,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_LEFT, true)
         });
		 
		// MOVE RIGHT SMALL	
		 this.facade.offer({
		 keyCodes: [{
		 		metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: ORYX.CONFIG.KEY_CODE_RIGHT,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_RIGHT, false)
         });
		 
		// MOVE RIGHT	
		 this.facade.offer({
		 keyCodes: [{
				keyCode: ORYX.CONFIG.KEY_CODE_RIGHT,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_RIGHT, true)
         });
		 
		// MOVE UP SMALL	
		 this.facade.offer({
		 keyCodes: [{
		 		metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: ORYX.CONFIG.KEY_CODE_UP,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_UP, false)
         });
		 
		// MOVE UP	
		 this.facade.offer({
		 keyCodes: [{
				keyCode: ORYX.CONFIG.KEY_CODE_UP,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_UP, true)
         });
		 
		// MOVE DOWN SMALL	
		 this.facade.offer({
		 keyCodes: [{
		 		metaKeys: [ORYX.CONFIG.META_KEY_META_CTRL],
				keyCode: ORYX.CONFIG.KEY_CODE_DOWN,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_DOWN, false)
         });
		 
		// MOVE DOWN	
		 this.facade.offer({
		 keyCodes: [{
				keyCode: ORYX.CONFIG.KEY_CODE_DOWN,
				keyAction: ORYX.CONFIG.KEY_ACTION_DOWN 
			}
		 ],
         functionality: this.move.bind(this, ORYX.CONFIG.KEY_CODE_DOWN, true)
         });
		 
         
    },
    
	/**
	 * Select all shapes in the editor
	 *
	 */
	selectAll: function(e){
    	Event.stop(e.event);
		this.facade.setSelection(this.facade.getCanvas().getChildShapes(true))
	},
	
	move: function(key, far, e) {
		
    	Event.stop(e.event);

		// calculate the distance to move the objects and get the selection.
		var distance = far? 20 : 5;
		var selection = this.facade.getSelection();
		var currentSelection = this.facade.getSelection();
		var p = {x: 0, y: 0};
		
		// switch on the key pressed and populate the point to move by.
		switch(key) {

			case ORYX.CONFIG.KEY_CODE_LEFT:
				p.x = -1*distance;
				break;
			case ORYX.CONFIG.KEY_CODE_RIGHT:
				p.x = distance;
				break;
			case ORYX.CONFIG.KEY_CODE_UP:
				p.y = -1*distance;
				break;
			case ORYX.CONFIG.KEY_CODE_DOWN:
				p.y = distance;
				break;
		}
		
		// move each shape in the selection by the point calculated and update it.
		selection = selection.findAll(function(shape){ 
			// Check if this shape is docked to an shape in the selection			
			if(shape instanceof ORYX.Core.Node && shape.dockers.length == 1 && selection.include( shape.dockers.first().getDockedShape() )){ 
				return false 
			} 
			
			// Check if any of the parent shape is included in the selection
			var s = shape.parent; 
			do{ 
				if(selection.include(s)){ 
					return false
				}
			}while(s = s.parent); 
			
			// Otherwise, return true
			return true;
			
		});
		
		/* Edges must not be movable, if only edges are selected and at least 
		 * one of them is docked.
		 */
		var edgesMovable = true;
		var onlyEdgesSelected = selection.all(function(shape) {
			if(shape instanceof ORYX.Core.Edge) {
				if(shape.isDocked()) {
					edgesMovable = false;
				}
				return true;	
			}
			return false;
		});
		
		if(onlyEdgesSelected && !edgesMovable) {
			/* Abort moving shapes */
			return;
		}
		
		selection = selection.map(function(shape){ 
			if( shape instanceof ORYX.Core.Node ){
				/*if( shape.dockers.length == 1 ){
					return shape.dockers.first()
				} else {*/
					return shape
				//}
			} else if( shape instanceof ORYX.Core.Edge ) {
				
				var dockers = shape.dockers;
				
				if( selection.include( shape.dockers.first().getDockedShape() ) ){
					dockers = dockers.without( shape.dockers.first() )
				}

				if( selection.include( shape.dockers.last().getDockedShape() ) ){
					dockers = dockers.without( shape.dockers.last() )
				}
				
				return dockers	
							
			} else {
				return null
			}
		
		}).flatten().compact();
		
		if (selection.size() > 0) {
			
			//Stop moving at canvas borders
			var selectionBounds = [ this.facade.getCanvas().bounds.lowerRight().x,
			                        this.facade.getCanvas().bounds.lowerRight().y,
			                        0,
			                        0 ];
			selection.each(function(s) {
				selectionBounds[0] = Math.min(selectionBounds[0], s.bounds.upperLeft().x);
				selectionBounds[1] = Math.min(selectionBounds[1], s.bounds.upperLeft().y);
				selectionBounds[2] = Math.max(selectionBounds[2], s.bounds.lowerRight().x);
				selectionBounds[3] = Math.max(selectionBounds[3], s.bounds.lowerRight().y);
			});
			if(selectionBounds[0]+p.x < 0)
				p.x = -selectionBounds[0];
			if(selectionBounds[1]+p.y < 0)
				p.y = -selectionBounds[1];
			if(selectionBounds[2]+p.x > this.facade.getCanvas().bounds.lowerRight().x)
				p.x = this.facade.getCanvas().bounds.lowerRight().x - selectionBounds[2];
			if(selectionBounds[3]+p.y > this.facade.getCanvas().bounds.lowerRight().y)
				p.y = this.facade.getCanvas().bounds.lowerRight().y - selectionBounds[3];
			
			if(p.x!=0 || p.y!=0) {
				// Instantiate the moveCommand
				var commands = [new ORYX.Core.Command.Move(selection, p, null, currentSelection, this)];
				// Execute the commands			
				this.facade.executeCommands(commands);
			}
			
		}
	},
	
	getUndockedCommant: function(shapes){

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
		
		command = new undockEdgeCommand( shapes );
		command.execute();	
		return command;
	}
	
//    /**
//     * The key handler for this plugin. Every action from the set of cut, copy,
//     * paste and delete should be accessible trough simple keyboard shortcuts.
//     * This method checks whether any event triggers one of those actions.
//     *
//     * @param {Object} event The keyboard event that should be analysed for
//     *     triggering of this plugin.
//     */
//    keyHandler: function(event){
//        //TODO document what event.which is.
//        
//        ORYX.Log.debug("keysMove.js handles a keyEvent.");
//        
//        // assure we have the current event.
//        if (!event) 
//            event = window.event;
//        
//        // get the currently pressed key and state of control key.
//        var pressedKey = event.which || event.keyCode;
//        var ctrlPressed = event.ctrlKey;
//
//		// if the key is one of the arrow keys, forward to move and return.
//		if ([ORYX.CONFIG.KEY_CODE_LEFT, ORYX.CONFIG.KEY_CODE_RIGHT,
//			ORYX.CONFIG.KEY_CODE_UP, ORYX.CONFIG.KEY_CODE_DOWN].include(pressedKey)) {
//			
//			this.move(pressedKey, !ctrlPressed);
//			return;
//		}
//		
//    }
	
});

	
	
ORYX.Plugins.File = Clazz.extend({

    facade: undefined,
	    
    construct: function(facade){
        this.facade = facade;
        /*
        this.facade.offer({
            'name': ORYX.I18N.File.print,
            'functionality': this.print.bind(this),
            'group': ORYX.I18N.File.group,
            'icon': ORYX.PATH + "images/printer.png",
            'description': ORYX.I18N.File.printDesc,
            'index': 3,
            'minShape': 0,
            'maxShape': 0
        });
        */
        /*
        this.facade.offer({
            'name': ORYX.I18N.File.pdf,
            'functionality': this.exportPDF.bind(this),
            'group': ORYX.I18N.File.group,
            'icon': ORYX.PATH + "images/page_white_acrobat.png",
            'description': ORYX.I18N.File.pdfDesc,
            'index': 4,
            'minShape': 0,
            'maxShape': 0
        });
        */
        /*
        this.facade.offer({
            'name': ORYX.I18N.File.info,
            'functionality': this.info.bind(this),
            'group': ORYX.I18N.File.group,
            'icon': ORYX.PATH + "images/information.png",
            'description': ORYX.I18N.File.infoDesc,
            'index': 5,
            'minShape': 0,
            'maxShape': 0
        });
        */
    },
    

    
    info: function(){
    
        var info = '<iframe src="' + ORYX.CONFIG.LICENSE_URL + '" type="text/plain" ' + 
				   'style="border:none;display:block;width:575px;height:460px;"/>' +
				   '\n\n<pre style="display:inline;">Version: </pre>' + 
				   '<iframe src="' + ORYX.CONFIG.VERSION_URL + '" type="text/plain" ' + 
				   'style="border:none;overflow:hidden;display:inline;width:40px;height:20px;"/>';

		this.infoBox = Ext.Msg.show({
		   title: ORYX.I18N.Oryx.title,
		   msg: info,
		   width: 640,
		   maxWidth: 640,
		   maxHeight: 480,
		   buttons: Ext.MessageBox.OK
		});
        
        return false;
    },
    
    exportPDF: function(){
    	
		this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_LOADING_ENABLE, text:ORYX.I18N.File.genPDF});
		
        var resource = location.href;
        
        // Get the serialized svg image source
        var svgClone = this.facade.getCanvas().getSVGRepresentation(true);
        
        var svgDOM = DataManager.serialize(svgClone);
		
        // Send the svg to the server.
        //TODO make this better by using content negotiation instead of format parameter.
        //TODO make this better by generating svg on the server, too.
        new Ajax.Request(ORYX.CONFIG.PDF_EXPORT_URL, {
            method: 'POST',
            parameters: {
                resource: resource,
                data: svgDOM,
                format: "pdf"
            },
            onSuccess: (function(request){
            	this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_LOADING_DISABLE});
				
                // Because the pdf may be opened in the same window as the
                // process, yet the process may not have been saved, we're
                // opening every other representation in a new window.
                // location.href = request.responseText
                window.open(request.responseText);
            }).bind(this),
			onFailure: (function(){
				this.facade.raiseEvent({type:ORYX.CONFIG.EVENT_LOADING_DISABLE});
				
				Ext.Msg.alert(ORYX.I18N.Oryx.title, ORYX.I18N.File.genPDFFailed);
			}).bind(this)
        });
    },
    
    print: function(){
		
		Ext.Msg.show({
		   title		: ORYX.I18N.File.printTitle,
		   msg			: ORYX.I18N.File.printMsg,
		   buttons		: Ext.Msg.YESNO,
		   icon			: Ext.MessageBox.QUESTION,
		   fn			:  function(btn) {
	        
								if (btn == "yes") {
								
									// Set all options for the new window
									var option = $H({
										width: 300,
										height: 400,
										toolbar: "no",
										status: "no",
										menubar: "yes",
										dependent: "yes",
										resizable: "yes",
										scrollbars: "yes"
									});
									
									// Create the new window with all the settings
									var newWindow = window.open("", "PrintWindow", option.invoke('join', '=').join(','));
									
									// Add a style tag to the head and hide all controls
									var head = newWindow.document.getElementsByTagName('head')[0];
									var style = document.createElement("style");
									style.innerHTML = " body {padding:0px; margin:0px} .svgcontainer { display:none; }";
									head.appendChild(style);
									
									// Clone the svg-node and append this to the new body
									newWindow.document.getElementsByTagName('body')[0].appendChild(this.facade.getCanvas().getSVGRepresentation());
									var svg = newWindow.document.getElementsByTagName('body')[0].getElementsByTagName('svg')[0];
									
									// Set the width and height
									svg.setAttributeNS(null, 'width', 1100);
									svg.setAttributeNS(null, 'height', 1400);
									
									// Set the correct size and rotation
									svg.lastChild.setAttributeNS(null, 'transform', 'scale(0.47, 0.47) rotate(270, 1510, 1470)');
									
									var markers = ['marker-start', 'marker-mid', 'marker-end']
									var path = $A(newWindow.document.getElementsByTagName('path'));
									path.each(function(pathNode){
										markers.each(function(marker){
											// Get the marker value
											var url = pathNode.getAttributeNS(null, marker);
											if (!url) {
												return
											};
											
											// Replace the URL and set them new
											url = "url(about:blank#" + url.slice(5);
											pathNode.setAttributeNS(null, marker, url);
										});
									});
									
									// Get the print dialog
									newWindow.print();
									
									return true;
								}
							}.bind(this)
			});
    }   
});


/**
 * Copyright (c) 2006
 * Martin Czuchra, Nicolas Peters, Daniel Polak, Willi Tscheschner, Philipp Berger
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

ORYX.Plugins.Save = Clazz.extend({
	
    facade: undefined,
	
    processURI: undefined,
    SECONDS_BETWEEN_FRAMES: undefined,
    cIndex: 0,
    cXpos: 0,
    cImageTimeout: false,
    isSaving: false,
    
    continueAnimation: function ()
    {
		//24 pixels por frame
		this.cXpos += 24;
		
		this.cIndex += 1;
		 
		//total frames 18
		if (this.cIndex >= 18) {
			this.cXpos =0;
			this.cIndex=0;
		}
		
		document.getElementById('editor_save_icon_id').style.backgroundPosition=(- this.cXpos)+'px 0';
		
                
		setTimeout(this.continueAnimation.bind(this), this.SECONDS_BETWEEN_FRAMES*1000);
    },
    startAnimation: function (callBack)
    {
        //cambia el color
        $('editor_save_icon_id').setStyle({color:'white'});
        $('editor_save_text_id').setStyle({color:'white'});
           
        document.getElementById('editor_save_icon_id').style.backgroundImage='url(' + ORYX.CONFIG.WAIT_ICON + ')';
	document.getElementById('editor_save_icon_id').style.width='24px';
	document.getElementById('editor_save_icon_id').style.height='24px';
		
	//FPS = Math.round(100/(maxSpeed+2-speed));
	var FPS = Math.round(100/9);
	this.SECONDS_BETWEEN_FRAMES = 1 / FPS;
	
        //var func = this.continueAnimation;
        
	setTimeout(this.continueAnimation.bind(this),  this.SECONDS_BETWEEN_FRAMES*1000);
        
        callBack.call();
    },
	
    construct: function(facade){
		this.facade = facade;
		
                var plugin = this;
                /*
                $('editor_save').observe('mouseover', function(event) 
                {
                    if (!plugin.isSaving)
                    {
                        $('editor_save_icon_id').removeClassName('editor_save_in');
                        $('editor_save_icon_id').addClassName('editor_save_over');
                     
                        $('editor_save_icon_id').setStyle({color:'white'});
                        $('editor_save_text_id').setStyle({color:'white'});      
                    }
                     
                });
                
                $('editor_save').observe('mouseout', function(event) 
                {
                     if (!plugin.isSaving)
                     {
                        $('editor_save_icon_id').removeClassName('editor_save_over');
                        $('editor_save_icon_id').addClassName('editor_save_in');
                     
                        $('editor_save_icon_id').setStyle({color:'black'});
                        $('editor_save_text_id').setStyle({color:'black'});      
                     }
                    
                });*/
                
                
                $('editor_save').observe( 'click', function() {
                    plugin.save(facade);
                });
                
               
              
                /*
		this.facade.offer({
			'name': ORYX.I18N.Save.save,
			'functionality': this.save.bind(this,false),
			'group': ORYX.I18N.Save.group,
			'icon': ORYX.PATH + "images/disk.png",
			'description': ORYX.I18N.Save.saveDesc,
			'index': 1,
			'minShape': 0,
			'maxShape': 0
		});
		*/
		
		
		
		window.onbeforeunload = this.onUnLoad.bind(this);
	
		this.changeDifference = 0;
		
		// Register on event for executing commands --> store all commands in a stack		 
		// --> Execute
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_UNDO_EXECUTE, function(){ this.changeDifference++ }.bind(this) );
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_EXECUTE_COMMANDS, function(){ this.changeDifference++ }.bind(this) );
		// --> Rollback
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_UNDO_ROLLBACK, function(){ this.changeDifference-- }.bind(this) );
		
		//TODO very critical for load time performance!!!
		//this.serializedDOM = DataManager.__persistDOM(this.facade);
	},
	
	onUnLoad: function(){

		/*
		if( this.changeDifference !== 0 ){
		
			return ORYX.I18N.Save.unsavedData;
			
		}*/		
		
	},
        
    // Arquematics 2012 
    // by javier Trigueros Martinez de los huertos
    saveSynchronously: function(facade)
    {
		// Reset changes
		this.changeDifference = 0;
		
		// Get the serialized svg image source
        var svgClone 	= facade.getCanvas().getSVGRepresentation(true)
        //, serializeSvgData =  new XMLSerializer().serializeToString(svgClone),
        , serializeSvgData = DataManager.serialize(svgClone)
        , imageData = 'data:image/svg+xml;charset=utf-8,' + arquematics.codec.encodeURIData(serializeSvgData);
        
        this.serializedDOM = Ext.encode(facade.getJSON());
		
		// Check if this is the NEW URL
       	var data = this.serializedDOM;
		// Send the request out
        
		this.sendSaveRequest( 
                            ORYX.CONFIG.SAVE, 
                            {'json': data,
                             'image': imageData
                            },
                            data);
			
		
    },
	
    sendSaveRequest: function(url, params, data)
    {
        var that = this;
        
        try
         {
                   var dataJsonEval = JSON.parse(data) 
                   , titleText =  $j.trim($j('#note_title').val())
                   , $cmdBtn = $j('#editor_save')
                   , $controlGroup = $j('#note_title').parents('.input-group')
                   , $cmdBtnSaveText = $cmdBtn.find('.save-btn-text');
                   
                   
                   if ((titleText.length === 0)
                    && (dataJsonEval.childShapes.length === 0))
                   {
                      $j('body').addClass('loading');
                      
                      window.location = ORYX.CONFIG.REDIR;  
                   }
                   else if ((dataJsonEval.childShapes.length > 0)
                            && (titleText.length > 0))
                   {
                        $cmdBtn.addClass('disabled');
                        $controlGroup.removeClass('has-error');
                        $cmdBtnSaveText.text($cmdBtnSaveText.data('text-saving'));
             
                       var $form = $j('#form-diagram'), 
                          callBack = function (formData) { 
                           
                           $j.ajax({
                            type: (ORYX.CONFIG.autoload)?"PUT":"POST",
                            url: $form.attr('action'),
                            datatype: "json",
                            data: formData,
                            cache: false,
                            success: function(dataJSON)
                            {
                                //TODO: mirar esto mejor
                                window.location = ORYX.CONFIG.REDIR;
                            },
                           statusCode: {
                                404: function() {
                                   
                                    $j('body').removeClass('loading');
                                   
                                    that.facade.raiseEvent({
                                                type: ORYX.CONFIG.EVENT_LOADING_DISABLE
                                         });

                                         Ext.Msg.alert(ORYX.I18N.Oryx.title, ORYX.I18N.Save.failed);
                                         window.location = ORYX.CONFIG.REDIR;
                       
                                },
                               500: function() {
                                   $j('body').removeClass('loading');
                                   
                                    that.facade.raiseEvent({
                                                type: ORYX.CONFIG.EVENT_LOADING_DISABLE
                                         });

                                         Ext.Msg.alert(ORYX.I18N.Oryx.title, ORYX.I18N.Save.failed);
                                         window.location = ORYX.CONFIG.REDIR;
                                }
                            },
                           error: function(dataJSON)
                           {
                               $j('body').removeClass('loading');
                               
                               that.facade.raiseEvent({
                                                type: ORYX.CONFIG.EVENT_LOADING_DISABLE
                                         });

                               Ext.Msg.alert(ORYX.I18N.Oryx.title, ORYX.I18N.Save.failed);
                               window.location = ORYX.CONFIG.REDIR;
                           }
                        }); 
                       };
                       

                       if (arquematics.crypt)
                       {
                            var pass = (!ORYX.CONFIG.PASS)?arquematics.utils.randomKeyString(50):ORYX.CONFIG.PASS;
                            
                           
                          	var formData = {
                                        //en el editor nunca se comparte
                                        "note[share]"            :0,
                                        "note[trash]"            :$j('#note_trash').val(),
                                        "note[is_favorite]"      :$j('#note_is_favorite').val(),
                                        "note[pass]"             :pass,
                                        "note[title]"            :arquematics.simpleCrypt.encryptBase64(pass ,titleText),
                                        "note[_csrf_token]"      :$j('#note__csrf_token').val(),
                                        "note[type]"             :ORYX.CONFIG.DIAGRAM_TYPE,
                                        "note[data_image]"       :arquematics.simpleCrypt.encryptBase64(pass , params.image),
                                        "note[content]"          :arquematics.simpleCrypt.encryptBase64(pass , params.json)
                                    };
                                    
                            $j('body').addClass('loading');

                            arquematics.utils.encryptDataAndSend(formData, callBack, 'note[pass]');
                       }
                       else
                       {
                           
                          $j('#note_data_image').val(imageData);
                          $j('#note_content').val( params.json);
                          $j('#note_type').val(ORYX.CONFIG.DIAGRAM_TYPE);
                         
                          $j('body').addClass('loading');
                          
                          arquematics.utils.prepareFormAndSend($form, callBack);
                       }
                   }
                   else if (titleText.length <= 0)
                   {
                        $cmdBtnSaveText.text($cmdBtnSaveText.data('text'));
                        $cmdBtn.removeClass('disabled');
                        $controlGroup.addClass('has-error');
                        
                        $j('#note_title').focus();
                        
                        $j('body').addClass('loading');
                        
                        that.isSaving = false;
                     	window.location = ORYX.CONFIG.REDIR; 
                     //errores
                   }
                }
                catch (Err)
                {
                   $j('body').removeClass('loading');
                    
                   window.location = ORYX.CONFIG.REDIR;  
                }		
    },
   
    
    /**
     * Saves the current process to the server.
     */
    save: function(facade){
        
        var that = this;
        
        if (!this.isSaving)
        {
           this.isSaving = true;
           
           that.saveSynchronously(facade);
        }
        return true;
    }	
});
	
	
	


/**
 * Method to load model or create new one
 * (moved from editor handler)
 */
window.onOryxResourcesLoaded = function() {
	
	if (location.hash.slice(1).length == 0 || location.hash.slice(1).indexOf('new')!=-1) {
		var stencilset = ORYX.Utils.getParamFromUrl('stencilset') || ORYX.CONFIG.SSET; // || "stencilsets/bpmn2.0/bpmn2.0.json";
		
		new ORYX.Editor({
			id: 'oryx-canvas123',
			stencilset: {
				url: ORYX.PATH + stencilset
			}
		});
	} else {
		ORYX.Editor.createByUrl(
			"/diagram/save" + location.hash.slice(1).replace(/\/*$/,"/").replace(/^\/*/,"/")+'json',
			{
				id: 'oryx-canvas123',
				onFailure: function(transport) {
		    	  if (403 == transport.status) {
		    		  Ext.Msg.show({
		                  title:'Authentication Failed',
		                  msg: 'You may not have access rights for this model, maybe you forgot to <a href="'+ORYX.CONFIG.WEB_URL+'/diagram/save/repository">log in</a>?',
		                  icon: Ext.MessageBox.WARNING,
		                  closeable: false,
		                  closable: false
		              });
		    	  }
		    	  else if (404 == transport.status) {
		    		  Ext.Msg.show({
		                  title:'Not Found',
		                  msg: 'The model you requested could not be found.',
		                  icon: Ext.MessageBox.WARNING,
		                  closeable: false,
		                  closable: false
		              });
		    	  }
		    	  else {
		    		  Ext.Msg.show({
		                  title:'Internal Error',
		                  msg: 'We\'re sorry, the model cannot be loaded due to an internal error',
		                  icon: Ext.MessageBox.WARNING,
		                  closeable: false,
		                  closable: false
		              });
				  }
			  }
			}
		);
  	}
};
/**
 * Copyright (c) 2009
 * Sven Wagner-Boysen
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
   @namespace Oryx name space for plugins
   @name ORYX.Plugins
*/
 if(!ORYX.Plugins)
	ORYX.Plugins = new Object();
	

/**
 * This plugin provides methods to layout elements that typically contain 
 * a bunch of child elements, such as subprocesses or lanes.
 * 
 * @class ORYX.Plugins.ContainerLayouter
 * @extends ORYX.Plugins.AbstractPlugin
 * @param {Object} facade
 * 		The facade of the Editor
 */
ORYX.Plugins.ContainerLayouter = {

	/**
	 *	Constructor
	 *	@param {Object} Facade: The Facade of the Editor
	 */
	construct: function(facade){
		this.facade = facade;

		// this does NOT work, because lanes and pools are loaded at start and initialized with a default size
		// if the lane was saved and had a bigger size, the dockers/edges will be corrupted, because the first 
		// positioning is handled as a resize event which triggers the layout with incorrect oldBounds!
		
		//this.facade.registerOnEvent('layout.container.minBounds', 
		//							this.handleLayoutContainerMinBounds.bind(this));
		//this.facade.registerOnEvent('layout.container.dockers', 
		//							this.handleLayoutContainerDockers.bind(this));
		
		this.hashedContainers = new Hash();
	},
	
	handleLayoutContainerDockers: function(event) {
		var sh = event.shape;
		
		if (!this.hashedContainers[sh.resourceId]) {
			this.hashedContainers[sh.resourceId] = sh.bounds.clone();
			return;
		}
		
		var offset = sh.bounds.upperLeft();
		offset.x -= this.hashedContainers[sh.resourceId].upperLeft().x;
		offset.y -= this.hashedContainers[sh.resourceId].upperLeft().y;
		
		this.hashedContainers[sh.resourceId] = sh.bounds.clone();
		
		this.moveChildDockers(sh, offset);
	},
	
	/**
	 * 
	 * 
	 * @param {Object} event
	 * 		The layout event object
	 */
	handleLayoutContainerMinBounds: function(event) {
		var shape = event.shape;
		var topOffset = event.topOffset;
		var oldBounds = shape._oldBounds;
		var options = event.options;
		var ignoreList = (options.ignoreChildsWithId ? options.ignoreChildsWithId : new Array());
		
		var childsBounds = this.retrieveChildsIncludingBounds(shape, ignoreList);
		if(!childsBounds) {return;}
		
		/* Get the upper left child shape */
		var ulShape = this.getChildShapesWithout(shape, ignoreList).find(function(node) {
			return childsBounds.upperLeft().y == node.bounds.upperLeft().y;
		});
		
		/* Ensure minimum size of the container */
		if(this.ensureContainersMinimumSize(shape, childsBounds, ulShape.absoluteBounds(), ignoreList, options)) {
			return;
		}
		
		
		var childsUl = childsBounds.upperLeft();
		var childsLr = childsBounds.lowerRight();
		var bottomTopSpaceRatio = (childsUl.y ? childsUl.y : 1) / ((oldBounds.height() - childsLr.y) ? (oldBounds.height() - childsLr.y) : 1);
		
		var newYValue = (bottomTopSpaceRatio * (shape.bounds.height() - childsBounds.height())) / (1 + bottomTopSpaceRatio);
		
		this.getChildShapesWithout(shape, ignoreList).each(function(childShape){
			var innerOffset = childShape.bounds.upperLeft().y - childsUl.y;
			childShape.bounds.moveTo({	x: childShape.bounds.upperLeft().x,	
										y: newYValue + innerOffset});
		});
		
		/* Calculate adjustment for dockers */
		var yAdjustment = ulShape.bounds.upperLeft().y - ulShape._oldBounds.upperLeft().y;
		
		/* Move docker by adjustment */
		this.moveChildDockers(shape, {x: 0, y: yAdjustment});
	},
	
	/**
	 * Ensures that the container has a minimum height and width to place all
	 * child elements inside.
	 * 
	 * @param {Object} shape
	 * 		The container.
	 * @param {Object} childsBounds
	 * 		The bounds including all children
	 * @param {Object} ulChildAbsBounds
	 * 		The absolute bounds including all children
	 */
	ensureContainersMinimumSize: function(shape, childsBounds, ulChildAbsBounds, ignoreList, options) {
		var bounds = shape.bounds;
		var ulShape = bounds.upperLeft();
		var lrShape = bounds.lowerRight();
		var ulChilds = childsBounds.upperLeft();
		var lrChilds = childsBounds.lowerRight();
		var absBounds = shape.absoluteBounds();
		if(!options) {
			options = new Object();
		}
		
		if(!shape.isResized) {
			/* Childs movement after widening the conatiner */
			var yMovement = 0;
			var xMovement = 0;
			var changeBounds = false;
			
			/* Widen the shape by the child bounds */
			var ulx = ulShape.x;
			var uly = ulShape.y;
			var lrx = lrShape.x;
			var lry = lrShape.y;
			
			if(ulChilds.x < 0) {
				ulx += ulChilds.x;
				xMovement -= ulChilds.x;
				changeBounds = true;
			}
			
			if(ulChilds.y < 0) {
				uly += ulChilds.y;
				yMovement -= ulChilds.y;
				changeBounds = true;
			}
			
			var xProtrusion = xMovement + ulChilds.x + childsBounds.width()
								- bounds.width();
			if(xProtrusion > 0) {
				lrx += xProtrusion;
				changeBounds = true;
			}
			
			var yProtrusion = yMovement + ulChilds.y + childsBounds.height()
								- bounds.height();
			if(yProtrusion > 0) {
				lry += yProtrusion;
				changeBounds = true;
			}
			
			bounds.set(ulx, uly, lrx, lry);
			
			/* Update hashed bounds for docker positioning */
			if(changeBounds) {
				this.hashedContainers[shape.resourceId] = bounds.clone();
			}
			
			this.moveChildsBy(shape, {x: xMovement, y: yMovement}, ignoreList);
			
			/* Signals that children are already move to correct position */
			return true;
		}
		
		/* Resize container to minimum size */
		
		var ulx = ulShape.x;
		var uly = ulShape.y;
		var lrx = lrShape.x;
		var lry = lrShape.y;
		changeBounds = false;
			
		/* Ensure height */
		if(bounds.height() < childsBounds.height()) {
			/* Shape was resized on upper left in height */
			if(ulShape.y != shape._oldBounds.upperLeft().y &&
				lrShape.y == shape._oldBounds.lowerRight().y) {
				uly = lry - childsBounds.height() - 1;	
				if(options.fixedY) {
					uly -= childsBounds.upperLeft().y;
				}
				changeBounds = true;
			} 
			/* Shape was resized on lower right in height */
			else if(ulShape.y == shape._oldBounds.upperLeft().y &&
				lrShape.y != shape._oldBounds.lowerRight().y) {
				lry = uly + childsBounds.height() + 1;	
				if(options.fixedY) {
					lry += childsBounds.upperLeft().y;
				}
				changeBounds = true;
			} 
			/* Both upper left and lower right changed */
			else if(ulChildAbsBounds) {
				var ulyDiff = absBounds.upperLeft().y - ulChildAbsBounds.upperLeft().y;
				var lryDiff = absBounds.lowerRight().y - ulChildAbsBounds.lowerRight().y;
				uly -= ulyDiff;
				lry -= lryDiff;
				uly--;
				lry++;
				changeBounds = true;
			}
		}
		
		/* Ensure width */
		if(bounds.width() < childsBounds.width()) {
			/* Shape was resized on upper left in height */
			if(ulShape.x != shape._oldBounds.upperLeft().x &&
				lrShape.x == shape._oldBounds.lowerRight().x) {
				ulx = lrx - childsBounds.width() - 1;
				if(options.fixedX) {
					ulx -= childsBounds.upperLeft().x;
				}	
				changeBounds = true;
			} 
			/* Shape was resized on lower right in height */
			else if(ulShape.x == shape._oldBounds.upperLeft().x &&
				lrShape.x != shape._oldBounds.lowerRight().x) {
				lrx = ulx + childsBounds.width() + 1;
				if(options.fixedX) {
					lrx += childsBounds.upperLeft().x;
				}	
				changeBounds = true;
			} 
			/* Both upper left and lower right changed */
			else if(ulChildAbsBounds) {
				var ulxDiff = absBounds.upperLeft().x - ulChildAbsBounds.upperLeft().x;
				var lrxDiff = absBounds.lowerRight().x - ulChildAbsBounds.lowerRight().x;
				ulx -= ulxDiff;
				lrx -= lrxDiff;
				ulx--;
				lrx++;
				changeBounds = true;
			}
		}
		
		/* Set minimum bounds */
		bounds.set(ulx, uly, lrx, lry);
		if(changeBounds) {
			//this.hashedContainers[shape.resourceId] = bounds.clone();
			this.handleLayoutContainerDockers({shape:shape});
		}
	},
	
	/**
	 * Moves all child shapes and related dockers of the container shape by the 
	 * relative move point.
	 * 
	 * @param {Object} shape
	 * 		The container shape
	 * @param {Object} relativeMovePoint
	 * 		The point that defines the movement
	 */
	moveChildsBy: function(shape, relativeMovePoint, ignoreList) {
		if(!shape || !relativeMovePoint) {
			return;
		}
		
		/* Move child shapes */
		this.getChildShapesWithout(shape, ignoreList).each(function(child) {
			child.bounds.moveBy(relativeMovePoint);
		});
		
		/* Move related dockers */
		//this.moveChildDockers(shape, relativeMovePoint);
	},
	
	/**
	 * Retrieves the absolute bounds that include all child shapes.
	 * 
	 * @param {Object} shape
	 */
	getAbsoluteBoundsForChildShapes: function(shape) {
//		var childsBounds = this.retrieveChildsIncludingBounds(shape);
//		if(!childsBounds) {return undefined}
//		
//		var ulShape = shape.getChildShapes(false).find(function(node) {
//			return childsBounds.upperLeft().y == node.bounds.upperLeft().y;
//		});
//		
////		var lrShape = shape.getChildShapes(false).find(function(node) {
////			return childsBounds.lowerRight().y == node.bounds.lowerRight().y;
////		});
//		
//		var absUl = ulShape.absoluteBounds().upperLeft();
//		
//		this.hashedContainers[shape.getId()].childsBounds = 
//						new ORYX.Core.Bounds(absUl.x, 
//											absUl.y,
//											absUl.x + childsBounds.width(),
//											absUl.y + childsBounds.height());
//		
//		return this.hashedContainers[shape.getId()];
	},
	
	/**
	 * Moves the docker when moving shapes.
	 * 
	 * @param {Object} shape
	 * @param {Object} offset
	 */
	moveChildDockers: function(shape, offset){
		
		if (!offset.x && !offset.y) {
			return;
		} 
		
		// Get all nodes
		shape.getChildNodes(true)
			// Get all incoming and outgoing edges
			.map(function(node){
				return [].concat(node.getIncomingShapes())
						.concat(node.getOutgoingShapes())
			})
			// Flatten all including arrays into one
			.flatten()
			// Get every edge only once
			.uniq()
			// Get all dockers
			.map(function(edge){
				return edge.dockers.length > 2 ? 
						edge.dockers.slice(1, edge.dockers.length-1) : 
						[];
			})
			// Flatten the dockers lists
			.flatten()
			.each(function(docker){
				docker.bounds.moveBy(offset);
			})
	},
	
	/**
	 * Calculates the bounds that include all child shapes of the given shape.
	 * 
	 * @param {Object} shape
	 * 		The parent shape.
	 */
	retrieveChildsIncludingBounds: function(shape, ignoreList) {
		var childsBounds = undefined;
		this.getChildShapesWithout(shape, ignoreList).each(function(childShape, i) {
			if(i == 0) {
				/* Initialize bounds that include all direct child shapes of the shape */
				childsBounds = childShape.bounds.clone();
				return;
			}
			
			/* Include other child elements */
			childsBounds.include(childShape.bounds);			
		});
		
		return childsBounds;
	},
	
	/**
	 * Returns the direct child shapes that are not on the ignore list.
	 */
	getChildShapesWithout: function(shape, ignoreList) {
		var childs = shape.getChildShapes(false);
		return childs.findAll(function(child) {
					return !ignoreList.member(child.getStencil().id());				
				});
	}
}

ORYX.Plugins.ContainerLayouter = ORYX.Plugins.AbstractPlugin.extend(ORYX.Plugins.ContainerLayouter);
/**
 * Copyright (c) 2009
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

if(!ORYX.Plugins) { ORYX.Plugins = {} }
if(!ORYX.Plugins.Layouter) { ORYX.Plugins.Layouter = {} }

new function(){
	
	/**
	 * Edge layouter is an implementation to layout an edge
	 * @class ORYX.Plugins.Layouter.EdgeLayouter
	 * @author Willi Tscheschner
	 */
	ORYX.Plugins.Layouter.EdgeLayouter = ORYX.Plugins.AbstractLayouter.extend({
		
		/**
		 * Layout only Edges
		 */
		layouted : [	"http://b3mn.org/stencilset/bpmn1.1#SequenceFlow", 
						"http://b3mn.org/stencilset/bpmn1.1#MessageFlow",
						"http://b3mn.org/stencilset/bpmn2.0#MessageFlow",
						"http://b3mn.org/stencilset/bpmn2.0#SequenceFlow", 
						"http://b3mn.org/stencilset/bpmn2.0conversation#ConversationLink",
						"http://b3mn.org/stencilset/epc#ControlFlow",
						"http://www.signavio.com/stencilsets/processmap#ProcessLink",
						"http://www.signavio.com/stencilsets/organigram#connection"],
		
		/**
		 * Layout a set on edges
		 * @param {Object} edges
		 */
		layout: function(edges){
			edges.each(function(edge){
				this.doLayout(edge)
			}.bind(this))
		},
		
		/**
		 * Layout one edge
		 * @param {Object} edge
		 */
		doLayout: function(edge){
			// Get from and to node
			var from 	= edge.getIncomingNodes()[0]; 
			var to 		= edge.getOutgoingNodes()[0];
			
			// Return if one is null
			if (!from || !to) { return }
			
			var positions = this.getPositions(from, to, edge);
		
			if (positions.length > 0){
				this.setDockers(edge, positions[0].a, positions[0].b);
			}
				
		},
		
		/**
		 * Returns a set on positions which are not containt either 
		 * in the bounds in from or to.
		 * @param {Object} from Shape where the edge is come from
		 * @param {Object} to Shape where the edge is leading to
		 * @param {Object} edge Edge between from and to
		 */
		getPositions : function(from, to, edge){
			
			// Get absolute bounds
			var ab = from.absoluteBounds();
			var bb = to.absoluteBounds();
			
			// Get center from and to
			var a = ab.center();
			var b = bb.center();
			
			var am = ab.midPoint();
			var bm = bb.midPoint();
		
			// Get first and last reference point
			var first = Object.clone(edge.dockers.first().referencePoint);
			var last = Object.clone(edge.dockers.last().referencePoint);
			// Get the absolute one
			var aFirst = edge.dockers.first().getAbsoluteReferencePoint();
			var aLast = edge.dockers.last().getAbsoluteReferencePoint(); 
			
			// IF ------>
			// or  |
			//     V
			// Do nothing
			if (Math.abs(aFirst.x-aLast.x) < 1 || Math.abs(aFirst.y-aLast.y) < 1) {
				return []
			}
			
			// Calc center position, between a and b
			// depending on there weight
			var m = {}
			m.x = a.x < b.x ? 
					(((b.x - bb.width()/2) - (a.x + ab.width()/2))/2) + (a.x + ab.width()/2): 
					(((a.x - ab.width()/2) - (b.x + bb.width()/2))/2) + (b.x + bb.width()/2);

			m.y = a.y < b.y ? 
					(((b.y - bb.height()/2) - (a.y + ab.height()/2))/2) + (a.y + ab.height()/2): 
					(((a.y - ab.height()/2) - (b.y + bb.height()/2))/2) + (b.y + bb.height()/2);
								
								
			// Enlarge both bounds with 10
			ab.widen(5); // Wide the from less than 
			bb.widen(20);// the to because of the arrow from the edge
								
			var positions = [];
			var off = this.getOffset.bind(this);
			
			// Checks ----+
			//            |
			//            V
			if (!ab.isIncluded(b.x, a.y)&&!bb.isIncluded(b.x, a.y)) {
				positions.push({
					a : {x:b.x+off(last,bm,"x"),y:a.y+off(first,am,"y")},
					z : this.getWeight(from, a.x < b.x ? "r" : "l", to, a.y < b.y ? "t" : "b", edge)
				});
			}
						
			// Checks | 
			//        +--->
			if (!ab.isIncluded(a.x, b.y)&&!bb.isIncluded(a.x, b.y)) {
				positions.push({
					a : {x:a.x+off(first,am,"x"),y:b.y+off(last,bm,"y")},
					z : this.getWeight(from, a.y < b.y ? "b" : "t", to, a.x < b.x ? "l" : "r", edge)
				});
			}
						
			// Checks  --+
			//           |
			//           +--->
			if (!ab.isIncluded(m.x, a.y)&&!bb.isIncluded(m.x, b.y)) {
				positions.push({
					a : {x:m.x,y:a.y+off(first,am,"y")},
					b : {x:m.x,y:b.y+off(last,bm,"y")},
					z : this.getWeight(from, "r", to, "l", edge, a.x > b.x)
				});
			}
			
			// Checks | 
			//        +---+
			//            |
			//            V
			if (!ab.isIncluded(a.x, m.y)&&!bb.isIncluded(b.x, m.y)) {
				positions.push({
					a : {x:a.x+off(first,am,"x"),y:m.y},
					b : {x:b.x+off(last,bm,"x"),y:m.y},
					z : this.getWeight(from, "b", to, "t", edge, a.y > b.y)
				});
			}	
			
			// Sort DESC of weights
			return positions.sort(function(a,b){ return a.z < b.z ? 1 : (a.z == b.z ? -1 : -1)});
		},
		
		/**
		 * Returns a offset for the pos to the center of the bounds
		 * 
		 * @param {Object} val
		 * @param {Object} pos2
		 * @param {String} dir Direction x|y
		 */
		getOffset: function(pos, pos2, dir){
			return pos[dir] - pos2[dir];
		},
		
		/**
		 * Returns a value which shows the weight for this configuration
		 * 
		 * @param {Object} from Shape which is coming from
		 * @param {String} d1 Direction where is goes
		 * @param {Object} to Shape which goes to
		 * @param {String} d2 Direction where it comes to
		 * @param {Object} edge Edge between from and to
		 * @param {Boolean} reverse Reverse the direction (e.g. "r" -> "l")
		 */
		getWeight: function(from, d1, to, d2, edge, reverse){
			
			d1 = (d1||"").toLowerCase();
			d2 = (d2||"").toLowerCase();
			
			if (!["t","r","b","l"].include(d1)){ d1 = "r"}
			if (!["t","r","b","l"].include(d2)){ d1 = "l"}
			
			// If reverse is set
			if (reverse) {
				// Reverse d1 and d2
				d1 = d1=="t"?"b":(d1=="r"?"l":(d1=="b"?"t":(d1=="l"?"r":"r")))
				d2 = d2=="t"?"b":(d2=="r"?"l":(d2=="b"?"t":(d2=="l"?"r":"r")))
			}
			
					
			var weight = 0;
			// Get rules for from "out" and to "in"
			var dr1 = this.facade.getRules().getLayoutingRules(from, edge)["out"];
			var dr2 = this.facade.getRules().getLayoutingRules(to, edge)["in"];

			var fromWeight = dr1[d1];
			var toWeight = dr2[d2];


			/**
			 * Return a true if the center 1 is in the same direction than center 2
			 * @param {Object} direction
			 * @param {Object} center1
			 * @param {Object} center2
			 */
			var sameDirection = function(direction, center1, center2){
				switch(direction){
					case "t": return Math.abs(center1.x - center2.x) < 2 && center1.y < center2.y
					case "r": return center1.x > center2.x && Math.abs(center1.y - center2.y) < 2
					case "b": return Math.abs(center1.x - center2.x) < 2 && center1.y > center2.y
					case "l": return center1.x < center2.x && Math.abs(center1.y - center2.y) < 2
					default: return false;
				}
			}

			// Check if there are same incoming edges from 'from'
			var sameIncomingFrom = from
								.getIncomingShapes()
								.findAll(function(a){ return a instanceof ORYX.Core.Edge})
								.any(function(e){ 
									return sameDirection(d1, e.dockers[e.dockers.length-2].bounds.center(), e.dockers.last().bounds.center());
								});

			// Check if there are same outgoing edges from 'to'
			var sameOutgoingTo = to
								.getOutgoingShapes()
								.findAll(function(a){ return a instanceof ORYX.Core.Edge})
								.any(function(e){ 
									return sameDirection(d2, e.dockers[1].bounds.center(), e.dockers.first().bounds.center());
								});
			
			// If there are equivalent edges, set 0
			//fromWeight = sameIncomingFrom ? 0 : fromWeight;
			//toWeight = sameOutgoingTo ? 0 : toWeight;
			
			// Get the sum of "out" and the direction plus "in" and the direction 						
			return (sameIncomingFrom||sameOutgoingTo?0:fromWeight+toWeight);
		},
		
		/**
		 * Removes all current dockers from the node 
		 * (except the start and end) and adds two new
		 * dockers, on the position a and b.
		 * @param {Object} edge
		 * @param {Object} a
		 * @param {Object} b
		 */
		setDockers: function(edge, a, b){
			if (!edge){ return }
			
			// Remove all dockers (implicit,
			// start and end dockers will not removed)
			edge.dockers.each(function(r){
				edge.removeDocker(r);
			});
			
			// For a and b (if exists), create
			// a new docker and set position
			[a, b].compact().each(function(pos){
				var docker = edge.createDocker(undefined, pos);
				docker.bounds.centerMoveTo(pos);
			});
			
			// Update all dockers from the edge
			edge.dockers.each(function(docker){
				docker.update()
			})
			
			// Update edge
			//edge.refresh();
			edge._update(true);
			
		}
	});
	
	
}()

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


if(!ORYX.Plugins) {
	ORYX.Plugins = new Object();
}

ORYX.Plugins.Toolbar = Clazz.extend({

	facade: undefined,
	plugs:	[],

	construct: function(facade, ownPluginData) {
		this.facade = facade;
		
		this.groupIndex = new Hash();
		ownPluginData.properties.each((function(value){
			if(value.group && value.index != undefined) {
				this.groupIndex[value.group] = value.index
			}
		}).bind(this));
		
		Ext.QuickTips.init();

		this.buttons = [];
                this.facade.registerOnEvent(ORYX.CONFIG.EVENT_BUTTON_UPDATE, this.onButtonUpdate.bind(this));
                this.facade.registerOnEvent(ORYX.CONFIG.EVENT_STENCIL_SET_LOADED, this.onSelectionChanged.bind(this));
                this.facade.registerOnEvent(ORYX.CONFIG.EVENT_WINDOW_FOCUS, this.onSelectionChanged.bind(this));

                Event.observe(window, "focus", function(event) {
                    this.facade.raiseEvent({type: ORYX.CONFIG.EVENT_WINDOW_FOCUS}, null);
                }.bind(this));
	},
    
    /**
     * Can be used to manipulate the state of a button.
     * @example
     * this.facade.raiseEvent({
     *   type: ORYX.CONFIG.EVENT_BUTTON_UPDATE,
     *   id: this.buttonId, // have to be generated before and set in the offer method
     *   pressed: true
     * });
     * @param {Object} event
     */
    onButtonUpdate: function(event){
        var button = this.buttons.find(function(button){
            return button.id === event.id;
        });
        
        if(event.pressed !== undefined){
            button.buttonInstance.toggle(event.pressed);
        }
    },

	registryChanged: function(pluginsData) {
        // Sort plugins by group and index
		var newPlugs =  pluginsData.sortBy((function(value) {
			return ((this.groupIndex[value.group] != undefined ? this.groupIndex[value.group] : "" ) + value.group + "" + value.index).toLowerCase();
		}).bind(this));
		var plugs = $A(newPlugs).findAll(function(value){
										return !this.plugs.include( value )
									}.bind(this));
		if(plugs.length<1)
			return;

		this.buttons = [];

		ORYX.Log.trace("Creating a toolbar.")
		if(!this.toolbar){
			this.toolbar = new Ext.ux.SlicedToolbar({
			height: 24
		});
                    var region = this.facade.addToRegion("north", this.toolbar, "Toolbar");
		}
		
		
		var currentGroupsName = this.plugs.last()?this.plugs.last().group:plugs[0].group;
        
        // Map used to store all drop down buttons of current group
        var currentGroupsDropDownButton = {};

		
		plugs.each((function(value) {
			if(!value.name) {return}
			this.plugs.push(value);
            // Add seperator if new group begins
			if(currentGroupsName != value.group) {
			    this.toolbar.add('-');
				currentGroupsName = value.group;
                currentGroupsDropDownButton = {};
			}
			//add eventtracking
			var tmp = value.functionality;
			value.functionality = function(){
				 if ("undefined" != typeof(pageTracker) && "function" == typeof(pageTracker._trackEvent) )
				 {
					pageTracker._trackEvent("ToolbarButton",value.name)
				}
				return tmp.apply(this, arguments);

			}
            // If an drop down group icon is provided, a split button should be used
            if(value.dropDownGroupIcon){
                var splitButton = currentGroupsDropDownButton[value.dropDownGroupIcon];
                
                // Create a new split button if this is the first plugin using it 
                if(splitButton === undefined){
                    splitButton = currentGroupsDropDownButton[value.dropDownGroupIcon] = new Ext.Toolbar.SplitButton({
                        cls: "x-btn-icon", //show icon only
                        icon: value.dropDownGroupIcon,
                        menu: new Ext.menu.Menu({
                            items: [] // items are added later on
                        }),
                        listeners: {
                          click: function(button, event){
                            // The "normal" button should behave like the arrow button
                            if(!button.menu.isVisible() && !button.ignoreNextClick){
                                button.showMenu();
                            } else {
                                button.hideMenu();
                            }
                          } 
                        }
                    });
                    
                    this.toolbar.add(splitButton);
                }
                
                // General config button which will be used either to create a normal button
                // or a check button (if toggling is enabled)
                var buttonCfg = {
                    icon: value.icon,
                    text: value.name,
                    itemId: value.id,
                    handler: value.toggle ? undefined : value.functionality,
                    checkHandler: value.toggle ? value.functionality : undefined,
                    listeners: {
                        render: function(item){
                            // After rendering, a tool tip should be added to component
                            if (value.description) {
                                new Ext.ToolTip({
                                    target: item.getEl(),
                                    title: value.description
                                });
                            }
                        }
                    }
                };
                
                // Create buttons depending on toggle
                if(value.toggle) {
                    var button = new Ext.menu.CheckItem(buttonCfg);
                } else {
                    var button = new Ext.menu.Item(buttonCfg);
                }
                
                splitButton.menu.add(button);
                
            } else { // create normal, simple button
                var button = new Ext.Toolbar.Button({
                    icon:           value.icon,         // icons can also be specified inline
                    cls:            'x-btn-icon',       // Class who shows only the icon
                    itemId:         value.id,
					tooltip:        value.description,  // Set the tooltip
                    tooltipType:    'title',            // Tooltip will be shown as in the html-title attribute
                    handler:        value.toggle ? null : value.functionality,  // Handler for mouse click
                    enableToggle:   value.toggle, // Option for enabling toggling
                    toggleHandler:  value.toggle ? value.functionality : null // Handler for toggle (Parameters: button, active)
                }); 
                
                this.toolbar.add(button);

                button.getEl().onclick = function() {this.blur()}
            }
			     
			value['buttonInstance'] = button;
			this.buttons.push(value);
			
		}).bind(this));

		this.enableButtons([]);
        this.toolbar.calcSlices();
		window.addEventListener("resize", function(event){this.toolbar.calcSlices()}.bind(this), false);
		window.addEventListener("onresize", function(event){this.toolbar.calcSlices()}.bind(this), false);

	},
	
	onSelectionChanged: function(event) {
		if(!event.elements){
			this.enableButtons([]);
		}else{
			this.enableButtons(event.elements);
		}
	},

	enableButtons: function(elements) {
		// Show the Buttons
		this.buttons.each((function(value){
			value.buttonInstance.enable();
						
			// If there is less elements than minShapes
			if(value.minShape && value.minShape > elements.length)
				value.buttonInstance.disable();
			// If there is more elements than minShapes
			if(value.maxShape && value.maxShape < elements.length)
				value.buttonInstance.disable();	
			// If the plugin is not enabled	
			if(value.isEnabled && !value.isEnabled())
				value.buttonInstance.disable();
			
		}).bind(this));		
	}
});

Ext.ns("Ext.ux");
Ext.ux.SlicedToolbar = Ext.extend(Ext.Toolbar, {
    currentSlice: 0,
    iconStandardWidth: 22, //22 px 
    seperatorStandardWidth: 2, //2px, minwidth for Ext.Toolbar.Fill
    toolbarStandardPadding: 2,
    
    initComponent: function(){
        Ext.apply(this, {
        });
        Ext.ux.SlicedToolbar.superclass.initComponent.apply(this, arguments);
    },
    
    onRender: function(){
        Ext.ux.SlicedToolbar.superclass.onRender.apply(this, arguments);
    },
    
    onResize: function(){
        Ext.ux.SlicedToolbar.superclass.onResize.apply(this, arguments);
    },
    
    calcSlices: function(){
        var slice = 0;
        this.sliceMap = {};
        var sliceWidth = 0;
        var toolbarWidth = this.getEl().getWidth();

        this.items.getRange().each(function(item, index){
            //Remove all next and prev buttons
            if (item.helperItem) {
                item.destroy();
                return;
            }
            
            var itemWidth = item.getEl().getWidth();
            
            if(sliceWidth + itemWidth + 5 * this.iconStandardWidth > toolbarWidth){
                var itemIndex = this.items.indexOf(item);
                
                this.insertSlicingButton("next", slice, itemIndex);
                
                if (slice !== 0) {
                    this.insertSlicingButton("prev", slice, itemIndex);
                }
                
                this.insertSlicingSeperator(slice, itemIndex);

                slice += 1;
                sliceWidth = 0;
            }
            
            this.sliceMap[item.id] = slice;
            sliceWidth += itemWidth;
        }.bind(this));
        
        // Add prev button at the end
        if(slice > 0){
            this.insertSlicingSeperator(slice, this.items.getCount()+1);
            this.insertSlicingButton("prev", slice, this.items.getCount()+1);
            var spacer = new Ext.Toolbar.Spacer();
            this.insertSlicedHelperButton(spacer, slice, this.items.getCount()+1);
            Ext.get(spacer.id).setWidth(this.iconStandardWidth);
        }
        
        this.maxSlice = slice;
        
        // Update view
        this.setCurrentSlice(this.currentSlice);
    },
    
    insertSlicedButton: function(button, slice, index){
        this.insertButton(index, button);
        this.sliceMap[button.id] = slice;
    },
    
    insertSlicedHelperButton: function(button, slice, index){
        button.helperItem = true;
        this.insertSlicedButton(button, slice, index);
    },
    
    insertSlicingSeperator: function(slice, index){
        // Align right
        this.insertSlicedHelperButton(new Ext.Toolbar.Fill(), slice, index);
    },
    
    // type => next or prev
    insertSlicingButton: function(type, slice, index){
        var nextHandler = function(){this.setCurrentSlice(this.currentSlice+1)}.bind(this);
        var prevHandler = function(){this.setCurrentSlice(this.currentSlice-1)}.bind(this);
        
        var button = new Ext.Toolbar.Button({
            cls: "x-btn-icon",
            icon: ORYX.CONFIG.ROOT_PATH + "images/toolbar_"+type+".png",
            handler: (type === "next") ? nextHandler : prevHandler
        });
        
        this.insertSlicedHelperButton(button, slice, index);
    },
    
    setCurrentSlice: function(slice){
        if(slice > this.maxSlice || slice < 0) return;
        
        this.currentSlice = slice;

        this.items.getRange().each(function(item){
            item.setVisible(slice === this.sliceMap[item.id]);
        }.bind(this));
    }
});/**
 * Copyright (c) 2009
 * Jan-Felix Schwarz, Willi Tscheschner, Nicolas Peters, Martin Czuchra, Daniel Polak
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

if(!ORYX.Plugins) {
	ORYX.Plugins = new Object();
}

ORYX.Plugins.ShapeMenuPlugin = {

	construct: function(facade) {
		this.facade = facade;
		
		this.alignGroups = new Hash();

		var containerNode = this.facade.getCanvas().getHTMLContainer();

		this.shapeMenu = new ORYX.Plugins.ShapeMenu(containerNode);
		this.currentShapes = [];

		// Register on dragging and resizing events for show/hide of ShapeMenu
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_START, this.hideShapeMenu.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DRAGDROP_END,  this.showShapeMenu.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_RESIZE_START,  (function(){
			this.hideShapeMenu();
			this.hideMorphMenu();
		}).bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_RESIZE_END,  this.showShapeMenu.bind(this));
		
		// Enable DragZone
		var DragZone = new Ext.dd.DragZone(containerNode.parentNode, {shadow: !Ext.isMac});
		DragZone.afterDragDrop = this.afterDragging.bind(this, DragZone);
		DragZone.beforeDragOver = this.beforeDragOver.bind(this, DragZone);
		
		// Memory of created Buttons
		this.createdButtons = {};
		
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_STENCIL_SET_LOADED, (function(){ this.registryChanged() }).bind(this));

		this.timer = null;
		
		this.resetElements = true;

	},

	hideShapeMenu: function(event) {
		window.clearTimeout(this.timer);
		this.timer = null;
		this.shapeMenu.hide();
	},

	showShapeMenu: function( dontGenerateNew ) {
	
		if( !dontGenerateNew || this.resetElements ){
			
			window.clearTimeout(this.timer);
			this.timer = window.setTimeout(function(){
				
					// Close all Buttons
				this.shapeMenu.closeAllButtons();
		
				// Show the Morph Button
				this.showMorphButton(this.currentShapes);
				
				// Show the Stencil Buttons
				this.showStencilButtons(this.currentShapes);	
				
				// Show the ShapeMenu
				this.shapeMenu.show(this.currentShapes);
				
				this.resetElements = false;
			}.bind(this), 300)
			
		} else {
			
			window.clearTimeout(this.timer);
			this.timer = null;
			
			// Show the ShapeMenu
			this.shapeMenu.show(this.currentShapes);
			
		}
	},

	registryChanged: function(pluginsData) {
		
		if(pluginsData) {
			pluginsData = pluginsData.each(function(value) {value.group = value.group ? value.group : 'unknown'});
			this.pluginsData = pluginsData.sortBy( function(value) {
				return (value.group + "" + value.index);
			});			
		}		
		
		this.shapeMenu.removeAllButtons();
		this.shapeMenu.setNumberOfButtonsPerLevel(ORYX.CONFIG.SHAPEMENU_RIGHT, 2);
		this.createdButtons = {};
		
		this.createMorphMenu();
		
		if( !this.pluginsData ){
			this.pluginsData = [];
		}

		this.baseMorphStencils = this.facade.getRules().baseMorphs();
		
		// Checks if the stencil set has morphing attributes
		var isMorphing = this.facade.getRules().containsMorphingRules();
		
		// Create Buttons for all Stencils of all loaded stencilsets
		var stencilsets = this.facade.getStencilSets();
		stencilsets.values().each((function(stencilSet){
			
			var nodes = stencilSet.nodes();
			nodes.each((function(stencil) {
								
				// Create a button for each node
				var option = {type: stencil.id(), namespace: stencil.namespace(), connectingType: true};
				var button = new ORYX.Plugins.ShapeMenuButton({
					callback: 	this.newShape.bind(this, option),
					icon: 		stencil.icon(),
					align: 		ORYX.CONFIG.SHAPEMENU_RIGHT,
					group:		0,
					//dragcallback: this.hideShapeMenu.bind(this),
					msg:		stencil.title() + " - " + ORYX.I18N.ShapeMenuPlugin.clickDrag
					});
				
				// Add button to shape menu
				this.shapeMenu.addButton(button); 
				
				// Add to the created Button Array
				this.createdButtons[stencil.namespace() + stencil.type() + stencil.id()] = button;
				
				// Drag'n'Drop will enable
				Ext.dd.Registry.register(button.node.lastChild, option);				
			}).bind(this));
		

			var edges = stencilSet.edges();
			edges.each((function(stencil) {

				// Create a button for each edge
				var option = {type: stencil.id(), namespace: stencil.namespace()};
				var button = new ORYX.Plugins.ShapeMenuButton({
					callback: 	this.newShape.bind(this, option),
					// icon: 		isMorphing ? ORYX.PATH + "images/edges.png" : stencil.icon(),
					icon: 		stencil.icon(),
					align: 		ORYX.CONFIG.SHAPEMENU_RIGHT,
					group:		1,
					//dragcallback: this.hideShapeMenu.bind(this),
					msg:		(isMorphing ? ORYX.I18N.Edge : stencil.title()) + " - " + ORYX.I18N.ShapeMenuPlugin.drag
				});
				
				// Add button to shape menu
				this.shapeMenu.addButton(button); 
				
				// Add to the created Button Array
				this.createdButtons[stencil.namespace() + stencil.type() + stencil.id()] = button;
				
				// Drag'n'Drop will enable
				Ext.dd.Registry.register(button.node.lastChild, option);
				
			}).bind(this));
		
		}).bind(this));				
					
	},
	
	createMorphMenu: function() {
		
		this.morphMenu = new Ext.menu.Menu({
			id: 'Oryx_morph_menu',
			items: []
		});
		
		this.morphMenu.on("mouseover", function() {
			this.morphMenuHovered = true;
		}, this);
		this.morphMenu.on("mouseout", function() {
			this.morphMenuHovered = false;
		}, this);
		
		
		// Create the button to show the morph menu
		var button = new ORYX.Plugins.ShapeMenuButton({
			hovercallback: 	(ORYX.CONFIG.ENABLE_MORPHMENU_BY_HOVER ? this.showMorphMenu.bind(this) : undefined), 
			resetcallback: 	(ORYX.CONFIG.ENABLE_MORPHMENU_BY_HOVER ? this.hideMorphMenu.bind(this) : undefined), 
			callback:		(ORYX.CONFIG.ENABLE_MORPHMENU_BY_HOVER ? undefined : this.toggleMorphMenu.bind(this)), 
			icon: 			ORYX.PATH + 'images/wrench_orange.png',
			align: 			ORYX.CONFIG.SHAPEMENU_BOTTOM,
			group:			0,
			msg:			ORYX.I18N.ShapeMenuPlugin.morphMsg
		});				
		
		this.shapeMenu.setNumberOfButtonsPerLevel(ORYX.CONFIG.SHAPEMENU_BOTTOM, 1)
		this.shapeMenu.addButton(button);
		this.morphMenu.getEl().appendTo(button.node);
		this.morphButton = button;
	},
	
	showMorphMenu: function() {
		this.morphMenu.show(this.morphButton.node);
		this._morphMenuShown = true;
	},
	
	hideMorphMenu: function() {
		this.morphMenu.hide();
		this._morphMenuShown = false;
	},
	
	toggleMorphMenu: function() {
		if(this._morphMenuShown)
			this.hideMorphMenu();
		else
			this.showMorphMenu();
	},
	
	onSelectionChanged: function(event) {
		var elements = event.elements;

		this.hideShapeMenu();
		this.hideMorphMenu();
				
		if( this.currentShapes.inspect() !== elements.inspect() ){
			this.currentShapes = elements;
			this.resetElements = true;
			
			this.showShapeMenu();
		} else {
			this.showShapeMenu(true)
		}
		
	},
	
	/**
	 * Show button for morphing the selected shape into another stencil
	 */
        
	showMorphButton: function(elements) {
		
		if(elements.length != 1) return;
		
		var possibleMorphs = this.facade.getRules().morphStencils({ stencil: elements[0].getStencil() });
		possibleMorphs = possibleMorphs.select(function(morph) {
			if(elements[0].getStencil().type() === "node") {
				//check containment rules
				return this.facade.getRules().canContain({containingShape:elements[0].parent, containedStencil:morph});
			} else { 
				//check connect rules
				return this.facade.getRules().canConnect({
											sourceShape:	elements[0].dockers.first().getDockedShape(), 
											edgeStencil:	morph, 
											targetShape:	elements[0].dockers.last().getDockedShape()
											});	
			}
		}.bind(this));
		if(possibleMorphs.size()<=1) return; // if morphing to other stencils is not possible, don't show button
		
		this.morphMenu.removeAll();
		
		// populate morph menu with the possible morph stencils ordered by their position
		possibleMorphs = possibleMorphs.sortBy(function(stencil) { return stencil.position(); });
		possibleMorphs.each((function(morph) {
			var menuItem = new Ext.menu.Item({ 
				text: morph.title(), 
				icon: morph.icon(), 
				disabled: morph.id()==elements[0].getStencil().id(),
				disabledClass: ORYX.CONFIG.MORPHITEM_DISABLED,
				handler: (function() { this.morphShape(elements[0], morph); }).bind(this) 
			});
			this.morphMenu.add(menuItem);
		}).bind(this));
		
		this.morphButton.prepareToShow();
		
	},

	/**
	 * Show buttons for creating following shapes
	 */
        
	showStencilButtons: function(elements) {

		if(elements.length != 1) return;

		//TODO temporaere nutzung des stencilsets
		var sset = this.facade.getStencilSets()[elements[0].getStencil().namespace()];

		// Get all available edges
		var edges = this.facade.getRules().outgoingEdgeStencils({canvas:this.facade.getCanvas(), sourceShape:elements[0]});
		
		// And find all targets for each Edge
		var targets = new Array();
		var addedEdges = new Array();
		
		var isMorphing = this.facade.getRules().containsMorphingRules();
		
		edges.each((function(edge) {
			
			if (isMorphing){
				if(this.baseMorphStencils.include(edge)) {
					var shallAppear = true;
				} else {
					
					// if edge is member of a morph groups where none of the base morphs is in the outgoing edges
					// we want to display the button (but only for the first one)
					
					var possibleMorphs = this.facade.getRules().morphStencils({ stencil: edge });
					
					var shallAppear = !possibleMorphs.any((function(morphStencil) {
						if(this.baseMorphStencils.include(morphStencil) && edges.include(morphStencil)) return true;
						return addedEdges.include(morphStencil);
					}).bind(this));
					
				}
			}
			if(shallAppear || !isMorphing) {
				if(this.createdButtons[edge.namespace() + edge.type() + edge.id()]) 
					this.createdButtons[edge.namespace() + edge.type() + edge.id()].prepareToShow();
				addedEdges.push(edge);
			}
			
			// get all targets for this edge
			targets = targets.concat(this.facade.getRules().targetStencils(
					{canvas:this.facade.getCanvas(), sourceShape:elements[0], edgeStencil:edge}));

		}).bind(this));
		
		targets.uniq();
		
		var addedTargets = new Array();
		// Iterate all possible target 
		targets.each((function(target) {
			
			if (isMorphing){
				
				// continue with next target stencil
				if (target.type()==="edge") return; 
				
				// continue when stencil should not shown in the shape menu
				if (!this.facade.getRules().showInShapeMenu(target)) return 
				
				// if target is not a base morph 
				if(!this.baseMorphStencils.include(target)) {
					
					// if target is member of a morph groups where none of the base morphs is in the targets
					// we want to display the button (but only for the first one)
					
					var possibleMorphs = this.facade.getRules().morphStencils({ stencil: target });
					if(possibleMorphs.size()==0) return; // continue with next target
	
					var baseMorphInTargets = possibleMorphs.any((function(morphStencil) {
						if(this.baseMorphStencils.include(morphStencil) && targets.include(morphStencil)) return true;
						return addedTargets.include(morphStencil);
					}).bind(this));
					
					if(baseMorphInTargets) return; // continue with next target
				}
			}
			
			// if this is reached the button shall appear in the shape menu:
			if(this.createdButtons[target.namespace() + target.type() + target.id()]) 
				this.createdButtons[target.namespace() + target.type() + target.id()].prepareToShow();
			addedTargets.push(target);
			
		}).bind(this));
		
	},

	
	beforeDragOver: function(dragZone, target, event){

		if (this.shapeMenu.isVisible){
			this.hideShapeMenu();
		}

		var coord = this.facade.eventCoordinates(event.browserEvent);
		var aShapes = this.facade.getCanvas().getAbstractShapesAtPosition(coord);

		if(aShapes.length <= 0) {return false;}	
		
		var el = aShapes.last();
		
		if(this._lastOverElement == el) {
			
			return false;
			
		} else {
			// check containment rules
			var option = Ext.dd.Registry.getHandle(target.DDM.currentTarget);
			
			// revert to original options if these were modified
			if(option.backupOptions) {
				for(key in option.backupOptions) {
					option[key] = option.backupOptions[key];
				}
				delete option.backupOptions;
			}

			var stencilSet = this.facade.getStencilSets()[option.namespace];

			var stencil = stencilSet.stencil(option.type);

			var candidate = aShapes.last();

			if(stencil.type() === "node") {
				//check containment rules
				var canContain = this.facade.getRules().canContain({containingShape:candidate, containedStencil:stencil});
									
				// if not canContain, try to find a morph which can be contained
				if(!canContain) {
					var possibleMorphs = this.facade.getRules().morphStencils({stencil: stencil});
					for(var i=0; i<possibleMorphs.size(); i++) {
						canContain = this.facade.getRules().canContain({
							containingShape:candidate, 
							containedStencil:possibleMorphs[i]
						});
						if(canContain) {
							option.backupOptions = Object.clone(option);
							option.type = possibleMorphs[i].id();
							option.namespace = possibleMorphs[i].namespace();
							break;
						}
					}
				}
					
				this._currentReference = canContain ? candidate : undefined;
					
	
			} else { //Edge
			
				var curCan = candidate, orgCan = candidate;
				var canConnect = false;
				while(!canConnect && curCan && !(curCan instanceof ORYX.Core.Canvas)){
					candidate = curCan;
					//check connection rules
					canConnect = this.facade.getRules().canConnect({
											sourceShape: this.currentShapes.first(), 
											edgeStencil: stencil, 
											targetShape: curCan
											});	
					curCan = curCan.parent;
				}

			 	// if not canConnect, try to find a morph which can be connected
				if(!canConnect) {
					
					candidate = orgCan;
					var possibleMorphs = this.facade.getRules().morphStencils({stencil: stencil});
					for(var i=0; i<possibleMorphs.size(); i++) {
						var curCan = candidate;
						var canConnect = false;
						while(!canConnect && curCan && !(curCan instanceof ORYX.Core.Canvas)){
							candidate = curCan;
							//check connection rules
							canConnect = this.facade.getRules().canConnect({
														sourceShape:	this.currentShapes.first(), 
														edgeStencil:	possibleMorphs[i], 
														targetShape:	curCan
													});	
							curCan = curCan.parent;
						}
						if(canConnect) {
							option.backupOptions = Object.clone(option);
							option.type = possibleMorphs[i].id();
							option.namespace = possibleMorphs[i].namespace();
							break;
						} else {
							candidate = orgCan;
						}
					}
				}
										
				this._currentReference = canConnect ? candidate : undefined;		
				
			}	

			this.facade.raiseEvent({
											type:		ORYX.CONFIG.EVENT_HIGHLIGHT_SHOW, 
											highlightId:'shapeMenu',
											elements:	[candidate],
											color:		this._currentReference ? ORYX.CONFIG.SELECTION_VALID_COLOR : ORYX.CONFIG.SELECTION_INVALID_COLOR
										});
												
			var pr = dragZone.getProxy();
			pr.setStatus(this._currentReference ? pr.dropAllowed : pr.dropNotAllowed );
			pr.sync();
										
		}
		
		this._lastOverElement = el;
		
		return false;
	},	

	afterDragging: function(dragZone, target, event) {
		
		if (!(this.currentShapes instanceof Array)||this.currentShapes.length<=0) {
			return;
		}
		var sourceShape = this.currentShapes;
		
		this._lastOverElement = undefined;
		
		// Hide the highlighting
		this.facade.raiseEvent({type: ORYX.CONFIG.EVENT_HIGHLIGHT_HIDE, highlightId:'shapeMenu'});
		
		// Check if drop is allowed
		var proxy = dragZone.getProxy()
		if(proxy.dropStatus == proxy.dropNotAllowed) { return this.facade.updateSelection();}
				
		// Check if there is a current Parent
		if(!this._currentReference) { return }
				
		var option = Ext.dd.Registry.getHandle(target.DDM.currentTarget);
		option['parent'] = this._currentReference;

		var xy = event.getXY();
		var pos = {x: xy[0], y: xy[1]};

		var a = this.facade.getCanvas().node.getScreenCTM();
		// Correcting the UpperLeft-Offset
		pos.x -= a.e; pos.y -= a.f;
		// Correcting the Zoom-Faktor
		pos.x /= a.a; pos.y /= a.d;
		// Correcting the ScrollOffset
		pos.x -= document.documentElement.scrollLeft;
		pos.y -= document.documentElement.scrollTop;

		var parentAbs = this._currentReference.absoluteXY();
		pos.x -= parentAbs.x;
		pos.y -= parentAbs.y;
		
		// If the ctrl key is not pressed, 
		// snapp the new shape to the center 
		// if it is near to the center of the other shape
		if (!event.ctrlKey){
			// Get the center of the shape
			var cShape = this.currentShapes[0].bounds.center();
			// Snapp +-20 Pixel horizontal to the center 
			if (20 > Math.abs(cShape.x - pos.x)){
				pos.x = cShape.x;
			}
			// Snapp +-20 Pixel vertical to the center 
			if (20 > Math.abs(cShape.y - pos.y)){
				pos.y = cShape.y;
			}
		}
				
		option['position'] = pos;
		option['connectedShape'] = this.currentShapes[0];
		if(option['connectingType']) {
			var stencilset = this.facade.getStencilSets()[option.namespace];
			var containedStencil = stencilset.stencil(option.type);
			var args = { sourceShape: this.currentShapes[0], targetStencil: containedStencil };
			option['connectingType'] = this.facade.getRules().connectMorph(args).id();
		}
		
		if (ORYX.CONFIG.SHAPEMENU_DISABLE_CONNECTED_EDGE===true) {
			delete option['connectingType'];
		}
			
		var command = new ORYX.Plugins.ShapeMenuPlugin.CreateCommand(Object.clone(option), this._currentReference, pos, this);
		
		this.facade.executeCommands([command]);
		
		// Inform about completed Drag 
		this.facade.raiseEvent({type: ORYX.CONFIG.EVENT_SHAPE_MENU_CLOSE, source:sourceShape, destination:this.currentShapes});
		
		// revert to original options if these were modified
		if(option.backupOptions) {
			for(key in option.backupOptions) {
				option[key] = option.backupOptions[key];
			}
			delete option.backupOptions;
		}	
		
		this._currentReference = undefined;		
	},

	newShape: function(option, event) {
		var stencilset = this.facade.getStencilSets()[option.namespace];
		var containedStencil = stencilset.stencil(option.type);

		if(this.facade.getRules().canContain({
			containingShape:this.currentShapes.first().parent,
			"containedStencil":containedStencil
		})) {

			option['connectedShape'] = this.currentShapes[0];
			option['parent'] = this.currentShapes.first().parent;
			option['containedStencil'] = containedStencil;
		
			var args = { sourceShape: this.currentShapes[0], targetStencil: containedStencil };
			var targetStencil = this.facade.getRules().connectMorph(args);
			if (!targetStencil){ return }// Check if there can be a target shape
			option['connectingType'] = targetStencil.id();

			if (ORYX.CONFIG.SHAPEMENU_DISABLE_CONNECTED_EDGE===true) {
				delete option['connectingType'];
			}
			
			var command = new ORYX.Plugins.ShapeMenuPlugin.CreateCommand(option, undefined, undefined, this);
		
			this.facade.executeCommands([command]);
		}
	},
	
	/**
	 * Morph a shape to a new stencil
	 * {Command implemented}
	 * @param {Shape} shape
	 * @param {Stencil} stencil
	 */
        
	morphShape: function(shape, stencil) {
		
		var MorphTo = ORYX.Core.Command.extend({
			construct: function(shape, stencil, facade){
				this.shape = shape;
				this.stencil = stencil;
				this.facade = facade;
			},
			execute: function(){
				
				var shape = this.shape;
				var stencil = this.stencil;
				var resourceId = shape.resourceId;
				
				// Serialize all attributes
				var serialized = shape.serialize();
				stencil.properties().each((function(prop) {
					if(prop.readonly()) {
						serialized = serialized.reject(function(serProp) {
							return serProp.name==prop.id();
						});
					}
				}).bind(this));
		
				// Get shape if already created, otherwise create a new shape
				if (this.newShape){
					newShape = this.newShape;
					this.facade.getCanvas().add(newShape);
				} else {
					newShape = this.facade.createShape({
									type: stencil.id(),
									namespace: stencil.namespace(),
									resourceId: resourceId
								});
				}
				
				// calculate new bounds using old shape's upperLeft and new shape's width/height
				var boundsObj = serialized.find(function(serProp){
					return (serProp.prefix === "oryx" && serProp.name === "bounds");
				});
				
				var changedBounds = null;
				
				if(!this.facade.getRules().preserveBounds(shape.getStencil())) {
					
					var bounds = boundsObj.value.split(",");
					if (parseInt(bounds[0], 10) > parseInt(bounds[2], 10)) { // if lowerRight comes first, swap array items
						var tmp = bounds[0];
						bounds[0] = bounds[2];
						bounds[2] = tmp;
						tmp = bounds[1];
						bounds[1] = bounds[3];
						bounds[3] = tmp;
					}
					bounds[2] = parseInt(bounds[0], 10) + newShape.bounds.width();
					bounds[3] = parseInt(bounds[1], 10) + newShape.bounds.height();
					boundsObj.value = bounds.join(",");
					
				}  else {
					
					var height = shape.bounds.height();
					var width  = shape.bounds.width();
					
					// consider the minimum and maximum size of
					// the new shape
					
					if (newShape.minimumSize) {
						if (shape.bounds.height() < newShape.minimumSize.height) {
							height = newShape.minimumSize.height;
						}
						
						
						if (shape.bounds.width() < newShape.minimumSize.width) {
							width = newShape.minimumSize.width;
						}
					}
					
					if(newShape.maximumSize) {
						if(shape.bounds.height() > newShape.maximumSize.height) {
							height = newShape.maximumSize.height;
						}	
						
						if(shape.bounds.width() > newShape.maximumSize.width) {
							width = newShape.maximumSize.width;
						}
					}
					
					changedBounds = {
						a : {
							x: shape.bounds.a.x,
							y: shape.bounds.a.y
						},
						b : {
							x: shape.bounds.a.x + width,
							y: shape.bounds.a.y + height
						}						
					};
					
				}
				
				var oPos = shape.bounds.center();
				if(changedBounds !== null) {
					newShape.bounds.set(changedBounds);
				}
				
				// Set all related dockers
				this.setRelatedDockers(shape, newShape);
				
				// store DOM position of old shape
				var parentNode = shape.node.parentNode;
				var nextSibling = shape.node.nextSibling;
				
				// Delete the old shape
				this.facade.deleteShape(shape);
				
				// Deserialize the new shape - Set all attributes
				newShape.deserialize(serialized);
				/*
				 * Change color to default if unchanged
				 * 23.04.2010
				 */
                                
				if(shape.getStencil().property("oryx-bgcolor") 
						&& shape.properties["oryx-bgcolor"]
						&& shape.getStencil().property("oryx-bgcolor").value().toUpperCase()== shape.properties["oryx-bgcolor"].toUpperCase()){
						if(newShape.getStencil().property("oryx-bgcolor")){
							newShape.setProperty("oryx-bgcolor", newShape.getStencil().property("oryx-bgcolor").value());
						}
				}	
				if(changedBounds !== null) {
					newShape.bounds.set(changedBounds);
				}
				
				if(newShape.getStencil().type()==="edge" || (newShape.dockers.length==0 || !newShape.dockers[0].getDockedShape())) {
					newShape.bounds.centerMoveTo(oPos);
				} 
				
				if(newShape.getStencil().type()==="node" && (newShape.dockers.length==0 || !newShape.dockers[0].getDockedShape())) {
					this.setRelatedDockers(newShape, newShape);
					
				}
				
				// place at the DOM position of the old shape
				if(nextSibling) parentNode.insertBefore(newShape.node, nextSibling);
				else parentNode.appendChild(newShape.node);
				
				// Set selection
				this.facade.setSelection([newShape]);
				this.facade.getCanvas().update();
				this.facade.updateSelection();
				this.newShape = newShape;
				
				this.facade.raiseEvent({
					type: ORYX.CONFIG.EVENT_SHAPE_MORPHED,
					shape: newShape
				});

				
			},
			rollback: function(){
				
				if (!this.shape || !this.newShape || !this.newShape.parent) {return}
				
				// Append shape to the parent
				this.newShape.parent.add(this.shape);
				// Set dockers
				this.setRelatedDockers(this.newShape, this.shape);
				// Delete new shape
				this.facade.deleteShape(this.newShape);
				// Set selection
				this.facade.setSelection([this.shape]);
				// Update
				this.facade.getCanvas().update();
				this.facade.updateSelection();
			},
			
			/**
			 * Set all incoming and outgoing edges from the shape to the new shape
			 * @param {Shape} shape
			 * @param {Shape} newShape
			 */
                        
			setRelatedDockers: function(shape, newShape){
				
				if(shape.getStencil().type()==="node") {
					
					(shape.incoming||[]).concat(shape.outgoing||[])
						.each(function(i) { 
							i.dockers.each(function(docker) {
								if (docker.getDockedShape() == shape) {
									var rPoint = Object.clone(docker.referencePoint);
									// Move reference point per percent

									var rPointNew = {
										x: rPoint.x*newShape.bounds.width()/shape.bounds.width(),
										y: rPoint.y*newShape.bounds.height()/shape.bounds.height()
									};

									docker.setDockedShape(newShape);
									// Set reference point and center to new position
									docker.setReferencePoint(rPointNew);
									if(i instanceof ORYX.Core.Edge) {
										docker.bounds.centerMoveTo(rPointNew);
									} else {
										var absXY = shape.absoluteXY();
										docker.bounds.centerMoveTo({x:rPointNew.x+absXY.x, y:rPointNew.y+absXY.y});
										//docker.bounds.moveBy({x:rPointNew.x-rPoint.x, y:rPointNew.y-rPoint.y});
									}
								}
							});	
						});
					
					// for attached events
					if(shape.dockers.length>0&&shape.dockers.first().getDockedShape()) {
						newShape.dockers.first().setDockedShape(shape.dockers.first().getDockedShape());
						newShape.dockers.first().setReferencePoint(Object.clone(shape.dockers.first().referencePoint));
					}
				
				} else { // is edge
					newShape.dockers.first().setDockedShape(shape.dockers.first().getDockedShape());
					newShape.dockers.first().setReferencePoint(shape.dockers.first().referencePoint);
					newShape.dockers.last().setDockedShape(shape.dockers.last().getDockedShape());
					newShape.dockers.last().setReferencePoint(shape.dockers.last().referencePoint);
				}
			}
		});
		
		// Create and execute command (for undo/redo)			
		var command = new MorphTo(shape, stencil, this.facade);
		this.facade.executeCommands([command]);
	}
}
ORYX.Plugins.ShapeMenuPlugin = ORYX.Plugins.AbstractPlugin.extend(ORYX.Plugins.ShapeMenuPlugin);

ORYX.Plugins.ShapeMenu = {

	/***
	 * Constructor.
	 */
        
	construct: function(parentNode) {

		this.bounds = undefined;
		this.shapes = undefined;
		this.buttons = [];
		this.isVisible = false;

		this.node = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", $(parentNode),
			['div', {id: ORYX.Editor.provideId(), 'class':'Oryx_ShapeMenu'}]);
		
		this.alignContainers = new Hash();
		this.numberOfButtonsPerLevel = new Hash();
	},

	addButton: function(button) {
		this.buttons.push(button);
		// lazy grafting of the align containers
		if(!this.alignContainers[button.align]) {
			this.alignContainers[button.align] = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", this.node,
					['div', {'class':button.align}]);
			this.node.appendChild(this.alignContainers[button.align]);
			
			// add event listeners for hover effect
			var onBubble = false;
			this.alignContainers[button.align].addEventListener(ORYX.CONFIG.EVENT_MOUSEOVER, this.hoverAlignContainer.bind(this, button.align), onBubble);
			this.alignContainers[button.align].addEventListener(ORYX.CONFIG.EVENT_MOUSEOUT, this.resetAlignContainer.bind(this, button.align), onBubble);
			this.alignContainers[button.align].addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.hoverAlignContainer.bind(this, button.align), onBubble);
		}
		this.alignContainers[button.align].appendChild(button.node);
	},

	deleteButton: function(button) {
		this.buttons = this.buttons.without(button);
		this.node.removeChild(button.node);
	},

	removeAllButtons: function() {
		var me = this;
		this.buttons.each(function(value){
			if (value.node&&value.node.parentNode)
				value.node.parentNode.removeChild(value.node);
		});
		this.buttons = [];
	},

	closeAllButtons: function() {
		this.buttons.each(function(value){ value.prepareToHide() });
		this.isVisible = false;
	},

	
	/**
	 * Show the shape menu
	 */
        
	show: function(shapes) {

		//shapes = (shapes||[]).findAll(function(r){ return r && r.node && r.node.parent });

		if(shapes.length <= 0 )
			return

		this.shapes = shapes;

		var newBounds = undefined;
		var tmpBounds = undefined;

		this.shapes.each(function(value) {
			var a = value.node.getScreenCTM();
			var upL = value.absoluteXY();
			a.e = a.a*upL.x;
			a.f = a.d*upL.y;
			tmpBounds = new ORYX.Core.Bounds(a.e, a.f, a.e+a.a*value.bounds.width(), a.f+a.d*value.bounds.height());

			/*if(value instanceof ORYX.Core.Edge) {
				tmpBounds.moveBy(value.bounds.upperLeft())
			}*/

			if(!newBounds)
				newBounds = tmpBounds
			else
				newBounds.include(tmpBounds);

		});

		this.bounds = newBounds;
		//this.bounds.moveBy({x:document.documentElement.scrollLeft, y:document.documentElement.scrollTop});

		var bounds = this.bounds;

		var a = this.bounds.upperLeft();

		var left = 0,
			leftButtonGroup = 0;
		var top = 0,
			topButtonGroup = 0;
		var bottom = 0,
			bottomButtonGroup;
		var right = 0
			rightButtonGroup = 0;
		var size = 22;
		
		this.getWillShowButtons().sortBy(function(button) {
			return button.group;
		});
		
		this.getWillShowButtons().each(function(button){
			
			var numOfButtonsPerLevel = this.getNumberOfButtonsPerLevel(button.align);

			if (button.align == ORYX.CONFIG.SHAPEMENU_LEFT) {
				// vertical levels
				if(button.group!=leftButtonGroup) {
					left = 0;
					leftButtonGroup = button.group;
				}
				var x = Math.floor(left / numOfButtonsPerLevel)
				var y = left % numOfButtonsPerLevel;
				
				button.setLevel(x);
				
				button.setPosition(a.x-5 - (x+1)*size, 
						a.y+numOfButtonsPerLevel*button.group*size + button.group*0.3*size + y*size);
				
				//button.setPosition(a.x-22, a.y+left*size);
				left++;
 			} else if (button.align == ORYX.CONFIG.SHAPEMENU_TOP) {
 				// horizontal levels
 				if(button.group!=topButtonGroup) {
					top = 0;
					topButtonGroup = button.group;
				}
 				var x = top % numOfButtonsPerLevel;
 				var y = Math.floor(top / numOfButtonsPerLevel);
 				
 				button.setLevel(y);
 				
 				button.setPosition(a.x+numOfButtonsPerLevel*button.group*size + button.group*0.3*size + x*size,
 						a.y-5 - (y+1)*size);
				top++;
 			} else if (button.align == ORYX.CONFIG.SHAPEMENU_BOTTOM) {
 				// horizontal levels
 				if(button.group!=bottomButtonGroup) {
					bottom = 0;
					bottomButtonGroup = button.group;
				}
 				var x = bottom % numOfButtonsPerLevel;
 				var y = Math.floor(bottom / numOfButtonsPerLevel);
 				
 				button.setLevel(y);
 				
 				button.setPosition(a.x+numOfButtonsPerLevel*button.group*size + button.group*0.3*size + x*size,
 						a.y+bounds.height() + 5 + y*size);
				bottom++;
			} else {
				// vertical levels
				if(button.group!=rightButtonGroup) {
					right = 0;
					rightButtonGroup = button.group;
				}
				var x = Math.floor(right / numOfButtonsPerLevel)
				var y = right % numOfButtonsPerLevel;
				
				button.setLevel(x);
				
				button.setPosition(a.x+bounds.width() + 5 + x*size, 
						a.y+numOfButtonsPerLevel*button.group*size + button.group*0.3*size + y*size - 5);
				right++;
			}

			button.show();
		}.bind(this));
		this.isVisible = true;

	},

	/**
	 * Hide the shape menu
	 */
        
	hide: function() {

		this.buttons.each(function(button){
			button.hide();
		});

		this.isVisible = false;
		//this.bounds = undefined;
		//this.shape = undefined;
	},

	hoverAlignContainer: function(align, evt) {
		this.buttons.each(function(button){
			if(button.align == align) button.showOpaque();
		});
	},
	
	resetAlignContainer: function(align, evt) {
		this.buttons.each(function(button){
			if(button.align == align) button.showTransparent();
		});
	},
	
	isHover: function() {
		return 	this.buttons.any(function(value){
					return value.isHover();
				});
	},
	
	getWillShowButtons: function() {
		return this.buttons.findAll(function(value){return value.willShow});
	},
	
	/**
	 * Returns a set on buttons for that align value
	 * @params {String} align
	 * @params {String} group
	 */
        
	getButtons: function(align, group){
		return this.getWillShowButtons().findAll(function(b){ return b.align == align && (group === undefined || b.group == group)})
	},
	
	/**
	 * Set the number of buttons to display on each level of the shape menu in the specified align group.
	 * Example: setNumberOfButtonsPerLevel(ORYX.CONFIG.SHAPEMENU_RIGHT, 2) causes that the buttons of the right align group 
	 * will be rendered in 2 rows.
	 */
        
	setNumberOfButtonsPerLevel: function(align, number) {
		this.numberOfButtonsPerLevel[align] = number;
	},
	
	/**
	 * Returns the number of buttons to display on each level of the shape menu in the specified align group.
	 * Default value is 1
	 */
        
	getNumberOfButtonsPerLevel: function(align) {
		if(this.numberOfButtonsPerLevel[align])
			return Math.min(this.getButtons(align,0).length, this.numberOfButtonsPerLevel[align]);
		else
			return 1;
	}

}
ORYX.Plugins.ShapeMenu = Clazz.extend(ORYX.Plugins.ShapeMenu);

ORYX.Plugins.ShapeMenuButton = {
	
	/**
	 * Constructor
	 * @param option A key map specifying the configuration options:
	 * 					id: 	(String) The id of the parent DOM element for the new button
	 * 					icon: 	(String) The url to the icon of the button
	 * 					msg:	(String) A tooltip message
	 * 					caption:(String) The caption of the button (attention: button width > 22, only set for single column button layouts)
	 * 					align:	(String) The direction in which the button is aligned
	 * 					group: 	(Integer) The button group in the specified alignment 
	 * 							(buttons in the same group will be aligned side by side)
	 * 					callback:		(Function) A callback that is executed when the button is clicked
	 * 					dragcallback:	(Function) A callback that is executed when the button is dragged
	 * 					hovercallback:	(Function) A callback that is executed when the button is hovered
	 * 					resetcallback:	(Function) A callback that is executed when the button is reset
	 * 					arguments:		(Array) An argument array to pass to the callback functions
	 */
	construct: function(option) {

		if(option) {
			this.option = option;
			if(!this.option.arguments)
				this.option.arguments = [];
		} else {
			//TODO error
		}

		this.parentId = this.option.id ? this.option.id : null;

		// graft the button.
		var buttonClassName = this.option.caption ? "Oryx_button_with_caption" : "Oryx_button";
		this.node = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", $(this.parentId),
			['div', {'class':buttonClassName}]);

		var imgOptions = {src:this.option.icon};
		if(this.option.msg){
			imgOptions.title = this.option.msg;
		}
		
		// graft and update icon (not in grafting for ns reasons).
		//TODO Enrich graft()-function to do this in one of the above steps.
		if(this.option.icon)
			ORYX.Editor.graft("http://www.w3.org/1999/xhtml", this.node,
				['img', imgOptions]);
		
		if(this.option.caption) {
			var captionNode = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", this.node, ['span']);
			ORYX.Editor.graft("http://www.w3.org/1999/xhtml", captionNode, this.option.caption);
		}

		var onBubble = false;

		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEOVER, this.hover.bind(this), onBubble);
		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEOUT, this.reset.bind(this), onBubble);
		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEDOWN, this.activate.bind(this), onBubble);
		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.hover.bind(this), onBubble);
		this.node.addEventListener('click', this.trigger.bind(this), onBubble);
		this.node.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.move.bind(this), onBubble);

		this.align = this.option.align ? this.option.align : ORYX.CONFIG.SHAPEMENU_RIGHT;
		this.group = this.option.group ? this.option.group : 0;

		this.hide();

		this.dragStart 	= false;
		this.isVisible 	= false;
		this.willShow 	= false;
		this.resetTimer;
	},
	
	hide: function() {
		this.node.style.display = "none";
		this.isVisible = false;
	},

	show: function() {
		this.node.style.display = "";
		this.node.style.opacity = this.opacity;
		this.isVisible = true;
	},
	
	showOpaque: function() {
		this.node.style.opacity = 1.0;
	},
	
	showTransparent: function() {
		this.node.style.opacity = this.opacity;
	},
	
	prepareToShow: function() {
		this.willShow = true;
	},

	prepareToHide: function() {
		this.willShow = false;
		this.hide();
	},

	setPosition: function(x, y) {
		this.node.style.left = x + "px";
		this.node.style.top = y + "px";
	},
	
	setLevel: function(level) {
		if(level==0) this.opacity = 0.5;
		else if(level==1) this.opacity = 0.2;
		//else if(level==2) this.opacity = 0.1;
		else this.opacity = 0.0;
	},
	
	setChildWidth: function(width) {
		this.childNode.style.width = width + "px";
	},

	reset: function(evt) {
		// Delete the timeout for hiding
		window.clearTimeout( this.resetTimer )
		this.resetTimer = window.setTimeout( this.doReset.bind(this), 100)
		
		if(this.option.resetcallback) {
			this.option.arguments.push(evt);
			var state = this.option.resetcallback.apply(this, this.option.arguments);
			this.option.arguments.remove(evt);
		}
	},
	
	doReset: function() {
		
		if(this.node.hasClassName('Oryx_down'))
			this.node.removeClassName('Oryx_down');

		if(this.node.hasClassName('Oryx_hover'))
			this.node.removeClassName('Oryx_hover');

	},

	activate: function(evt) {
		this.node.addClassName('Oryx_down');
		//Event.stop(evt);
		this.dragStart = true;
	},

	isHover: function() {
		return this.node.hasClassName('Oryx_hover') ? true: false;
	},

	hover: function(evt) {
		// Delete the timeout for hiding
		window.clearTimeout( this.resetTimer )
		this.resetTimer = null;
		
		this.node.addClassName('Oryx_hover');
		this.dragStart = false;
		
		if(this.option.hovercallback) {
			this.option.arguments.push(evt);
			var state = this.option.hovercallback.apply(this, this.option.arguments);
			this.option.arguments.remove(evt);
		}
	},

	move: function(evt) {
		if(this.dragStart && this.option.dragcallback) {
			this.option.arguments.push(evt);
			var state = this.option.dragcallback.apply(this, this.option.arguments);
			this.option.arguments.remove(evt);
		}
	},

	trigger: function(evt) {
		if(this.option.callback) {
			//Event.stop(evt);
			this.option.arguments.push(evt);
			var state = this.option.callback.apply(this, this.option.arguments);
			this.option.arguments.remove(evt);
		}
		this.dragStart = false;
	},

	toString: function() {
		return "HTML-Button " + this.id;
	}
}
ORYX.Plugins.ShapeMenuButton = Clazz.extend(ORYX.Plugins.ShapeMenuButton);

//create command for undo/redo
ORYX.Plugins.ShapeMenuPlugin.CreateCommand = ORYX.Core.Command.extend({
	construct: function(option, currentReference, position, plugin){
		this.option = option;
		this.currentReference = currentReference;
		this.position = position;
		this.plugin = plugin;
		this.shape;
		this.edge;
		this.targetRefPos;
		this.sourceRefPos;
		/*
		 * clone options parameters
		 */
        this.connectedShape = option.connectedShape;
        this.connectingType = option.connectingType;
        this.namespace = option.namespace;
        this.type = option.type;
        this.containedStencil = option.containedStencil;
        this.parent = option.parent;
        this.currentReference = currentReference;
        this.shapeOptions = option.shapeOptions;
	},			
	execute: function(){
		
		var resume = false;
		
		if (this.shape) {
			if (this.shape instanceof ORYX.Core.Node) {
				this.parent.add(this.shape);
				if (this.edge) {
					this.plugin.facade.getCanvas().add(this.edge);
					this.edge.dockers.first().setDockedShape(this.connectedShape);
					this.edge.dockers.first().setReferencePoint(this.sourceRefPos);
					this.edge.dockers.last().setDockedShape(this.shape);
					this.edge.dockers.last().setReferencePoint(this.targetRefPos);
				}
				
				this.plugin.facade.setSelection([this.shape]);
				
			} else if (this.shape instanceof ORYX.Core.Edge) {
				this.plugin.facade.getCanvas().add(this.shape);
				this.shape.dockers.first().setDockedShape(this.connectedShape);
				this.shape.dockers.first().setReferencePoint(this.sourceRefPos);
			}
			resume = true;
		}
		else {
			this.shape = this.plugin.facade.createShape(this.option);
			this.edge = (!(this.shape instanceof ORYX.Core.Edge)) ? this.shape.getIncomingShapes().first() : undefined;
		}
		
		if (this.currentReference && this.position) {
			
			if (this.shape instanceof ORYX.Core.Edge) {
			
				if (!(this.currentReference instanceof ORYX.Core.Canvas)) {
					this.shape.dockers.last().setDockedShape(this.currentReference);
					
					// @deprecated It now uses simply the midpoint
					var upL = this.currentReference.absoluteXY();
					var refPos = {
						x: this.position.x - upL.x,
						y: this.position.y - upL.y
					};
					
					this.shape.dockers.last().setReferencePoint(this.currentReference.bounds.midPoint());
				}
				else {
					this.shape.dockers.last().bounds.centerMoveTo(this.position);
					//this.shape.dockers.last().update();
				}
				this.sourceRefPos = this.shape.dockers.first().referencePoint;
				this.targetRefPos = this.shape.dockers.last().referencePoint;
				
			} else if (this.edge){
				this.sourceRefPos = this.edge.dockers.first().referencePoint;
				this.targetRefPos = this.edge.dockers.last().referencePoint;
			}
		} else {
			var containedStencil = this.containedStencil;
			var connectedShape = this.connectedShape;
			var bc = connectedShape.bounds;
			var bs = this.shape.bounds;
			
			var pos = bc.center();
			if(containedStencil.defaultAlign()==="north") {
				pos.y -= (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET + (bs.height()/2);
			} else if(containedStencil.defaultAlign()==="northeast") {
				pos.x += (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.width()/2);
				pos.y -= (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.height()/2);
			} else if(containedStencil.defaultAlign()==="southeast") {
				pos.x += (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.width()/2);
				pos.y += (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.height()/2);
			} else if(containedStencil.defaultAlign()==="south") {
				pos.y += (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET + (bs.height()/2);
			} else if(containedStencil.defaultAlign()==="southwest") {
				pos.x -= (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.width()/2);
				pos.y += (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.height()/2);
			} else if(containedStencil.defaultAlign()==="west") {
				pos.x -= (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET + (bs.width()/2);
			} else if(containedStencil.defaultAlign()==="northwest") {
				pos.x -= (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.width()/2);
				pos.y -= (bc.height() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET_CORNER + (bs.height()/2);
			} else {
				pos.x += (bc.width() / 2) + ORYX.CONFIG.SHAPEMENU_CREATE_OFFSET + (bs.width()/2);
			}
			
			// Move shape to the new position
			this.shape.bounds.centerMoveTo(pos);
			
			// Move all dockers of a node to the position
			if (this.shape instanceof ORYX.Core.Node){
				(this.shape.dockers||[]).each(function(docker){
					docker.bounds.centerMoveTo(pos);
				})
			}
			
			//this.shape.update();
			this.position = pos;
			
			if (this.edge){
				this.sourceRefPos = this.edge.dockers.first().referencePoint;
				this.targetRefPos = this.edge.dockers.last().referencePoint;
			}
		}
		
		this.plugin.facade.getCanvas().update();
		this.plugin.facade.updateSelection();
		
		if (!resume) {
			// If there is a connected shape
			if (this.edge){
				// Try to layout it
				this.plugin.doLayout(this.edge);
			} else if (this.shape instanceof ORYX.Core.Edge){
				// Try to layout it
				this.plugin.doLayout(this.shape);
			}
		}

	},
	rollback: function(){
		this.plugin.facade.deleteShape(this.shape);
		if(this.edge) {
			this.plugin.facade.deleteShape(this.edge);
		}
		//this.currentParent.update();
		this.plugin.facade.setSelection(this.plugin.facade.getSelection().without(this.shape, this.edge));
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
if (!ORYX.Plugins) {
    ORYX.Plugins = new Object();
}

/**
 * This plugin is responsible for displaying loading indicators and to prevent
 * the user from accidently unloading the page by, e.g., pressing the backspace
 * button and returning to the previous site in history.
 * @param {Object} facade The editor plugin facade to register enhancements with.
 */
ORYX.Plugins.Loading = {

    construct: function(facade){
    
        this.facade = facade;
        
        // The parent Node
        this.node = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", this.facade.getCanvas().getHTMLContainer().parentNode, ['div', {
            'class': 'LoadingIndicator'
        }, '']);
        
        this.facade.registerOnEvent(ORYX.CONFIG.EVENT_LOADING_ENABLE, this.enableLoading.bind(this));
        this.facade.registerOnEvent(ORYX.CONFIG.EVENT_LOADING_DISABLE, this.disableLoading.bind(this));
		this.facade.registerOnEvent(ORYX.CONFIG.EVENT_LOADING_STATUS, this.showStatus.bind(this));
        
        this.disableLoading();

        //carga el modelo si procede
        if (ORYX.CONFIG.DATA != false)
        {

          if (arquematics.crypt)
          {

              ORYX.CONFIG.PASS = arquematics.crypt.decryptHexToString(ORYX.CONFIG.PASS);

              ORYX.CONFIG.DATA = arquematics.simpleCrypt.decryptBase64(ORYX.CONFIG.PASS, ORYX.CONFIG.DATA);
              
              var title = arquematics.simpleCrypt.decryptBase64(ORYX.CONFIG.PASS, $j('#note_title').val());
              $j('#note_title').val(title);
          }
            
          this.facade.importJSON(ORYX.CONFIG.DATA);       
        }
        
        
    },
    
    enableLoading: function(options){
		if(options.text) 
			this.node.innerHTML = options.text + "...";
		else
			this.node.innerHTML = ORYX.I18N.Loading.waiting;
		this.node.removeClassName('StatusIndicator');
		this.node.addClassName('LoadingIndicator');
                this.node.style.display = "block";
		
		var pos = this.facade.getCanvas().rootNode.parentNode.parentNode.parentNode.parentNode;

		this.node.style.top 		= pos.offsetTop + 'px';
		this.node.style.left 		= pos.offsetLeft +'px';
					
    },
    
    disableLoading: function(){
        this.node.style.display = "none";
    },
	
	showStatus: function(options) {
		if(options.text) {
			this.node.innerHTML = options.text;
			this.node.addClassName('StatusIndicator');
			this.node.removeClassName('LoadingIndicator');
			this.node.style.display = 'block';

			var pos = this.facade.getCanvas().rootNode.parentNode.parentNode.parentNode.parentNode;

			this.node.style.top 	= pos.offsetTop + 'px';
			this.node.style.left 	= pos.offsetLeft +'px';
												
			var tout = options.timeout ? options.timeout : 2000;
			
			window.setTimeout((function(){
            
                this.disableLoading();
                
            }).bind(this), tout);
		}
		
	}
};

ORYX.Plugins.Loading = Clazz.extend(ORYX.Plugins.Loading);

}(jQuery, $, arquematics, ORYX, document, window));


