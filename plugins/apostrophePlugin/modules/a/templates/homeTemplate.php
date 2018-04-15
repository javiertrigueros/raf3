<?php 
    $page = aTools::getCurrentPage(); 
    $toolbar = isset($toolbar) ? $sf_data->getRaw('toolbar') : 'Sidebar'; 
    $slots = isset($slots) ? $sf_data->getRaw('slots') : array('arLast', 'arBlog','aRichText','aText','aRawHTML'); 
?>

<?php use_helper('a') ?>

<?php // Defining the <body> class ?>
<?php slot('a-body-class','a-home') ?>

<?php // Breadcrumb is removed for the home page template because it is redundant ?>
<?php slot('a-breadcrumb', '') ?>

<?php // Subnav is removed for the home page template because it is redundant ?>
<?php slot('a-subnav', '') ?>
<?php // sin pie de momento ?>
<?php slot('a-footer', '') ?>



<div id="inner-border">
    <div id="content-shadow">
        <div id="content-top-shadow">
            <div id="content-bottom-shadow">
									
                <div id="second-menu" class="clearfix">
                     
                    <?php a_slot('second-menu', 'arMenuSecundary', array('global' => true, 'history' => false)) ?>
                    <!-- end ul#nav -->
                </div> <!-- end #second-menu -->
                                                                                                                                                        
                <?php a_slot('slide', 'arBlogSlider', array(
                    'slideshowOptions' => array('width' => 960, 'height' => 340, 'resizeType' => 'c'), 
                    'history' => false)) ?>                                                                                                                                         
        
                <?php  a_area('column-left', array(
                    'allowed_types' => $slots,
                    'slug' => $page->slug,
                    'history' => false,
                    'areaLabel' => __('Add Content',array(),'blog'),
                    'type_options' => array(
                        'arBlog' => array(),
                        'aRichText' => array(
                            'tool' => $toolbar,
                                // 'allowed-tags' => array(),
                                // 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
                                // 'allowed-styles' => array('color','font-weight','font-style'),
                        ),
                        'aText' => array(
                            'multiline' => false
                        ),
                        'aRawHTML' => array(
                        ),
                    ))) ?>
                
                    <?php  a_area('column-center', array(
                    'allowed_types' => $slots,
                    'slug' => $page->slug,
                    'history' => false,
                    'areaLabel' => __('Add Content',array(),'blog'),
                    'type_options' => array(
                        'arBlog' => array(
                            'class' => 'recent-middle'
                        ),
                        'aRichText' => array(
                            'tool' => $toolbar,
                                // 'allowed-tags' => array(),
                                // 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
                                // 'allowed-styles' => array('color','font-weight','font-style'),
                        ),
                        'aText' => array(
                            'multiline' => false
                        ),
                        'aRawHTML' => array(
                        ),
                    ))) ?>
                
                    <?php  a_area('column-right', array(
                    'allowed_types' => $slots,
                    'slug' => $page->slug,
                    'history' => false,
                    'areaLabel' => __('Add Content',array(),'blog'),
                    'type_options' => array(
                        'arBlog' => array(
                            'class' => 'recent-last'
                        ),
                        'aRichText' => array(
                            'tool' => $toolbar,
                                // 'allowed-tags' => array(),
                                // 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
                                // 'allowed-styles' => array('color','font-weight','font-style'),
                        ),
                        'aText' => array(
                            'multiline' => false
                        ),
                        'aRawHTML' => array(
                        ),
                    ))) ?>
                
<div class="clear"></div>
<div id="main-content" class="clearfix">
	<div id="left-area">
                <?php  a_area('left-area', array(
                    'allowed_types' => array('arLast', 'aRawHTML'),
                    'slug' => $page->slug,
                    'history' => false,
                    'areaLabel' => __('Add Content',array(),'blog'),
                    'type_options' => array(
                        'arLast' => array(
                            'class' => 'recent-last'
                        ),
                        
                        'aRawHTML' => array(
                        ),
                    ))) ?>
            
    </div> <!-- end # left-area  -->

    <div id="sidebar">
             <!--  #sidebar -->
    </div> <!-- end #sidebar -->
</div> <!-- end #main-content -->

<div id="additional-widgets" class="clearfix">
   <!-- #additional-widgets --> 
</div> <!-- end #additional-widgets -->

            </div> <!-- end #content-bottom-shadow -->
	</div> <!-- end #content-top-shadow -->
    </div> <!-- end #content-shadow -->
</div><!-- end #inner-border -->