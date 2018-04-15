
(function($j, $, arquematics, ORYX,  document, window) {
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
    isSaving: false,
    
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
        function _utf8_encode (string) {
            string = string.replace(/\r\n/g,"\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                }
                else if((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
                else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            }

            return utftext;
        }
        
        function encode (input) {
            try {
                return btoa(input);
            }
            catch (e) {

                var output = "";
                var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
                var i = 0;

            input = _utf8_encode(input);

            while (i < input.length) {

                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

            }

                return output;
            }
        }
        
        function imageSVGtoPNG (data)
        {
            var d = $j.Deferred()
            , image = new Image();
            
            image.onload = function() {
                var canvas = document.createElement('canvas')
                , ctx
                , data;
                canvas.width = this.width;
                canvas.height = this.height ;
                ctx = canvas.getContext("2d");
                ctx.drawImage(image, 0, 0);
                
                d.resolve(canvas.toDataURL("image/png"));
            };
            
            image.src = data;
            
            return d;
        }
	// Reset changes
	this.changeDifference = 0;
	// Get the serialized svg image source
        var that = this
        , svgClone 	= facade.getCanvas().getSVGRepresentation(true)
        // , s = new XMLSerializer()
        //, serializeSvgData =  new XMLSerializer().serializeToString(svgClone),
        //, serializeSvgData = s.serializeToString(svgClone)
        , serializeSvgData = DataManager.serialize(svgClone)
        , imageData = 'data:image/svg+xml;charset=utf-8,' + arquematics.codec.encodeURIData(serializeSvgData);
        this.serializedDOM = Ext.encode(facade.getJSON());
		
	// Check if this is the NEW URL
       	var data = this.serializedDOM;
        
        $j.when(imageSVGtoPNG(imageData))
        .done(function (imageDataUrl){
            // Send the request out
            that.sendSaveRequest( 
                            ORYX.CONFIG.SAVE, 
                            {'json': data,
                             'image': imageDataUrl
                            },
                            data);
        });	
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
             
                       var $form = $j('#form-diagram')
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
                           
                          $j('#note_data_image').val(params.image);
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

}(jQuery, $, arquematics, ORYX,  document, window));


