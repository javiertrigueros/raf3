<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $itemIds = isset($itemIds) ? $sf_data->getRaw('itemIds') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
?>

<?php use_helper('a') ?>

<?php if ($editable): ?>

 <?php slot("a-slot-controls-$pageid-$name-$permid") ?>
   <li class="a-controls-item choose-images">
     <?php aRouteTools::pushTargetEngineSlug('/admin/media', 'aMedia') ?>
     <?php echo link_to('<span class="icon"></span>' . a_get_option($options, 'chooseLabel', a_('Choose Images')),
       'aMedia/select',
       array(
         'query_string' => 
           http_build_query(
             array_merge(
               $options['constraints'],
               array("multiple" => true,
               "aMediaIds" => implode(",", $itemIds),
               "type" => "image",
               "label" => a_get_option($options, 'browseLabel', a_('Creating a Gallery.')),
               "after" => a_url('arSliderResponsiveSlot', 'edit') . "?" . 
                 http_build_query(
                   array(
                     "slot" => $name, 
                     "slug" => $slug, 
                     "permid" => $permid,
                     // actual_url will be added by JS, window.location is more reliable than
                     // guessing at the full context here when we might be in an AJAX update etc.
                     "noajax" => 1))))),
         'class' => 'a-btn icon a-media a-inject-actual-url a-js-choose-button')) ?>
     <?php aRouteTools::popTargetEnginePage('aMedia') ?>
   </li>

   <?php include_partial('a/variant', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>

 <?php end_slot() ?>

<?php endif ?>


<?php if (count($items) > 0): ?>

<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/flexslider.css"); ?>

<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery.fitvids.js"); ?>
<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery.flexslider.js"); ?>

<div id="<?php echo $id ?>" class="featured flexslider <?php echo $options['autoplay']?'et_slider_auto et_slider_speed_5000':''  ?>">
  
    <ul class="slides">   
	<?php foreach ($items as $item): ?>
		<li class="slide">
                    <div class="featured-top-shadow"></div>
                    <?php //print_r( $item) ?>
                    <?php  include_component('arMedia', 'showImage', 
                            array('mediaItem' => $item,
                                  'width' => $options['width'],
                                  'height' => $options['height'],
                                  'resizeType' => 'c'
                            )) ?>
                    <div class="featured-bottom-shadow"></div>
                </li><!-- end .slide -->
	<?php endforeach ?>
    </ul>
 </div>

<script type="text/javascript">
     
	$(document).ready(function(){
                var $featured = $('#<?php echo $id ?>'),
                    et_slider_settings;

		$("#entries, .fslider_widget").fitVids();

		if ( $featured.length ){
                    
			if ( $featured.hasClass('et_slider_auto') )  
                        {
				var et_slider_autospeed_class_value = /et_slider_speed_(\d+)/g;
				var et_slider_autospeed = et_slider_autospeed_class_value.exec( $featured.attr('class') );

                                et_slider_settings = {
                                        slideshowSpeed: et_slider_autospeed[1],
                                        slideshow: true,
                                        pauseOnHover: true
                                };
			}
                        else 
                        {
                                et_slider_settings = {
                                        pauseOnHover: true,
                                        slideshow: false
                                };
                        }

                        $featured.flexslider( et_slider_settings );

                        $('.fslider_widget iframe').each( function(){
                            var src_attr = $(this).attr('src'),
                                            wmode_character = src_attr.indexOf( '?' ) == -1 ? '?' : '&amp;',
                                            this_src = src_attr + wmode_character + 'wmode=opaque';
                                            $(this).attr('src',this_src);
                        });

		
                        $(window).resize( function(){
                       
                            if ($('.container').width() <= 300)
                            {
                                    $featured.hide();
                            }
                            else
                            {
                                $featured.hide();
                                $featured.flexslider( 'resize' );
                                $featured.show();  
                            }
                        });
                }
                
        });
</script>
<?php endif; ?>

