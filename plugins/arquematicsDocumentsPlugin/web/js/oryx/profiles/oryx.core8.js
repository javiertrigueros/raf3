

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
 * @classDescription With Bounds you can set and get position and size of UIObjects.
 */
ORYX.Core.Bounds = {

	/**
	 * Constructor
	 */
	construct: function() {
		this._changedCallbacks = []; //register a callback with changedCallacks.push(this.method.bind(this));
		this.a = {};
		this.b = {};
		this.set.apply(this, arguments);
		this.suspendChange = false;
		this.changedWhileSuspend = false;
	},
	
	/**
	 * Calls all registered callbacks.
	 */
	_changed: function(sizeChanged) {
		if(!this.suspendChange) {
			this._changedCallbacks.each(function(callback) {
				callback(this, sizeChanged);
			}.bind(this));
			this.changedWhileSuspend = false;
		} else
			this.changedWhileSuspend = true;
	},
	
	/**
	 * Registers a callback that is called, if the bounds changes.
	 * @param callback {Function} The callback function.
	 */
	registerCallback: function(callback) {
		if(!this._changedCallbacks.member(callback)) {
			this._changedCallbacks.push(callback);	
		}
	},
	
	/**
	 * Unregisters a callback.
	 * @param callback {Function} The callback function.
	 */
	unregisterCallback: function(callback) {
			this._changedCallbacks = this._changedCallbacks.without(callback);
	},
	
	/**
	 * Sets position and size of the shape dependent of four coordinates
	 * (set(ax, ay, bx, by);), two points (set({x: ax, y: ay}, {x: bx, y: by});)
	 * or one bound (set({a: {x: ax, y: ay}, b: {x: bx, y: by}});).
	 */
	set: function() {
		
		var changed = false;
		
		switch (arguments.length) {
		
			case 1:
				if(this.a.x !== arguments[0].a.x) {
					changed = true;
					this.a.x = arguments[0].a.x;
				}
				if(this.a.y !== arguments[0].a.y) {
					changed = true;
					this.a.y = arguments[0].a.y;
				}
				if(this.b.x !== arguments[0].b.x) {
					changed = true;
					this.b.x = arguments[0].b.x;
				}
				if(this.b.y !== arguments[0].b.y) {
					changed = true;
					this.b.y = arguments[0].b.y;
				}
				break;
			
			case 2:
				var ax = Math.min(arguments[0].x, arguments[1].x);
				var ay = Math.min(arguments[0].y, arguments[1].y);
				var bx = Math.max(arguments[0].x, arguments[1].x);
				var by = Math.max(arguments[0].y, arguments[1].y);
				if(this.a.x !== ax) {
					changed = true;
					this.a.x = ax;
				}
				if(this.a.y !== ay) {
					changed = true;
					this.a.y = ay;
				}
				if(this.b.x !== bx) {
					changed = true;
					this.b.x = bx;
				}
				if(this.b.y !== by) {
					changed = true;
					this.b.y = by;
				}
				break;
			
			case 4:
				var ax = Math.min(arguments[0], arguments[2]);
				var ay = Math.min(arguments[1], arguments[3]);
				var bx = Math.max(arguments[0], arguments[2]);
				var by = Math.max(arguments[1], arguments[3]);
				if(this.a.x !== ax) {
					changed = true;
					this.a.x = ax;
				}
				if(this.a.y !== ay) {
					changed = true;
					this.a.y = ay;
				}
				if(this.b.x !== bx) {
					changed = true;
					this.b.x = bx;
				}
				if(this.b.y !== by) {
					changed = true;
					this.b.y = by;
				}
				break;
		}
		
		if(changed) {
			this._changed(true);
		}
	},
	
	/**
	 * Moves the bounds so that the point p will be the new upper left corner.
	 * @param {Point} p
	 * or
	 * @param {Number} x
	 * @param {Number} y
	 */
	moveTo: function() {
		
		var currentPosition = this.upperLeft();
		switch (arguments.length) {
			case 1:
				this.moveBy({
					x: arguments[0].x - currentPosition.x,
					y: arguments[0].y - currentPosition.y
				});
				break;
			case 2:
				this.moveBy({
					x: arguments[0] - currentPosition.x,
					y: arguments[1] - currentPosition.y
				});
				break;
			default:
				//TODO error
		}
		
	},
	
	/**
	 * Moves the bounds relatively by p.
	 * @param {Point} p
	 * or
	 * @param {Number} x
	 * @param {Number} y
	 * 
	 */
	moveBy: function() {
		var changed = false;
		
		switch (arguments.length) {
			case 1:
				var p = arguments[0];
				if(p.x !== 0 || p.y !== 0) {
					changed = true;
					this.a.x += p.x;
					this.b.x += p.x;
					this.a.y += p.y;
					this.b.y += p.y;
				}
				break;	
			case 2:
				var x = arguments[0];
				var y = arguments[1];
				if(x !== 0 || y !== 0) {
					changed = true;
					this.a.x += x;
					this.b.x += x;
					this.a.y += y;
					this.b.y += y;
				}
				break;	
			default:
				//TODO error
		}
		
		if(changed) {
			this._changed();
		}
	},
	
	/***
	 * Includes the bounds b into the current bounds.
	 * @param {Bounds} b
	 */
	include: function(b) {
		
		if( (this.a.x === undefined) && (this.a.y === undefined) &&
			(this.b.x === undefined) && (this.b.y === undefined)) {
			return b;
		};
		
		var cx = Math.min(this.a.x,b.a.x);
		var cy = Math.min(this.a.y,b.a.y);
		
		var dx = Math.max(this.b.x,b.b.x);
		var dy = Math.max(this.b.y,b.b.y);

		
		this.set(cx, cy, dx, dy);
	},
	
	/**
	 * Relatively extends the bounds by p.
	 * @param {Point} p
	 */
	extend: function(p) {
		
		if(p.x !== 0 || p.y !== 0) {
			// this is over cross for the case that a and b have same coordinates.
			//((this.a.x > this.b.x) ? this.a : this.b).x += p.x;
			//((this.b.y > this.a.y) ? this.b : this.a).y += p.y;
			this.b.x += p.x;
			this.b.y += p.y;
			
			this._changed(true);
		}
	},
	
	/**
	 * Widens the scope of the bounds by x.
	 * @param {Number} x
	 */
	widen: function(x) {
		if (x !== 0) {
			this.suspendChange = true;
			this.moveBy({x: -x, y: -x});
			this.extend({x: 2*x, y: 2*x});
			this.suspendChange = false;
			if(this.changedWhileSuspend) {
				this._changed(true);
			}
		}
	},
	
	/**
	 * Returns the upper left corner's point regardless of the
	 * bound delimiter points.
	 */
	upperLeft: function() {
		
		return {x:this.a.x, y:this.a.y};
	},
	
	/**
	 * Returns the lower Right left corner's point regardless of the
	 * bound delimiter points.
	 */
	lowerRight: function() {
		
		return {x:this.b.x, y:this.b.y};
	},
	
	/**
	 * @return {Number} Width of bounds.
	 */
	width: function() {
		return this.b.x - this.a.x;
	},
	
	/**
	 * @return {Number} Height of bounds.
	 */
	height: function() {
		return this.b.y - this.a.y;
	},
	
	/**
	 * @return {Point} The center point of this bounds.
	 */
	center: function() {
		return {
			x: (this.a.x + this.b.x)/2.0, 
			y: (this.a.y + this.b.y)/2.0
		};
	},

	
	/**
	 * @return {Point} The center point of this bounds relative to upperLeft.
	 */
	midPoint: function() {
		return {
			x: (this.b.x - this.a.x)/2.0, 
			y: (this.b.y - this.a.y)/2.0
		};
	},
		
	/**
	 * Moves the center point of this bounds to the new position.
	 * @param p {Point} 
	 * or
	 * @param x {Number}
	 * @param y {Number}
	 */
	centerMoveTo: function() {
		var currentPosition = this.center();
		
		switch (arguments.length) {
			
			case 1:
				this.moveBy(arguments[0].x - currentPosition.x,
							arguments[0].y - currentPosition.y);
				break;
			
			case 2:
				this.moveBy(arguments[0] - currentPosition.x,
							arguments[1] - currentPosition.y);
				break;
		}
	},
	
	isIncluded: function(point, offset) {
		
		var pointX, pointY, offset;

		// Get the the two Points	
		switch(arguments.length) {
			case 1:
				pointX = arguments[0].x;
				pointY = arguments[0].y;
				offset = 0;
				
				break;
			case 2:
				if(arguments[0].x && arguments[0].y) {
					pointX = arguments[0].x;
					pointY = arguments[0].y;
					offset = Math.abs(arguments[1]);
				} else {
					pointX = arguments[0];
					pointY = arguments[1];
					offset = 0;
				}
				break;
			case 3:
				pointX = arguments[0];
				pointY = arguments[1];
				offset = Math.abs(arguments[2]);
				break;
			default:
				throw "isIncluded needs one, two or three arguments";
		}
				
		var ul = this.upperLeft();
		var lr = this.lowerRight();
		
		if(pointX >= ul.x - offset 
			&& pointX <= lr.x + offset && pointY >= ul.y - offset 
			&& pointY <= lr.y + offset)
			return true;
		else 
			return false;
	},
	
	/**
	 * @return {Bounds} A copy of this bounds.
	 */
	clone: function() {
		
		//Returns a new bounds object without the callback
		// references of the original bounds
		return new ORYX.Core.Bounds(this);
	},
	
	toString: function() {
		
		return "( "+this.a.x+" | "+this.a.y+" )/( "+this.b.x+" | "+this.b.y+" )";
	},
	
	serializeForERDF: function() {

		return this.a.x+","+this.a.y+","+this.b.x+","+this.b.y;
	}
 };
 
ORYX.Core.Bounds = Clazz.extend(ORYX.Core.Bounds);/**
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
 * @classDescription Abstract base class for all objects that have a graphical representation
 * within the editor.
 * @extends Clazz
 */
ORYX.Core.UIObject = {
	/**
	 * Constructor of the UIObject class.
	 */
	construct: function(options) {	
		
		this.isChanged = true;			//Flag, if UIObject has been changed since last update.
		this.isResized = true;
		this.isVisible = true;			//Flag, if UIObject's display attribute is set to 'inherit' or 'none'
		this.isSelectable = false;		//Flag, if UIObject is selectable.
		this.isResizable = false;		//Flag, if UIObject is resizable.
		this.isMovable = false;			//Flag, if UIObject is movable.
		
		this.id = ORYX.Editor.provideId();	//get unique id
		this.parent = undefined;		//parent is defined, if this object is added to another uiObject.
		this.node = undefined;			//this is a reference to the SVG representation, either locally or in DOM.
		this.children = [];				//array for all add uiObjects
		
		this.bounds = new ORYX.Core.Bounds();		//bounds with undefined values

		this._changedCallback = this._changed.bind(this);	//callback reference for calling _changed
		this.bounds.registerCallback(this._changedCallback);	//set callback in bounds
		
		if(options && options.eventHandlerCallback) {
			this.eventHandlerCallback = options.eventHandlerCallback;
		}
	},
	
	/**
	 * Sets isChanged flag to true. Callback for the bounds object.
	 */
	_changed: function(bounds, isResized) {
		this.isChanged = true;
		if(this.bounds == bounds)
			this.isResized = isResized || this.isResized;
	},
	
	/**
	 * If something changed, this method calls the refresh method that must be implemented by subclasses.
	 */
	update: function() {
		if(this.isChanged) {
			this.refresh();
			this.isChanged = false;
			
			//call update of all children
			this.children.each(function(value) {
				value.update();
			});
		}
	},
	
	/**
	 * Is called in update method, if isChanged is set to true. Sub classes should call the super class method.
	 */
	refresh: function() {
		
	},
	
	/**
	 * @return {Array} Array of all child UIObjects.
	 */
	getChildren: function() {
		return this.children.clone();
	},
	
	/**
	 * @return {Array} Array of all parent UIObjects.
	 */
	getParents: function(){
		var parents = [];
		var parent = this.parent;
		while(parent){
			parents.push(parent);
			parent = parent.parent;
		}
		return parents;
	},
	
	/**
	 * Returns TRUE if the given parent is one of the UIObjects parents or the UIObject themselves, otherwise FALSE.
	 * @param {UIObject} parent
	 * @return {Boolean} 
	 */
	isParent: function(parent){
		var cparent = this;
		while(cparent){
			if (cparent === parent){
				return true;
			}
			cparent = cparent.parent;
		}
		return false;
	},
	
	/**
	 * @return {String} Id of this UIObject
	 */
	getId: function() {
		return this.id;
	},
	
	/**
	 * Method for accessing child uiObjects by id.
	 * @param {String} id
	 * @param {Boolean} deep
	 * 
	 * @return {UIObject} If found, it returns the UIObject with id.
	 */
	getChildById: function(id, deep) {
		return this.children.find(function(uiObj) {
			if(uiObj.getId() === id) {
				return uiObj;
			} else {
				if(deep) {
					var obj = uiObj.getChildById(id, deep);
					if(obj) {
						return obj;
					}
				}
			}
		});
	},
	
	/**
	 * Adds an UIObject to this UIObject and sets the parent of the
	 * added UIObject. It is also added to the SVG representation of this
	 * UIObject.
	 * @param {UIObject} uiObject
	 */
	add: function(uiObject) {
		//add uiObject, if it is not already a child of this object
		if (!(this.children.member(uiObject))) {
			//if uiObject is child of another parent, remove it from that parent.
			if(uiObject.parent) {
				uiObject.remove(uiObject);
			}
			
			//add uiObject to children
			this.children.push(uiObject);
			
			//set parent reference
			uiObject.parent = this;
			
			//add uiObject.node to this.node
			uiObject.node = this.node.appendChild(uiObject.node);
			
			//register callback to get informed, if child is changed
			uiObject.bounds.registerCallback(this._changedCallback);
			
		
			if(this.eventHandlerCallback)
				this.eventHandlerCallback({type:ORYX.CONFIG.EVENT_SHAPEADDED,shape:uiObject})
			//uiObject.update();
		} else {
			ORYX.Log.info("add: ORYX.Core.UIObject is already a child of this object.");
		}
	},
	
	/**
	 * Removes UIObject from this UIObject. The SVG representation will also
	 * be removed from this UIObject's SVG representation.
	 * @param {UIObject} uiObject
	 */
	remove: function(uiObject) {
		//if uiObject is a child of this object, remove it.
		if (this.children.member(uiObject)) {
			//remove uiObject from children
			this.children = this._uiObjects.without(uiObject);
			
			//delete parent reference of uiObject
			uiObject.parent = undefined;
			
			//delete uiObject.node from this.node
			uiObject.node = this.node.removeChild(uiObject.node);
			
			//unregister callback to get informed, if child is changed
			uiObject.bounds.unregisterCallback(this._changedCallback);
		} else {
			ORYX.Log.info("remove: ORYX.Core.UIObject is not a child of this object.");
		}
		
	},
	
	/**
	 * Calculates absolute bounds of this UIObject.
	 */
	absoluteBounds: function() {
		if(this.parent) {
			var absUL = this.absoluteXY();
			return new ORYX.Core.Bounds(absUL.x, absUL.y,
							absUL.x + this.bounds.width(),
							absUL.y + this.bounds.height());
		} else {
			return this.bounds.clone();
		}
	},

	/**
	 * @return {Point} The absolute position of this UIObject.
	 */
	absoluteXY: function() {
		if(this.parent) {
			var pXY = this.parent.absoluteXY();		
			return {x: pXY.x + this.bounds.upperLeft().x , y: pXY.y + this.bounds.upperLeft().y};
			
		} else {
			return {x: this.bounds.upperLeft().x , y: this.bounds.upperLeft().y};
		}
	},

	/**
	 * @return {Point} The absolute position from the Center of this UIObject.
	 */
	absoluteCenterXY: function() {
		if(this.parent) {
			var pXY = this.parent.absoluteXY();		
			return {x: pXY.x + this.bounds.center().x , y: pXY.y + this.bounds.center().y};
			
		} else {
			return {x: this.bounds.center().x , y: this.bounds.center().y};
		}
	},
	
	/**
	 * Hides this UIObject and all its children.
	 */
	hide: function() {
		this.node.setAttributeNS(null, 'display', 'none');
		this.isVisible = false;
		this.children.each(function(uiObj) {
			uiObj.hide();	
		});
	},
	
	/**
	 * Enables visibility of this UIObject and all its children.
	 */
	show: function() {
		this.node.setAttributeNS(null, 'display', 'inherit');
		this.isVisible = true;
		this.children.each(function(uiObj) {
			uiObj.show();	
		});		
	},
	
	addEventHandlers: function(node) {
		
		node.addEventListener(ORYX.CONFIG.EVENT_MOUSEDOWN, this._delegateEvent.bind(this), false);
		node.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this._delegateEvent.bind(this), false);	
		node.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this._delegateEvent.bind(this), false);
		node.addEventListener(ORYX.CONFIG.EVENT_MOUSEOVER, this._delegateEvent.bind(this), false);
		node.addEventListener(ORYX.CONFIG.EVENT_MOUSEOUT, this._delegateEvent.bind(this), false);
		node.addEventListener('click', this._delegateEvent.bind(this), false);
		node.addEventListener(ORYX.CONFIG.EVENT_DBLCLICK, this._delegateEvent.bind(this), false);
			
	},
		
	_delegateEvent: function(event) {
		if(this.eventHandlerCallback) {
			this.eventHandlerCallback(event, this);
		}
	},
	
	toString: function() { return "UIObject " + this.id }
 };
 ORYX.Core.UIObject = Clazz.extend(ORYX.Core.UIObject);/**
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
 * Top Level uiobject.
 * @class ORYX.Core.AbstractShape
 * @extends ORYX.Core.UIObject
 */
ORYX.Core.AbstractShape = ORYX.Core.UIObject.extend(
/** @lends ORYX.Core.AbstractShape.prototype */
{

	/**
	 * Constructor
	 */
	construct: function(options, stencil) {
		
		arguments.callee.$.construct.apply(this, arguments);
		
		this.resourceId = ORYX.Editor.provideId(); //Id of resource in DOM
		
		// stencil reference
		this._stencil = stencil;
		// if the stencil defines a super stencil that should be used for its instances, set it.
		if (this._stencil._jsonStencil.superId){
			stencilId = this._stencil.id()
			superStencilId = stencilId.substring(0, stencilId.indexOf("#") + 1) + stencil._jsonStencil.superId;
			stencilSet =  this._stencil.stencilSet();
			this._stencil = stencilSet.stencil(superStencilId);
		}
		
		//Hash map for all properties. Only stores the values of the properties.
		this.properties = new Hash();
		this.propertiesChanged = new Hash();

		// List of properties which are not included in the stencilset, 
		// but which gets (de)serialized
		this.hiddenProperties = new Hash();
		
		
		//Initialization of property map and initial value.
		this._stencil.properties().each((function(property) {
			var key = property.prefix() + "-" + property.id();
			this.properties[key] = property.value();
			this.propertiesChanged[key] = true;
		}).bind(this));
		
		// if super stencil was defined, also regard stencil's properties:
		if (stencil._jsonStencil.superId) {
			stencil.properties().each((function(property) {
				var key = property.prefix() + "-" + property.id();
				var value = property.value();
				var oldValue = this.properties[key];
				this.properties[key] = value;
				this.propertiesChanged[key] = true;

				// Raise an event, to show that the property has changed
				// required for plugins like processLink.js
				//window.setTimeout( function(){

					this._delegateEvent({
							type	: ORYX.CONFIG.EVENT_PROPERTY_CHANGED, 
							name	: key, 
							value	: value,
							oldValue: oldValue
						});

				//}.bind(this), 10)

			}).bind(this));
		}

	},

	layout: function() {

	},
	
	/**
	 * Returns the stencil object specifiing the type of the shape.
	 */
	getStencil: function() {
		return this._stencil;
	},
	
	/**
	 * 
	 * @param {Object} resourceId
	 */
	getChildShapeByResourceId: function(resourceId) {

		resourceId = ERDF.__stripHashes(resourceId);
		
		return this.getChildShapes(true).find(function(shape) {
					return shape.resourceId == resourceId
				});
	},
	/**
	 * 
	 * @param {Object} deep
	 * @param {Object} iterator
	 */
	getChildShapes: function(deep, iterator) {
		var result = [];

		this.children.each(function(uiObject) {
			if(uiObject instanceof ORYX.Core.Shape && uiObject.isVisible ) {
				if(iterator) {
					iterator(uiObject);
				}
				result.push(uiObject);
				if(deep) {
					result = result.concat(uiObject.getChildShapes(deep, iterator));
				} 
			}
		});

		return result;
	},
    
    /**
     * @param {Object} shape
     * @return {boolean} true if any of shape's childs is given shape
     */
    hasChildShape: function(shape){
        return this.getChildShapes().any(function(child){
            return (child === shape) || child.hasChildShape(shape);
        });
    },
	
	/**
	 * 
	 * @param {Object} deep
	 * @param {Object} iterator
	 */
	getChildNodes: function(deep, iterator) {
		var result = [];

		this.children.each(function(uiObject) {
			if(uiObject instanceof ORYX.Core.Node && uiObject.isVisible) {
				if(iterator) {
					iterator(uiObject);
				}
				result.push(uiObject);
			}
			if(uiObject instanceof ORYX.Core.Shape) {
				if(deep) {
					result = result.concat(uiObject.getChildNodes(deep, iterator));
				}
			}
		});

		return result;
	},
	
	/**
	 * 
	 * @param {Object} deep
	 * @param {Object} iterator
	 */
	getChildEdges: function(deep, iterator) {
		var result = [];

		this.children.each(function(uiObject) {
			if(uiObject instanceof ORYX.Core.Edge && uiObject.isVisible) {
				if(iterator) {
					iterator(uiObject);
				}
				result.push(uiObject);
			}
			if(uiObject instanceof ORYX.Core.Shape) {
				if(deep) {
					result = result.concat(uiObject.getChildEdges(deep, iterator));
				}
			}
		});

		return result;
	},
	
	/**
	 * Returns a sorted array of ORYX.Core.Node objects.
	 * Ordered in z Order, the last object has the highest z Order.
	 */
	//TODO deep iterator
	getAbstractShapesAtPosition: function() {
		var x, y;
		switch (arguments.length) {
			case 1:
				x = arguments[0].x;
				y = arguments[0].y;
				break;
			case 2:	//two or more arguments
				x = arguments[0];
				y = arguments[1];
				break;
			default:
				throw "getAbstractShapesAtPosition needs 1 or 2 arguments!"
		}

		if(this.isPointIncluded(x, y)) {

			var result = [];
			result.push(this);

			//check, if one child is at that position						
			
			
			var childNodes = this.getChildNodes();
			var childEdges = this.getChildEdges();
			
			[childNodes, childEdges].each(function(ne){
				var nodesAtPosition = new Hash();
				
				ne.each(function(node) {
					if(!node.isVisible){ return }
					var candidates = node.getAbstractShapesAtPosition( x , y );
					if(candidates.length > 0) {
						var nodesInZOrder = $A(node.node.parentNode.childNodes);
						var zOrderIndex = nodesInZOrder.indexOf(node.node);
						nodesAtPosition[zOrderIndex] = candidates;
					}
				});
				
				nodesAtPosition.keys().sort().each(function(key) {
					result = result.concat(nodesAtPosition[key]);
				});
 			});
						
			return result;
			
		} else {
			return [];
		}
	},
	
	/**
	 * 
	 * @param key {String} Must be 'prefix-id' of property
	 * @param value {Object} Can be of type String or Number according to property type.
	 */
	setProperty: function(key, value, force) {
		var oldValue = this.properties[key];
		if(oldValue !== value || force === true) {
			this.properties[key] = value;
			this.propertiesChanged[key] = true;
			this._changed();
			
			// Raise an event, to show that the property has changed
			//window.setTimeout( function(){

			if (!this._isInSetProperty) {
				this._isInSetProperty = true;
				
				this._delegateEvent({
						type	: ORYX.CONFIG.EVENT_PROPERTY_CHANGED, 
						elements : [this],
						name	: key, 
						value	: value,
						oldValue: oldValue
					});
				
				delete this._isInSetProperty;
			}
			//}.bind(this), 10)
		}
	},

	/**
	 * 
	 * @param {String} Must be 'prefix-id' of property
	 * @param {Object} Can be of type String or Number according to property type.
	 */
	setHiddenProperty: function(key, value) {
		// IF undefined, Delete
		if (value === undefined) {
			delete this.hiddenProperties[key];
			return;
		}
		var oldValue = this.hiddenProperties[key];
		if(oldValue !== value) {
			this.hiddenProperties[key] = value;
		}
	},
	/**
	 * Calculate if the point is inside the Shape
	 * @param {Point}
	 */
	isPointIncluded: function(pointX, pointY, absoluteBounds) {
		var absBounds = absoluteBounds ? absoluteBounds : this.absoluteBounds();
		return absBounds.isIncluded(pointX, pointY);
				
	},
	
	/**
	 * Get the serialized object
	 * return Array with hash-entrees (prefix, name, value)
	 * Following values will given:
	 * 		Type
	 * 		Properties
	 */
	serialize: function() {
		var serializedObject = [];
		
		// Add the type
		serializedObject.push({name: 'type', prefix:'oryx', value: this.getStencil().id(), type: 'literal'});	
	
		// Add hidden properties
		this.hiddenProperties.each(function(prop){
			serializedObject.push({name: prop.key.replace("oryx-", ""), prefix: "oryx", value: prop.value, type: 'literal'});
		}.bind(this));
		
		// Add all properties
		this.getStencil().properties().each((function(property){
			
			var prefix = property.prefix();	// Get prefix
			var name = property.id();		// Get name
			
			//if(typeof this.properties[prefix+'-'+name] == 'boolean' || this.properties[prefix+'-'+name] != "")
				serializedObject.push({name: name, prefix: prefix, value: this.properties[prefix+'-'+name], type: 'literal'});

		}).bind(this));
		
		return serializedObject;
	},
		
		
	deserialize: function(serialize){
		// Search in Serialize
		var initializedDocker = 0;
		
		// Sort properties so that the hidden properties are first in the list
		serialize = serialize.sort(function(a,b){ return Number(this.properties.keys().member(a.prefix+"-"+a.name)) > Number(this.properties.keys().member(b.prefix+"-"+b.name)) ? -1 : 0 }.bind(this));
		
		serialize.each((function(obj){
			
			var name 	= obj.name;
			var prefix 	= obj.prefix;
			var value 	= obj.value;
            
            // Complex properties can be real json objects, encode them to a string
            if(Ext.type(value) === "object") value = Ext.encode(value);

			switch(prefix + "-" + name){
				case 'raziel-parent': 
							// Set parent
							if(!this.parent) {break};
							
							// Set outgoing Shape
							var parent = this.getCanvas().getChildShapeByResourceId(value);
							if(parent) {
								parent.add(this);
							}
							
							break;											
				default:
							// Set property
							if(this.properties.keys().member(prefix+"-"+name)) {
								this.setProperty(prefix+"-"+name, value);
							} else if(!(name === "bounds"||name === "parent"||name === "target"||name === "dockers"||name === "docker"||name === "outgoing"||name === "incoming")) {
								this.setHiddenProperty(prefix+"-"+name, value);
							}
					
			}
		}).bind(this));
	},
	
	toString: function() { return "ORYX.Core.AbstractShape " + this.id },
    
    /**
     * Converts the shape to a JSON representation.
     * @return {Object} A JSON object with included ORYX.Core.AbstractShape.JSONHelper and getShape() method.
     */
    toJSON: function(){
        var json = {
            resourceId: this.resourceId,
            properties: Ext.apply({}, this.properties, this.hiddenProperties).inject({}, function(props, prop){
              var key = prop[0];
              var value = prop[1];
                
              //If complex property, value should be a json object
              if(this.getStencil().property(key)
                && this.getStencil().property(key).type() === ORYX.CONFIG.TYPE_COMPLEX 
                && Ext.type(value) === "string"){
                  try {value = Ext.decode(value);} catch(error){}
              }
              
              //Takes "my_property" instead of "oryx-my_property" as key
              key = key.replace(/^[\w_]+-/, "");
              props[key] = value;
              
              return props;
            }.bind(this)),
            stencil: {
                id: this.getStencil().idWithoutNs()
            },
            childShapes: this.getChildShapes().map(function(shape){
                return shape.toJSON()
            })
        };
        
        if(this.getOutgoingShapes){
            json.outgoing = this.getOutgoingShapes().map(function(shape){
                return {
                    resourceId: shape.resourceId
                };
            });
        }
        
        if(this.bounds){
            json.bounds = { 
                lowerRight: this.bounds.lowerRight(), 
                upperLeft: this.bounds.upperLeft() 
            };
        }
        
        if(this.dockers){
            json.dockers = this.dockers.map(function(docker){
                var d = docker.getDockedShape() && docker.referencePoint ? docker.referencePoint : docker.bounds.center();
                d.getDocker = function(){return docker;};
                return d;
            })
        }
        
        Ext.apply(json, ORYX.Core.AbstractShape.JSONHelper);
        
        // do not pollute the json attributes (for serialization), so put the corresponding
        // shape is encapsulated in a method
        json.getShape = function(){
            return this;
        }.bind(this);
        
        return json;
    }
 });
 
/**
 * @namespace Collection of methods which can be used on a shape json object (ORYX.Core.AbstractShape#toJSON()).
 * @example
 * Ext.apply(shapeAsJson, ORYX.Core.AbstractShape.JSONHelper);
 */
ORYX.Core.AbstractShape.JSONHelper = {
     /**
      * Iterates over each child shape.
      * @param {Object} iterator Iterator function getting a child shape and his parent as arguments.
      * @param {boolean} [deep=false] Iterate recursively (childShapes of childShapes)
      * @param {boolean} [modify=false] If true, the result of the iterator function is taken as new shape, return false to delete it. This enables modifying the object while iterating through the child shapes.
      * @example
      * // Increases the lowerRight x value of each direct child shape by one. 
      * myShapeAsJson.eachChild(function(shape, parentShape){
      *     shape.bounds.lowerRight.x = shape.bounds.lowerRight.x + 1;
      *     return shape;
      * }, false, true);
      */
     eachChild: function(iterator, deep, modify){
         if(!this.childShapes) return;
         
         var newChildShapes = []; //needed if modify = true
         
         this.childShapes.each(function(shape){
             var res = iterator(shape, this);
             if(res) newChildShapes.push(res); //if false is returned, and modify = true, current shape is deleted.
             
             if(deep) shape.eachChild(iterator, deep, modify);
         }.bind(this));
         
         if(modify) this.childShapes = newChildShapes;
     },
     
     getChildShapes: function(deep){
         var allShapes = this.childShapes;
         
         if(deep){
             this.eachChild(function(shape){
                 allShapes = allShapes.concat(shape.getChildShapes(deep));
             }, true);
         }
         
         return allShapes;
     },
     
     /**
      * @return {String} Serialized JSON object
      */
     serialize: function(){
         return Ext.encode(this);
     }
 }
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

/**
   @namespace Namespace for the Oryx core elements.
   @name ORYX.Core
*/
if(!ORYX.Core) {ORYX.Core = {};}

/**
 * @class Oryx canvas.
 * @extends ORYX.Core.AbstractShape
 *
 */
ORYX.Core.Canvas = ORYX.Core.AbstractShape.extend({
    /** @lends ORYX.Core.Canvas.prototype */

	/**
	 * Defines the current zoom level
	 */
	zoomLevel:1,

	/**
	 * Constructor
	 */
	construct: function(options) {
		arguments.callee.$.construct.apply(this, arguments);

		if(!(options && options.width && options.height)) {
		
			ORYX.Log.fatal("Canvas is missing mandatory parameters options.width and options.height.");
			return;
		}
			
		//TODO: set document resource id
		this.resourceId = options.id;

		this.nodes = [];
		
		this.edges = [];
		
		//init svg document
		this.rootNode = ORYX.Editor.graft("http://www.w3.org/2000/svg", options.parentNode,
			['svg', {id: this.id, width: options.width, height: options.height},
				['defs', {}]
			]);
			
		this.rootNode.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
		this.rootNode.setAttribute("xmlns:svg", "http://www.w3.org/2000/svg");

		this._htmlContainer = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", options.parentNode,
			['div', {id: "oryx_canvas_htmlContainer", style:"position:absolute; top:5px"}]);
		
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.rootNode,
			['g', {},
				['g', {"class": "stencils"},
					['g', {"class": "me"}],
					['g', {"class": "children"}],
					['g', {"class": "edge"}]
				],
				['g', {"class":"svgcontainer"}]
			]);
		
		/*
		var off = 2 * ORYX.CONFIG.GRID_DISTANCE;
		var size = 3;
		var d = "";
		for(var i = 0; i <= options.width; i += off)
			for(var j = 0; j <= options.height; j += off)
				d = d + "M" + (i - size) + " " + j + " l" + (2*size) + " 0 m" + (-size) + " " + (-size) + " l0 " + (2*size) + " m0" + (-size) + " ";
							
		ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node.firstChild.firstChild,
			['path', {d:d , stroke:'#000000', 'stroke-width':'0.15px'},]);
		*/
		
		//Global definition of default font for shapes
		//Definitions in the SVG definition of a stencil will overwrite these settings for
		// that stencil.
		/*if(navigator.platform.indexOf("Mac") > -1) {
			this.node.setAttributeNS(null, 'stroke', 'black');
			this.node.setAttributeNS(null, 'stroke-width', '0.5px');
			this.node.setAttributeNS(null, 'font-family', 'Skia');
			//this.node.setAttributeNS(null, 'letter-spacing', '2px');
			this.node.setAttributeNS(null, 'font-size', ORYX.CONFIG.LABEL_DEFAULT_LINE_HEIGHT);
		} else {
			this.node.setAttributeNS(null, 'stroke', 'none');
			this.node.setAttributeNS(null, 'font-family', 'Verdana');
			this.node.setAttributeNS(null, 'font-size', ORYX.CONFIG.LABEL_DEFAULT_LINE_HEIGHT);
		}*/
		
		this.node.setAttributeNS(null, 'stroke', 'black');
		this.node.setAttributeNS(null, 'font-family', 'Verdana, sans-serif');
		this.node.setAttributeNS(null, 'font-size-adjust', 'none');
		this.node.setAttributeNS(null, 'font-style', 'normal');
		this.node.setAttributeNS(null, 'font-variant', 'normal');
		this.node.setAttributeNS(null, 'font-weight', 'normal');
		this.node.setAttributeNS(null, 'line-heigth', 'normal');
		
		this.node.setAttributeNS(null, 'font-size', ORYX.CONFIG.LABEL_DEFAULT_LINE_HEIGHT);
			
		this.bounds.set(0,0,options.width, options.height);
		
		this.addEventHandlers(this.rootNode.parentNode);
		
		//disable context menu
		this.rootNode.oncontextmenu = function() {return false;};
	},
	
	focus: function(){
		/*
		// Get a href
		if (!this.headerA){
			this.headerA = Ext.get("header").child("a").dom
		}
		
		// Focus it and blurs it
		this.headerA.focus();
		this.headerA.blur();*/
		
		if (this.headerA)
		{
			this.headerA.focus();
			this.headerA.blur();
		}
	},
	
	update: function() {
		
		this.nodes.each(function(node) {
			this._traverseForUpdate(node);
		}.bind(this));
		
		// call stencil's layout callback
		// (needed for row layouting of xforms)
		//this.getStencil().layout(this);
		
		var layoutEvents = this.getStencil().layout();
		
		if(layoutEvents) {
			layoutEvents.each(function(event) {
		
				// setup additional attributes
				event.shape = this;
				event.forceExecution = true;
				event.target = this.rootNode;
				
				// do layouting
				
				this._delegateEvent(event);
			}.bind(this))
		}
		
		this.nodes.invoke("_update");
		
		this.edges.invoke("_update", true);
		
		/*this.children.each(function(child) {
			child._update();
		});*/
	},
	
	_traverseForUpdate: function(shape) {
		var childRet = shape.isChanged;
		shape.getChildNodes(false, function(child) {
			if(this._traverseForUpdate(child)) {
				childRet = true;
			}
		}.bind(this));
		
		if(childRet) {
			shape.layout();
			return true;
		} else {
			return false;
		}
	},
	
	layout: function() {
		
		
		
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
	 * buggy crap! use base class impl instead! 
	 * @param {Object} iterator
	 */
/*	getChildEdges: function(iterator) {
		if(iterator) {
			this.edges.each(function(edge) {
				iterator(edge);
			});
		}
		
		return this.edges.clone();
	},
*/	
	/**
	 * Overrides the UIObject.add method. Adds uiObject to the correct sub node.
	 * @param {UIObject} uiObject
	 */
	add: function(uiObject) {
		//if uiObject is child of another UIObject, remove it.
		if(uiObject instanceof ORYX.Core.UIObject) {
			if (!(this.children.member(uiObject))) {
				//if uiObject is child of another parent, remove it from that parent.
				if(uiObject.parent) {
					uiObject.parent.remove(uiObject);
				}

				//add uiObject to the Canvas
				this.children.push(uiObject);

				//set parent reference
				uiObject.parent = this;

				//add uiObject.node to this.node depending on the type of uiObject
				if(uiObject instanceof ORYX.Core.Shape) {
					if(uiObject instanceof ORYX.Core.Edge) {
						uiObject.addMarkers(this.rootNode.getElementsByTagNameNS(NAMESPACE_SVG, "defs")[0]);
						uiObject.node = this.node.childNodes[0].childNodes[2].appendChild(uiObject.node);
						this.edges.push(uiObject);
					} else {
						uiObject.node = this.node.childNodes[0].childNodes[1].appendChild(uiObject.node);
						this.nodes.push(uiObject);
					}
				} else {	//UIObject
					uiObject.node = this.node.appendChild(uiObject.node);
				}

				uiObject.bounds.registerCallback(this._changedCallback);
					
				if(this.eventHandlerCallback)
					this.eventHandlerCallback({type:ORYX.CONFIG.EVENT_SHAPEADDED,shape:uiObject})
			} else {
				
				ORYX.Log.warn("add: ORYX.Core.UIObject is already a child of this object.");
			}
		} else {

			ORYX.Log.fatal("add: Parameter is not of type ORYX.Core.UIObject.");
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
					this.edges = this.edges.without(uiObject);
				} else {
					uiObject.node = this.node.childNodes[0].childNodes[1].removeChild(uiObject.node);
					this.nodes = this.nodes.without(uiObject);
				}
			} else {	//UIObject
					uiObject.node = this.node.removeChild(uiObject.node);
			}

			uiObject.bounds.unregisterCallback(this._changedCallback);
		} else {

			ORYX.Log.warn("remove: ORYX.Core.UIObject is not a child of this object.");
		}
	},
    
    /**
     * Creates shapes out of the given collection of shape objects and adds them to the canvas.
     * @example 
     * canvas.addShapeObjects({
         bounds:{ lowerRight:{ y:510, x:633 }, upperLeft:{ y:146, x:210 } },
         resourceId:"oryx_F0715955-50F2-403D-9851-C08CFE70F8BD",
         childShapes:[],
         properties:{},
         stencil:{
           id:"Subprocess"
         },
         outgoing:[{resourceId: 'aShape'}],
         target: {resourceId: 'aShape'}
       });
     * @param {Object} shapeObjects 
     * @param {Function} [eventHandler] An event handler passed to each newly created shape (as eventHandlerCallback)
     * @return {Array} A collection of ORYX.Core.Shape
     * @methodOf ORYX.Core.Canvas.prototype
     */
    addShapeObjects: function(shapeObjects, eventHandler){
        if(!shapeObjects) return;
        
        /*FIXME This implementation is very evil! At first, all shapes are created on
          canvas. In a second step, the attributes are applied. There must be a distinction
          between the configuration phase (where the outgoings, for example, are just named),
          and the creation phase (where the outgoings are evaluated). This must be reflected
          in code to provide a nicer API/ implementation!!! */
        
        var addShape = function(shape, parent){
            // Try to create a new Shape
            try {
                // Create a new Stencil
                var stencil = ORYX.Core.StencilSet.stencil(this.getStencil().namespace() + shape.stencil.id );
    
                // Create a new Shape
                var ShapeClass = (stencil.type() == "node") ? ORYX.Core.Node : ORYX.Core.Edge;
                var newShape = new ShapeClass(
                  {'eventHandlerCallback': eventHandler},
                  stencil);
                
                // Set the resource id
                newShape.resourceId = shape.resourceId;
                
                // Set parent to json object to be used later
                // Due to the nested json structure, normally shape.parent is not set/ must not be set. 
                // In special cases, it can be easier to set this directly instead of a nested structure.
                shape.parent = "#" + ((shape.parent && shape.parent.resourceId) || parent.resourceId);
                
                // Add the shape to the canvas
                this.add( newShape );

                return {
                  json: shape,
                  object: newShape
                };
            } catch(e) {
                ORYX.Log.warn("LoadingContent: Stencil could not create.");
            }
        }.bind(this);
        
        /** Builds up recursively a flatted array of shapes, including a javascript object and json representation
         * @param {Object} shape Any object that has Object#childShapes
         */
        var addChildShapesRecursively = function(shape){
            var addedShapes = [];
            
            shape.childShapes.each(function(childShape){
  			  /*
  			   *  workaround for Chrome, for some reason an undefined shape is given
  			   */
            	var xy=addShape(childShape, shape);
  			  if(!(typeof xy ==="undefined")){
  					addedShapes.push(xy);
  			  }
              addedShapes = addedShapes.concat(addChildShapesRecursively(childShape));
            });
            
            return addedShapes;
        }.bind(this);

        var shapes = addChildShapesRecursively({
            childShapes: shapeObjects, 
            resourceId: this.resourceId
        });
                    

        // prepare deserialisation parameter
        shapes.each(
            function(shape){
            	var properties = [];
                for(field in shape.json.properties){
                    properties.push({
                      prefix: 'oryx',
                      name: field,
                      value: shape.json.properties[field]
                    });
                  }
                  
                  // Outgoings
                  shape.json.outgoing.each(function(out){
                    properties.push({
                      prefix: 'raziel',
                      name: 'outgoing',
                      value: "#"+out.resourceId
                    });
                  });
                  
                  // Target 
                  // (because of a bug, the first outgoing is taken when there is no target,
                  // can be removed after some time)
                  if(shape.object instanceof ORYX.Core.Edge) {
	                  var target = shape.json.target || shape.json.outgoing[0];
	                  if(target){
	                    properties.push({
	                      prefix: 'raziel',
	                      name: 'target',
	                      value: "#"+target.resourceId
	                    });
	                  }
                  }
                  
                  // Bounds
                  if (shape.json.bounds) {
                      properties.push({
                          prefix: 'oryx',
                          name: 'bounds',
                          value: shape.json.bounds.upperLeft.x + "," + shape.json.bounds.upperLeft.y + "," + shape.json.bounds.lowerRight.x + "," + shape.json.bounds.lowerRight.y
                      });
                  }
                  
                  //Dockers [{x:40, y:50}, {x:30, y:60}] => "40 50 30 60  #"
                  if(shape.json.dockers){
                    properties.push({
                      prefix: 'oryx',
                      name: 'dockers',
                      value: shape.json.dockers.inject("", function(dockersStr, docker){
                        return dockersStr + docker.x + " " + docker.y + " ";
                      }) + " #"
                    });
                  }
                  
                  //Parent
                  properties.push({
                    prefix: 'raziel',
                    name: 'parent',
                    value: shape.json.parent
                  });
            
                  shape.__properties = properties;
	         }.bind(this)
        );
  
        // Deserialize the properties from the shapes
        // This can't be done earlier because Shape#deserialize expects that all referenced nodes are already there
        
        // first, deserialize all nodes
        shapes.each(function(shape) {
        	if(shape.object instanceof ORYX.Core.Node) {
        		shape.object.deserialize(shape.__properties);
        	}
        });
        
        // second, deserialize all edges
        shapes.each(function(shape) {
        	if(shape.object instanceof ORYX.Core.Edge) {
        		shape.object.deserialize(shape.__properties);
        	}
        });
       
        return shapes.pluck("object");
    },
    
    /**
     * Updates the size of the canvas, regarding to the containg shapes.
     */
    updateSize: function(){
        // Check the size for the canvas
        var maxWidth    = 0;
        var maxHeight   = 0;
        var offset      = 100;
        this.getChildShapes(true, function(shape){
            var b = shape.bounds;
            maxWidth    = Math.max( maxWidth, b.lowerRight().x + offset)
            maxHeight   = Math.max( maxHeight, b.lowerRight().y + offset)
        }); 
        
        if( this.bounds.width() < maxWidth || this.bounds.height() < maxHeight ){
            this.setSize({width: Math.max(this.bounds.width(), maxWidth), height: Math.max(this.bounds.height(), maxHeight)})
        }
    },

	getRootNode: function() {
		return this.rootNode;
	},
	
	getSvgContainer: function() {
		return this.node.childNodes[1];
	},
	
	getHTMLContainer: function() {
		return this._htmlContainer;
	},	

	/**
	 * Return all elements of the same highest level
	 * @param {Object} elements
	 */
	getShapesWithSharedParent: function(elements) {

		// If there is no elements, return []
		if(!elements || elements.length < 1) { return [] }
		// If there is one element, return this element
		if(elements.length == 1) { return elements}

		return elements.findAll(function(value){
			var parentShape = value.parent;
			while(parentShape){
				if(elements.member(parentShape)) return false;
				parentShape = parentShape.parent
			}
			return true;
		});		

	},

	setSize: function(size, dontSetBounds) {
		if(!size || !size.width || !size.height){return}
		
		if(this.rootNode.parentNode){
			this.rootNode.parentNode.style.width = size.width + 'px';
			this.rootNode.parentNode.style.height = size.height + 'px';
		}
		
		this.rootNode.setAttributeNS(null, 'width', size.width);
		this.rootNode.setAttributeNS(null, 'height', size.height);

		//this._htmlContainer.style.top = "-" + (size.height + 4) + 'px';		
		if( !dontSetBounds ){
			this.bounds.set({a:{x:0,y:0},b:{x:size.width/this.zoomLevel,y:size.height/this.zoomLevel}})		
		}
	},
	
	/**
	 * Returns an SVG document of the current process.
	 * @param {Boolean} escapeText Use true, if you want to parse it with an XmlParser,
	 * 					false, if you want to use the SVG document in browser on client side.
	 */
	getSVGRepresentation: function(escapeText) {
		// Get the serialized svg image source
        var svgClone = this.getRootNode().cloneNode(true);
		
		this._removeInvisibleElements(svgClone);
		
		var x1, y1, x2, y2;
		try {
			var bb = this.getRootNode().childNodes[1].getBBox();
			x1 = bb.x;
			y1 = bb.y;
			x2 = bb.x + bb.width;
			y2 = bb.y + bb.height;
		} catch(e) {
			this.getChildShapes(true).each(function(shape) {
				var absBounds = shape.absoluteBounds();
				var ul = absBounds.upperLeft();
				var lr = absBounds.lowerRight();
				if(x1 == undefined) {
					x1 = ul.x;
					y1 = ul.y;
					x2 = lr.x;
					y2 = lr.y;
				} else {
					x1 = Math.min(x1, ul.x);
					y1 = Math.min(y1, ul.y);
					x2 = Math.max(x2, lr.x);
					y2 = Math.max(y2, lr.y);
				}
			});
		}
		
		var margin = 50;
		
		var width, height, tx, ty;
		if(x1 == undefined) {
			width = 0;
			height = 0;
			tx = 0;
			ty = 0;
		} else {
			width = x2 - x1;
			height = y2 - y1;
			tx = -x1+margin/2;
			ty = -y1+margin/2;
		}
		 
		
		
        // Set the width and height
        svgClone.setAttributeNS(null, 'width', width + margin);
        svgClone.setAttributeNS(null, 'height', height + margin);
		
		svgClone.childNodes[1].firstChild.setAttributeNS(null, 'transform', 'translate(' + tx + ", " + ty + ')');
		
		//remove scale factor
		svgClone.childNodes[1].removeAttributeNS(null, 'transform');
		
		try{
			var svgCont = svgClone.childNodes[1].childNodes[1];
			svgCont.parentNode.removeChild(svgCont);
		} catch(e) {}

		if(escapeText) {
			$A(svgClone.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_SVG, 'tspan')).each(function(elem) {
				elem.textContent = elem.textContent.escapeHTML();
			});
			
			$A(svgClone.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_SVG, 'text')).each(function(elem) {
				if(elem.childNodes.length == 0)
					elem.textContent = elem.textContent.escapeHTML();
			});
		}
		
		// generating absolute urls for the pdf-exporter
		$A(svgClone.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_SVG, 'image')).each(function(elem) {
			var href = elem.getAttributeNS("http://www.w3.org/1999/xlink","href");
			
			if(!href.match("^(http|https)://")) {
				href = window.location.protocol + "//" + window.location.host + href;
				elem.setAttributeNS("http://www.w3.org/1999/xlink", "href", href);
			}
		});
		
		
		// escape all links
		$A(svgClone.getElementsByTagNameNS(ORYX.CONFIG.NAMESPACE_SVG, 'a')).each(function(elem) {
			elem.setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", (elem.getAttributeNS("http://www.w3.org/1999/xlink","href")||"").escapeHTML());
		});
		
        return svgClone;
	},
	
	/**   
	* Removes all nodes (and its children) that has the
	* attribute visibility set to "hidden"
	*/
	_removeInvisibleElements: function(element) {
		var index = 0;
		while(index < element.childNodes.length) {
			var child = element.childNodes[index];
			if(child.getAttributeNS &&
				child.getAttributeNS(null, "visibility") === "hidden") {
				element.removeChild(child);
			} else {
				this._removeInvisibleElements(child);
				index++; 
			}
		}
		
	},
	
	/**
	 * This method checks all shapes on the canvas and removes all shapes that
	 * contain invalid bounds values or dockers values(NaN)
	 */
	/*cleanUp: function(parent) {
		if (!parent) {
			parent = this;
		}
		parent.getChildShapes().each(function(shape){
			var a = shape.bounds.a;
			var b = shape.bounds.b;
			if (isNaN(a.x) || isNaN(a.y) || isNaN(b.x) || isNaN(b.y)) {
				parent.remove(shape);
			}
			else {
				shape.getDockers().any(function(docker) {
					a = docker.bounds.a;
					b = docker.bounds.b;
					if (isNaN(a.x) || isNaN(a.y) || isNaN(b.x) || isNaN(b.y)) {
						parent.remove(shape);
						return true;
					}
					return false;
				});
				shape.getMagnets().any(function(magnet) {
					a = magnet.bounds.a;
					b = magnet.bounds.b;
					if (isNaN(a.x) || isNaN(a.y) || isNaN(b.x) || isNaN(b.y)) {
						parent.remove(shape);
						return true;
					}
					return false;
				});
				this.cleanUp(shape);
			}
		}.bind(this));
	},*/

	_delegateEvent: function(event) {
		if(this.eventHandlerCallback && ( event.target == this.rootNode || event.target == this.rootNode.parentNode )) {
			this.eventHandlerCallback(event, this);
		}
	},
	
	toString: function() { return "Canvas " + this.id },
    
    /**
     * Calls {@link ORYX.Core.AbstractShape#toJSON} and adds some stencil set information.
     */
    toJSON: function() {
        var json = arguments.callee.$.toJSON.apply(this, arguments);
        
//		if(ORYX.CONFIG.STENCILSET_HANDLER.length > 0) {
//			json.stencilset = {
//				url: this.getStencil().stencilSet().namespace()
//	        };
//		} else {
			json.stencilset = {
				url: this.getStencil().stencilSet().source(),
				namespace: this.getStencil().stencilSet().namespace()
	        };	
//		}
        
        
        return json;
    }
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

var idCounter = 0;
var ID_PREFIX = "resource";

/**
 * Main initialization method. To be called when loading
 * of the document, including all scripts, is completed.
 */
function init() {

	/* When the blank image url is not set programatically to a local
	 * representation, a spacer gif on the site of ext is loaded from the
	 * internet. This causes problems when internet or the ext site are not
	 * available. */
	Ext.BLANK_IMAGE_URL = ORYX.PATH + 'lib/ext-2.0.2/resources/images/default/s.gif';	
	
	ORYX.Log.debug("Querying editor instances");

	// Hack for WebKit to set the SVGElement-Classes
	ORYX.Editor.setMissingClasses();
    
    // If someone wants to create the editor instance himself
    if (window.onOryxResourcesLoaded) {
        window.onOryxResourcesLoaded();
    } 
    // Else if this is a newly created model
    else if(window.location.pathname.include(ORYX.CONFIG.ORYX_NEW_URL)){
        new ORYX.Editor({
            id: 'oryx-canvas123',
            fullscreen: true,
            stencilset: {
                url: ORYX.PATH + ORYX.Utils.getParamFromUrl("stencilset")
            }
        });
    } 
    // Else fetch the model from server and display editor
    else {
        //HACK for distinguishing between different backends
		// Backend of 2008 uses /self URL ending
	    var modelUrl = window.location.href.replace(/#.*/g, "");
		if(modelUrl.endsWith("/self")) {
			modelUrl = modelUrl.replace("/self","/json");
		} else {
			modelUrl += "&data";
		}

        ORYX.Editor.createByUrl(modelUrl, {
            id: modelUrl
        });
    }
}

/**
   @namespace Global Oryx name space
   @name ORYX
*/
if(!ORYX) {var ORYX = {};}

/**
 * The Editor class.
 * @class ORYX.Editor
 * @extends Clazz
 * @param {Object} config An editor object, passed to {@link ORYX.Editor#loadSerialized}
 * @param {String} config.id Any ID that can be used inside the editor. If fullscreen=false, any HTML node with this id must be present to render the editor to this node.
 * @param {boolean} [config.fullscreen=true] Render editor in fullscreen mode or not.
 * @param {String} config.stencilset.url Stencil set URL.
 * @param {String} [config.stencil.id] Stencil type used for creating the canvas.  
 * @param {Object} config.properties Any properties applied to the canvas.
*/
ORYX.Editor = {
    /** @lends ORYX.Editor.prototype */
	// Defines the global dom event listener 
	DOMEventListeners: new Hash(),

	// Defines the selection
	selection: [],
	
	// Defines the current zoom level
	zoomLevel:1.0,

	construct: function(config) {
		// initialization.
		this._eventsQueue 	= [];
		this.loadedPlugins 	= [];
		this.pluginsData 	= [];
		
		
		//meta data about the model for the signavio warehouse
		//directory, new, name, description, revision, model (the model data)
		
		this.modelMetaData = config;
		
		var model = config;
		if(config.model) {
			model = config.model;
		}
		
		this.id = model.resourceId;
        if(!this.id) {
        	this.id = model.id;
        	if(!this.id) {
        		this.id = ORYX.Editor.provideId();
        	}
        }
        
        // Defines if the editor should be fullscreen or not
		this.fullscreen = model.fullscreen || true;
		
		// Initialize the eventlistener
		this._initEventListener();

		// Load particular stencilset
		if(ORYX.CONFIG.BACKEND_SWITCH) {
			var ssUrl = (model.stencilset.namespace||model.stencilset.url).replace("#", "%23");
        	ORYX.Core.StencilSet.loadStencilSet(ORYX.CONFIG.STENCILSET_HANDLER + ssUrl, this.id);
		} else {
			var ssUrl = model.stencilset.url;
        	ORYX.Core.StencilSet.loadStencilSet(ssUrl, this.id);
		}
		
        
        //TODO load ealier and asynchronous??
        this._loadStencilSetExtensionConfig();
        
        //Load predefined StencilSetExtensions
        if(!!ORYX.CONFIG.SSEXTS){
        	ORYX.CONFIG.SSEXTS.each(function(ssext){
                this.loadSSExtension(ssext.namespace);
            }.bind(this));
        }

		// CREATES the canvas
		this._createCanvas(model.stencil ? model.stencil.id : null, model.properties);

		// GENERATES the whole EXT.VIEWPORT
		this._generateGUI();

		// Initializing of a callback to check loading ends
		var loadPluginFinished 	= false;
		var loadContentFinished = false;
		var initFinished = function(){	
			if( !loadPluginFinished || !loadContentFinished ){ return }
			this._finishedLoading();
		}.bind(this)
		
		// disable key events when Ext modal window is active
		ORYX.Editor.makeExtModalWindowKeysave(this._getPluginFacade());
		
		// LOAD the plugins
		window.setTimeout(function(){
			this.loadPlugins();
			loadPluginFinished = true;
			initFinished();
		}.bind(this), 100);

		// LOAD the content of the current editor instance
		window.setTimeout(function(){
            this.loadSerialized(model);
            this.getCanvas().update();
			loadContentFinished = true;
			initFinished();
		}.bind(this), 200);
	},
	
	_finishedLoading: function() {
		if(Ext.getCmp('oryx-loading-panel')){
			Ext.getCmp('oryx-loading-panel').hide()
		}
		
		// Do Layout for viewport
		this.layout.doLayout();
		// Generate a drop target
		new Ext.dd.DropTarget(this.getCanvas().rootNode.parentNode);
		
		// Fixed the problem that the viewport can not 
		// start with collapsed panels correctly
		if (ORYX.CONFIG.PANEL_RIGHT_COLLAPSED === true){
			this.layout_regions.east.collapse();
		}
		if (ORYX.CONFIG.PANEL_LEFT_COLLAPSED === true){
			this.layout_regions.west.collapse();
		}
		
		// Raise Loaded Event
		this.handleEvents( {type:ORYX.CONFIG.EVENT_LOADED} )
		
	},
	
	_initEventListener: function(){

		// Register on Events
		
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_KEYDOWN, this.catchKeyDownEvents.bind(this), true);
		document.documentElement.addEventListener(ORYX.CONFIG.EVENT_KEYUP, this.catchKeyUpEvents.bind(this), true);

		// Enable Key up and down Event
		this._keydownEnabled = 	true;
		this._keyupEnabled =  	true;

		this.DOMEventListeners[ORYX.CONFIG.EVENT_MOUSEDOWN] = [];
		this.DOMEventListeners[ORYX.CONFIG.EVENT_MOUSEUP] 	= [];
		this.DOMEventListeners[ORYX.CONFIG.EVENT_MOUSEOVER] = [];
		this.DOMEventListeners[ORYX.CONFIG.EVENT_MOUSEOUT] 	= [];
		this.DOMEventListeners[ORYX.CONFIG.EVENT_SELECTION_CHANGED] = [];
		this.DOMEventListeners[ORYX.CONFIG.EVENT_MOUSEMOVE] = [];
				
	},
	
	/**
	 * Generate the whole viewport of the
	 * Editor and initialized the Ext-Framework
	 * 
	 */
	_generateGUI: function() {

		//TODO make the height be read from eRDF data from the canvas.
		// default, a non-fullscreen editor shall define its height by layout.setHeight(int) 
		
		// Defines the layout hight if it's NOT fullscreen
		var layoutHeight 	= 400;
	
		var canvasParent	= this.getCanvas().rootNode.parentNode;

		// DEFINITION OF THE VIEWPORT AREAS
		this.layout_regions = {
				
				// DEFINES TOP-AREA
				north	: new Ext.Panel({ //TOOO make a composite of the oryx header and addable elements (for toolbar), second one should contain margins
					region	: 'north',
					cls		: 'x-panel-editor-north',
					autoEl	: 'div',
					border	: false
				}),	
				
				// DEFINES RIGHT-AREA
				east	: new Ext.Panel({
					region	: 'east',
					layout	: 'fit',
					cls		: 'x-panel-editor-east',
					/*layout: 'accordion',
					layoutConfig: {
		               // layout-specific configs go here
						titleCollapse: true,
						animate: true,
						activeOnTop: true
	                },*/
					autoEl	: 'div',
					border	:false,
					cmargins: {left:0, right:0},
					collapsible	: true,
					width	: ORYX.CONFIG.PANEL_RIGHT_WIDTH || 200,
					split	: true,
					title	: "East"
				}),
				
				
				// DEFINES BOTTOM-AREA
				south	: new Ext.Panel({
					region	: 'south',
					cls		: 'x-panel-editor-south',
					autoEl	: 'div',
					border	: false
				}),
				
				
				// DEFINES LEFT-AREA
				west	: new Ext.Panel({
					region	: 'west',
					layout	: 'anchor',
					autoEl	: 'div',
					cls		: 'x-panel-editor-west',
					collapsible	: true,
					width	: ORYX.CONFIG.PANEL_LEFT_WIDTH || 200,
					autoScroll:true,
					cmargins: {left:0, right:0},
					split	: true,
					title	: "West"
				}),
				
				
				// DEFINES CENTER-AREA (FOR THE EDITOR)
				center	: new Ext.Panel({
					region	: 'center',
					cls		: 'x-panel-editor-center',
					autoScroll: true,
					items	: {
						layout	: "fit",
						autoHeight: true,
						el		: canvasParent
					}
				})
		}
		
		// Hide every region except the center
		for (region in this.layout_regions) {
			if ( region != "center" ) {
				//this.layout_regions[ region ].hide();
			}
		}
		
		// Config for the Ext.Viewport 
		var layout_config = {
			layout: 'border',
			items: [
				this.layout_regions.north,
				this.layout_regions.east,
				this.layout_regions.south,
				this.layout_regions.west,
				this.layout_regions.center
			]
		}

		// IF Fullscreen, use a viewport
		if (this.fullscreen) {
			this.layout = new Ext.Viewport( layout_config )
		
		// IF NOT, use a panel and render it to the given id
		} else {
			layout_config.renderTo 	= this.id;
			layout_config.height 	= layoutHeight;
			this.layout = new Ext.Panel( layout_config )
		}
		
		//Generates the ORYX-Header
		this._generateHeader();
		
		
		// Set the editor to the center, and refresh the size
	 	canvasParent.parentNode.setAttributeNS(null, 'align', 'center');
	 	canvasParent.setAttributeNS(null, 'align', 'left');
		this.getCanvas().setSize({
			width	: ORYX.CONFIG.CANVAS_WIDTH,
			height	: ORYX.CONFIG.CANVAS_HEIGHT
		});		
						
	},
	
	_generateHeader: function(){
		
		var headerPanel = new Ext.Panel({
			height		: 30,
			autoHeight	: false,
			border		: false,
			html		: "<div id='header'><a href=\""+ORYX.CONFIG.WEB_URL+"\" target=\"_blank\"><img src='"+ORYX.PATH+"images/oryx.small.gif' border=\"0\" /></a><div style='clear: both;'></div></div>" 
		});

		var maActive 	= ORYX.MashupAPI && ORYX.MashupAPI.isUsed;
		var maKey		= maActive ? ORYX.MashupAPI.key : "";
		var maCanRun	= maActive ? ORYX.MashupAPI.canRun : false;	
		var maIsRemoteM	= maActive ? ORYX.MashupAPI.isModelRemote : true;	
		
		var maModelImage= maIsRemoteM ? "<img src='"+ORYX.PATH+"images/page_white_put.png'/>" : "";
		var maModelAuthI= maActive ? "<span class='mashupinfo'><img src='"+ORYX.PATH+"images/" +( maCanRun ? "plugin_error" : "plugin") +".png'/>" + maModelImage + "</span>" : "";
		
		
		// Callback if the user changes
		var fn = function(val){
			
			var publicText = ORYX.I18N.Oryx.notLoggedOn;
			var user = val && val.identifier && val.identifier != "public" ? decodeURI(val.identifier.gsub('"', "")).replace(/\+/g," ") : "";
			
			if( user.length <= 0 ){
				user 	= 	publicText;
			}
			
			var content = 	"<div id='header'>" +
								"<a href=\""+ORYX.CONFIG.WEB_URL+"\" target=\"_blank\">" +
									"<img src='"+ORYX.PATH+"images/oryx.small.gif' border=\"0\" />" + 
								"</a>" + 
								"<span class='openid " + (publicText == user ? "not" : "") + "'>" + 
									user + 
									maModelAuthI + 
								"</span>" + 
								"<div style='clear: both;'/>" + 
							"</div>";
			
			if( headerPanel.body ){
				headerPanel.body.dom.innerHTML = content;
			} else {
				headerPanel.html = content
			}
		};	
		
		ORYX.Editor.Cookie.onChange(fn);
		fn(ORYX.Editor.Cookie.getParams());
		
        var headerPanel2 = new Ext.Panel({
								height		: 30,
								autoHeight	: false,
								border		: false,
								html		:  $(ORYX.CONFIG.HEADER).innerHTML
				});
               
        var headerPanel3 = new Ext.Panel({
									height		: 30,
                        			width		: 30,
									autoHeight	: false,
									border		: false,
									html		: "<ul class='panel-group'><li id='move_front' class='icon-move_front' title='"+ ORYX.I18N.Arrangement.btf +"'>" + ORYX.I18N.Arrangement.btf +"</li><li id='move_back' class='icon-move_back' title='"+ ORYX.I18N.Arrangement.btb +"'>" + ORYX.I18N.Arrangement.btb + "</li><li id='move_forwards' class='icon-move_forwards' title='"+ ORYX.I18N.Arrangement.bf +"'>" + ORYX.I18N.Arrangement.bf + "</li><li id='move_backwards' class='icon-move_backwards' title='"+ ORYX.I18N.Arrangement.bb +"'>"+ ORYX.I18N.Arrangement.bb +"</li></ul><ul class='panel-group'><li id='aling_bottom' class='icon-aling_bottom' title='"+ ORYX.I18N.Arrangement.ab +"'>" + ORYX.I18N.Arrangement.ab +"</li><li id='aling_middle' class='icon-aling_middle' title='"+ ORYX.I18N.Arrangement.am +"'>" + ORYX.I18N.Arrangement.am +"</li><li id='aling_top' class='icon-aling_top' title='"+ ORYX.I18N.Arrangement.at +"'>" + ORYX.I18N.Arrangement.at +"</li></ul><ul class='panel-group'><li id='aling_left' class='icon-aling_left' title='"+ ORYX.I18N.Arrangement.al +"'>" + ORYX.I18N.Arrangement.al +"</li><li id='aling_center' class='icon-aling_center' title='"+ ORYX.I18N.Arrangement.ac +"'>" + ORYX.I18N.Arrangement.ac +"</li><li id='aling_right' class='icon-aling_right' title='"+ ORYX.I18N.Arrangement.ar +"'>" + ORYX.I18N.Arrangement.ar +"</li><li id='aling_size' class='icon-aling_size' title='"+ ORYX.I18N.Arrangement.as +"'>" + ORYX.I18N.Arrangement.as +"</li></ul><ul class='panel-group'><li id='shape_group' class='icon-shape_group' title='"+ ORYX.I18N.Grouping.group +"'>"+ ORYX.I18N.Grouping.group +"</li><li id='shape_ungroup' class='icon-shape_ungroup' title='"+ ORYX.I18N.Grouping.ungroup +"'>" + ORYX.I18N.Grouping.ungroup + "</li></ul>" 
				});
                
                
                
		// The oryx header
		this.addToRegion("north", headerPanel2 );
        this.addToRegion("east", headerPanel3 );
	},
	
	/**
	 * adds a component to the specified region
	 * 
	 * @param {String} region
	 * @param {Ext.Component} component
	 * @param {String} title, optional
	 * @return {Ext.Component} dom reference to the current region or null if specified region is unknown
	 */
	addToRegion: function(region, component, title) {
		
		if (region.toLowerCase && this.layout_regions[region.toLowerCase()]) {
			var current_region = this.layout_regions[region.toLowerCase()];

			
			current_region.add(component);
						
			ORYX.Log.debug("original dimensions of region %0: %1 x %2", current_region.region, current_region.width, current_region.height)

			// update dimensions of region if required.
			if  (!current_region.width && component.initialConfig && component.initialConfig.width) {
				ORYX.Log.debug("resizing width of region %0: %1", current_region.region, component.initialConfig.width)	
				current_region.setWidth(component.initialConfig.width)
			}
			if  (component.initialConfig && component.initialConfig.height) {
				ORYX.Log.debug("resizing height of region %0: %1", current_region.region, component.initialConfig.height)
				var current_height = current_region.height || 0;
				current_region.height = component.initialConfig.height + current_height;
				current_region.setHeight(component.initialConfig.height + current_height)
			}
			
						
			// set title if provided as parameter.
            if (typeof title == "string") {
                    switch(region.toLowerCase()) {
                    case "east":
                            if (current_region.title != "East"){
                                    title = current_region.title + " and " + title;
                                    current_region.setTitle(title);
                            }
                            current_region.setTitle(title);
                            break;
                    case "west":
                            if (current_region.title != "West"){
                                    title = current_region.title + " and " + title;
                                    current_region.setTitle(title);
                            }
                            current_region.setTitle(title);
                            break;
                    default :
                            current_region.setTitle(title);
                    }
            }

			
            //If we we want to put more than one content pane into the east region
            //we have to add a layout that can handle this. 
			if (region.toLowerCase() == 'east' && current_region.items.length >= 2 ) {
				var layout = new Ext.layout.Accordion( current_region.layoutConfig );
            	current_region.setLayout( layout );
				
				var items = current_region.items.clone();			
			} 
			
			//This renders the layout
			current_region.ownerCt.doLayout();
			current_region.show();

			if(Ext.isMac)
				ORYX.Editor.resizeFix();
			
			return current_region;
		}
		
		return null;
	},
	

	getAvailablePlugins: function(){
		var curAvailablePlugins=ORYX.availablePlugins.clone();
		curAvailablePlugins.each(function(plugin){
			if(this.loadedPlugins.find(function(loadedPlugin){
				return loadedPlugin.type==this.name;
			}.bind(plugin))){
				plugin.engaged=true;
			}else{
				plugin.engaged=false;
			}
			}.bind(this));
		return curAvailablePlugins;
	},

	loadScript: function (url, callback){
	    var script = document.createElement("script")
	    script.type = "text/javascript";
	    if (script.readyState){  //IE
	        script.onreadystatechange = function(){
	            if (script.readyState == "loaded" || script.readyState == "complete"){
	                script.onreadystatechange = null;
	                callback();
	            }
        	};
    	} else {  //Others
	        script.onload = function(){
	            callback();
	        };
		}
	    script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	},
	/**
	 * activate Plugin
	 * 
	 * @param {String} name
	 * @param {Function} callback
	 * 		callback(sucess, [errorCode])
	 * 			errorCodes: NOTUSEINSTENCILSET, REQUIRESTENCILSET, NOTFOUND, YETACTIVATED
	 */
	activatePluginByName: function(name, callback, loadTry){

		var match=this.getAvailablePlugins().find(function(value){return value.name==name});
		if(match && (!match.engaged || (match.engaged==='false'))){		
				var loadedStencilSetsNamespaces = this.getStencilSets().keys();
				var facade = this._getPluginFacade();
				var newPlugin;
				var me=this;
				ORYX.Log.debug("Initializing plugin '%0'", match.name);
				
					if (!match.requires 	|| !match.requires.namespaces 	|| match.requires.namespaces.any(function(req){ return loadedStencilSetsNamespaces.indexOf(req) >= 0 }) ){
						if(!match.notUsesIn 	|| !match.notUsesIn.namespaces 	|| !match.notUsesIn.namespaces.any(function(req){ return loadedStencilSetsNamespaces.indexOf(req) >= 0 })){
	
					try {
						
						var className 	= eval(match.name);
							var newPlugin = new className(facade, match);
							newPlugin.type = match.name;
							
							// If there is an GUI-Plugin, they get all Plugins-Offer-Meta-Data
							if (newPlugin.registryChanged) 
								newPlugin.registryChanged(me.pluginsData);
							
							// If there have an onSelection-Method it will pushed to the Editor Event-Handler
							if (newPlugin.onSelectionChanged) 
								me.registerOnEvent(ORYX.CONFIG.EVENT_SELECTION_CHANGED, newPlugin.onSelectionChanged.bind(newPlugin));
							this.loadedPlugins.push(newPlugin);
							this.loadedPlugins.each(function(loaded){
								if(loaded.registryChanged)
									loaded.registryChanged(this.pluginsData);
							}.bind(me));
							callback(true);
						
					} catch(e) {
						ORYX.Log.warn("Plugin %0 is not available", match.name);
						if(!!loadTry){
							callback(false,"INITFAILED");
							return;
						}
						this.loadScript("plugins/scripts/"+match.source, this.activatePluginByName.bind(this,match.name,callback,true));
					}
					}else{
						callback(false,"NOTUSEINSTENCILSET");
						ORYX.Log.info("Plugin need a stencilset which is not loaded'", match.name);
					}
								
				} else {
					callback(false,"REQUIRESTENCILSET");
					ORYX.Log.info("Plugin need a stencilset which is not loaded'", match.name);
				}

			
			}else{
				callback(false, match?"NOTFOUND":"YETACTIVATED");
				//TODO error handling
			}
	},

	/**
	 *  Laden der Plugins
	 */
	loadPlugins: function() {
		
		// if there should be plugins but still are none, try again.
		// TODO this should wait for every plugin respectively.
		/*if (!ORYX.Plugins && ORYX.availablePlugins.length > 0) {
			window.setTimeout(this.loadPlugins.bind(this), 100);
			return;
		}*/
		
		var me = this;
		var newPlugins = [];


		var loadedStencilSetsNamespaces = this.getStencilSets().keys();

		// Available Plugins will be initalize
		var facade = this._getPluginFacade();
		
		// If there is an Array where all plugins are described, than only take those
		// (that comes from the usage of oryx with a mashup api)
		if( ORYX.MashupAPI && ORYX.MashupAPI.loadablePlugins && ORYX.MashupAPI.loadablePlugins instanceof Array ){
		
			// Get the plugins from the available plugins (those who are in the plugins.xml)
			ORYX.availablePlugins = $A(ORYX.availablePlugins).findAll(function(value){
										return ORYX.MashupAPI.loadablePlugins.include( value.name )
									})
			
			// Add those plugins to the list, which are only in the loadablePlugins list
			ORYX.MashupAPI.loadablePlugins.each(function( className ){
				if( !(ORYX.availablePlugins.find(function(val){ return val.name == className }))){
					ORYX.availablePlugins.push( {name: className } );
				}
			})
		}
		
		
		ORYX.availablePlugins.each(function(value) {
			ORYX.Log.debug("Initializing plugin '%0'", value.name);
				if( (!value.requires 	|| !value.requires.namespaces 	|| value.requires.namespaces.any(function(req){ return loadedStencilSetsNamespaces.indexOf(req) >= 0 }) ) &&
					(!value.notUsesIn 	|| !value.notUsesIn.namespaces 	|| !value.notUsesIn.namespaces.any(function(req){ return loadedStencilSetsNamespaces.indexOf(req) >= 0 }) )&&
					//We assume if there is no engaged attribute in an XML
					//node of a plugin the plugin is activated by default.
					//If there is an engaged attribute and it is set to true
					//the plugin will not be loaded
					(!value.engaged || value.engaged=="true" )){

				try {
					var className 	= eval(value.name);
					if( className ){
						var plugin		= new className(facade, value);
						plugin.type		= value.name;
						newPlugins.push( plugin );
						plugin.engaged=true;
					}
				} catch(e) {
					ORYX.Log.warn("Plugin %0 is not available", value.name);
				}
							
			} else {
				ORYX.Log.info("Plugin need a stencilset which is not loaded'", value.name);
			}
			
		});

		newPlugins.each(function(value) {
			// If there is an GUI-Plugin, they get all Plugins-Offer-Meta-Data
			if(value.registryChanged)
				value.registryChanged(me.pluginsData);

			// If there have an onSelection-Method it will pushed to the Editor Event-Handler
			if(value.onSelectionChanged)
				me.registerOnEvent(ORYX.CONFIG.EVENT_SELECTION_CHANGED, value.onSelectionChanged.bind(value));
		});

		this.loadedPlugins = newPlugins;
		
		// Hack for the Scrollbars
		if(Ext.isMac) {
			ORYX.Editor.resizeFix();
		}
		
		this.registerPluginsOnKeyEvents();
		
		this.setSelection();
		
	},

    /**
     * Loads the stencil set extension file, defined in ORYX.CONFIG.SS_EXTENSIONS_CONFIG
     */
    _loadStencilSetExtensionConfig: function(){
        // load ss extensions
        new Ajax.Request(ORYX.CONFIG.SS_EXTENSIONS_CONFIG, {
            method: 'GET',
            asynchronous: false,
            onSuccess: (function(transport) {
                var jsonObject = Ext.decode(transport.responseText);
                this.ss_extensions_def = jsonObject;
            }).bind(this),
            onFailure: (function(transport) {
                ORYX.Log.error("Editor._loadStencilSetExtensionConfig: Loading stencil set extension configuration file failed." + transport);
            }).bind(this)
        });
    },

	/**
	 * Creates the Canvas
	 * @param {String} [stencilType] The stencil type used for creating the canvas. If not given, a stencil with myBeRoot = true from current stencil set is taken.
	 * @param {Object} [canvasConfig] Any canvas properties (like language).
	 */
	_createCanvas: function(stencilType, canvasConfig) {
        if (stencilType) {
            // Add namespace to stencilType
            if (stencilType.search(/^http/) === -1) {
                stencilType = this.getStencilSets().values()[0].namespace() + stencilType;
            }
        }
        else {
            // Get any root stencil type
            stencilType = this.getStencilSets().values()[0].findRootStencilName();
        }
        
		// get the stencil associated with the type
		var canvasStencil = ORYX.Core.StencilSet.stencil(stencilType);
			
		if (!canvasStencil) 
			ORYX.Log.fatal("Initialisation failed, because the stencil with the type %0 is not part of one of the loaded stencil sets.", stencilType);
		
		// create all dom
		// TODO fix border, so the visible canvas has a double border and some spacing to the scrollbars
		var div = ORYX.Editor.graft("http://www.w3.org/1999/xhtml", null, ['div']);
		// set class for custom styling
		div.addClassName("ORYX_Editor");
						
		// create the canvas
		this._canvas = new ORYX.Core.Canvas({
			width					: ORYX.CONFIG.CANVAS_WIDTH,
			height					: ORYX.CONFIG.CANVAS_HEIGHT,
			'eventHandlerCallback'	: this.handleEvents.bind(this),
			id						: this.id,
			parentNode				: div
		}, canvasStencil);
        
        if (canvasConfig) {
          // Migrate canvasConfig to an RDF-like structure
          //FIXME this isn't nice at all because we don't want rdf any longer
          var properties = [];
          for(field in canvasConfig){
            properties.push({
              prefix: 'oryx',
              name: field,
              value: canvasConfig[field]
            });
          }
            
          this._canvas.deserialize(properties);
        }
				
	},

	/**
	 * Returns a per-editor singleton plugin facade.
	 * To be used in plugin initialization.
	 */
	_getPluginFacade: function() {

		// if there is no pluginfacade already created:
		if(!(this._pluginFacade))

			// create it.
			this._pluginFacade = {

				activatePluginByName:		this.activatePluginByName.bind(this),
				//deactivatePluginByName:		this.deactivatePluginByName.bind(this),
				getAvailablePlugins:	this.getAvailablePlugins.bind(this),
				offer:					this.offer.bind(this),
				getStencilSets:			this.getStencilSets.bind(this),
				getRules:				this.getRules.bind(this),
				loadStencilSet:			this.loadStencilSet.bind(this),
				createShape:			this.createShape.bind(this),
				deleteShape:			this.deleteShape.bind(this),
				getSelection:			this.getSelection.bind(this),
				setSelection:			this.setSelection.bind(this),
				updateSelection:		this.updateSelection.bind(this),
				getCanvas:				this.getCanvas.bind(this),
				
				importJSON:				this.importJSON.bind(this),
				importERDF:				this.importERDF.bind(this),
				getERDF:				this.getERDF.bind(this),
                                getJSON:                this.getJSON.bind(this),
                                getSerializedJSON:      this.getSerializedJSON.bind(this),
				
				executeCommands:		this.executeCommands.bind(this),
				
				registerOnEvent:		this.registerOnEvent.bind(this),
				unregisterOnEvent:		this.unregisterOnEvent.bind(this),
				raiseEvent:				this.handleEvents.bind(this),
				enableEvent:			this.enableEvent.bind(this),
				disableEvent:			this.disableEvent.bind(this),
				
				eventCoordinates:		this.eventCoordinates.bind(this),
				addToRegion:			this.addToRegion.bind(this),
				
				getModelMetaData:		this.getModelMetaData.bind(this)
			};

		// return it.
		return this._pluginFacade;
	},

	/**
	 * Implementes the command pattern
	 * (The real usage of the command pattern
	 * is implemented and shown in the Plugins/undo.js)
	 *
	 * @param <Oryx.Core.Command>[] Array of commands
	 */
	executeCommands: function(commands){
		
		// Check if the argument is an array and the elements are from command-class
		if ( 	commands instanceof Array 	&& 
				commands.length > 0 		&& 
				commands.all(function(command){ return command instanceof ORYX.Core.Command }) ) {
		
			// Raise event for executing commands
			this.handleEvents({
				type		: ORYX.CONFIG.EVENT_EXECUTE_COMMANDS,
				commands	: commands
			});
			
			// Execute every command
			commands.each(function(command){
				command.execute();
			})
			
		}
	},
	
    /**
     * Returns JSON of underlying canvas (calls ORYX.Canvas#toJSON()).
     * @return {Object} Returns JSON representation as JSON object.
     */
    getJSON: function(){
        var canvas = this.getCanvas().toJSON();
        canvas.ssextensions = this.getStencilSets().values()[0].extensions().keys();
        return canvas;
    },
    
    /**
     * Serializes a call to toJSON().
     * @return {String} Returns JSON representation as string.
     */
    getSerializedJSON: function(){
        return Ext.encode(this.getJSON());
    },
	
    /**
	 * @return {String} Returns eRDF representation.
	 * @deprecated Use ORYX.Editor#getJSON instead, if possible.
	 */
	getERDF:function(){

		// Get the serialized dom
        var serializedDOM = DataManager.serializeDOM( this._getPluginFacade() );
		
		// Add xml definition if there is no
		serializedDOM = '<?xml version="1.0" encoding="utf-8"?>' +
						'<html xmlns="http://www.w3.org/1999/xhtml" ' +
						'xmlns:b3mn="http://b3mn.org/2007/b3mn" ' +
						'xmlns:ext="http://b3mn.org/2007/ext" ' +
						'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" ' +
						'xmlns:atom="http://b3mn.org/2007/atom+xhtml">' +
						'<head profile="http://purl.org/NET/erdf/profile">' +
						'<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />' +
						'<link rel="schema.dcTerms" href="http://purl.org/dc/terms/ " />' +
						'<link rel="schema.b3mn" href="http://b3mn.org" />' +
						'<link rel="schema.oryx" href="http://oryx-editor.org/" />' +
						'<link rel="schema.raziel" href="http://raziel.org/" />' +
						'<base href="' +
						location.href.split("?")[0] +
						'" />' +
						'</head><body>' +
						serializedDOM +
						'</body></html>';
		
		return serializedDOM;				
	},
    
	/**
	* Imports shapes in JSON as expected by {@link ORYX.Editor#loadSerialized}
	* @param {Object|String} jsonObject The (serialized) json object to be imported
	* @param {boolean } [noSelectionAfterImport=false] Set to true if no shapes should be selected after import
	* @throws {SyntaxError} If the serialized json object contains syntax errors
	*/
	importJSON: function(jsonObject, noSelectionAfterImport) {
		
        try {
            jsonObject = this.renewResourceIds(jsonObject);
        } catch(error){
            throw error;
        }     
		//check, if the imported json model can be loaded in this editor
		// (stencil set has to fit)
        if (!jsonObject.stencilset) {
        	Ext.Msg.alert(ORYX.I18N.JSONImport.title, ORYX.I18N.JSONImport.invalidJSON);
        	return null;
        }
		if(jsonObject.stencilset.namespace && jsonObject.stencilset.namespace !== this.getCanvas().getStencil().stencilSet().namespace()) {
			Ext.Msg.alert(ORYX.I18N.JSONImport.title, String.format(ORYX.I18N.JSONImport.wrongSS, jsonObject.stencilset.namespace, this.getCanvas().getStencil().stencilSet().namespace()));
			return null;
		} else {
			var commandClass = ORYX.Core.Command.extend({
			construct: function(jsonObject, loadSerializedCB, noSelectionAfterImport, facade){
				this.jsonObject = jsonObject;
				this.noSelection = noSelectionAfterImport;
				this.facade = facade;
				this.shapes;
				this.connections = [];
				this.parents = new Hash();
				this.selection = this.facade.getSelection();
				this.loadSerialized = loadSerializedCB;
			},			
			execute: function(){
				
				if (!this.shapes) {
					// Import the shapes out of the serialization		
					this.shapes	= this.loadSerialized( this.jsonObject );		
					
					//store all connections
					this.shapes.each(function(shape) {
						
						if (shape.getDockers) {
							var dockers = shape.getDockers();
							if (dockers) {
								if (dockers.length > 0) {
									this.connections.push([dockers.first(), dockers.first().getDockedShape(), dockers.first().referencePoint]);
								}
								if (dockers.length > 1) {
									this.connections.push([dockers.last(), dockers.last().getDockedShape(), dockers.last().referencePoint]);
								}
							}
						}
						
						//store parents
						this.parents[shape.id] = shape.parent;
					}.bind(this));
				} else {
					this.shapes.each(function(shape) {
						this.parents[shape.id].add(shape);
					}.bind(this));
					
					this.connections.each(function(con) {
						con[0].setDockedShape(con[1]);
						con[0].setReferencePoint(con[2]);
						//con[0].update();
					});
				}
				
				//this.parents.values().uniq().invoke("update");
				this.facade.getCanvas().update();
					
				if(!this.noSelection)
					this.facade.setSelection(this.shapes);
				else
					this.facade.updateSelection();
				},
				rollback: function(){
					var selection = this.facade.getSelection();
					
					this.shapes.each(function(shape) {
						selection = selection.without(shape);
						this.facade.deleteShape(shape);
					}.bind(this));
					
					/*this.parents.values().uniq().each(function(parent) {
						if(!this.shapes.member(parent))
							parent.update();
					}.bind(this));*/
					
					this.facade.getCanvas().update();
					
					this.facade.setSelection(selection);
				}
			})
			
			var command = new commandClass(jsonObject, 
											this.loadSerialized.bind(this),
											noSelectionAfterImport,
											this._getPluginFacade());
			
			this.executeCommands([command]);	
			
			return command.shapes.clone();
		}
	},
    
    /**
     * This method renew all resource Ids and according references.
     * Warning: The implementation performs a substitution on the serialized object for
     * easier implementation. This results in a low performance which is acceptable if this
     * is only used when importing models.
     * @param {Object|String} jsonObject
     * @throws {SyntaxError} If the serialized json object contains syntax errors.
     * @return {Object} The jsonObject with renewed ids.
     * @private
     */
    renewResourceIds: function(jsonObject){
        // For renewing resource ids, a serialized and object version is needed
        if(Ext.type(jsonObject) === "string"){
            try {
                var serJsonObject = jsonObject;
                jsonObject = Ext.decode(jsonObject);
            } catch(error){
                throw new SyntaxError(error.message);
            }
        } else {
            var serJsonObject = Ext.encode(jsonObject);
        }        
        
        // collect all resourceIds recursively
        var collectResourceIds = function(shapes){
            if(!shapes) return [];
            
            return shapes.map(function(shape){
                return collectResourceIds(shape.childShapes).concat(shape.resourceId);
            }).flatten();
        }
        var resourceIds = collectResourceIds(jsonObject.childShapes);
        
        // Replace each resource id by a new one
        resourceIds.each(function(oldResourceId){
            var newResourceId = ORYX.Editor.provideId();
            serJsonObject = serJsonObject.gsub('"'+oldResourceId+'"', '"'+newResourceId+'"')
        });
        
        return Ext.decode(serJsonObject);
    },
	
	/**
	 * Import erdf structure to the editor
	 *
	 */
	importERDF: function( erdfDOM ){

		var serialized = this.parseToSerializeObjects( erdfDOM );	
		
		if(serialized)
			return this.importJSON(serialized, true);
	},

	/**
	 * Parses one model (eRDF) to the serialized form (JSON)
	 * 
	 * @param {Object} oneProcessData
	 * @return {Object} The JSON form of given eRDF model, or null if it couldn't be extracted 
	 */
	parseToSerializeObjects: function( oneProcessData ){
		
		// Firefox splits a long text node into chunks of 4096 characters.
		// To prevent truncation of long property values the normalize method must be called
		if(oneProcessData.normalize) oneProcessData.normalize();
		try {
			var xsl = "";
			var source=ORYX.PATH + "lib/extract-rdf.xsl";
			new Ajax.Request(source, {
				asynchronous: false,
				method: 'get',
				onSuccess: function(transport){
					xsl = transport.responseText
				}.bind(this),
				onFailure: (function(transport){
					ORYX.Log.error("XSL load failed" + transport);
				}).bind(this)
			});
			var domParser = new DOMParser();
			var xmlObject = oneProcessData;
			var xslObject = domParser.parseFromString(xsl, "text/xml");
        	var xsltProcessor = new XSLTProcessor();
        	var xslRef = document.implementation.createDocument("", "", null);
        	xsltProcessor.importStylesheet(xslObject);
        
            var new_rdf = xsltProcessor.transformToFragment(xmlObject, document);
            var serialized_rdf = (new XMLSerializer()).serializeToString(new_rdf);
			}catch(e){
			Ext.Msg.alert("Oryx", error);
			var serialized_rdf = "";
		}
            
            // Firefox 2 to 3 problem?!
            serialized_rdf = !serialized_rdf.startsWith("<?xml") ? "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" + serialized_rdf : serialized_rdf;

        var req = new Ajax.Request(ORYX.CONFIG.ROOT_PATH+"rdf2json", {
          method: 'POST',
          asynchronous: false,
          onSuccess: function(transport) {
              Ext.decode(transport.responseText);
          },
          parameters: {
              rdf: serialized_rdf
          }
        });
        
        return Ext.decode(req.transport.responseText);
	},

    /**
     * Loads serialized model to the oryx.
     * @example
     * editor.loadSerialized({
     *    resourceId: "mymodel1",
     *    childShapes: [
     *       {
     *          stencil:{ id:"Subprocess" },
     *          outgoing:[{resourceId: 'aShape'}],
     *          target: {resourceId: 'aShape'},
     *          bounds:{ lowerRight:{ y:510, x:633 }, upperLeft:{ y:146, x:210 } },
     *          resourceId: "myshape1",
     *          childShapes:[],
     *          properties:{},
     *       }
     *    ],
     *    properties:{
     *       language: "English"
     *    },
     *    stencilset:{
     *       url:ORYX.PATH + "/stencilsets/bpmn1.1/bpmn1.1.json"
     *    },
     *    stencil:{
     *       id:"BPMNDiagram"
     *    }
     * });
     * @param {Object} model Description of the model to load.
     * @param {Array} [model.ssextensions] List of stenctil set extensions.
     * @param {String} model.stencilset.url
     * @param {String} model.stencil.id 
     * @param {Array} model.childShapes
     * @param {Array} [model.properties]
     * @param {String} model.resourceId
     * @return {ORYX.Core.Shape[]} List of created shapes
     * @methodOf ORYX.Editor.prototype
     */
    loadSerialized: function( model ){
        var canvas  = this.getCanvas();
      
        // Bugfix (cf. http://code.google.com/p/oryx-editor/issues/detail?id=240)
        // Deserialize the canvas' stencil set extensions properties first!
        this.loadSSExtensions(model.ssextensions);
        var shapes = this.getCanvas().addShapeObjects(model.childShapes, this.handleEvents.bind(this));
        
        if(model.properties) {
        	for(key in model.properties) {
        		var prop = model.properties[key];
        		if (!(typeof prop === "string")) {
        			prop = Ext.encode(prop);
        		}
            	this.getCanvas().setProperty("oryx-" + key, prop);
            }
        }
        
        
        this.getCanvas().updateSize();
        return shapes;
    },
    
    /**
     * Calls ORYX.Editor.prototype.ss_extension_namespace for each element
     * @param {Array} ss_extension_namespaces An array of stencil set extension namespaces.
     */
    loadSSExtensions: function(ss_extension_namespaces){
        if(!ss_extension_namespaces) return;

        ss_extension_namespaces.each(function(ss_extension_namespace){
            this.loadSSExtension(ss_extension_namespace);
        }.bind(this));
    },
	
	/**
	* Loads a stencil set extension.
	* The stencil set extensions definiton file must already
	* be loaded when the editor is initialized.
	*/
	loadSSExtension: function(ss_extension_namespace) {				
		
		if (this.ss_extensions_def) {
			var extension = this.ss_extensions_def.extensions.find(function(ex){
				return (ex.namespace == ss_extension_namespace);
			});
			
			if (!extension) {
				return;
			}
			
			var stencilset = this.getStencilSets()[extension["extends"]];
			
			if (!stencilset) {
				return;
			}
			
			stencilset.addExtension(ORYX.CONFIG.SS_EXTENSIONS_FOLDER + extension["definition"])
			//stencilset.addExtension("/oryx/build/stencilsets/extensions/" + extension["definition"])
			this.getRules().initializeRules(stencilset);
			
			this._getPluginFacade().raiseEvent({
				type: ORYX.CONFIG.EVENT_STENCIL_SET_LOADED
			});
		}
		
	},

	disableEvent: function(eventType){
		if(eventType == ORYX.CONFIG.EVENT_KEYDOWN) {
			this._keydownEnabled = false;
		}
		if(eventType == ORYX.CONFIG.EVENT_KEYUP) {
			this._keyupEnabled = false;
		}
		if(this.DOMEventListeners.keys().member(eventType)) {
			var value = this.DOMEventListeners.remove(eventType);
			this.DOMEventListeners['disable_' + eventType] = value;
		}
	},

	enableEvent: function(eventType){
		if(eventType == ORYX.CONFIG.EVENT_KEYDOWN) {
			this._keydownEnabled = true;
		}
		
		if(eventType == ORYX.CONFIG.EVENT_KEYUP) {
			this._keyupEnabled = true;
		}
		
		if(this.DOMEventListeners.keys().member("disable_" + eventType)) {
			var value = this.DOMEventListeners.remove("disable_" + eventType);
			this.DOMEventListeners[eventType] = value;
		}
	},

	/**
	 *  Methods for the PluginFacade
	 */
	registerOnEvent: function(eventType, callback) {
		if(!(this.DOMEventListeners.keys().member(eventType))) {
			this.DOMEventListeners[eventType] = [];
		}

		this.DOMEventListeners[eventType].push(callback);
	},

	unregisterOnEvent: function(eventType, callback) {
		if(this.DOMEventListeners.keys().member(eventType)) {
			this.DOMEventListeners[eventType] = this.DOMEventListeners[eventType].without(callback);
		} else {
			// Event is not supported
			// TODO: Error Handling
		}
	},

	getSelection: function() {
		return this.selection;
	},

	getStencilSets: function() { 
		return ORYX.Core.StencilSet.stencilSets(this.id); 
	},
	
	getRules: function() {
		return ORYX.Core.StencilSet.rules(this.id);
	},
	
	loadStencilSet: function(source) {
		try {
			ORYX.Core.StencilSet.loadStencilSet(source, this.id);
			this.handleEvents({type:ORYX.CONFIG.EVENT_STENCIL_SET_LOADED});
		} catch (e) {
			ORYX.Log.warn("Requesting stencil set file failed. (" + e + ")");
		}
	},

	offer: function(pluginData) {
		if(!this.pluginsData.member(pluginData)){
			this.pluginsData.push(pluginData);
		}
	},
	
	/**
	 * It creates an new event or adds the callback, if already existing,
	 * for the key combination that the plugin passes in keyCodes attribute
	 * of the offer method.
	 * 
	 * The new key down event fits the schema:
	 * 		key.event[.metactrl][.alt][.shift].'thekeyCode'
	 */
	registerPluginsOnKeyEvents: function() {
		this.pluginsData.each(function(pluginData) {
			
			if(pluginData.keyCodes) {
				
				pluginData.keyCodes.each(function(keyComb) {
					var eventName = "key.event";
					
					/* Include key action */
					eventName += '.' + keyComb.keyAction;
					
					if(keyComb.metaKeys) {
						/* Register on ctrl or apple meta key as meta key */
						if(keyComb.metaKeys.
							indexOf(ORYX.CONFIG.META_KEY_META_CTRL) > -1) {
								eventName += "." + ORYX.CONFIG.META_KEY_META_CTRL;
						}
							
						/* Register on alt key as meta key */
						if(keyComb.metaKeys.
							indexOf(ORYX.CONFIG.META_KEY_ALT) > -1) {
								eventName += '.' + ORYX.CONFIG.META_KEY_ALT;
						}
						
						/* Register on shift key as meta key */
						if(keyComb.metaKeys.
							indexOf(ORYX.CONFIG.META_KEY_SHIFT) > -1) {
								eventName += '.' + ORYX.CONFIG.META_KEY_SHIFT;
						}		
					}
					
					/* Register on the actual key */
					if(keyComb.keyCode)	{
						eventName += '.' + keyComb.keyCode;
					}
					
					/* Register the event */
					ORYX.Log.debug("Register Plugin on Key Event: %0", eventName);
					this.registerOnEvent(eventName,pluginData.functionality);
				
				}.bind(this));
			}
		}.bind(this));
	},

	setSelection: function(elements, subSelectionElement, force) {
		
		if (!elements) { elements = [] }
		
		elements = elements.compact().findAll(function(n){ return n instanceof ORYX.Core.Shape });
		
		if (elements.first() instanceof ORYX.Core.Canvas) {
			elements = [];
		}
		
		if (!force && elements.length === this.selection.length && this.selection.all(function(r){ return elements.include(r) })){
			return;
		}
		
		this.selection = elements;
		this._subSelection = subSelectionElement;
		
		this.handleEvents({type:ORYX.CONFIG.EVENT_SELECTION_CHANGED, elements:elements, subSelection: subSelectionElement})
	},
	
	updateSelection: function() {
		this.setSelection(this.selection, this._subSelection, true);
		/*var s = this.selection;
		this.setSelection();
		this.setSelection(s);*/
	},

	getCanvas: function() {
		return this._canvas;
	},
	

	/**
	*	option = {
	*		type: string,
	*		position: {x:int, y:int},
	*		connectingType:	uiObj-Class
	*		connectedShape: uiObj
	*		draggin: bool
	*		namespace: url
	*       parent: ORYX.Core.AbstractShape
	*		template: a template shape that the newly created inherits properties from.
	*		}
	*/
	createShape: function(option) {

		if(option && option.serialize && option.serialize instanceof Array){
		
			var type = option.serialize.find(function(obj){return (obj.prefix+"-"+obj.name) == "oryx-type"});
			var stencil = ORYX.Core.StencilSet.stencil(type.value);
		
			if(stencil.type() == 'node'){
				var newShapeObject = new ORYX.Core.Node({'eventHandlerCallback':this.handleEvents.bind(this)}, stencil);	
			} else {
				var newShapeObject = new ORYX.Core.Edge({'eventHandlerCallback':this.handleEvents.bind(this)}, stencil);	
			}
		
			this.getCanvas().add(newShapeObject);
			newShapeObject.deserialize(option.serialize);
		
			return newShapeObject;
		}

		// If there is no argument, throw an exception
		if(!option || !option.type || !option.namespace) { throw "To create a new shape you have to give an argument with type and namespace";}
		
		var canvas = this.getCanvas();
		var newShapeObject;

		// Get the shape type
		var shapetype = option.type;

		// Get the stencil set
		var sset = ORYX.Core.StencilSet.stencilSet(option.namespace);

		// Create an New Shape, dependents on an Edge or a Node
		if(sset.stencil(shapetype).type() == "node") {
			newShapeObject = new ORYX.Core.Node({'eventHandlerCallback':this.handleEvents.bind(this)}, sset.stencil(shapetype))
		} else {
			newShapeObject = new ORYX.Core.Edge({'eventHandlerCallback':this.handleEvents.bind(this)}, sset.stencil(shapetype))
		}
		
		// when there is a template, inherit the properties.
		if(option.template) {

			newShapeObject._jsonStencil.properties = option.template._jsonStencil.properties;
			newShapeObject.postProcessProperties();
		}

		// Add to the canvas
		if(option.parent && newShapeObject instanceof ORYX.Core.Node) {
			option.parent.add(newShapeObject);
		} else {
			canvas.add(newShapeObject);
		}
		
		
		// Set the position
		var point = option.position ? option.position : {x:100, y:200};
	
		
		var con;
		// If there is create a shape and in the argument there is given an ConnectingType and is instance of an edge
		if(option.connectingType && option.connectedShape && !(newShapeObject instanceof ORYX.Core.Edge)) {

			// there will be create a new Edge
			con = new ORYX.Core.Edge({'eventHandlerCallback':this.handleEvents.bind(this)}, sset.stencil(option.connectingType));
			
			// And both endings dockers will be referenced to the both shapes
			con.dockers.first().setDockedShape(option.connectedShape);
			
			var magnet = option.connectedShape.getDefaultMagnet()
			var cPoint = magnet ? magnet.bounds.center() : option.connectedShape.bounds.midPoint();
			con.dockers.first().setReferencePoint( cPoint );
			con.dockers.last().setDockedShape(newShapeObject);
			con.dockers.last().setReferencePoint(newShapeObject.getDefaultMagnet().bounds.center());		
			
			// The Edge will be added to the canvas and be updated
			canvas.add(con);	
			//con.update();
			
		} 
		
		// Move the new Shape to the position
		if(newShapeObject instanceof ORYX.Core.Edge && option.connectedShape) {

			newShapeObject.dockers.first().setDockedShape(option.connectedShape);
			
			if( option.connectedShape instanceof ORYX.Core.Node ){
				newShapeObject.dockers.first().setReferencePoint(option.connectedShape.getDefaultMagnet().bounds.center());					
				newShapeObject.dockers.last().bounds.centerMoveTo(point);			
			} else {
				newShapeObject.dockers.first().setReferencePoint(option.connectedShape.bounds.midPoint());								
			}

		} else {
			
			var b = newShapeObject.bounds
			if( newShapeObject instanceof ORYX.Core.Node && newShapeObject.dockers.length == 1){
				b = newShapeObject.dockers.first().bounds
			}
			
			b.centerMoveTo(point);
			
			var upL = b.upperLeft();
			b.moveBy( -Math.min(upL.x, 0) , -Math.min(upL.y, 0) )
			
			var lwR = b.lowerRight();
			b.moveBy( -Math.max(lwR.x-canvas.bounds.width(), 0) , -Math.max(lwR.y-canvas.bounds.height(), 0) )
			
		}
		
		// Update the shape
		if (newShapeObject instanceof ORYX.Core.Edge) {
			newShapeObject._update(false);
		}
		
		// And refresh the selection
		if(!(newShapeObject instanceof ORYX.Core.Edge)) {
			this.setSelection([newShapeObject]);
		}
		
		if(con && con.alignDockers) {
			con.alignDockers();
		} 
		if(newShapeObject.alignDockers) {
			newShapeObject.alignDockers();
		}

		return newShapeObject;
	},
	
	deleteShape: function(shape) {
		
		if (!shape || !shape.parent){ return }
		
		//remove shape from parent
		// this also removes it from DOM
		shape.parent.remove(shape);
		
		//delete references to outgoing edges
		shape.getOutgoingShapes().each(function(os) {
			var docker = os.getDockers().first();
			if(docker && docker.getDockedShape() == shape) {
				docker.setDockedShape(undefined);
			}
		});
		
		//delete references to incoming edges
		shape.getIncomingShapes().each(function(is) {
			var docker = is.getDockers().last();
			if(docker && docker.getDockedShape() == shape) {
				docker.setDockedShape(undefined);
			}
		});
		
		//delete references of the shape's dockers
		shape.getDockers().each(function(docker) {
			docker.setDockedShape(undefined);
		});
	},
	
	/**
	 * Returns an object with meta data about the model.
	 * Like name, description, ...
	 * 
	 * Empty object with the current backend.
	 * 
	 * @return {Object} Meta data about the model
	 */
	getModelMetaData: function() {
		return this.modelMetaData;
	},

	/* Event-Handler Methods */
	
	/**
	* Helper method to execute an event immediately. The event is not
	* scheduled in the _eventsQueue. Needed to handle Layout-Callbacks.
	*/
	_executeEventImmediately: function(eventObj) {
		if(this.DOMEventListeners.keys().member(eventObj.event.type)) {
			this.DOMEventListeners[eventObj.event.type].each((function(value) {
				value(eventObj.event, eventObj.arg);		
			}).bind(this));
		}
	},

	_executeEvents: function() {
		this._queueRunning = true;
		while(this._eventsQueue.length > 0) {
			var val = this._eventsQueue.shift();
			this._executeEventImmediately(val);
		}
		this._queueRunning = false;
	},
	
	/**
	 * Leitet die Events an die Editor-Spezifischen Event-Methoden weiter
	 * @param {Object} event Event , welches gefeuert wurde
	 * @param {Object} uiObj Target-UiObj
	 */
	handleEvents: function(event, uiObj) {
		
		ORYX.Log.trace("Dispatching event type %0 on %1", event.type, uiObj);

		switch(event.type) {
			case ORYX.CONFIG.EVENT_MOUSEDOWN:
				this._handleMouseDown(event, uiObj);
				break;
			case ORYX.CONFIG.EVENT_MOUSEMOVE:
				this._handleMouseMove(event, uiObj);
				break;
			case ORYX.CONFIG.EVENT_MOUSEUP:
				this._handleMouseUp(event, uiObj);
				break;
			case ORYX.CONFIG.EVENT_MOUSEOVER:
				this._handleMouseHover(event, uiObj);
				break;
			case ORYX.CONFIG.EVENT_MOUSEOUT:
				this._handleMouseOut(event, uiObj);
				break;
		}
		/* Force execution if necessary. Used while handle Layout-Callbacks. */
		if(event.forceExecution) {
			this._executeEventImmediately({event: event, arg: uiObj});
		} else {
			this._eventsQueue.push({event: event, arg: uiObj});
		}
		
		if(!this._queueRunning) {
			this._executeEvents();
		}
		
		// TODO: Make this return whether no listener returned false.
		// So that, when one considers bubbling undesireable, it won't happen.
		return false;
	},

	catchKeyUpEvents: function(event) {
		if(!this._keyupEnabled) {
			return;
		}
		/* assure we have the current event. */
        if (!event) 
            event = window.event;
        
		// Checks if the event comes from some input field
		if ( ["INPUT", "TEXTAREA"].include(event.target.tagName.toUpperCase()) ){
			return;
		}
		
		/* Create key up event type */
		var keyUpEvent = this.createKeyCombEvent(event,	ORYX.CONFIG.KEY_ACTION_UP);
		
		ORYX.Log.debug("Key Event to handle: %0", keyUpEvent);

		/* forward to dispatching. */
		this.handleEvents({type: keyUpEvent, event:event});
	},
	
	/**
	 * Catches all key down events and forward the appropriated event to 
	 * dispatching concerning to the pressed keys.
	 * 
	 * @param {Event} 
	 * 		The key down event to handle
	 */
	catchKeyDownEvents: function(event) {
		if(!this._keydownEnabled) {
			return;
		}
		/* Assure we have the current event. */
        if (!event) 
            event = window.event;
        
		/* Fixed in FF3 */
		// This is a mac-specific fix. The mozilla event object has no knowledge
		// of meta key modifier on osx, however, it is needed for certain
		// shortcuts. This fix adds the metaKey field to the event object, so
		// that all listeners that registered per Oryx plugin facade profit from
		// this. The original bug is filed in
		// https://bugzilla.mozilla.org/show_bug.cgi?id=418334
		//if (this.__currentKey == ORYX.CONFIG.KEY_CODE_META) {
		//	event.appleMetaKey = true;
		//}
		//this.__currentKey = pressedKey;
		
		// Checks if the event comes from some input field
		if ( ["INPUT", "TEXTAREA"].include(event.target.tagName.toUpperCase()) ){
			return;
		}
		
		/* Create key up event type */
		var keyDownEvent = this.createKeyCombEvent(event, ORYX.CONFIG.KEY_ACTION_DOWN);
		
		ORYX.Log.debug("Key Event to handle: %0", keyDownEvent);
		
		/* Forward to dispatching. */
		this.handleEvents({type: keyDownEvent,event: event});
	},
	
	/**
	 * Creates the event type name concerning to the pressed keys.
	 * 
	 * @param {Event} keyDownEvent
	 * 		The source keyDownEvent to build up the event name
	 */
	createKeyCombEvent: function(keyEvent, keyAction) {

		/* Get the currently pressed key code. */
        var pressedKey = keyEvent.which || keyEvent.keyCode;
		//this.__currentKey = pressedKey;
		
		/* Event name */
		var eventName = "key.event";
		
		/* Key action */
		if(keyAction) {
			eventName += "." + keyAction;
		}
		
		/* Ctrl or apple meta key is pressed */
		if(keyEvent.ctrlKey || keyEvent.metaKey) {
			eventName += "." + ORYX.CONFIG.META_KEY_META_CTRL;
		}
		
		/* Alt key is pressed */
		if(keyEvent.altKey) {
			eventName += "." + ORYX.CONFIG.META_KEY_ALT;
		}
		
		/* Alt key is pressed */
		if(keyEvent.shiftKey) {
			eventName += "." + ORYX.CONFIG.META_KEY_SHIFT;
		}
		
		/* Return the composed event name */
		return  eventName + "." + pressedKey;
	},

	_handleMouseDown: function(event, uiObj) {
		
		// get canvas.
		var canvas = this.getCanvas();
		// Try to get the focus
		canvas.focus();
	
		// find the shape that is responsible for this element's id.
		var element = event.currentTarget;
		var elementController = uiObj;

		// gather information on selection.
		var currentIsSelectable = (elementController !== null) &&
			(elementController !== undefined) && (elementController.isSelectable);
		var currentIsMovable = (elementController !== null) &&
			(elementController !== undefined) && (elementController.isMovable);
		var modifierKeyPressed = event.shiftKey || event.ctrlKey;
		var noObjectsSelected = this.selection.length === 0;
		var currentIsSelected = this.selection.member(elementController);


		// Rule #1: When there is nothing selected, select the clicked object.
		if(currentIsSelectable && noObjectsSelected) {

			this.setSelection([elementController]);

			ORYX.Log.trace("Rule #1 applied for mouse down on %0", element.id);

		// Rule #3: When at least one element is selected, and there is no
		// control key pressed, and the clicked object is not selected, select
		// the clicked object.
		} else if(currentIsSelectable && !noObjectsSelected &&
			!modifierKeyPressed && !currentIsSelected) {

			this.setSelection([elementController]);

			//var objectType = elementController.readAttributes();
			//alert(objectType[0] + ": " + objectType[1]);

			ORYX.Log.trace("Rule #3 applied for mouse down on %0", element.id);

		// Rule #4: When the control key is pressed, and the current object is
		// not selected, add it to the selection.
		} else if(currentIsSelectable && modifierKeyPressed
			&& !currentIsSelected) {
				
			var newSelection = this.selection.clone();
			newSelection.push(elementController)
			this.setSelection(newSelection)

			ORYX.Log.trace("Rule #4 applied for mouse down on %0", element.id);

		// Rule #6
		} else if(currentIsSelectable && currentIsSelected &&
			modifierKeyPressed) {

			var newSelection = this.selection.clone();
			this.setSelection(newSelection.without(elementController))

			ORYX.Log.trace("Rule #6 applied for mouse down on %0", elementController.id);

		// Rule #5: When there is at least one object selected and no control
		// key pressed, we're dragging.
		/*} else if(currentIsSelectable && !noObjectsSelected
			&& !modifierKeyPressed) {

			if(this.log.isTraceEnabled())
				this.log.trace("Rule #5 applied for mouse down on "+element.id);
*/
		// Rule #2: When clicked on something that is neither
		// selectable nor movable, clear the selection, and return.
		} else if (!currentIsSelectable && !currentIsMovable) {
			
			this.setSelection([]);
			
			ORYX.Log.trace("Rule #2 applied for mouse down on %0", element.id);

			return;

		// Rule #7: When the current object is not selectable but movable,
		// it is probably a control. Leave the selection unchanged but set
		// the movedObject to the current one and enable Drag. Dockers will
		// be processed in the dragDocker plugin.
		} else if(!currentIsSelectable && currentIsMovable && !(elementController instanceof ORYX.Core.Controls.Docker)) {
			
			// TODO: If there is any moveable elements, do this in a plugin
			//ORYX.Core.UIEnableDrag(event, elementController);

			ORYX.Log.trace("Rule #7 applied for mouse down on %0", element.id);
		
		// Rule #8: When the element is selectable and is currently selected and no 
		// modifier key is pressed
		} else if(currentIsSelectable && currentIsSelected &&
			!modifierKeyPressed) {
			
			this._subSelection = this._subSelection != elementController ? elementController : undefined;
						
			this.setSelection(this.selection, this._subSelection);
			
			ORYX.Log.trace("Rule #8 applied for mouse down on %0", element.id);
		}
		
		
		// prevent event from bubbling, return.
		//Event.stop(event);
		return;
	},

	_handleMouseMove: function(event, uiObj) {
		return;
	},

	_handleMouseUp: function(event, uiObj) {
		// get canvas.
		var canvas = this.getCanvas();

		// find the shape that is responsible for this elemement's id.
		var elementController = uiObj;

		//get event position
		var evPos = this.eventCoordinates(event);

		//Event.stop(event);
	},

	_handleMouseHover: function(event, uiObj) {
		return;
	},

	_handleMouseOut: function(event, uiObj) {
		return;
	},

	/**
	 * Calculates the event coordinates to SVG document coordinates.
	 * @param {Event} event
	 * @return {SVGPoint} The event coordinates in the SVG document
	 */
	eventCoordinates: function(event) {

		var canvas = this.getCanvas();

		var svgPoint = canvas.node.ownerSVGElement.createSVGPoint();
		svgPoint.x = event.clientX;
		svgPoint.y = event.clientY;
		var matrix = canvas.node.getScreenCTM();
		return svgPoint.matrixTransform(matrix.inverse());
	}
};
ORYX.Editor = Clazz.extend(ORYX.Editor);

/**
 * Creates a new ORYX.Editor instance by fetching a model from given url and passing it to the constructur
 * @param {String} modelUrl The JSON URL of a model.
 * @param {Object} config Editor config passed to the constructur, merged with the response of the request to modelUrl
 */
ORYX.Editor.createByUrl = function(modelUrl, config){
    if(!config) config = {};
    
    new Ajax.Request(modelUrl, {
      method: 'GET',
      onSuccess: function(transport) {
        var editorConfig = Ext.decode(transport.responseText);
        editorConfig = Ext.applyIf(editorConfig, config);
        new ORYX.Editor(editorConfig);
      
        if ("function" == typeof(config.onSuccess)) {
		  	config.onSuccess(transport);
	    }
      }.bind(this),
      onFailure: function(transport) {
    	if ("function" == typeof(config.onFailure)) {
    	  config.onFailure(transport);
    	}
      }.bind(this)
    });
}

// TODO Implement namespace awareness on attribute level.
/**
 * graft() function
 * Originally by Sean M. Burke from interglacial.com, altered for usage with
 * SVG and namespace (xmlns) support. Be sure you understand xmlns before
 * using this funtion, as it creates all grafted elements in the xmlns
 * provided by you and all element's attribures in default xmlns. If you
 * need to graft elements in a certain xmlns and wish to assign attributes
 * in both that and another xmlns, you will need to do stepwise grafting,
 * adding non-default attributes yourself or you'll have to enhance this
 * function. Latter, I would appreciate: martin�apfelfabrik.de
 * @param {Object} namespace The namespace in which
 * 					elements should be grafted.
 * @param {Object} parent The element that should contain the grafted
 * 					structure after the function returned.
 * @param {Object} t the crafting structure.
 * @param {Object} doc the document in which grafting is performed.
 */
ORYX.Editor.graft = function(namespace, parent, t, doc) {

    doc = (doc || (parent && parent.ownerDocument) || document);
    var e;
    if(t === undefined) {
        throw "Can't graft an undefined value";
    } else if(t.constructor == String) {
        e = doc.createTextNode( t );
    } else {
        for(var i = 0; i < t.length; i++) {
            if( i === 0 && t[i].constructor == String ) {
                var snared;
                snared = t[i].match( /^([a-z][a-z0-9]*)\.([^\s\.]+)$/i );
                if( snared ) {
                    e = doc.createElementNS(namespace, snared[1] );
                    e.setAttributeNS(null, 'class', snared[2] );
                    continue;
                }
                snared = t[i].match( /^([a-z][a-z0-9]*)$/i );
                if( snared ) {
                    e = doc.createElementNS(namespace, snared[1] );  // but no class
                    continue;
                }

                // Otherwise:
                e = doc.createElementNS(namespace, "span" );
                e.setAttribute(null, "class", "namelessFromLOL" );
            }

            if( t[i] === undefined ) {
                throw "Can't graft an undefined value in a list!";
            } else if( t[i].constructor == String || t[i].constructor == Array ) {
                this.graft(namespace, e, t[i], doc );
            } else if(  t[i].constructor == Number ) {
                this.graft(namespace, e, t[i].toString(), doc );
            } else if(  t[i].constructor == Object ) {
                // hash's properties => element's attributes
                for(var k in t[i]) { e.setAttributeNS(null, k, t[i][k] ); }
            } else {

			}
        }
    }
	if(parent) {
	    parent.appendChild( e );
	} else {

	}
    return e; // return the topmost created node
};

ORYX.Editor.provideId = function() {
	var res = [], hex = '0123456789ABCDEF';

	for (var i = 0; i < 36; i++) res[i] = Math.floor(Math.random()*0x10);

	res[14] = 4;
	res[19] = (res[19] & 0x3) | 0x8;

	for (var i = 0; i < 36; i++) res[i] = hex[res[i]];

	res[8] = res[13] = res[18] = res[23] = '-';

	return "oryx_" + res.join('');
};

/**
 * When working with Ext, conditionally the window needs to be resized. To do
 * so, use this class method. Resize is deferred until 100ms, and all subsequent
 * resizeBugFix calls are ignored until the initially requested resize is
 * performed.
 */
ORYX.Editor.resizeFix = function() {
	if (!ORYX.Editor._resizeFixTimeout) {
		ORYX.Editor._resizeFixTimeout = window.setTimeout(function() {
			window.resizeBy(1,1);
			window.resizeBy(-1,-1);
			ORYX.Editor._resizefixTimeout = null;
		}, 100); 
	}
};

ORYX.Editor.Cookie = {
	
	callbacks:[],
		
	onChange: function( callback, interval ){
	
		this.callbacks.push(callback);
		this.start( interval )
	
	},
	
	start: function( interval ){
		
		if( this.pe ){
			return;
		}
		
		var currentString = document.cookie;
		
		this.pe = new PeriodicalExecuter( function(){
			
			if( currentString != document.cookie ){
				currentString = document.cookie;
				this.callbacks.each(function(callback){ callback(this.getParams()) }.bind(this));
			}
			
		}.bind(this), ( interval || 10000 ) / 1000);	
	},
	
	stop: function(){

		if( this.pe ){
			this.pe.stop();
			this.pe = null;
		}
	},
		
	getParams: function(){
		var res = {};
		
		var p = document.cookie;
		p.split("; ").each(function(param){ res[param.split("=")[0]] = param.split("=")[1];});
		
		return res;
	},	
	
	toString: function(){
		return document.cookie;
	}
};

/**
 * Workaround for SAFARI/Webkit, because
 * when trying to check SVGSVGElement of instanceof there is 
 * raising an error
 * 
 */
ORYX.Editor.SVGClassElementsAreAvailable = true;
ORYX.Editor.setMissingClasses = function() {
	
	try {
		SVGElement;
	} catch(e) {
		ORYX.Editor.SVGClassElementsAreAvailable = false;
		SVGSVGElement 		= document.createElementNS('http://www.w3.org/2000/svg', 'svg').toString();
		SVGGElement 		= document.createElementNS('http://www.w3.org/2000/svg', 'g').toString();
		SVGPathElement 		= document.createElementNS('http://www.w3.org/2000/svg', 'path').toString();
		SVGTextElement 		= document.createElementNS('http://www.w3.org/2000/svg', 'text').toString();
		//SVGMarkerElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'marker').toString();
		SVGRectElement 		= document.createElementNS('http://www.w3.org/2000/svg', 'rect').toString();
		SVGImageElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'image').toString();
		SVGCircleElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'circle').toString();
		SVGEllipseElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'ellipse').toString();
		SVGLineElement	 	= document.createElementNS('http://www.w3.org/2000/svg', 'line').toString();
		SVGPolylineElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'polyline').toString();
		SVGPolygonElement 	= document.createElementNS('http://www.w3.org/2000/svg', 'polygon').toString();
		
	}
	
};
ORYX.Editor.checkClassType = function( classInst, classType ) {
	
	if( ORYX.Editor.SVGClassElementsAreAvailable ){
		return classInst instanceof classType
	} else {
		return classInst == classType
	}
};
ORYX.Editor.makeExtModalWindowKeysave = function(facade) {
	Ext.override(Ext.Window,{
		beforeShow : function(){
			delete this.el.lastXY;
			delete this.el.lastLT;
			if(this.x === undefined || this.y === undefined){
				var xy = this.el.getAlignToXY(this.container, 'c-c');
				var pos = this.el.translatePoints(xy[0], xy[1]);
				this.x = this.x === undefined? pos.left : this.x;
				this.y = this.y === undefined? pos.top : this.y;
			}
			this.el.setLeftTop(this.x, this.y);
	
			if(this.expandOnShow){
				this.expand(false);
			}
	
			if(this.modal){
				facade.disableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
				Ext.getBody().addClass("x-body-masked");
				this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
				this.mask.show();
			}
		},
		afterHide : function(){
	        this.proxy.hide();
	        if(this.monitorResize || this.modal || this.constrain || this.constrainHeader){
	            Ext.EventManager.removeResizeListener(this.onWindowResize, this);
	        }
	        if(this.modal){
	            this.mask.hide();
	            facade.enableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
	            Ext.getBody().removeClass("x-body-masked");
	        }
	        if(this.keyMap){
	            this.keyMap.disable();
	        }
	        this.fireEvent("hide", this);
	    },
	    beforeDestroy : function(){
	    	if(this.modal)
	    		facade.enableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
	        Ext.destroy(
	            this.resizer,
	            this.dd,
	            this.proxy,
	            this.mask
	        );
	        Ext.Window.superclass.beforeDestroy.call(this);
	    }
	});
}/**
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


ORYX.Core.UIEnableDrag = function(event, uiObj, option) {

	this.uiObj = uiObj;
	var upL = uiObj.bounds.upperLeft();

	var a = uiObj.node.getScreenCTM();
	this.faktorXY= {x: a.a, y: a.d};
	
	this.scrollNode = uiObj.node.ownerSVGElement.parentNode.parentNode;
	
	this.offSetPosition =  {
		x: Event.pointerX(event) - (upL.x * this.faktorXY.x),
		y: Event.pointerY(event) - (upL.y * this.faktorXY.y)};

	this.offsetScroll	= {x:this.scrollNode.scrollLeft,y:this.scrollNode.scrollTop};
		
	this.dragCallback = ORYX.Core.UIDragCallback.bind(this);
	this.disableCallback = ORYX.Core.UIDisableDrag.bind(this);

	this.movedCallback = option ? option.movedCallback : undefined;
	this.upCallback = option ? option.upCallback : undefined;
	
	document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.disableCallback, true);
	document.documentElement.addEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, 	this.dragCallback , false);

};

ORYX.Core.UIDragCallback = function(event) {

	var position = {
		x: Event.pointerX(event) - this.offSetPosition.x,
		y: Event.pointerY(event) - this.offSetPosition.y}

	position.x 	-= this.offsetScroll.x - this.scrollNode.scrollLeft; 
	position.y 	-= this.offsetScroll.y - this.scrollNode.scrollTop;

	position.x /= this.faktorXY.x;
	position.y /= this.faktorXY.y;

	this.uiObj.bounds.moveTo(position);
	//this.uiObj.update();

	if(this.movedCallback)
		this.movedCallback(event);
	
	Event.stop(event);

};

ORYX.Core.UIDisableDrag = function(event) {
	document.documentElement.removeEventListener(ORYX.CONFIG.EVENT_MOUSEMOVE, this.dragCallback, false);
	document.documentElement.removeEventListener(ORYX.CONFIG.EVENT_MOUSEUP, this.disableCallback, true);
	
	if(this.upCallback)
		this.upCallback(event);
		
	this.upCallback = undefined;
	this.movedCallback = undefined;		
	
	Event.stop(event);	
};