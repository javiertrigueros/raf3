<script type="text/javascript">
    
    ''
    
    document.addEventListener('DOMContentLoaded', function () {
        var element = document.createElement('script');
        element.src = 'https://maps.googleapis.com/maps/api/js?key=<?php echo sfConfig::get('app_arquematics_plugin_googleAPI') ?>&language=es-ES&callback=Initialize';
        element.type = 'text/javascript';
        var scripts = document.getElementsByTagName('script')[0];
        scripts.parentNode.insertBefore(element, scripts);
   }, false);
   
   function Initialize()
   {
      $(document).ready(function(){
        $.getScript( "/arquematicsPlugin/js/vendor/jquery/components/gmaps.js", function( data, textStatus, jqxhr ) {
            $.getScript( "/arquematicsPlugin/js/arquematics/arquematics.geomap.js", function( data, textStatus, jqxhr ) {
               
                   arquematics.geomap.key = '<?php echo sfConfig::get('app_arquematics_plugin_googleAPI') ?>';
               
                    $('#map-name').on("click", function (e) 
                    {
                        e.preventDefault();

                        $('#map-container').show();
        
                        var $node = $('#map-container').find('.map-item');
        
                        if ($node.find('.map-static').children().length === 0)
                        {
                            arquematics.geomap.renderStaticMap($node);
                        }
                    });
    
                    $('#map-container').find('.cancel').on("click", function (e) 
                    {
                        e.stopPropagation();
        
                        $('#map-container').hide();
                
                    });
       
            });
        });
      }); 
   }
</script>