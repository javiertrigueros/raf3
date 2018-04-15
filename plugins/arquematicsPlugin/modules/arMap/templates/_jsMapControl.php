<?php /*
 * carga google maps de forma asincrona
 * y los scripts:
 * - gmaps.js
 * - arquematics.geomap.js
 * - arquematics.geotag.js
 * 
 * se cargarán despues de cargar google maps 
 */
?>
<script type="text/javascript">
   
   document.addEventListener('DOMContentLoaded', function () {
        var element = document.createElement('script');
        element.src = 'https://maps.googleapis.com/maps/api/js?key=<?php echo sfConfig::get('app_arquematics_plugin_googleAPI') ?>&language=es-ES&callback=Initialize';
        element.type = 'text/javascript';
        var scripts = document.getElementsByTagName('script')[0];
        scripts.parentNode.insertBefore(element, scripts);
   }, false);
   
   function Initialize() {
      $(document).ready(function(){
        $.getScript( "/arquematicsPlugin/js/vendor/jquery/components/gmaps.js", function( data, textStatus, jqxhr ) {
            $.getScript( "/arquematicsPlugin/js/arquematics/arquematics.geomap.js", function( data, textStatus, jqxhr ) {
                
                arquematics.geomap.key = '<?php echo sfConfig::get('app_arquematics_plugin_googleAPI') ?>';
                
                $.getScript( "/arquematicsPlugin/js/arquematics/widget/wall/arquematics.geotag.js", function( data, textStatus, jqxhr ) {
               
               
                    var item = $('#locate_formated_address').geotag({
                        wall_url:               '<?php echo url_for('@wall') ?>',
                        cancel_url:             '<?php echo url_for('@wall_map_cancel?id=') ?>',
                        tool_handler:           '#arMap',
                        tool_container:         '#map-control',
                        tool_focus:             '#locate_formated_address',
                        has_content:            <?php echo ($hasContent)?'true':'false' ?>,
                        show_tool:              <?php echo ($showTool)?'true':'false' ?>
                });
         
                arquematics.wall.subscribeTool(item.data('geotag'));
                
               
                });
            });
        });
      }); 
  }
  
</script>


<!-- The template to display maps available in wall control -->
<script id="map-template-preview" type="text/x-jquery-tmpl">
<div data-hash="${hash}"
     data-content="${content}"
     data-content-enc="${content_enc}"
     data-map-id="${id}" class="map-item content-data">
   
     <span data-map-id="${id}" id="remove-map-${id}" class="icon-remove-map cmd-remove-map fa fa-times-circle"></span>
 
     <div class="map-static col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
     <div class="map-dinamic col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
</div>
</script>
