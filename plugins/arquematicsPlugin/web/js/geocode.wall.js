/**
 *
 * load Google Maps
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 */

(function($){
 
  $.fn.wallGeocode = function(settings)
  {
      
   var defaults = {
        time: 500
   };
  
   var options = $.extend(defaults, settings);
   
   var hasLoadScripts = false;

   function loadGoogleMaps()
   {
        if ((typeof google === "undefined") && (!hasLoadScripts))
        {
            $.getScript('http://maps.google.com/maps/api/js?sensor=true').done(function() 
            {
                $.getScript('/arquematicsPlugin/js/jquery.ui.map.js').done(function() 
                {   
                    hasLoadScripts = true;
                    $('body').trigger("wallGeocodeLoad"); 
                    
                }).fail(function(jqxhr, settings, exception) {
                //alert(exception); // restaura el metodo  
                });  
            
            }).fail(function() {
            //alert('failed to load google maps');
            });
        }
    }
    
    loadGoogleMaps();
  };
 
    
})(jQuery);