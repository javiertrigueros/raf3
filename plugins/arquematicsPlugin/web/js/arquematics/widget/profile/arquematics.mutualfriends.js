/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 *  - arquematics.infinite.js
 */

/**
 * 
 * @param {jQuery} $
 * @returns 
 */
(function($) {

$.widget( "arquematics.mutualfriends", {
	options: {
           user_profile: '#user-profile',
           url_load: '',
           content_loader: '#mutual-profile-loader'
	},
        
        bodyHeight: 0,
        
        _create: function() {
            this._initControlHandlers();
	},
        
        _initControlHandlers: function () {
            var that = this
            , options = this.options;
            
            $(document).infinite({
                url : options.url_load,
                initPage: 2,
                trigger: 60,
                showOnLoad: options.content_loader
            });
            
            $('body').bind('changeScrollContent', function (e, $node, url, $nodeExtra)
             {
               if ($node instanceof jQuery)
               {
                  $node.insertBefore(options.content_loader);
                  //continua cargando el documento hasta 
                  //que termina la lista o aumenta el largo de la pagina
                  if ($(document).height() <= that.bodyHeight)
                  {
                     $(document).infinite('load');     
                  }
               }
             });
            
        },
        _init: function()
        {
            var $profileNode = $(this.options.user_profile);
             
            this.bodyHeight = $(document).height();
            
            if ($profileNode.data('count_mutual_show') < $profileNode.data('count_mutual') )
            {
                //carga una vez contenido y luego hace 
                //el test si ha cambiado 
                $(document).infinite('load');     
            }
        }
});

}(jQuery));