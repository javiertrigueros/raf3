/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * depende de https://github.com/HPNeo/gmaps
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 */
(function ($, arquematics, GMaps, google) {
    
arquematics.geomap = {
    key: '',
    default_map_height: 300,
    
    _calculateGoogleZoom: function (east, west, pixelWidth)
    {
             var GLOBE_WIDTH = 256;
             var angle = east - west;
             if (angle < 0) {angle += 360;}
             angle = (angle < 0)?angle += 360:angle;
             var ret = Math.round(Math.log(pixelWidth * 360 / angle / GLOBE_WIDTH) / Math.LN2);
             return (ret >= 2)? ret - 2:ret;
    },
    
    renderStaticMap: function ($node, callback, attempt)
    {
              var that = this,
                 attempt = attempt || 0,
                 text = $node.data('content'),
                 $mapStatic = $node.find('.map-static');
         

              $node.height(arquematics.geomap.default_map_height / 2 + 30);       
              $mapStatic.height(arquematics.geomap.default_map_height / 2 + 20);       

             GMaps.geocode({
                    address:  text,
                    callback: function(results, status)
                    {
                        if(status === 'OK')
                        {
                            
                            var latlng = results[0].geometry.location;
                            var view =   results[0].geometry.viewport;
                            var containerWidth = $mapStatic.width();
                            
                            var url = GMaps.staticMapURL({
                                key: arquematics.geomap.key,
                                size: [containerWidth - Math.floor(containerWidth * 0.01), arquematics.geomap.default_map_height / 2],
                                lat: latlng.lat(),
                                lng: latlng.lng(),
                                zoom: that._calculateGoogleZoom(
                                                    view.getNorthEast().lng(),
                                                    view.getSouthWest().lng(),
                                                    containerWidth),
                                markers: [
                                    {lat: latlng.lat(),
                                     lng: latlng.lng(),
                                     color: 'blue'}]
                            });
                            //lo de with 100% y height auto es un 
                            //truco para cuando cambia de ancho el navegador o se de da al menu de contraer
                            var tmpString =   '<div class="link-locate-static">'
                                            +   '<div class="link-locate-image">'
                                            +       '<img src="${static_image_url}" width="100%" height="auto" />'
                                            +   '</div>'
                                            +   '<div  class="link-text">${formated_address}</div>'
                                            + '</div>';
                                    
                            var data = {formated_address: text,
                                             static_image_url: url,
                                             width: containerWidth - Math.floor(containerWidth * 0.01),
                                             height: arquematics.geomap.default_map_height / 2};
                            
                            var $wallMapLocateStaticNode = $.tmpl(tmpString,data);


                                         
                            $mapStatic.append($wallMapLocateStaticNode);
                            
                            //mapa dinamico cuando hacemos click
                            $wallMapLocateStaticNode.click( function (e) 
                            {
                                e.preventDefault();
                                
                                $node.find('.map-static').hide();
                                
                                arquematics.geomap.renderActiveMap($node);
                                
                            });
                            
                            if(typeof callback === 'function') {
                                callback.call(data);
                            }
                        }
                          
                        //hace 10 intentos
                        else if (attempt < 10)
                        {
                           setTimeout(function() {
                              arquematics.geomap.renderStaticMap($node, callback, attempt + 1);
                           }, 2000);       
                        }
                        //hace otros 10 dando más tiempo
                        else if (attempt < 20)
                        {
                           setTimeout(function() {
                              arquematics.geomap.renderStaticMap($node, callback, attempt + 1);
                           }, 5000);       
                        }
                    }
                 });
    },
    
     geolocateControl: function ($control, $node, showDinamic, callback)
     {
        //options por defecto
        showDinamic = showDinamic || true;
        callback = callback || false;
        
         
        var geocoder = new google.maps.Geocoder();
            
            $control.autocomplete({
                    //This bit uses the geocoder to fetch address values
                    source: function(request, response) {
                        geocoder.geocode( {'address': request.term }, function(results, status) {
                            response($.map(results, function(item) {
                                return {
                                    label:  item.formatted_address,
                                    value: item.formatted_address,
                                    latitude: item.geometry.location.lat(),
                                    longitude: item.geometry.location.lng(),
                                    view: (typeof(item.geometry.viewport) === "undefined")?  false: item.geometry.viewport
                                };
                            }));
                        });
                    },
                    //This bit is executed upon selection of an address
                    select: function(event, ui) 
                    {
                        $node.data('content',ui.item.value);
                        
                        if (showDinamic)
                        {
                          arquematics.geomap.renderActiveMap($node, callback);      
                        }
                        else
                        {
                          arquematics.geomap.renderStaticMap($node, callback);      
                        }
                        
                    }
           });  
     },
    
     renderActiveMap: function ($node, callback, attempt)
     {
            var that = this,
                attempt = attempt || 0,
                text = $node.data('content'),
                $mapDinamic = $node.find('.map-dinamic');
                

             $node.height(arquematics.geomap.default_map_height + 30);       
             $mapDinamic.height(arquematics.geomap.default_map_height);       
            
            
            var divItemDOM = $mapDinamic.get(0);
            
            GMaps.geocode({
                    address: text,
                    callback: function(results, status)
                    {
                        if(status === 'OK')
                        {
                            var latlng = results[0].geometry.location;
                            var mapControl = new GMaps({
                                                streetViewControl: true,
                                                mapType: 'Roadmap',
                                                mapTypeControl: false,
                                                div: divItemDOM,
                                                lat: latlng.lat(),
                                                lng: latlng.lng()
                            });
                            mapControl.addMarker({
                                lat: latlng.lat(),
                                lng: latlng.lng(),
                                title: text,
                                infoWindow: {
                                    content: text
                                }
                            });
                            
                            if (typeof(results[0].geometry.viewport) !== "undefined")
                            {
                                var view = results[0].geometry.viewport;
                                var bounds = new google.maps.LatLngBounds(
                                    new google.maps.LatLng(
                                            view.getSouthWest().lat(),
                                            view.getSouthWest().lng()),
                                    new google.maps.LatLng(
                                            view.getNorthEast().lat(),
                                            view.getNorthEast().lng()));
                                            
                                mapControl.fitBounds(bounds);
                             }
                             
                             if(typeof callback === 'function') {
                                callback.call(results[0]);
                             }
                        }
                        //hace 10 intentos
                        else if (attempt < 10)
                        {
                           setTimeout(function() {
                              arquematics.geomap.renderActiveMap($node, callback, attempt + 1);
                           }, 2000);       
                        }
                        //hace otros 10 dando más tiempo
                        else if (attempt < 20)
                        {
                           setTimeout(function() {
                              arquematics.geomap.renderActiveMap($node, callback, attempt + 1);
                           }, 5000);       
                        }
                    }
                }); 
         }
    
};
}(jQuery, arquematics, GMaps, google));