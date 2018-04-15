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