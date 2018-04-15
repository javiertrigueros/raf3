/**
 * 
 * @constructor
 */
mindmaps.ExportMapView = function() {
  var self = this;

  // create dialog
  var $dialog = $("#template-export-map").tmpl().dialog({
    autoOpen : false,
    modal : true,
    zIndex : 5000,
    width : "auto",
    height : "auto",
    close : function() {
      $(this).dialog("destroy");
      $(this).remove();
    },
    open : function() {
      $(this).css({
        "max-width" : $(window).width() * 0.9,
        "max-height" : $(window).height() * 0.8
      });
      $dialog.dialog("option", "position", "center");
    },
    buttons : {
      "Ok" : function() {
        $(this).dialog("close");
      }
    }
  });

  /**
   * Shows the dialog.
   * 
   */
  this.showDialog = function() {
    $dialog.dialog("open");
  };

  /**
   * Hides the dialog.
   */
  this.hideDialog = function() {
    $dialog.dialog("close");
  };

  this.setImage = function($img) {
    $("#export-preview").html($img);
  };
};

/**
 * 
 * @constructor
 * @param {mindmaps.EventBus} eventBus
 * @param {mindmaps.MindMapModel} mindmapModel
 * @param {mindmaps.ExportMapView} view
 */
mindmaps.ExportMapPresenter = function(eventBus, mindmapModel, view) {
  var renderer = new mindmaps.StaticCanvasRenderer();

  this.go = function() {
    var $img = renderer.renderAsPNG(mindmapModel.getDocument());
    view.setImage($img);

    // slightly delay showing the dialog. otherwise dialog is not correctly
    // centered, because the image is inserted too late
    setTimeout(function() {

        var doc = mindmapModel.getDocument();
        var data = renderer.renderAsDataPNG(doc);
        var dataJson = doc.prepareSave().serialize();
        $('#diagram_vector_image').val(data);
        $('#diagram_json').val(dataJson);
    
        $('#form_diagram').submit();
        //alert(data);
        //alert(dataJson);
        
        /*
         var dataString = 'diagram[json]='+ dataJson +
                          '&diagram[vector_image]='+ data +
                          '&diagram[_csrf_token]=' + $('#diagram__csrf_token').val();
                                            
                            
          $.ajax({
                type: "POST",
                url: $("#form_diagram").attr('action'),
                cache: false,
                data: dataString,
                dataType: "json",
                success: function(dataJSON) 
                {
                    if (dataJSON.status == 200)
                    {
                        
                            
                    }
                                 
                                    
                },
                error: function() 
                {
                                
                }
             });
        
        */
    }, 30);
  };
};
