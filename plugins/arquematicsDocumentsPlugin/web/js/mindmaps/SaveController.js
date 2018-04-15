/**
 * <pre>
 * Escucha el SAVE_AR_COMMAND guarda imagen y diagrama en el servidor
 * </pre>
 * 
 * @constructor
 * @param {mindmaps.EventBus} eventBus
 * @param {mindmaps.commandRegistry} commandRegistry
 * @param {mindmaps.mindmapModel} mindmapModel
 */
mindmaps.SaveController = function(eventBus, commandRegistry, mindmapModel) {

  var isSaving = false;
   
  /*
   *
  var SECONDS_BETWEEN_FRAMES;
  var cIndex    = 0;
  var cXpos     = 0;
  
  function startAnimation()
  {		
      
        $('.ui-icon-disk').css('background-image', 'url("' + mindmaps.WAIT_ICON + '")');
	$('.ui-icon-disk').width(24);
        $('.ui-icon-disk').height(24);
        
        $('#button-SAVE_ARQUEMATICS_COMMAND span.ui-button-text').css('color','#FFF');
        
        var FPS = Math.round(100/9);
         
	SECONDS_BETWEEN_FRAMES = 1 / FPS;
	
        setInterval(function() {
          
           //24 pixels por frame
            cXpos += 24;
		
            cIndex += 1;
            //total frames 18
            if (cIndex >= 18) 
            {
                cXpos =0;
		cIndex=0;
            }
				
            $('.ui-icon-disk').css('backgroundPosition', - cXpos +'px 0');
            
        }, SECONDS_BETWEEN_FRAMES*1000); 	
         
   }*/
  
  /**
   * Prepara la notificacion.
   */
  function setupSaveButton() 
  {
    
    var command = commandRegistry.get(mindmaps.SaveArCommand);
    command.setHandler(goSave);

    function goSave(dataImage,dataJson ) 
    {
       if (!isSaving)
       {
            isSaving = true;
           
            try
            {
                    //startAnimation();
                    
                    dataImage = window.atob(dataImage.replace('data:image/png;base64,',''));
                    
                    var $form = $('#form-diagram'), 
                        callBack = function (formData) {
                    
                        $.ajax({
                            type: (mindmaps.autoload)?"PUT":"POST",
                            url:  $form.attr('action'),
                            datatype: "json",
                            contentType: "application/x-www-form-urlencoded",
                            data: formData,
                            cache: false,
                            success: function(dataJSON)
                            {
                               window.location = $('#takeBack').attr('href'); 
                            },
                            error: function() 
                            {
                                eventBus.publish(mindmaps.Event.DOCUMENT_SAVED, doc);
                                window.location = $('#takeBack').attr('href');
                            }
                        });
                     };
                  
                   var titleText =  $.trim($('#note_title').val());
                  
                    if (arquematics.crypt)
                    {
                      
                          var pass = (!mindmaps.PASS)?arquematics.utils.randomKeyString(50):mindmaps.PASS
                          , data = {
                                        //en el editor nunca se comparte
                                        "note[share]"            :0,
                                        "note[trash]"            :$('#note_trash').val(),
                                        "note[is_favorite]"      :$('#note_is_favorite').val(),
                                        "note[pass]"             :pass,
                                        "note[title]"            :arquematics.simpleCrypt.encryptHex(pass ,titleText),
                                        "note[_csrf_token]"      :$('#note__csrf_token').val(),
                                        "note[type]"             :mindmaps.DIAGRAM_TYPE,
                                        "note[data_image]"       :arquematics.simpleCrypt.encryptHex(pass , dataImage),
                                        "note[content]"          :arquematics.simpleCrypt.encryptHex(pass , dataJson)
                                    };
                                    
                           arquematics.utils.encryptDataAndSend(data, callBack, 'note[pass]');
                       
                    }
                    else
                    {
                          $('#note_data_image').val(dataImage);
                          $('#note_content').val(dataJson);
                          $('#note_type').val(mindmaps.DIAGRAM_TYPE);
                          
                          arquematics.utils.prepareFormAndSend($form, callBack);  
                    }
        }
        catch (Err)
        {
            window.location = $('#takeBack').attr('href');
        }      
      }
    }
    //super hack feo
    $( ".saveBtn" ).on( "click", function(e) {
       e.preventDefault();
       
       var $cmdBtn = $(e.currentTarget)
       ,$cmdBtnSaveText = $cmdBtn.find('.save-btn-text');
       
       var titleText =  $.trim($('#note_title').val())
         , $controlGroup = $('#note_title').parents('.input-group');
       
        var renderer = new mindmaps.StaticCanvasRenderer();
            var doc = mindmapModel.getDocument();
       
        var dataImage = renderer.renderAsDataPNG(doc);
        var dataJson = doc.prepareSave().serialize();
        var dataJsonEval = JSON.parse(dataJson);
       
      
       if ((titleText.length === 0)
         && (dataJsonEval.mindmap.root.children.length === 0))
       {
           $cmdBtn.addClass('disabled');
           $cmdBtnSaveText.text($cmdBtnSaveText.data('text-saving'));
           //no tiene nada que salvar
           window.location = $('#takeBack').attr('href');      
       }
       else if ((titleText.length > 0) 
           && (dataJsonEval.mindmap.root.children.length > 0))
       {
           $cmdBtn.addClass('disabled');
           $controlGroup.removeClass('has-error');
           $cmdBtnSaveText.text($cmdBtnSaveText.data('text-saving'));

           goSave(dataImage,dataJson);                  
       }
       else
       {
           $cmdBtnSaveText.text($cmdBtnSaveText.data('text'));
           $cmdBtn.removeClass('disabled');
           $controlGroup.addClass('has-error');
           $('#note_title').focus();
       }
        
    });
    
  }

  setupSaveButton();
};
