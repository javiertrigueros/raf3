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

/**
 * Init namespaces
 */
if(!ORYX) {var ORYX = {};}
if(!ORYX.Core) {ORYX.Core = {};}

/**
 * @classDescription Base class for Shapes.
 * @extends ORYX.Core.AbstractShape
 */
ORYX.Core.Shape = {

	/**
	 * Constructor
	 */
	construct: function(options, stencil) {
		// call base class constructor
		arguments.callee.$.construct.apply(this, arguments);
		
		this.dockers = [];
		this.magnets = [];
		
		this._defaultMagnet;
		
		this.incoming = [];
		this.outgoing = [];
		
		this.nodes = [];
		
		this._dockerChangedCallback = this._dockerChanged.bind(this);
		
		//Hash map for all labels. Labels are not treated as children of shapes.
		this._labels = new Hash();
		
		// create SVG node
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg",
			null,
			['g', {id:this.id},
				['g', {"class": "stencils"},
					['g', {"class": "me"}],
					['g', {"class": "children", style:"overflow:hidden"}],
					['g', {"class": "edge"}]
				],
				['g', {"class": "controls"},
					['g', {"class": "dockers"}],
					['g', {"class": "magnets"}]				
				]
			]);
	},

	/**
	 * If changed flag is set, refresh method is called.
	 */
	update: function() {
		//if(this.isChanged) {
			//this.layout();
		//}
	},
	
	/**
	 * !!!Not called from any sub class!!!
	 */
	_update: function() {

	},
	
	/**
	 * Calls the super class refresh method
	 *  and updates the svg elements that are referenced by a property.
	 */
	refresh: function() {
		//call base class refresh method
		arguments.callee.$.refresh.apply(this, arguments);
		
		if(this.node.ownerDocument) {
			//adjust SVG to properties' values
			var me = this;
			this.propertiesChanged.each((function(propChanged) {
				if(propChanged.value) {
					var prop = this.properties[propChanged.key];
					var property = this.getStencil().property(propChanged.key);
					this.propertiesChanged[propChanged.key] = false;

					//handle choice properties
					if(property.type() == ORYX.CONFIG.TYPE_CHOICE) {
						//iterate all references to SVG elements
						property.refToView().each((function(ref) {
							//if property is referencing a label, update the label
							if(ref !== "") {
								var label = this._labels[this.id + ref];
								if (label) {
									// if a choice is not valid anymore (due to changed stencil set), choose the default value
									if ("undefined" == typeof(property.item(prop)) || !property.item(prop)) {
										label.text(property.value()); // standard value of the stencil set
									}
									else {
										label.text(property.item(prop).value());
									}
								}
							}
						}).bind(this));
							
						//if the choice's items are referencing SVG elements
						// show the selected and hide all other referenced SVG
						// elements
						var refreshedSvgElements = new Hash();
						property.items().each((function(item) {
							item.refToView().each((function(itemRef) {
								if(itemRef == "") { this.propertiesChanged[propChanged.key] = true; return; }
								
								var svgElem = this.node.ownerDocument.getElementById(this.id + itemRef);
	
								if(!svgElem) { this.propertiesChanged[propChanged.key] = true; return; }
								
								
								/* Do not refresh the same svg element multiple times */
								if(!refreshedSvgElements[svgElem.id] || prop == item.value()) {
									svgElem.setAttributeNS(null, 'display', ((prop == item.value()) ? 'inherit' : 'none'));
									refreshedSvgElements[svgElem.id] = svgElem;
								}
								
								// Reload the href if there is an image-tag
								if(ORYX.Editor.checkClassType(svgElem, SVGImageElement)) {
									svgElem.setAttributeNS('http://www.w3.org/1999/xlink', 'href', svgElem.getAttributeNS('http://www.w3.org/1999/xlink', 'href'));
								}
							}).bind(this));
						}).bind(this));
						
					} else { //handle properties that are not of type choice
						//iterate all references to SVG elements
						property.refToView().each((function(ref) {
							//if the property does not reference an SVG element,
							// do nothing

							if(ref === "") { this.propertiesChanged[propChanged.key] = true; return; }
		
							var refId = this.id + ref;

							//get the SVG element
							var svgElem = this.node.ownerDocument.getElementById(refId);

							//if the SVG element can not be found
							if(!svgElem || !(svgElem.ownerSVGElement)) { 
								//if the referenced SVG element is a SVGAElement, it cannot
								// be found with getElementById (Firefox bug).
								// this is a work around
								if(property.type() === ORYX.CONFIG.TYPE_URL || property.type() === ORYX.CONFIG.TYPE_DIAGRAM_LINK) {
									var svgElems = this.node.ownerDocument.getElementsByTagNameNS('http://www.w3.org/2000/svg', 'a');
									
									svgElem = $A(svgElems).find(function(elem) {
										return elem.getAttributeNS(null, 'id') === refId;
									});
									
									if(!svgElem) { this.propertiesChanged[propChanged.key] = true; return; } 
								} else {
									this.propertiesChanged[propChanged.key] = true;
									return;
								}					
							}
							
							if (property.complexAttributeToView()) {
								var label = this._labels[refId];
								if (label) {
									try {
								    	propJson = prop.evalJSON();
								    	var value = propJson[property.complexAttributeToView()]
								    	label.text(value ? value : prop);
								    } catch (e) {
								    	label.text(prop);
								    }
								}
								
							} else {

								switch (property.type()) {
									case ORYX.CONFIG.TYPE_BOOLEAN:	
										
										if (typeof prop == "string")
											prop = prop === "true"
	
										svgElem.setAttributeNS(null, 'display', (!(prop === property.inverseBoolean())) ? 'inherit' : 'none');
										
										break;
									case ORYX.CONFIG.TYPE_COLOR:
										if(property.fill()) {
											if (svgElem.tagName.toLowerCase() === "stop"){
												svgElem.setAttributeNS(null, "stop-color", prop);
												
												// Adjust stop color of the others
												if (svgElem.parentNode.tagName.toLowerCase() === "radialgradient"){
													ORYX.Utils.adjustGradient(svgElem.parentNode, svgElem);
												}
											} else {
												svgElem.setAttributeNS(null, 'fill', prop);
											}
										}
										if(property.stroke()) {
											svgElem.setAttributeNS(null, 'stroke', prop);
										}
										break;
									case ORYX.CONFIG.TYPE_STRING:
										var label = this._labels[refId];
										if (label) {
											label.text(prop);
										}
										break;
									case ORYX.CONFIG.TYPE_INTEGER:
										var label = this._labels[refId];
										if (label) {
											label.text(prop);
										}
										break;
									case ORYX.CONFIG.TYPE_FLOAT:
										if(property.fillOpacity()) {
											svgElem.setAttributeNS(null, 'fill-opacity', prop);
										} 
										if(property.strokeOpacity()) {
											svgElem.setAttributeNS(null, 'stroke-opacity', prop);
										}
										if(!property.fillOpacity() && !property.strokeOpacity()) {
											var label = this._labels[refId];
											if (label) {
												label.text(prop);
											}
										}
										break;
									case ORYX.CONFIG.TYPE_URL:
									case ORYX.CONFIG.TYPE_DIAGRAM_LINK:
										//TODO what is the dafault path?
										var hrefAttr = svgElem.getAttributeNodeNS('http://www.w3.org/1999/xlink', 'xlink:href');
										if(hrefAttr) {
											hrefAttr.textContent = prop;
										} else {
											svgElem.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', prop);
										}	
										break;
								}
							}
						}).bind(this));
						
						
					}
					
				}
			}).bind(this));
			
			//update labels
			this._labels.values().each(function(label) {
				label.update();
			});
		}
	},
	
	layout: function() {
		//this.getStencil().layout(this)
		var layoutEvents = this.getStencil().layout()
		if(this instanceof ORYX.Core.Node && layoutEvents) {
			layoutEvents.each(function(event) {
				
				// setup additional attributes
				event.shape = this;
				event.forceExecution = true;
				
				// do layouting
				this._delegateEvent(event);
			}.bind(this))
			
		}
	},
	
	/**
	 * Returns an array of Label objects.
	 */
	getLabels: function() {
		return this._labels.values();
	},

	/**
	 * Returns an array of dockers of this object.
	 */
	getDockers: function() {
		return this.dockers;
	},
	
	getMagnets: function() {
		return this.magnets;
	},
	
	getDefaultMagnet: function() {
		if(this._defaultMagnet) {
			return this._defaultMagnet;
		} else if (this.magnets.length > 0) {
			return this.magnets[0];
		} else {
			return undefined;
		}
	},

	getParentShape: function() {
		return this.parent;
	},
	
	getIncomingShapes: function(iterator) {
		if(iterator) {
			this.incoming.each(iterator);
		}
		return this.incoming;
	},
	
	getIncomingNodes: function(iterator) {
        return this.incoming.select(function(incoming){
            var isNode = (incoming instanceof ORYX.Core.Node);
            if(isNode && iterator) iterator(incoming);
            return isNode;
        });
    },
	
	
	getOutgoingShapes: function(iterator) {
		if(iterator) {
			this.outgoing.each(iterator);
		}
		return this.outgoing;
	},
    
    getOutgoingNodes: function(iterator) {
        return this.outgoing.select(function(out){
            var isNode = (out instanceof ORYX.Core.Node);
            if(isNode && iterator) iterator(out);
            return isNode;
        });
    },
	
	getAllDockedShapes: function(iterator) {
		var result = this.incoming.concat(this.outgoing);
		if(iterator) {
			result.each(iterator);
		}
		return result;
	},

	getCanvas: function() {
		if(this.parent instanceof ORYX.Core.Canvas) {
			return this.parent;
		} else if(this.parent instanceof ORYX.Core.Shape) {
			return this.parent.getCanvas();
		} else {
			return undefined;
		}
	},
	
	/**
	 * 
	 * @param {Object} deep
	 * @param {Object} iterator
	 */
	getChildNodes: function(deep, iterator) {
		if(!deep && !iterator) {
			return this.nodes.clone();
		} else {
			var result = [];
			this.nodes.each(function(uiObject) {
				if(!uiObject.isVisible){return}
				if(iterator) {
					iterator(uiObject);
				}
				result.push(uiObject);
				
				if(deep && uiObject instanceof ORYX.Core.Shape) {
					result = result.concat(uiObject.getChildNodes(deep, iterator));
				}
			});
	
			return result;
		}
	},
	
	/**
	 * Overrides the UIObject.add method. Adds uiObject to the correct sub node.
	 * @param {UIObject} uiObject
	 * @param {Number} index
	 */
	add: function(uiObject, index) {
               
		//parameter has to be an UIObject, but
		// must not be an Edge.
		if(uiObject instanceof ORYX.Core.UIObject 
			&& !(uiObject instanceof ORYX.Core.Edge)) {
			
                      
                
			if (!(this.children.member(uiObject))) {
				//if uiObject is child of another parent, remove it from that parent.
				if(uiObject.parent) {
					uiObject.parent.remove(uiObject);
				}

				//add uiObject to this Shape
				if(index != undefined)
					this.children.splice(index, 0, uiObject);
				else
					this.children.push(uiObject);

				//set parent reference
				uiObject.parent = this;

				//add uiObject.node to this.node depending on the type of uiObject
				var parent;
				if(uiObject instanceof ORYX.Core.Node) {
					parent = this.node.childNodes[0].childNodes[1];
					this.nodes.push(uiObject);
				} else if(uiObject instanceof ORYX.Core.Controls.Control) {
					var ctrls = this.node.childNodes[1];
					if(uiObject instanceof ORYX.Core.Controls.Docker) {
						parent = ctrls.childNodes[0];
						if (this.dockers.length >= 2){
							this.dockers.splice(index!==undefined?Math.min(index, this.dockers.length-1):this.dockers.length-1, 0, uiObject);
						} else {
							this.dockers.push(uiObject);
						}
					} else if(uiObject instanceof ORYX.Core.Controls.Magnet) {
						parent = ctrls.childNodes[1];
						this.magnets.push(uiObject);
					} else {
						parent = ctrls;
					}
				} else {	//UIObject
					parent = this.node;
				}

				if(index != undefined && index < parent.childNodes.length)
					uiObject.node = parent.insertBefore(uiObject.node, parent.childNodes[index]);
				else
					uiObject.node = parent.appendChild(uiObject.node);
					
				this._changed();
				//uiObject.bounds.registerCallback(this._changedCallback);
				
				
				if(this.eventHandlerCallback)
					this.eventHandlerCallback({type:ORYX.CONFIG.EVENT_SHAPEADDED,shape:uiObject})
					
			} else {

				ORYX.Log.warn("add: ORYX.Core.UIObject is already a child of this object.");
			}
		} else {

			ORYX.Log.warn("add: Parameter is not of type ORYX.Core.UIObject.");
		}
	},

	/**
	 * Overrides the UIObject.remove method. Removes uiObject.
	 * @param {UIObject} uiObject
	 */
	remove: function(uiObject) {
		//if uiObject is a child of this object, remove it.
		if (this.children.member(uiObject)) {
			//remove uiObject from children
			this.children = this.children.without(uiObject);

			//delete parent reference of uiObject
			uiObject.parent = undefined;

			//delete uiObject.node from this.node
			if(uiObject instanceof ORYX.Core.Shape) {
				if(uiObject instanceof ORYX.Core.Edge) {
					uiObject.removeMarkers();
					uiObject.node = this.node.childNodes[0].childNodes[2].removeChild(uiObject.node);
				} else {
					uiObject.node = this.node.childNodes[0].childNodes[1].removeChild(uiObject.node);
					this.nodes = this.nodes.without(uiObject);
				}
			} else if(uiObject instanceof ORYX.Core.Controls.Control) {
				if (uiObject instanceof ORYX.Core.Controls.Docker) {
					uiObject.node = this.node.childNodes[1].childNodes[0].removeChild(uiObject.node);
					this.dockers = this.dockers.without(uiObject);
				} else if (uiObject instanceof ORYX.Core.Controls.Magnet) {
					uiObject.node = this.node.childNodes[1].childNodes[1].removeChild(uiObject.node);
					this.magnets = this.magnets.without(uiObject);
				} else {
					uiObject.node = this.node.childNodes[1].removeChild(uiObject.node);
				}
			}

			this._changed();
			//uiObject.bounds.unregisterCallback(this._changedCallback);
		} else {

			ORYX.Log.warn("remove: ORYX.Core.UIObject is not a child of this object.");
		}
	},
	
	/**
	 * Calculate the Border Intersection Point between two points
	 * @param {PointA}
	 * @param {PointB}
	 */
	getIntersectionPoint: function() {
			
		var pointAX, pointAY, pointBX, pointBY;
		
		// Get the the two Points	
		switch(arguments.length) {
			case 2:
				pointAX = arguments[0].x;
				pointAY = arguments[0].y;
				pointBX = arguments[1].x;
				pointBY = arguments[1].y;
				break;
			case 4:
				pointAX = arguments[0];
				pointAY = arguments[1];
				pointBX = arguments[2];
				pointBY = arguments[3];
				break;
			default:
				throw "getIntersectionPoints needs two or four arguments";
		}
		
		
		
		// Defined an include and exclude point
		var includePointX, includePointY, excludePointX, excludePointY;

		var bounds = this.absoluteBounds();
		
		if(this.isPointIncluded(pointAX, pointAY, bounds)){
			includePointX = pointAX;
			includePointY = pointAY;
		} else {
			excludePointX = pointAX;
			excludePointY = pointAY;
		}

		if(this.isPointIncluded(pointBX, pointBY, bounds)){
			includePointX = pointBX;
			includePointY = pointBY;
		} else {
			excludePointX = pointBX;
			excludePointY = pointBY;
		}
				
		// If there is no inclue or exclude Shape, than return
		if(!includePointX || !includePointY || !excludePointX || !excludePointY) {
			return undefined;
		}

		var midPointX = 0;
		var midPointY = 0;		
		
		var refPointX, refPointY;
		
		var minDifferent = 1;
		// Get the UpperLeft and LowerRight
		//var ul = bounds.upperLeft();
		//var lr = bounds.lowerRight();
		
		var i = 0;
		
		while(true) {
			// Calculate the midpoint of the current to points	
			var midPointX = Math.min(includePointX, excludePointX) + ((Math.max(includePointX, excludePointX) - Math.min(includePointX, excludePointX)) / 2.0);
			var midPointY = Math.min(includePointY, excludePointY) + ((Math.max(includePointY, excludePointY) - Math.min(includePointY, excludePointY)) / 2.0);
			
			
			// Set the new midpoint by the means of the include of the bounds
			if(this.isPointIncluded(midPointX, midPointY, bounds)){
				includePointX = midPointX;
				includePointY = midPointY;
			} else {
				excludePointX = midPointX;
				excludePointY = midPointY;
			}			
			
			// Calc the length of the line
			var length = Math.sqrt(Math.pow(includePointX - excludePointX, 2) + Math.pow(includePointY - excludePointY, 2))
			// Calc a point one step from the include point
			refPointX = includePointX + ((excludePointX - includePointX) / length),
			refPointY = includePointY + ((excludePointY - includePointY) / length)
					
			
			// If the reference point not in the bounds, break
			if(!this.isPointIncluded(refPointX, refPointY, bounds)) {
				break
			}
							
			
		}

		// Return the last includepoint
		return {x:refPointX , y:refPointY};
	},

   
    
    /**
     * Calculate if the point is inside the Shape
     * @param {PointX}
     * @param {PointY} 
     */
    isPointIncluded: function(){
		return  false
	},

    
    /**
     * Calculate if the point is over an special offset area
     * @param {Point}
     */
    isPointOverOffset: function(){
		return  this.isPointIncluded.apply( this , arguments )
	},
		
	_dockerChanged: function() {

	},
		
	/**
	 * Create a Docker for this Edge
	 *
	 */
	createDocker: function(index, position) {
		var docker = new ORYX.Core.Controls.Docker({eventHandlerCallback: this.eventHandlerCallback});
		docker.bounds.registerCallback(this._dockerChangedCallback);
		if(position) {
			docker.bounds.centerMoveTo(position);
		}
		this.add(docker, index);
		
		return docker
	},

	/**
	 * Get the serialized object
	 * return Array with hash-entrees (prefix, name, value)
	 * Following values will given:
	 * 		Bounds
	 * 		Outgoing Shapes
	 * 		Parent
	 */
	serialize: function() {
		var serializedObject = arguments.callee.$.serialize.apply(this);

		// Add the bounds
		serializedObject.push({name: 'bounds', prefix:'oryx', value: this.bounds.serializeForERDF(), type: 'literal'});

		// Add the outgoing shapes
		this.getOutgoingShapes().each((function(followingShape){
			serializedObject.push({name: 'outgoing', prefix:'raziel', value: '#'+ERDF.__stripHashes(followingShape.resourceId), type: 'resource'});			
		}).bind(this));

		// Add the parent shape, if the parent not the canvas
		//if(this.parent instanceof ORYX.Core.Shape){
			serializedObject.push({name: 'parent', prefix:'raziel', value: '#'+ERDF.__stripHashes(this.parent.resourceId), type: 'resource'});	
		//}			
		
		return serializedObject;
	},
		
		
	deserialize: function(serialze){
		arguments.callee.$.deserialize.apply(this, arguments);
		
		// Set the Bounds
		var bounds = serialze.find(function(ser){ return (ser.prefix+"-"+ser.name) == 'oryx-bounds'});
		if(bounds) {
			var b = bounds.value.replace(/,/g, " ").split(" ").without("");
			if(this instanceof ORYX.Core.Edge){
				this.dockers.first().bounds.centerMoveTo(parseFloat(b[0]), parseFloat(b[1]));
				this.dockers.last().bounds.centerMoveTo(parseFloat(b[2]), parseFloat(b[3]));
			} else {
				this.bounds.set(parseFloat(b[0]), parseFloat(b[1]), parseFloat(b[2]), parseFloat(b[3]));
			}			
		}
	},

		
	/**
	 * Private methods.
	 */

	/**
	 * Child classes have to overwrite this method for initializing a loaded
	 * SVG representation.
	 * @param {SVGDocument} svgDocument
	 */
	_init: function(svgDocument) {
		//adjust ids
		this._adjustIds(svgDocument, 0);
	},

	_adjustIds: function(element, idIndex) {
		if(element instanceof Element) {
			var eid = element.getAttributeNS(null, 'id');
			if(eid && eid !== "") {
				element.setAttributeNS(null, 'id', this.id + eid);
			} else {
				element.setAttributeNS(null, 'id', this.id + "_" + this.id + "_" + idIndex);
				idIndex++;
			}
			
			// Replace URL in fill attribute
			var fill = element.getAttributeNS(null, 'fill');
			if (fill&&fill.include("url(#")){
				fill = fill.replace(/url\(#/g, 'url(#'+this.id);
				element.setAttributeNS(null, 'fill', fill);
			}
			
			if(element.hasChildNodes()) {
				for(var i = 0; i < element.childNodes.length; i++) {
					idIndex = this._adjustIds(element.childNodes[i], idIndex);
				}
			}
		}
		return idIndex;
	},

	toString: function() { return "ORYX.Core.Shape " + this.getId() }
};
ORYX.Core.Shape = ORYX.Core.AbstractShape.extend(ORYX.Core.Shape);/**
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

/**
 * Init namespaces
 */
if(!ORYX) {var ORYX = {};}
if(!ORYX.Core) {ORYX.Core = {};}
if(!ORYX.Core.Controls) {ORYX.Core.Controls = {};}


/**
 * @classDescription Abstract base class for all Controls.
 */
ORYX.Core.Controls.Control = ORYX.Core.UIObject.extend({
	
	toString: function() { return "Control " + this.id; }
 });/**
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


/**
 * Init namespaces
 */
if(!ORYX) {var ORYX = {};}
if(!ORYX.Core) {ORYX.Core = {};}
if(!ORYX.Core.Controls) {ORYX.Core.Controls = {};}


/**
 * @classDescription Represents a movable docker that can be bound to a shape. Dockers are used
 * for positioning shape objects.
 * @extends {Control}
 * 
 * TODO absoluteXY und absoluteCenterXY von einem Docker liefern falsche Werte!!!
 */
ORYX.Core.Controls.Docker = ORYX.Core.Controls.Control.extend({
	/**
	 * Constructor
	 */
	construct: function() {
		arguments.callee.$.construct.apply(this, arguments);
		
		this.isMovable = true;				// Enables movability
		this.bounds.set(0, 0, 16, 16);		// Set the bounds
		this.referencePoint = undefined;		// Refrenzpoint 
		this._dockedShapeBounds = undefined;		
		this._dockedShape = undefined;
		this._oldRefPoint1 = undefined;
		this._oldRefPoint2 = undefined;
		
		//this.anchors = [];
		this.anchorLeft;
		this.anchorRight;
		this.anchorTop;
		this.anchorBottom;

		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg",
			null,
			['g']);

		// The DockerNode reprasentation
		this._dockerNode = ORYX.Editor.graft("http://www.w3.org/2000/svg",
			this.node,
			['g', {"pointer-events":"all"},
					['circle', {cx:"8", cy:"8", r:"8", stroke:"none", fill:"none"}],
					['circle', {cx:"8", cy:"8", r:"3", stroke:"black", fill:"red", "stroke-width":"1"}]
				]);
			
		// The ReferenzNode reprasentation	
		this._referencePointNode = ORYX.Editor.graft("http://www.w3.org/2000/svg",
			this.node,	
			['g', {"pointer-events":"none"},
				['circle', {cx: this.bounds.upperLeft().x, cy: this.bounds.upperLeft().y, r: 3, fill:"red", "fill-opacity":0.4}]]);

		// Hide the Docker
		this.hide();
		
		//Add to the EventHandler
		this.addEventHandlers(this.node);

		// Buffer the Update Callback for un-/register on Event-Handler 
		this._updateCallback = this._changed.bind(this);
	},
	
	update: function() {
		// If there have an DockedShape	
		if(this._dockedShape) {
			if(this._dockedShapeBounds && this._dockedShape instanceof ORYX.Core.Node) {
				// Calc the delta of width and height of the lastBounds and the current Bounds
				var dswidth = this._dockedShapeBounds.width();
				var dsheight = this._dockedShapeBounds.height();
				if(!dswidth)
					dswidth = 1;
				if(!dsheight)
					dsheight = 1;	
				var widthDelta = 	this._dockedShape.bounds.width() 	/ dswidth;
				var heightDelta = 	this._dockedShape.bounds.height() 	/ dsheight;
				
				// If there is an different
				if(widthDelta !== 1.0 || heightDelta !== 1.0) {
					// Set the delta
					this.referencePoint.x *= widthDelta;
					this.referencePoint.y *= heightDelta;
				}
	
				// Clone these bounds
				this._dockedShapeBounds = this._dockedShape.bounds.clone();				
			}
			
			// Get the first and the last Docker of the parent Shape
			var dockerIndex = this.parent.dockers.indexOf(this)
			var dock1 = this;
			var dock2 = this.parent.dockers.length > 1 ? 
							(dockerIndex === 0?							// If there is the first element
							 	this.parent.dockers[dockerIndex + 1]:	// then take the next docker
								this.parent.dockers[dockerIndex - 1]):  // if not, then take the docker before
							undefined;
			
			// Calculate the first absolute Refenzpoint 
			var absoluteReferenzPoint1 = dock1.getDockedShape() ? 
				dock1.getAbsoluteReferencePoint() : 
				dock1.bounds.center();

			// Calculate the last absolute Refenzpoint 
			var absoluteReferenzPoint2 = dock2 && dock2.getDockedShape() ? 
				dock2.getAbsoluteReferencePoint() : 
				dock2 ? 
					dock2.bounds.center() :
					undefined;

			// If there is no last absolute Referenzpoint		
			if(!absoluteReferenzPoint2) {
				// Calculate from the middle of the DockedShape
				var center = this._dockedShape.absoluteCenterXY();
				var minDimension = this._dockedShape.bounds.width() * this._dockedShape.bounds.height(); 
				absoluteReferenzPoint2 = {
					x: absoluteReferenzPoint1.x + (center.x - absoluteReferenzPoint1.x) * -minDimension,
					y: absoluteReferenzPoint1.y + (center.y - absoluteReferenzPoint1.y) * -minDimension
				}
			}
			
			var newPoint = undefined;
			
			/*if (!this._oldRefPoint1 || !this._oldRefPoint2 ||
				absoluteReferenzPoint1.x !== this._oldRefPoint1.x ||
				absoluteReferenzPoint1.y !== this._oldRefPoint1.y ||
				absoluteReferenzPoint2.x !== this._oldRefPoint2.x ||
				absoluteReferenzPoint2.y !== this._oldRefPoint2.y) {*/
				
				// Get the new point for the Docker, calucalted by the intersection point of the Shape and the two points
				newPoint = this._dockedShape.getIntersectionPoint(absoluteReferenzPoint1, absoluteReferenzPoint2);
				
				// If there is new point, take the referencepoint as the new point
				if(!newPoint) {
					newPoint = this.getAbsoluteReferencePoint();
				}
				
				if(this.parent && this.parent.parent) {
					var grandParentPos = this.parent.parent.absoluteXY();
					newPoint.x -= grandParentPos.x;
					newPoint.y -= grandParentPos.y;
				}
				
				// Set the bounds to the new point
				this.bounds.centerMoveTo(newPoint)
			
				this._oldRefPoint1 = absoluteReferenzPoint1;
				this._oldRefPoint2 = absoluteReferenzPoint2;
			} 
			/*else {
				newPoint = this.bounds.center();
			}*/
			
			
	//	}
		
		// Call the super class
		arguments.callee.$.update.apply(this, arguments);
	},

	/**
	 * Calls the super class refresh method and updates the view of the docker.
	 */
	refresh: function() {
		arguments.callee.$.refresh.apply(this, arguments);
		
		// Refresh the dockers node
		var p = this.bounds.upperLeft();
		this._dockerNode.setAttributeNS(null, 'transform','translate(' + p.x + ', ' + p.y + ')');
		
		// Refresh the referencepoints node
		p = Object.clone(this.referencePoint);
		
		if(p && this._dockedShape){
			var upL 
			if(this.parent instanceof ORYX.Core.Edge) {
				upL = this._dockedShape.absoluteXY();
			} else {
				upL = this._dockedShape.bounds.upperLeft();
			}
			p.x += upL.x;
			p.y += upL.y;
		} else {
			p = this.bounds.center();
		}			

		this._referencePointNode.setAttributeNS(null, 'transform','translate(' + p.x + ', ' + p.y + ')');
	},

	/**
	 * Set the reference point
	 * @param {Object} point
	 */	
	setReferencePoint: function(point) {
		// Set the referencepoint
		if(this.referencePoint !== point &&
			(!this.referencePoint || 
			!point ||
			this.referencePoint.x !== point.x || 
			this.referencePoint.y !== point.y)) {
				
			this.referencePoint = point;
			this._changed();			
		}

		
		// Update directly, because the referencepoint has no influence of the bounds
		//this.refresh();
	},
	
	/**
	 * Get the absolute referencepoint
	 */
	getAbsoluteReferencePoint: function() {
		if(!this.referencePoint || !this._dockedShape) {
			return undefined;
		} else {
			var absUL = this._dockedShape.absoluteXY();
			return {	
						x: this.referencePoint.x + absUL.x,
						y: this.referencePoint.y + absUL.y
					}
		}
	},	
	
	/**
	 * Set the docked Shape from the docker
	 * @param {Object} shape
	 */
	setDockedShape: function(shape) {

		// If there is an old docked Shape
		if(this._dockedShape) {
			this._dockedShape.bounds.unregisterCallback(this._updateCallback)
			
			// Delete the Shapes from the incoming and outgoing array
			// If this Docker the incoming of the Shape
			if(this === this.parent.dockers.first()) {
				
				this.parent.incoming = this.parent.incoming.without(this._dockedShape);
				this._dockedShape.outgoing = this._dockedShape.outgoing.without(this.parent);
			
			// If this Docker the outgoing of the Shape	
			} else if (this === this.parent.dockers.last()){
	
				this.parent.outgoing = this.parent.outgoing.without(this._dockedShape);
				this._dockedShape.incoming = this._dockedShape.incoming.without(this.parent);
							
			}
			
		}

		
		// Set the new Shape
		this._dockedShape = shape;
		this._dockedShapeBounds = undefined;
		var referencePoint = undefined;
		
		// If there is an Shape, register the updateCallback if there are changes in the shape bounds
		if(this._dockedShape) {
			
			// Add the Shapes to the incoming and outgoing array
			// If this Docker the incoming of the Shape
			if(this === this.parent.dockers.first()) {
				
				this.parent.incoming.push(shape);
				shape.outgoing.push(this.parent);
			
			// If this Docker the outgoing of the Shape	
			} else if (this === this.parent.dockers.last()){
	
				this.parent.outgoing.push(shape);
				shape.incoming.push(this.parent);
							
			}
			
			// Get the bounds and set the new referencepoint
			var bounds = this.bounds;
			var absUL = shape.absoluteXY();
			
			/*if(shape.parent){
				var b = shape.parent.bounds.upperLeft();
				absUL.x -= b.x;
				absUL.y -= b.y;
			}*/
			
			referencePoint = {
				x: bounds.center().x - absUL.x,
				y: bounds.center().y - absUL.y
			}	
						
			this._dockedShapeBounds = this._dockedShape.bounds.clone();
			
			this._dockedShape.bounds.registerCallback(this._updateCallback);
			
			// Set the color of the docker as docked
			this.setDockerColor(ORYX.CONFIG.DOCKER_DOCKED_COLOR);				
		} else {
			// Set the color of the docker as undocked
			this.setDockerColor(ORYX.CONFIG.DOCKER_UNDOCKED_COLOR);
		}

		// Set the referencepoint
		this.setReferencePoint(referencePoint);
		this._changed();
		//this.update();
	},
	
	/**
	 * Get the docked Shape
	 */
	getDockedShape: function() {
		return this._dockedShape;
	},

	/**
	 * Returns TRUE if the docker has a docked shape
	 */
	isDocked: function() {
		return !!this._dockedShape;
	},
		
	/**
	 * Set the Color of the Docker
	 * @param {Object} color
	 */
	setDockerColor: function(color) {
		this._dockerNode.lastChild.setAttributeNS(null, "fill", color);
	},
	
	/**
	 * Hides this UIObject and all its children.
	 */
	hide: function() {
		this.node.setAttributeNS(null, 'visibility', 'hidden');
		
		this.children.each(function(uiObj) {
			uiObj.hide();	
		});				
	},
	
	/**
	 * Enables visibility of this UIObject and all its children.
	 */
	show: function() {
		this.node.setAttributeNS(null, 'visibility', 'visible');
		
		this.children.each(function(uiObj) {
			uiObj.show();	
		});		
	},
	
	toString: function() { return "Docker " + this.id }
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

/**
 * Init namespaces
 */
if(!ORYX) {var ORYX = {};}
if(!ORYX.Core) {ORYX.Core = {};}
if(!ORYX.Core.Controls) {ORYX.Core.Controls = {};}


/**
 * @classDescription Represents a magnet that is part of another shape and can
 * be attached to dockers. Magnets are used for linking edge objects
 * to other Shape objects.
 * @extends {Control}
 */
ORYX.Core.Controls.Magnet = ORYX.Core.Controls.Control.extend({
		
	/**
	 * Constructor
	 */
	construct: function() {
		arguments.callee.$.construct.apply(this, arguments);
		
		//this.anchors = [];
		this.anchorLeft;
		this.anchorRight;
		this.anchorTop;
		this.anchorBottom;
		
		this.bounds.set(0, 0, 16, 16);
		
		//graft magnet's root node into owner's control group.
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg",
			null,
			['g', {"pointer-events":"all"},
					['circle', {cx:"8", cy:"8", r:"4", stroke:"none", fill:"red", "fill-opacity":"0.3"}],
				]);
			
		this.hide();
	},
	
	update: function() {
		arguments.callee.$.update.apply(this, arguments);
		
		//this.isChanged = true;
	},
	
	_update: function() {		
		arguments.callee.$.update.apply(this, arguments);
		
		//this.isChanged = true;
	},
	
	refresh: function() {
		arguments.callee.$.refresh.apply(this, arguments);

		var p = this.bounds.upperLeft();
		/*if(this.parent) {
			var parentPos = this.parent.bounds.upperLeft();
			p.x += parentPos.x;
			p.y += parentPos.y;
		}*/
		
		this.node.setAttributeNS(null, 'transform','translate(' + p.x + ', ' + p.y + ')');
	},
	
	show: function() {
		//this.refresh();
		arguments.callee.$.show.apply(this, arguments);
	},
	
	toString: function() {
		return "Magnet " + this.id;
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

/**
 * Init namespaces
 */
if (!ORYX) {
    var ORYX = {};
}
if (!ORYX.Core) {
    ORYX.Core = {};
}

/**
 * @classDescription Abstract base class for all Nodes.
 * @extends ORYX.Core.Shape
 */
ORYX.Core.Node = {

    /**
     * Constructor
     * @param options {Object} A container for arguments.
     * @param stencil {Stencil}
     */
    construct: function(options, stencil){
        arguments.callee.$.construct.apply(this, arguments);
        
        this.isSelectable = true;
        this.isMovable = true;
		this._dockerUpdated = false;
        
        this._oldBounds = new ORYX.Core.Bounds(); //init bounds with undefined values
        this._svgShapes = []; //array of all SVGShape objects of
        // SVG representation
        
        //TODO vielleicht in shape verschieben?
        this.minimumSize = undefined; // {width:..., height:...}
        this.maximumSize = undefined;
        
        //TODO vielleicht in shape oder uiobject verschieben?
        // vielleicht sogar isResizable ersetzen?
        this.isHorizontallyResizable = false;
        this.isVerticallyResizable = false;
        
        this.dataId = undefined;
        
        this._init(this._stencil.view());
    },
        
    /**
     * This method checks whether the shape is resized correctly and calls the
     * super class update method.
     */
    _update: function(){
		
		this.dockers.invoke("update");
		if (this.isChanged) {

			var bounds = this.bounds;
            var oldBounds = this._oldBounds;
						
			if (this.isResized) {
			
				var widthDelta = bounds.width() / oldBounds.width();
				var heightDelta = bounds.height() / oldBounds.height();
				
				//iterate over all relevant svg elements and resize them
				this._svgShapes.each(function(svgShape){
					//adjust width
					if (svgShape.isHorizontallyResizable) {
						svgShape.width = svgShape.oldWidth * widthDelta;
					}
					//adjust height
					if (svgShape.isVerticallyResizable) {
						svgShape.height = svgShape.oldHeight * heightDelta;
					}
					
					//check, if anchors are set
					var anchorOffset;
					var leftIncluded = svgShape.anchorLeft;
					var rightIncluded = svgShape.anchorRight;
					
					if (rightIncluded) {
						anchorOffset = oldBounds.width() - (svgShape.oldX + svgShape.oldWidth);
						if (leftIncluded) {
							svgShape.width = bounds.width() - svgShape.x - anchorOffset;
						}
						else {
							svgShape.x = bounds.width() - (anchorOffset + svgShape.width);
						}
					}
					else 
						if (!leftIncluded) {
							svgShape.x = widthDelta * svgShape.oldX;
							if (!svgShape.isHorizontallyResizable) {
								svgShape.x = svgShape.x + svgShape.width * widthDelta / 2 - svgShape.width / 2;
							}
						}
					
					var topIncluded = svgShape.anchorTop;
					var bottomIncluded = svgShape.anchorBottom;
					
					if (bottomIncluded) {
						anchorOffset = oldBounds.height() - (svgShape.oldY + svgShape.oldHeight);
						if (topIncluded) {
							svgShape.height = bounds.height() - svgShape.y - anchorOffset;
						}
						else {
							// Hack for choreography task layouting
							if (!svgShape._isYLocked) {
								svgShape.y = bounds.height() - (anchorOffset + svgShape.height);
							}
						}
					}
					else 
						if (!topIncluded) {
							svgShape.y = heightDelta * svgShape.oldY;
							if (!svgShape.isVerticallyResizable) {
								svgShape.y = svgShape.y + svgShape.height * heightDelta / 2 - svgShape.height / 2;
							}
						}
				});
				
				//check, if the current bounds is unallowed horizontally or vertically resized
				var p = {
					x: 0,
					y: 0
				};
				if (!this.isHorizontallyResizable && bounds.width() !== oldBounds.width()) {
					p.x = oldBounds.width() - bounds.width();
				}
				if (!this.isVerticallyResizable && bounds.height() !== oldBounds.height()) {
					p.y = oldBounds.height() - bounds.height();
				}
				if (p.x !== 0 || p.y !== 0) {
					bounds.extend(p);
				}
				
				//check, if the current bounds are between maximum and minimum bounds
				p = {
					x: 0,
					y: 0
				};
				var widthDifference, heightDifference;
				if (this.minimumSize) {
				
					ORYX.Log.debug("Shape (%0)'s min size: (%1x%2)", this, this.minimumSize.width, this.minimumSize.height);
					widthDifference = this.minimumSize.width - bounds.width();
					if (widthDifference > 0) {
						p.x += widthDifference;
					}
					heightDifference = this.minimumSize.height - bounds.height();
					if (heightDifference > 0) {
						p.y += heightDifference;
					}
				}
				if (this.maximumSize) {
				
					ORYX.Log.debug("Shape (%0)'s max size: (%1x%2)", this, this.maximumSize.width, this.maximumSize.height);
					widthDifference = bounds.width() - this.maximumSize.width;
					if (widthDifference > 0) {
						p.x -= widthDifference;
					}
					heightDifference = bounds.height() - this.maximumSize.height;
					if (heightDifference > 0) {
						p.y -= heightDifference;
					}
				}
				if (p.x !== 0 || p.y !== 0) {
					bounds.extend(p);
				}
				
				//update magnets
				
				var widthDelta = bounds.width() / oldBounds.width();
				var heightDelta = bounds.height() / oldBounds.height();
				
				var leftIncluded, rightIncluded, topIncluded, bottomIncluded, center, newX, newY;
				
				this.magnets.each(function(magnet){
					leftIncluded = magnet.anchorLeft;
					rightIncluded = magnet.anchorRight;
					topIncluded = magnet.anchorTop;
					bottomIncluded = magnet.anchorBottom;
					
					center = magnet.bounds.center();
					
					if (leftIncluded) {
						newX = center.x;
					}
					else 
						if (rightIncluded) {
							newX = bounds.width() - (oldBounds.width() - center.x)
						}
						else {
							newX = center.x * widthDelta;
						}
					
					if (topIncluded) {
						newY = center.y;
					}
					else 
						if (bottomIncluded) {
							newY = bounds.height() - (oldBounds.height() - center.y);
						}
						else {
							newY = center.y * heightDelta;
						}
					
					if (center.x !== newX || center.y !== newY) {
						magnet.bounds.centerMoveTo(newX, newY);
					}
				});
				
				//set new position of labels
				this.getLabels().each(function(label){
					leftIncluded = label.anchorLeft;
					rightIncluded = label.anchorRight;
					topIncluded = label.anchorTop;
					bottomIncluded = label.anchorBottom;
					
					
					if (leftIncluded) {
					
					}
					else 
						if (rightIncluded) {
							label.x = bounds.width() - (oldBounds.width() - label.oldX)
						}
						else {
							label.x *= widthDelta;
						}
					
					if (topIncluded) {
					
					}
					else 
						if (bottomIncluded) {
							label.y = bounds.height() - (oldBounds.height() - label.oldY);
						}
						else {
							label.y *= heightDelta;
						}
				});
				
				//update docker
				var docker = this.dockers[0];
				if (docker) {
					docker.bounds.unregisterCallback(this._dockerChangedCallback);
					if (!this._dockerUpdated) {
						docker.bounds.centerMoveTo(this.bounds.center());
						this._dockerUpdated = false;
					}
					
					docker.update();
					docker.bounds.registerCallback(this._dockerChangedCallback);
				}
				this.isResized = false;
			}
            
            this.refresh();
			
			this.isChanged = false;
			
			this._oldBounds = this.bounds.clone();
        }
		
		this.children.each(function(value) {
			if(!(value instanceof ORYX.Core.Controls.Docker)) {
				value._update();
			}
		});
		
		if (this.dockers.length > 0&&!this.dockers.first().getDockedShape()) {
			this.dockers.each(function(docker){
				docker.bounds.centerMoveTo(this.bounds.center())
			}.bind(this))
		}
		
		/*this.incoming.each((function(edge) {
			if(!(this.dockers[0] && this.dockers[0].getDockedShape() instanceof ORYX.Core.Node))
				edge._update(true);
		}).bind(this));
		
		this.outgoing.each((function(edge) {
			if(!(this.dockers[0] && this.dockers[0].getDockedShape() instanceof ORYX.Core.Node))
				edge._update(true);
		}).bind(this)); */
    },
    
    /**
     * This method repositions and resizes the SVG representation
     * of the shape.
     */
    refresh: function(){
        arguments.callee.$.refresh.apply(this, arguments);
        
        /** Movement */
        var x = this.bounds.upperLeft().x;
        var y = this.bounds.upperLeft().y;
        
        //set translation in transform attribute
        /*var attributeTransform = document.createAttributeNS(ORYX.CONFIG.NAMESPACE_SVG, "transform");
        attributeTransform.nodeValue = "translate(" + x + ", " + y + ")";
        this.node.firstChild.setAttributeNode(attributeTransform);*/
		// Move owner element
		this.node.firstChild.setAttributeNS(null, "transform", "translate(" + x + ", " + y + ")");
		// Move magnets
		this.node.childNodes[1].childNodes[1].setAttributeNS(null, "transform", "translate(" + x + ", " + y + ")");
        
        /** Resize */
        
        //iterate over all relevant svg elements and update them
        this._svgShapes.each(function(svgShape){
            svgShape.update();
        });
    },
    
    _dockerChanged: function(){
		var docker = this.dockers[0];
        
        //set the bounds of the the association
        this.bounds.centerMoveTo(docker.bounds.center());
        
		this._dockerUpdated = true;
        //this._update(true);
    },
    
    /**
     * This method traverses a tree of SVGElements and returns
     * all SVGShape objects. For each basic shape or path element
     * a SVGShape object is initialized.
     *
     * @param svgNode {SVGElement}
     * @return {Array} Array of SVGShape objects
     */
    _initSVGShapes: function(svgNode){
        var svgShapes = [];
        try {
            var svgShape = new ORYX.Core.SVG.SVGShape(svgNode);
            svgShapes.push(svgShape);
        } 
        catch (e) {
            //do nothing
        }
        
        if (svgNode.hasChildNodes()) {
            for (var i = 0; i < svgNode.childNodes.length; i++) {
                svgShapes = svgShapes.concat(this._initSVGShapes(svgNode.childNodes[i]));
            }
        }
        
        return svgShapes;
    },
    
    /**
     * Calculate if the point is inside the Shape
     * @param {PointX}
     * @param {PointY} 
     * @param {absoluteBounds} optional: for performance
     */
    isPointIncluded: function(pointX, pointY, absoluteBounds){
        // If there is an arguments with the absoluteBounds
        var absBounds = absoluteBounds && absoluteBounds instanceof ORYX.Core.Bounds ? absoluteBounds : this.absoluteBounds();
        
        if (!absBounds.isIncluded(pointX, pointY)) {
			return false;
		} else {
			
		}
			
        
        //point = Object.clone(point);
        var ul = absBounds.upperLeft();
        var x = pointX - ul.x;
        var y = pointY - ul.y;		
	
		var i=0;
		do {
			var isPointIncluded = this._svgShapes[i++].isPointIncluded( x, y );
		} while( !isPointIncluded && i < this._svgShapes.length)
		
		return isPointIncluded;

        /*return this._svgShapes.any(function(svgShape){
            return svgShape.isPointIncluded(point);
        });*/
    },
 
    
    /**
     * Calculate if the point is over an special offset area
     * @param {Point}
     */
    isPointOverOffset: function( pointX, pointY ){       
		var isOverEl = arguments.callee.$.isPointOverOffset.apply( this , arguments );
		
		if (isOverEl) {
						
	        // If there is an arguments with the absoluteBounds
	        var absBounds = this.absoluteBounds();
	        absBounds.widen( - ORYX.CONFIG.BORDER_OFFSET );
			
	        if ( !absBounds.isIncluded( pointX, pointY )) {
	            return true;
	        }		
		}
		
		return false;
		
	},
	   
    serialize: function(){
        var result = arguments.callee.$.serialize.apply(this);
        
        // Add the docker's bounds
        // nodes only have at most one docker!
        this.dockers.each((function(docker){
			if (docker.getDockedShape()) {
				var center = docker.referencePoint;
				center = center ? center : docker.bounds.center();
				result.push({
					name: 'docker',
					prefix: 'oryx',
					value: $H(center).values().join(','),
					type: 'literal'
				});
			}
        }).bind(this));
        
        // Get the spezific serialized object from the stencil
        try {
            //result = this.getStencil().serialize(this, result);

			var serializeEvent = this.getStencil().serialize();
			
			/*
			 * call serialize callback by reference, result should be found
			 * in serializeEvent.result
			 */
			if(serializeEvent.type) {
				serializeEvent.shape = this;
				serializeEvent.data = result;
				serializeEvent.result = undefined;
				serializeEvent.forceExecution = true;
				
				this._delegateEvent(serializeEvent);
				
				if(serializeEvent.result) {
					result = serializeEvent.result;
				}
			}
        } 
        catch (e) {
        }
        return result;
    },
    
    deserialize: function(data){
    	arguments.callee.$.deserialize.apply(this, [data]);
		
	    try {
            //data = this.getStencil().deserialize(this, data);

			var deserializeEvent = this.getStencil().deserialize();
			
			/*
			 * call serialize callback by reference, result should be found
			 * in serializeEventInfo.result
			 */
			if(deserializeEvent.type) {
				deserializeEvent.shape = this;
				deserializeEvent.data = data;
				deserializeEvent.result = undefined;
				deserializeEvent.forceExecution = true;
				
				this._delegateEvent(deserializeEvent);
				if(deserializeEvent.result) {
					data = deserializeEvent.result;
				}
			}
        } 
        catch (e) {
        }
		
		// Set the outgoing shapes
		var outgoing = data.findAll(function(ser){ return (ser.prefix+"-"+ser.name) == 'raziel-outgoing'});
		outgoing.each((function(obj){
			// TODO: Look at Canvas
			if(!this.parent) {return};
								
			// Set outgoing Shape
			var next = this.getCanvas().getChildShapeByResourceId(obj.value);
																	
			if(next){
				if(next instanceof ORYX.Core.Edge) {
					//Set the first docker of the next shape
					next.dockers.first().setDockedShape(this);
					next.dockers.first().setReferencePoint(next.dockers.first().bounds.center());
				} else if(next.dockers.length > 0) { //next is a node and next has a docker
					next.dockers.first().setDockedShape(this);
					//next.dockers.first().setReferencePoint({x: this.bounds.width() / 2.0, y: this.bounds.height() / 2.0});
				}
			}	
			
		}).bind(this));
        
        if (this.dockers.length === 1) {
            var dockerPos;
            dockerPos = data.find(function(entry){
                return (entry.prefix + "-" + entry.name === "oryx-dockers");
            });
            
            if (dockerPos) {
                var points = dockerPos.value.replace(/,/g, " ").split(" ").without("").without("#");
				if (points.length === 2 && this.dockers[0].getDockedShape()) {
                    this.dockers[0].setReferencePoint({
                        x: parseFloat(points[0]),
                        y: parseFloat(points[1])
                    });
                }
                else {
                    this.dockers[0].bounds.centerMoveTo(parseFloat(points[0]), parseFloat(points[1]));
                }
            }
        }
    },
    
    /**
     * This method excepts the SVGDoucment that is the SVG representation
     * of this shape.
     * The bounds of the shape are calculated, the SVG representation's upper left point
     * is moved to 0,0 and it the method sets if this shape is resizable.
     *
     * @param {SVGDocument} svgDocument
     */
    _init: function(svgDocument){
        arguments.callee.$._init.apply(this, arguments);
		
        var svgNode = svgDocument.getElementsByTagName("g")[0]; //outer most g node
        // set all required attributes
        var attributeTitle = svgDocument.ownerDocument.createAttributeNS(null, "title");
        attributeTitle.nodeValue = this.getStencil().title();
        svgNode.setAttributeNode(attributeTitle);
        
        var attributeId = svgDocument.ownerDocument.createAttributeNS(null, "id");
        attributeId.nodeValue = this.id;
        svgNode.setAttributeNode(attributeId);
        
        // 
        var stencilTargetNode = this.node.childNodes[0].childNodes[0]; //<g class=me>"
        svgNode = stencilTargetNode.appendChild(svgNode);
        
        // Add to the EventHandler
        this.addEventHandlers(svgNode);
        
        /**set minimum and maximum size*/
        var minSizeAttr = svgNode.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "minimumSize");
        if (minSizeAttr) {
            minSizeAttr = minSizeAttr.replace("/,/g", " ");
            var minSizeValues = minSizeAttr.split(" ");
            minSizeValues = minSizeValues.without("");
            
            if (minSizeValues.length > 1) {
                this.minimumSize = {
                    width: parseFloat(minSizeValues[0]),
                    height: parseFloat(minSizeValues[1])
                };
            }
            else {
                //set minimumSize to (1,1), so that width and height of the stencil can never be (0,0)
                this.minimumSize = {
                    width: 1,
                    height: 1
                };
            }
        }
        
        var maxSizeAttr = svgNode.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "maximumSize");
        if (maxSizeAttr) {
            maxSizeAttr = maxSizeAttr.replace("/,/g", " ");
            var maxSizeValues = maxSizeAttr.split(" ");
            maxSizeValues = maxSizeValues.without("");
            
            if (maxSizeValues.length > 1) {
                this.maximumSize = {
                    width: parseFloat(maxSizeValues[0]),
                    height: parseFloat(maxSizeValues[1])
                };
            }
        }
        
        if (this.minimumSize && this.maximumSize &&
        (this.minimumSize.width > this.maximumSize.width ||
        this.minimumSize.height > this.maximumSize.height)) {
        
            //TODO wird verschluckt!!!
            throw this + ": Minimum Size must be greater than maxiumSize.";
        }
        
        /**get current bounds and adjust it to upperLeft == (0,0)*/
        //initialize all SVGShape objects
        this._svgShapes = this._initSVGShapes(svgNode);
        
        //get upperLeft and lowerRight of stencil
        var upperLeft = {
            x: undefined,
            y: undefined
        };
        var lowerRight = {
            x: undefined,
            y: undefined
        };
        var me = this;
        this._svgShapes.each(function(svgShape){
            upperLeft.x = (upperLeft.x !== undefined) ? Math.min(upperLeft.x, svgShape.x) : svgShape.x;
            upperLeft.y = (upperLeft.y !== undefined) ? Math.min(upperLeft.y, svgShape.y) : svgShape.y;
            lowerRight.x = (lowerRight.x !== undefined) ? Math.max(lowerRight.x, svgShape.x + svgShape.width) : svgShape.x + svgShape.width;
            lowerRight.y = (lowerRight.y !== undefined) ? Math.max(lowerRight.y, svgShape.y + svgShape.height) : svgShape.y + svgShape.height;
            
            /** set if resizing is enabled */
            //TODO isResizable durch die beiden anderen booleans ersetzen?
            if (svgShape.isHorizontallyResizable) {
                me.isHorizontallyResizable = true;
                me.isResizable = true;
            }
            if (svgShape.isVerticallyResizable) {
                me.isVerticallyResizable = true;
                me.isResizable = true;
            }
            if (svgShape.anchorTop && svgShape.anchorBottom) {
                me.isVerticallyResizable = true;
                me.isResizable = true;
            }
            if (svgShape.anchorLeft && svgShape.anchorRight) {
                me.isHorizontallyResizable = true;
                me.isResizable = true;
            }
        });
        
        //move all SVGShapes by -upperLeft
        this._svgShapes.each(function(svgShape){
            svgShape.x -= upperLeft.x;
            svgShape.y -= upperLeft.y;
            svgShape.update();
        });
        
        //set bounds of shape
        //the offsets are also needed for positioning the magnets and the docker
        var offsetX = upperLeft.x;
        var offsetY = upperLeft.y;
        
        lowerRight.x -= offsetX;
        lowerRight.y -= offsetY;
        upperLeft.x = 0;
        upperLeft.y = 0;
        
        //prevent that width or height of initial bounds is 0
        if (lowerRight.x === 0) {
            lowerRight.x = 1;
        }
        if (lowerRight.y === 0) {
            lowerRight.y = 1;
        }
        
        this._oldBounds.set(upperLeft, lowerRight);
        this.bounds.set(upperLeft, lowerRight);
        
        /**initialize magnets */
        
        var magnets = svgDocument.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_ORYX, "magnets");
        
        if (magnets && magnets.length > 0) {
        
            magnets = $A(magnets[0].getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_ORYX, "magnet"));
            
            var me = this;
            magnets.each(function(magnetElem){
                var magnet = new ORYX.Core.Controls.Magnet({
                    eventHandlerCallback: me.eventHandlerCallback
                });
                var cx = parseFloat(magnetElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "cx"));
                var cy = parseFloat(magnetElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "cy"));
                magnet.bounds.centerMoveTo({
                    x: cx - offsetX,
                    y: cy - offsetY
                });
                
                //get anchors
                var anchors = magnetElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "anchors");
                if (anchors) {
                    anchors = anchors.replace("/,/g", " ");
                    anchors = anchors.split(" ").without("");
                    for(var i = 0; i < anchors.length; i++) {
						switch(anchors[i].toLowerCase()) {
							case "left":
								magnet.anchorLeft = true;
								break;
							case "right":
								magnet.anchorRight = true;
								break;
							case "top":
								magnet.anchorTop = true;
								break;
							case "bottom":
								magnet.anchorBottom = true;
								break;
						}
					}
                }
                
                me.add(magnet);
                
                //check, if magnet is default magnet
                if (!this._defaultMagnet) {
                    var defaultAttr = magnetElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "default");
                    if (defaultAttr && defaultAttr.toLowerCase() === "yes") {
                        me._defaultMagnet = magnet;
                    }
                }
            });
        }
        else {
            // Add a Magnet in the Center of Shape			
            var magnet = new ORYX.Core.Controls.Magnet();
            magnet.bounds.centerMoveTo(this.bounds.width() / 2, this.bounds.height() / 2);
            this.add(magnet);
        }
        
        /**initialize docker */
        var dockerElem = svgDocument.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_ORYX, "docker");
        
        if (dockerElem && dockerElem.length > 0) {
            dockerElem = dockerElem[0];
            var docker = this.createDocker();
            var cx = parseFloat(dockerElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "cx"));
            var cy = parseFloat(dockerElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "cy"));
            docker.bounds.centerMoveTo({
                x: cx - offsetX,
                y: cy - offsetY
            });
            
            //get anchors
            var anchors = dockerElem.getAttributeNS(ORYX.CONFIG.NAMESPACE_ORYX, "anchors");
            if (anchors) {
                anchors = anchors.replace("/,/g", " ");
                anchors = anchors.split(" ").without("");
                
				for(var i = 0; i < anchors.length; i++) {
					switch(anchors[i].toLowerCase()) {
						case "left":
							docker.anchorLeft = true;
							break;
						case "right":
							docker.anchorRight = true;
							break;
						case "top":
							docker.anchorTop = true;
							break;
						case "bottom":
							docker.anchorBottom = true;
							break;
					}
				}
            }
        }
        
        /**initialize labels*/
        var textElems = svgNode.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_SVG, 'text');
        $A(textElems).each((function(textElem){
            var label = new ORYX.Core.SVG.Label({
                textElement: textElem,
				shapeId: this.id
            });
            label.x -= offsetX;
            label.y -= offsetY;
            this._labels[label.id] = label;
        }).bind(this));
    },
	
	/**
	 * Override the Method, that a docker is not shown
	 *
	 */
	createDocker: function() {
		var docker = new ORYX.Core.Controls.Docker({eventHandlerCallback: this.eventHandlerCallback});
		docker.bounds.registerCallback(this._dockerChangedCallback);
		
		this.dockers.push( docker );
		docker.parent = this;
		docker.bounds.registerCallback(this._changedCallback);		
		
		return docker		
	},	
    
    toString: function(){
        return this._stencil.title() + " " + this.id
    }
};
ORYX.Core.Node = ORYX.Core.Shape.extend(ORYX.Core.Node);