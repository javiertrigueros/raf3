;(function($) {

    // here it goes!
    $.fn.gmapsGeoLocate = function(method) {

        // plugin's default options
        var defaults = {

            foo: 'bar'

        }

        var settings = {}

        //GEOCODER
        var geocoder = new google.maps.Geocoder();

        // public methods

        var methods = {
            //inicio
            init : function(options) {

                // iterate through all the DOM elements we are
                // attaching the plugin to
                return this.each(function() {

                  settings = $.extend({}, defaults, options)

                  // "element" principal
                  var element = $(this);

                    // code goes here
                  $(this).autocomplete({
                        //This bit uses the geocoder to fetch address values
                    source: function(request, response) {
                        geocoder.geocode( {'address': request.term }, function(results, status) {
                            response($.map(results, function(item) {
                                return {
                                    label:  item.formatted_address,
                                    value: item.formatted_address,
                                    latitude: item.geometry.location.lat(),
                                    longitude: item.geometry.location.lng()
                                }
                            }));
                        })
                    },
                    //This bit is executed upon selection of an address
                    select: function(event, ui) {
                        $("#" & settings.longitude_id).val(ui.item.latitude);
                        $("#" & settings.latitude_id).val(ui.item.longitude);
                        //var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                        //marker.setPosition(location);
                        //map.setCenter(location);
                        }
                    });
                    // code here
                });

            },

            //metodos publicos
            foo_public_method: function() {

                // code goes here

            }

        }
 
        // private methods
        var helpers = {

            // a private method. for demonstration purposes only - remove it!
            foo_private_method: function() {

                // code goes here

            }

        }

        // if a method as the given argument exists
        if (methods[method]) {

            // call the respective method
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

        // if an object is given as method OR nothing is given as argument
        } else if (typeof method === 'object' || !method) {

            // call the initialization method
            return methods.init.apply(this, arguments);

        // otherwise
        } else {

            // trigger an error
            $.error( 'Method "' +  method + '" does not exist in pluginName plugin!');

        }

    }

})(jQuery);