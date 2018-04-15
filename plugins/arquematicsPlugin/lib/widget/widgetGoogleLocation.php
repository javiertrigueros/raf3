<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class widgetUniqueFormInput extends sfWidgetFormInput
{

   

    public function configure($options = array(), $attributes = array())
    {

       parent::configure($options, $attributes);
    }

    /*
    public function getJavascripts()
    {
        return array(
            '/js/jquery-ui-1.8.13.custom.min.js',
            'http://maps.google.com/maps/api/js?sensor=false',
            '/js/gmapsGeoLocate.js');
    }
   
    
   
    public function getStylesheets ()
    {
        return array(
               '/css/themes/cupertino/jquery.ui.all.css' => 'screen',
	       '/css/themes/cupertino/jquery.ui.autocomplete.css' => 'screen'
	    );
    }
    */
    
    public function render ($name, $value = null,$attributes = array(), $errors = array())
    {

     
      if (!is_array($value) && is_numeric($value))
      {
          $value = GmapsLocatePeer::retrieveByPk($value);
      }

      if (isset($value) && is_object($value))
      {
          $data['formated_address']   = $value->getFormatedAddress();
        
      }
      else
      {
          $data['formated_address']   = $value;
      }


      // render the javascript code for the widget

      $nameId = $this->generateId($name);

      $javascript = sprintf('<script type="text/javascript">
        jQuery(document).ready(function()
        {
            jQuery("#%s").gmapsGeoLocate();
        });
      </script>',$nameId);

    
       return parent::render($name, $data['formated_address'],$attributes, $errors).$javascript;
       

        //return  "<pre>".$value."</pre>";

        /*
         // generate field id
	 $lat_id     = $this->generateId($name.'[lat]');
	 $lng_id     = $this->generateId($name.'[lng]');
	 $address_id = $this->generateId($name.'[address]');
	 $map_id     = $this->generateId($name.'[map]');
	 $lookup_id  = $this->generateId($name.'[lookup]');

         $template_vars = array(
        '{div.id}'             => $this->generateId($name),
        '{div.class}'          => $this->getOption('div.class'),
        '{map.id}'             => $this->generateId($name.'[map]'),
        '{map.style}'          => $this->getOption('map.style'),
        '{map.height}'         => $this->getOption('map.height'),
        '{map.width}'          => $this->getOption('map.width'),
        '{input.lookup.id}'    => $this->generateId($name.'[lookup]'),
        '{input.lookup.name}'  => $this->getOption('lookup.name'),
        '{input.address.id}'   => $this->generateId($name.'[address]'),
        '{input.latitude.id}'  => $this->generateId($name.'[latitude]'),
        '{input.longitude.id}' => $this->generateId($name.'[longitude]'),
        );


        

         // evitar cualquier error o aviso sobre formatos no válidos de $value
      $value = !is_array($value) ? array() : $value;
      $value['address']   = isset($value['address'])   ? $value['address'] : '';
      $value['longitude'] = isset($value['longitude']) ? $value['longitude'] : '';
      $value['latitude']  = isset($value['latitude'])  ? $value['latitude'] : '';

      // definir el widget de la dirección
      $address = new sfWidgetFormInputText(array(), $this->getOption('address.options'));
      $template_vars['{input.search}'] = $address->render($name.'[address]', $value['address']);

      // definir los campos de longitud y latitud
      $hidden = new sfWidgetFormInputHidden;
      $template_vars['{input.longitude}'] = $hidden->render($name.'[longitude]', $value['longitude']);
      $template_vars['{input.latitude}']  = $hidden->render($name.'[latitude]', $value['latitude']);


      // render the javascript code for the widget
      $javascript = sprintf(
      '
	      <script>
	        jQuery(window).bind("load", function() {
	          new swGmapWidget({	            lng: "%s",
	            lat: "%s",
	            address: "%s",
	            lookup: "%s",
	            map: "%s"
	          });
	        })
	      </script>
	    ',
	    $lng_id,
	    $lat_id,
	    $address_id,
	    $lookup_id,
	    $map_id
	    );

	   
	    // combinar las plantillas y las variables
      return strtr(
        $this->getOption('template.html').$javascript,
        $template_vars
      );
         * 
         */
    }
    
    
}
