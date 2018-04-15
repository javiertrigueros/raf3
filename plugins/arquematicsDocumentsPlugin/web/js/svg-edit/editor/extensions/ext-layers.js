/*globals svgEditor, svgCanvas, $*/
/*jslint vars: true, eqeq: true*/
/*
 * ext-helloworld.js
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Alexis Deveria
 *
 */
 
/* 
	This is a very basic SVG-Edit extension. It adds a "Hello World" button in
	the left panel. Clicking on the button, and then the canvas will show the
	user the point on the canvas that was clicked on.
*/
 
svgEditor.addExtension("Layers Ext", function() {'use strict';

           $(document).ready(function() {
         
                $('#layers_ext').sidr({
                    closeExtra: '.close-layers',
                    side: 'left',         // Accepts 'left' or 'right'
                    name: 'sidr-menu-layers',
                    body: 'body',
                    renaming: false,
                    absIds: '#main-navbar, #tools_bottom, #ruler_corner,#ruler_x, #ruler_y,  #workarea',
                    source: '#sidepanels'
                }); 
            });
  
		return {
			name: "Layers Ext",
			// For more notes on how to make an icon file, see the source of
			// the hellorworld-icon.xml
			svgicons: svgEditor.curConfig.extPath + "layers-icon.xml",
			
			// Multiple buttons can be added in this array
			buttons: [{
				// Must match the icon ID in helloworld-icon.xml
				id: "layers_ext", 
				
				// This indicates that the button will be added to the "mode"
				// button panel on the left side
				type: "mode", 
				
				// Tooltip text
				title: "Layers", 
				
				// Events
				events: {
					'click': function() {
                        $('#tool_select').click();
					}
				}
			}],
			// This is triggered when the main mouse button is pressed down 
			// on the editor canvas (not the tool panels)
			mouseDown: function() {
				// Check the mode on mousedown
				if(svgCanvas.getMode() === "layers_ext") {
				
					// The returned object must include "started" with 
					// a value of true in order for mouseUp to be triggered
					return {started: true};
				}
			},
			
			// This is triggered from anywhere, but "started" must have been set
			// to true (see above). Note that "opts" is an object with event info
			mouseUp: function(opts) {
				// Check the mode on mouseup
				if(svgCanvas.getMode() === "layers_ext") {
					var zoom = svgCanvas.getZoom();
					
					// Get the actual coordinate by dividing by the zoom value
					var x = opts.mouse_x / zoom;
					var y = opts.mouse_y / zoom;
					
					var text = "Esto va bien: " 
						+ x + ", " + y;
						
					// Show the text using the custom alert function
					$.alert(text);
				}
			}
		};
});

