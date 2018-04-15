<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
  $admin = ($sf_params->get('module') == 'aBlogAdmin') ? true : false;

  $width = isset($width) ? $sf_data->getRaw('width') : 480;
  $toolbar = isset($toolbar) ? $sf_data->getRaw('toolbar') : 'Sidebar'; 
  $slots = isset($slots) ? $sf_data->getRaw('slots') : array('aInsetImage','aRichText',  'arImageResponsive', 'arGalleryResponsive',  'aFile', 'aRawHTML'); 
?>

<div class="a-blog-item-content">
  <?php //echo url_for($a_blog_post->Page->slug) ?>
  <?php  a_area('blog-body', array(
	'allowed_types' => $slots,
        'slug' => $a_blog_post->Page->slug,
        'areaLabel' => __('Add Content',array(),'blog'),
        'type_options' => array(
                //arquematics Slots
                 'arGalleryResponsive' => array(
                   'gridWidth' => 715,
                   'gridHeight' => 300
                ),
                'arSliderResponsive' => array(
                   'gridWidth' => 715,
                   'gridHeight' => 300
                ),
                'arImageResponsive' => array(),
                //other Slots
            
                'aRichText' => array(
		  'tool' => $toolbar,
			// 'allowed-tags' => array(),
			// 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
			// 'allowed-styles' => array('color','font-weight','font-style'),
		),
                'aImage' => array(),
		'aVideo' => array(
			'width' => $width,
			'height' => false,
			'resizeType' => 's',
			'flexHeight' => true,
			'title' => false,
			'description' => false,
		),
		'aSlideshow' => array(
			'width' => $width,
			'height' => false,
			'resizeType' => 's',
			'flexHeight' => true,
			'constraints' => array('minimum-width' => $width),
			'arrows' => true,
			'interval' => false,
			'random' => false,
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',
			'allowed_variants' => array('normal','autoplay'), 
		),
                
		'aSmartSlideshow' => array(
			'width' => $width,
			'height' => false,
			'resizeType' => 's',
			'flexHeight' => true,
			'constraints' => array('minimum-width' => $width),
			'arrows' => true,
			'interval' => false,
			'random' => false,
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',
		),
		'aFile' => array(
		),
               
            
               
            
                'aPhotoGrid' => array(),
		'aAudio' => array(
			'width' => $width,
			'title' => true,
			'description' => true,
			'download' => true,
			'playerTemplate' => 'default',
		),
		'aFeed' => array(
			'posts' => 5,
			'links' => true,
			'dateFormat' => false,
			'itemTemplate' => 'aFeedItem',
			// 'markup' => '<strong><em><p><br><ul><li><a>',
			// 'attributes' => false,
			// 'styles' => false,
		),
		'aButton' => array(
			'width' => $width,
			'flexHeight' => true,
			'resizeType' => 's',
			'constraints' => array('minimum-width' => $width),
			'rollover' => true,
			'title' => true,
			'description' => false
		),
		'aBlog' => array(
			// 'excerptLength' => 100, 
			// 'aBlogMeta' => true,
			// 'maxImages' => 1, 
			'slideshowOptions' => array(
				'width' => $width,
				'height' => false
			),
		),
		'aEvent' => array(
			// 'excerptLength' => 100, 
			// 'aBlogMeta' => true,
			// 'maxImages' => 1, 
			'slideshowOptions' => array(
				'width' => $width,
				'height' => false
			),
		),
                'aInsetImage' => array(
                        'width' => 300,
                        'height' => false,
                        'resizeType' => 's',
                        'flexHeight' => true,
                        'allowed_variants' => array('topLeft', 'topRight')),
                'aText' => array(
                        'multiline' => false
                ),
		'aRawHTML' => array(
		),
	))) ?>
	

</div>