<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $calendar = isset($calendar) ? $sf_data->getRaw('calendar') : null;
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
                        <?php  include_partial('aBlog/breadcrumbs', array('aBlogItem' => $aEvent)) ?>
                        
                        <div id="entries">
	
			<div class="entry post clearfix">
                            <?php if ($aEvent->userHasPrivilege('edit')): ?>
                            <ul class="a-ui a-controls">
                                <li><?php echo a_button(a_('Edit'), url_for('@ar_blog_event_edit?page_back='.arMenuInfo::PAGE.'&id='.$aEvent->getId()), array('icon','a-edit')) ?></li>
                            </ul>
                            <?php endif; ?>
                            <h1 class="title">
                                 
                                <?php echo $aEvent->getTitle() ?>
                                 <?php if (!$aEvent['is_publish']): ?>
                                    <span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
                                <?php endif ?>
                            </h1>
                           
                            
                            <div class="post-meta">
                                    <p class="meta-info">
                                          <?php include_partial('aBlog/author', array('a_blog_post' => $aEvent)) ?>
                                          <?php echo aDate::pretty($aEvent['published_at']); ?>
                                          <?php include_partial('aBlog/categories', array('aBlogItem' => $aEvent)) ?>
                                          <?php include_partial('aBlog/tags', array('aBlogItem' => $aEvent)) ?>
                                    </p>
                            </div> <!-- end .post-meta -->
                            <?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
                            <?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
  
                            <?php include_component('a', 'standardArea', array('name' => 'blog-body', 'edit' => false, 'toolbar' => 'main', 'slug' => $aEvent->Page->slug, 'width' => sfConfig::get('app_aBlog_media_width', 480), 'minusSlots' => array('aBlog', 'aEvent'))) ?>
                            
                            <?php if ($aEvent->userHasPrivilege('edit')): ?>
                                <?php echo a_button(__('Edit this entry',null,'blog'), url_for('@ar_blog_event_edit?page_back='.arMenuInfo::PAGE.'&id='.$aEvent->getId()), array('icon','a-edit')) ?>
                            <?php endif; ?>
                        </div> <!-- end .entry -->

                <?php include_partial('arComment/comments',array('aBlogItem' => $aEvent)); ?>  
	 </div> <!-- end #entries -->
        </div> <!-- end #left-area -->

                        <div id="sidebar">
                            <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'info' => $info, 'url' => 'aEvent/index', 'searchLabel' => a_('Search Events'), 'newLabel' => a_('New Event'), 'newModule' => 'aEventAdmin', 'newComponent' => 'newEvent', 'url' => 'aEvent/index', 'calendar' => $calendar)) ?>
                        </div> <!-- end #sidebar -->
                        <div id="index-top-shadow"></div>
                    </div> <!-- end #main-content -->

		</div> <!-- end #content-bottom-shadow -->
            </div> <!-- end #content-top-shadow -->
	</div> <!-- end #content-shadow -->
    </div> <!-- end #inner-border -->
</div>


<?php slot('og-meta') ?>
<?php // og-meta is meta information for Facebook that gets read when something is shared with Add This (or anything else)  ?>
<meta property="og:title" content="<?php echo $aEvent->getTitle() ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo url_for('a_event_post', $aEvent, true) ?>"/>
<?php $items = $aEvent->getMediaForArea('blog-body', 'image', 1) ?>
<?php if (count($items)): ?>
    <?php foreach ($items as $item): ?>
        <meta property="og:image" content="<?php echo $item->getImgSrcUrl(400, false, 's', 'jpg', true) ?>"/>	
    <?php endforeach ?>
<?php endif ?>
<meta property="og:site_name" content="<?php echo sfContext::getInstance()->getResponse()->getTitle(); ?>"/>
<meta property="og:description" content="<?php echo $aEvent->getTextForArea('blog-body', 25) ?>"/>
<?php end_slot() ?>

<?php slot('body_class','') ?>
<?php slot('a-subnav','') ?>
<?php slot('sidebar_search','') ?>