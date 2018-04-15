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
 
svgEditor.addExtension("Import", function() {
                'use strict';

		return {
			name: "Import",
			// For more notes on how to make an icon file, see the source of
			// the hellorworld-icon.xml
			svgicons: svgEditor.curConfig.extPath + "import-icon.xml",
                        //svgicons: false,
			
			// Multiple buttons can be added in this array
			buttons: [{
				// Must match the icon ID in helloworld-icon.xml
				id: "file_import", 
				
				// This indicates that the button will be added to the "mode"
				// button panel on the left side
				type: "app_menu",
                                position: 0,
                                icon: '<i class="icon-folder-open-empty"></i>',
				
				// Tooltip text
				title: "Import File", 
				
				// Events
				events: {
					'click': function() {
						// The action taken when the button is clicked on.
						// For "mode" buttons, any other button will 
						// automatically be de-pressed.
						
                                                
                                                var $form = $('<form>'),
                                                    $inputFile = $('<input type="file" name="file">')
                                                                    .appendTo($form),
                                                    file = null,
                                                    reader = new FileReader(),
                                                    image = new Image();
                                            
                                                    image.onload = function(evt) {
                                                        /*
                                                        var width = this.width;
                                                        var height = this.height;*/
                                                        var $img = $(this);
                                                        $img.hide();
                                                        $img.addClass('import-img');
                                                        $img.attr('width', this.width);
                                                        $img.attr('height', this.height);
                                                        
                                                        $('body').prepend($img);
                                                        
                                                        svgCanvas.setMode('image');
                                                        
                                                        $('#right-panel').show();
                                                        
                                                        var $imgToAdd = $img.clone();
                                                        $imgToAdd.show();
                                                        $imgToAdd.attr('width', '100%');
                                                        $imgToAdd.attr('height', 'auto');
                                                        
                                                        $('#image-import').empty();
                                                        $('#image-import').append($imgToAdd);
                                                        
                                                    };
                                            
                                                    reader.onload = function(e){
                                                       /*
                                                       // 'image/svg+xml'
                                                       if (file.type === 'image/svg+xml')
                                                       {
                                                           svgCanvas.importSvgString(e.target.result);    
                                                       }
                                                       else
                                                       {
                                                                
                                                       }*/
                                                       
                                                       image.src = e.target.result;
                                                    };
                                                            
                                                    $inputFile.on('change',  function(e){
                                                        //solo un archivo
                                                        if(e.target.files.length!== 1){
                                                            alert('Please select a file to encrypt!');
                                                            return false;
                                                        }

                                                        file = e.target.files[0];
                                                        //(1024 * 1024)
                                                        if(file.size > 1048576 ){
                                                            alert('Please choose files smaller than 1mb, otherwise you may crash your browser. \nThis is a known issue. See the tutorial.');
                                                            return;
                                                        }
                                                        
                                                        reader.readAsDataURL(file);
                                                       
                                                    });
                                                    
                                                    $inputFile.click();
                                                
					}
				}
			}],
			// This is triggered when the main mouse button is pressed down 
			// on the editor canvas (not the tool panels)
			mouseDown: function() {
				// Check the mode on mousedown
				if(svgCanvas.getMode() === "image") {
                                        
                              
                                        //$('.import-img').remove();
					// The returned object must include "started" with 
					// a value of true in order for mouseUp to be triggered
					return {started: true};
				}
			},
			
			// This is triggered from anywhere, but "started" must have been set
			// to true (see above). Note that "opts" is an object with event info
			mouseUp: function(opts) {
				// Check the mode on mouseup
				if(svgCanvas.getMode() === "image") {
                                    svgCanvas.setMode('select');
				}
			}
		};
});

