/*
 * jQuery UI addresspicker @VERSION
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Progressbar
 *
 * Depends:
 *   jquery.ui.core.js
 *   jquery.ui.widget.js
 *   jquery.ui.autocomplete.js
 */
(function( $, undefined ) {

$.widget( "ui.addresspicker", {
	options: {
	  appendAddressString: "",
		mapOptions: {
		  zoom: 5, 
		  center: new google.maps.LatLng(40.463667, -3.74922), 
		  scrollwheel: false,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		},
		elements: {
		  map: false,
		  lat: false,
		  lng: false,
                  zoom: false,
                  south_west_lat: false,
                  south_west_lng: false,
                  north_east_lat: false,
                  north_east_lng: false,
		  locality: false,
		  country: false
		},
	  draggableMarker: true
	},

	marker: function() {
		return this.gmarker;
	},
	
	map: function() {
	  return this.gmap;
	},

  updatePosition: function() {
    this._updatePosition(this.gmarker.getPosition());
   //si no es visible el elemento lo muestra
    $(this.options.elements.map).show();
  },
  
  reloadPosition: function() {
    this.gmarker.setVisible(true);
    this.gmarker.setPosition(new google.maps.LatLng(this.lat.val(), this.lng.val()));
    this.gmap.setCenter(this.gmarker.getPosition());
  },
  
  selected: function() {
    return this.selectedResult;
  },
  
  _create: function() {
	  this.geocoder = new google.maps.Geocoder();
          this.lat      = $(this.options.elements.lat);
          this.lng      = $(this.options.elements.lng);
                
          this.south_west_lat  = $(this.options.elements.south_west_lat);
          this.south_west_lng = $(this.options.elements.south_west_lng);
          this.north_east_lat = $(this.options.elements.north_east_lat);
          this.north_east_lng = $(this.options.elements.north_east_lng);
                  
          this.locality = $(this.options.elements.locality);
          this.country  = $(this.options.elements.country);
          
          this.zoom  = $(this.options.elements.zoom);
          
	  this.element.autocomplete({
			source: $.proxy(this._geocode, this),  
			focus:  $.proxy(this._focusAddress, this),
			select: $.proxy(this._selectAddress, this)
		});
		
          if (this.options.elements.map) 
          {
            this.mapElement = $(this.options.elements.map);
            this._initMap();
          }
	},

  _initMap: function() {
    if (this.lat && this.lat.val()) {
      this.options.mapOptions.center = new google.maps.LatLng(this.lat.val(), this.lng.val());
    }

    this.gmap = new google.maps.Map(this.mapElement[0], this.options.mapOptions);
    this.gmarker = new google.maps.Marker({
      position: this.options.mapOptions.center, 
      map:this.gmap, 
      draggable: this.options.draggableMarker});
  
    google.maps.event.addListener(this.gmarker, 'dragend', $.proxy(this._markerMoved, this));
    this.gmarker.setVisible(false);
    
    if (this.south_west_lat && this.south_west_lat.val())
    {
        var bounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(
                                this.south_west_lat.val(),
                                this.south_west_lng.val()),
    
                        new google.maps.LatLng(
                                this.north_east_lat.val(),
                                this.north_east_lng.val())
                    );
    
       this.gmap.fitBounds(bounds);
    }
    
   
    
    
  },
  
  _updateBoundsView: function(viewport) {
    var southWest = viewport.getSouthWest();
    var northEast = viewport.getNorthEast();
    
    if (this.south_west_lat) {
      this.south_west_lat.val(southWest.lat());
    }
    if (this.south_west_lng) {
        this.south_west_lng.val(southWest.lng());
    }
    if (this.north_east_lat) {
        this.north_east_lat.val(northEast.lat());
    }
    if (this.north_east_lng) {
        this.north_east_lng.val(northEast.lng());
    }
  },
  
  _updatePosition: function(location) {
    if (this.lat) {
      this.lat.val(location.lat());
    }
    if (this.lng) {
      this.lng.val(location.lng());
    }
  },
  
  _markerMoved: function() {
    this._updatePosition(this.gmarker.getPosition());
  },
  
  // Autocomplete source method: fill its suggests with google geocoder results
  _geocode: function(request, response) {
    var address = request.term;
    this.geocoder.geocode( { 'address': address + this.options.appendAddressString}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) 
      {
        for (var i = 0; i < results.length; i++) {
          results[i].label =  results[i].formatted_address;
        }
      } 
      response(results);
    })
  },
  
  _findInfo: function(result, type) {
    for (var i = 0; i < result.address_components.length; i++) {
      var component = result.address_components[i];
      if (component.types.indexOf(type) !=-1) {
        return component.long_name;
      }
    }
    return false;
  },
  
  simpleFocus: function()
  {
        var bounds = new google.maps.LatLngBounds(
                new google.maps.LatLng( this.south_west_lat.val(),  this.south_west_lng.val()),
                new google.maps.LatLng(this.north_east_lat.val(), this.north_east_lng.val)
                );
                    
        this.gmap.fitBounds(bounds);
  },
  
  _focusAddress: function(event, ui) {
    var address = ui.item;
    /*
    if (!address) {
      return;
    }
    */
   
    if (this.gmarker) {
      this.gmarker.setPosition(address.geometry.location);
      this.gmarker.setVisible(true);
     
      /*
      var bounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(address.geometry.viewport.southwest.lat, address.geometry.viewport.southwest.lng),
    new google.maps.LatLng(address.geometry.viewport.northeast.lat, address.geometry.viewport.northeast.lng)
);
    */
      this.gmap.fitBounds(address.geometry.viewport);
      this._updateBoundsView(address.geometry.viewport);
       /*
      $.each(address.geometry.viewport, function(key, element) {
                        alert('key: ' + key + '\n' + 'value: ' + element);
                    });*/
                   
    }
    this._updatePosition(address.geometry.location);
    
    if (this.locality) {
      this.locality.val(this._findInfo(address, 'locality'));
    }
    if (this.country) {
      this.country.val(this._findInfo(address, 'country'));
    }
    
    if (this.zoom)
    {
      this.zoom.val(this.gmap.getZoom());  
    }
    
  },
  
  _selectAddress: function(event, ui) {
    this.selectedResult = ui.item;
  }
});

$.extend( $.ui.addresspicker, {
	version: "@VERSION"
});


// make IE think it doesn't suck
if(!Array.indexOf){
	Array.prototype.indexOf = function(obj){
		for(var i=0; i<this.length; i++){
			if(this[i]==obj){
				return i;
			}
		}
		return -1;
	}
}

})( jQuery );
