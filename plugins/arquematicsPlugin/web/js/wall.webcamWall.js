/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 */
(function($){
 $.fn.webcamWall = function(settings) 
 {
    var defaults = {
        width: 300,
        height: 240
    };
  
    var options = $.extend(defaults, settings);
 
    var pos = 0;
    var ctx = null;
    var saveCB;
    var image = [];
    var canvas = document.createElement("canvas");
  
  function configureVideoCanvas()
  {
     canvas.setAttribute('width', options.width);
     canvas.setAttribute('height', options.height);
     if (canvas.toDataURL) 
     {
        ctx = canvas.getContext("2d");
                
        image = ctx.getImageData(0, 0, options.width, options.height);
        
        saveCB = function(data) {
                        //console.log('entra');
                        var col = data.split(";");
                        var img = image;

                        for(var i = 0; i < options.width; i++) {
                                var tmp = parseInt(col[i]);
                                img.data[pos + 0] = (tmp >> 16) & 0xff;
                                img.data[pos + 1] = (tmp >> 8) & 0xff;
                                img.data[pos + 2] = tmp & 0xff;
                                img.data[pos + 3] = 0xff;
                                pos+= 4;
                        }

                        if (pos >= 4 * options.width * options.height) {
                                ctx.putImageData(img, 0, 0);
                                
                                $.post("/wall/message/camera",
                                        {type: "data",
                                         datatype: "json",
                                         image: canvas.toDataURL("image/png")},
                                         function(dataJson)
                                         {
                                            if(dataJson.status == 200)
                                            {
                                                $("#webcam-preview").prepend(dataJson.HTML);
                                            }
                                            else{}
                                             // activa el boton
                                            $('#webcam-button').button('reset');
                                            return false;
                                         }
                                       );
                        
                                
                                pos = 0;
                        }
                };

        } else {
                saveCB = function(data) {
                        //console.log('entra');
                        image.push(data);
                        pos+= 4 * options.width;
                        if (pos >= 4 * options.width * options.height) {
                          $.post("/wall/message/camera",
                                    {type: "pixel",
                                    image: image.join('|')},
                                    function(dataJson)
                                    {
                                        if(dataJson.status == 200){
                                            $("#webcam-preview").prepend(dataJson.HTML);
                                        }
                                        else
                                        {
                                        
                                        }
                                        // activa el boton
                                        $('#webcam-button').button('reset');
                                        return false;
                                    }
                            );
                          
                          pos = 0;
                        }
                };
        }

  }
  //configura la camara
  configureVideoCanvas();
  
  //captura de la webcamp
  $("#webcam-button").click(function() 
  {
    //desactiva el botton
    $('#webcam-button').button('loading');
    
    try
    {
      webcam.capture();
    }
    catch(err)
    {
       // activa el boton
       $('#webcam-button').button('reset');
       if (typeof console == "object")
       {
        console.log("webcam.capture error");
        console.log(err.description); 
       }
       
    }
    return false;
   });
   
   //extiende el plugin
   return this.each(function()
   {
    //carga el plugin
    $(this).webcam({
       width: options.width,
       height: options.height,
       mode: "callback",
       swffile: "/arquematicsPlugin/js/jscam_canvas_only.swf",
       onSave: saveCB,
       onCapture: function () 
       {
                webcam.save();
       },
       debug: function (type, string)
       {
                $("#webcam_status").html(type + ": " + string);
       }
    });
  
   });
   
 };
 
})(jQuery);

