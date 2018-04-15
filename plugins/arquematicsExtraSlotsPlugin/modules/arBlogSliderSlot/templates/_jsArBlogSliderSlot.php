 <script type="text/javascript">
      
   (function($){
       
	var $featured = $('#<?php echo $id ?>'),
            $controllers = $('#<?php echo $id ?>-controllers'),
		et_container_width = $('.container').width(),
		et_slider;

	$(document).ready(function(){
		var et_slider_settings;

		$("#entries, .fslider_widget").fitVids();

		if ( $featured.length ){
			var $featured_controllers = $controllers.find('ul li'),
				$featured_controllers_links = $featured_controllers.find('a'),
				$et_active_arrow = $controllers.find('#active_item');

			et_slider_settings = {
				slideshow	: false,
				before: function(slider){
					$featured_controllers_links.removeClass('active').eq( slider.animatingTo ).addClass('active');

					$et_active_arrow.animate({"left": $featured_controllers.eq( slider.animatingTo ).find('div').position().left}, "slow");
				},
				start: function(slider) {
					et_slider = slider;
				}
			}

			if ( $featured.hasClass('et_slider_auto') ) {
				var et_slider_autospeed_class_value = /et_slider_speed_(\d+)/g;

				et_slider_settings.slideshow = true;

				et_slider_autospeed = et_slider_autospeed_class_value.exec( $featured.attr('class') );

				et_slider_settings.slideshowSpeed = et_slider_autospeed[1];
			}

			et_slider_settings.pauseOnHover = true;

			$featured.flexslider( et_slider_settings );
		}

		$('.fslider_widget').flexslider( { slideshow: false } );

		jQuery('.fslider_widget iframe').each( function(){
			var src_attr = jQuery(this).attr('src'),
				wmode_character = src_attr.indexOf( '?' ) == -1 ? '?' : '&amp;',
				this_src = src_attr + wmode_character + 'wmode=opaque';
			jQuery(this).attr('src',this_src);
		} );

		
		$(window).resize( function(){
                       
                        if ($('.container').width() <= 300)
                        {
                            $featured.hide();
                        }
                        else
                        {
                            $featured.show();  
                        }
                        
			if ( et_container_width != $('.container').width() ) {
                            et_container_width = $('.container').width();
                            if ( ! $featured.is(':visible') ) $featured.flexslider( 'pause' );
                            
                        }
		});
	});

	$(window).load(function(){
		var $flexcontrol = $('#<?php echo $id ?> .flex-control-nav'),
                    $flexnav = $('#<?php echo $id ?> .flex-direction-nav');

		$controllers.find('a').click( function(){
			var $this_control = $(this),
				order = $this_control.closest('li').prevAll('li').length;

			if ( $this_control.hasClass('active-slide') ) return;

			//et_slider.flexAnimate( order, et_slider.vars.pauseOnAction );
			$featured.flexslider( order );

			return false;
		} );
	});
})(jQuery)
    
</script>