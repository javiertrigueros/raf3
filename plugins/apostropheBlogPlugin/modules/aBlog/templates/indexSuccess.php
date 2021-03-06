<?php
  // Compatible with sf_escaping_strategy: true
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $results = isset($results) ? $sf_data->getRaw('results') : null;
  
  $info = isset($info) ? $sf_data->getRaw('info') : null;
?>

<div id="content">
    <div id="inner-border">
        <div id="content-shadow">
            <div id="content-top-shadow">
                <div id="content-bottom-shadow">
                    <div class="clearfix" id="second-menu">
                        <?php a_slot('second-menu', 'arMenuSecundary', array('global' => true, 'singleton' => true, 'history' => false)) ?>
                    </div> <!-- end #second-menu -->
                    
                    <div class="clearfix" id="main-content">
                        <div id="left-area">
                            <?php  include_partial('aBlog/breadcrumbs') ?>
                          
                            <div id="entries">
                                <?php include_partial('aBlog/filters', array('type' => a_('post'), 'typePlural' => a_('posts'),  'url' => 'aBlog/index', 'count' => $pager->count(), 'params' => $params)) ?>
                                
                                <?php foreach ($results as $a_blog_post): ?>
                                    <?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post)) ?>
                                <?php endforeach ?>
                                
                                <?php if ($pager->haveToPaginate()): ?>
                                    <?php include_partial('aBlog/pager', array('max_per_page' => $max_per_page, 'pager' => $pager, 'pagerUrl' => url_for('aBlog/index?' . http_build_query($params['pagination'])))) ?>
                                <?php endif ?>
                            </div> <!-- end #entries -->
                        </div> <!-- end #left-area -->
                        <div id="sidebar">
                             <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'info' => $info, 'url' => 'aBlog/index', 'searchLabel' => a_('Search Posts'), 'newLabel' => a_('New Post'), 'newModule' => 'aBlogAdmin', 'newComponent' => 'newPost')) ?>
                        </div> <!-- end #sidebar -->
			<div id="index-top-shadow"></div>
                    </div> <!-- end #main-content -->
                </div> <!-- end #content-bottom-shadow -->
	</div> <!-- end #content-top-shadow -->
      </div> <!-- end #content-shadow -->
    </div> <!-- end #inner-border -->
</div>


<?php slot('a-subnav','') ?>
<?php slot('body_class','') ?>
<?php slot('sidebar_search','') ?>