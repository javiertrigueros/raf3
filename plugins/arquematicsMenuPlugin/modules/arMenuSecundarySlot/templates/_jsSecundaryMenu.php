<?php $id = isset($id) ? $sf_data->getRaw('id') : false; ?>
<script type="text/javascript" >
    
        $(document).ready(function() {

          if ( ! $('html#ie7').length ) 
          {
                duplicate_menu( 
                    $('#<?php echo $id ?>'),
                    $('#cmd-movile-<?php echo $id ?>'),
                    'category_mobile_menu',
                    'et_mobile_menu' );
                                
                $('#<?php echo $id ?>').superfish();
                $('#cmd-movile-<?php echo $id ?> ul.et_mobile_menu').superfish();
          }
          
        });
        
        if ( !$.isFunction('duplicate_menu') ) 
        {
            function duplicate_menu( $menu, $append_to, menu_id, menu_class )
            {
			var $cloned_nav;

			$menu.clone().attr('id',menu_id)
                                .removeClass()
                                .attr('class',menu_class)
                                .appendTo( $append_to );
                        
			$cloned_nav = $append_to.find('> ul');
			$cloned_nav.find('li:first').addClass('et_first_mobile_item');

			$append_to.click( function(){
				if ( $(this).hasClass('closed') ){
					$(this).removeClass( 'closed' ).addClass( 'opened' );
					$cloned_nav.slideDown( 500 );
				} else {
					$(this).removeClass( 'opened' ).addClass( 'closed' );
					$cloned_nav.slideUp( 500 );
				}
				return false;
			} );
                        
                        $menu.find('a').click( function(event){
				event.stopPropagation();
			} );
                        
			$append_to.find('a').click( function(event){
				event.stopPropagation();
			} );
                        
                        
            };
        }
        
 </script>