<?php
  // Compatible with sf_escaping_strategy: true
  $categories = isset($categories) ? $sf_data->getRaw('categories') : null;
  $authors = isset($authors) ? $sf_data->getRaw('authors') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $noFeed = isset($noFeed) ? $sf_data->getRaw('noFeed') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $tagsByPopularity = isset($tagsByPopularity) ? $sf_data->getRaw('tagsByPopularity') : null;
  $tagsByName = isset($tagsByName) ? $sf_data->getRaw('tagsByName') : null;
  $url = isset($url) ? $sf_data->getRaw('url') : null;
  $searchLabel = isset($searchLabel) ? $sf_data->getRaw('searchLabel') : null;
  $newLabel = isset($newLabel) ? $sf_data->getRaw('newLabel') : null;
  $adminModule = isset($adminModule) ? $sf_data->getRaw('adminModule') : null;
  $calendar = isset($calendar) ? $sf_data->getRaw('calendar') : null;
  $tag = (!is_null($sf_params->get('tag'))) ? $sf_params->get('tag') : null;
  $layoutOptions = isset($layoutOptions) ? $sf_data->getRaw('layoutOptions') : null;
  // Handy flags to shut these features off; eliminates some common override cases
  $showDates = isset($showDates) ? $sf_data->getRaw('showDates') : true;
  $showCategories = isset($showCategories) ? $sf_data->getRaw('showCategories') : true;
  $info = isset($info) ? $sf_data->getRaw('info') : array();

  $showCalendar = isset($params['pagination']['year'])? $params['pagination']['year'] !== '1970': true;
  
 ?>

<?php $selected = array('icon','a-selected','alt','icon-right'); // Class names for selected filters ?>

<?php // Categories in the subnav are redundant when there is only one possible on this page. ?>
<?php // If I've missed something and this has to be reverted, FM will need an override ?>
<?php if (count($categories) === 1): ?>
  <?php $showCategories = false ?>
<?php endif ?>

<?php // This partial sets up the sidebar as a series of Symfony slots, then ?>
<?php // includes aBlog/sidebarLayout which outputs them. You can override that partial ?>
<?php // to alter the sequence and impose your own elements without losing out on ?>
<?php // bug fixes we make later (although you'll have to watch for entirely new slots). ?>

<?php // Do not jam year month and day into non-date filters when departing from an individual post ?>
<?php if ($sf_params->get('action') === 'show'): ?>
  <?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author'))) ?>
<?php else: ?>
  <?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author'))) ?>
<?php endif ?>

<?php foreach ($params['extraFilterCriteria'] as $efc): ?>
  <?php $filterUrl = aUrl::addParams($filterUrl, array($efc['urlParameter'] => $sf_params->get($efc['urlParameter']))) ?>
<?php endforeach ?>

<?php slot('sidebar_search') ?>
<div id="search-form">
    <form action="<?php echo url_for(aUrl::addParams($filterUrl, array('q' => ''))) ?>" method="get">
  	<label for="a-search-blog-field" style="display:none;"><?php echo $searchLabel; ?></label><?php // label for accessibility ?>
        <input type="text" placeholder="<?php echo $searchLabel; ?>" label="<?php echo $searchLabel; ?>" autocomplete="off" name="q" value="" id="searchinput"/>
        <input type="image" src="<?php echo image_path('/apostropheBlogPlugin/images/search_btn.png') ?>" id="searchsubmit" value="<?php echo a_('Search Pages'); ?>" alt="<?php echo a_('Search'); ?>" title="<?php echo a_('Search'); ?>"/>
    </form>
</div><!-- end #search-form -->
<?php end_slot('sidebar_search') ?>

<?php slot('a_blog_sidebar_dates') ?>

<?php if ($showDates): ?>
  <?php if (isset($calendar) && $calendar & $showCalendar): ?>
    <?php include_partial('aEvent/calendar', array('calendar' => $calendar)) ?>
  <?php else: ?>
    <h4 class="main-title widget-title"><?php echo a_('Browse by') ?></h4>

    <div class="widget">
        <ul>
            <li>
                <?php $selected_day = ($dateRange == 'day') ? $selected : array() ?>
    		<?php echo a_button('Day', url_for($url) . '?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day']), array_merge(array('a-link'),$selected_day)) ?>
            </li>
            <li>
                <?php $selected_month = ($dateRange == 'month') ? $selected : array() ?>
    		<?php echo a_button('Month', url_for($url) .'?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month']), array_merge(array('a-link'),$selected_month)) ?>
            </li>
            <li>
                <?php $selected_year = ($dateRange == 'year') ? $selected : array() ?>
              
                <?php echo a_button('Year', url_for($url).'?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year']), array_merge(array('a-link'),$selected_year)) ?>
            </li>
        </ul>
    </div>    
  <?php endif ?>
<?php endif ?>

<?php end_slot('a_blog_sidebar_dates') ?>

<?php if (count($categories) > 0): ?>
  <?php slot('a_blog_sidebar_categories') ?>

    <h4 class="main-title widget-title"><?php echo a_(sfConfig::get('app_aBlog_categories_label', 'Categories')) ?></h4>
   
    <div class="widget">
        <ul>
            <?php foreach ($categories as $category): ?>
                 <?php $isSelected = ($category['slug'] === $sf_params->get('cat')) ?>
                 <?php $selected_category = $isSelected ? $selected : array() ?>
                 <li class="cat-item <?php echo $isSelected ? 'current-cat' : '' ?>">
                      <?php echo a_button($category['name'], url_for(aUrl::addParams($filterUrl, array('cat' => ($sf_params->get('cat') === $category['slug']) ? '' : $category['slug']))), array_merge(array('a-link'),$selected_category)) ?>
                 </li>
             <?php endforeach ?>
	</ul>
    </div>
  <?php end_slot('a_blog_sidebar_categories') ?>
<?php endif ?>
    
<?php /*slot('a_blog_breadcrumbs') ?>

    <div id="breadcrumbs">
	<a href="<?php url_for('aBlog/index') ?>"><?php echo a_('Blog') ?></a>
        <span class="raquo">&bnsp;»&bnsp;</span>
        <?php if (strlen($sf_params->get('cat'))): ?>
	  <?php $category = Doctrine::getTable('aCategory')->findOneBySlug($sf_params->get('cat')) ?>
	  <?php if ($category): ?>
	   <?php echo $category->getName() ?>
	  <?php endif ?>
	<?php endif ?>
 </div> <!-- end #breadcrumbs -->
    
<?php end_slot('a_blog_breadcrumbs') */?>

  
<?php if(count($tagsByName)): ?>
<?php slot('a_blog_sidebar_tags') ?>

    <h4 class="main-title widget-title"><?php echo a_('Tags') ?></h4>
    <div class="widget">
        <?php /*
	<?php if (isset($tag)): ?>
       
            <h4 class="a-tag-sidebar-title selected-tag"><?php echo a_('Selected Tag') ?></h4>
            <ul class="a-ui a-tag-sidebar-list">
                <li>
                    <?php echo a_button($tag, url_for(aUrl::addParams($filterUrl, array('tag' => ''))), array('a-link','icon','a-selected')) ?> 
                </li>
            </ul>
	<?php endif ?> */?>

	<?php /*<h4 class="a-tag-sidebar-title popular"><?php echo a_('Popular Tags') ?></h4> */?>
	<ul class="a-ui a-tag-sidebar-list popular">
		<?php $n=1; foreach ($tagsByPopularity as $tagInfo): ?>
		  <?php if (isset($tag) && ($tagInfo['name'] == $tag)): ?>
                  <li>

                  <a class="a-link icon a-selected a-remove-filter-button ar-blog-tag" 
                     href="<?php echo url_for(aUrl::addParams($filterUrl, array('tag' => ''))) ?>">
                      <?php echo $tagInfo['name'] ?>
                  </a>
                  <a class="a-link icon a-selected a-remove-filter-button ar-blog-tag" 
                     href="<?php echo url_for(aUrl::addParams($filterUrl, array('tag' => ''))) ?>">
                      <span class="icon"></span>
                  </a>
                  </li>
                  <?php else: ?>
                  <li <?php echo ($n == count($tagsByPopularity) ? 'class="last"':'') ?>>
                    <?php /*echo a_button('<span class="a-tag-count icon">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag','icon','no-icon','icon-right')) */?>
                    <?php echo a_button($tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','icon','no-icon','icon-right','ar-blog-tag')) ?>
                  </li>
                  <?php endif; ?>
		<?php $n++; endforeach ?>
	</ul>

        <?php /*
        <div class="more-tags a-tag-sidebar-title all-tags" >
            <span><?php echo a_('More tags') ?></span>
        </div>
        
	<ul id="all-tags" class="a-ui a-tag-sidebar-list all-tags">
                
		<?php $n=1; foreach ($tagsByName as $tagInfo): ?>
		  <li <?php echo ($n == count($tagsByName) ? 'class="last"':'') ?>>
                    <?php echo a_button('<span class="a-tag-count icon">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag','icon','no-icon','icon-right')) ?>
                  </li>
		<?php $n++; endforeach ?>
	</ul> */?>

    </div>
<?php end_slot('a_blog_sidebar_tags') ?>
<?php endif ?>

<?php slot('a_blog_sidebar_authors') ?>

<?php if (count($authors) > 1): ?>
  <h4 class="main-title widget-title"><?php echo a_('Authors') ?></h4>
  
  <div class="widget">
      <ul>
        <?php foreach ($authors as $author): ?>
		<?php $selected_author = ($author['username'] === $sf_params->get('author')) ? $selected : array() ?>
          <li class="<?php echo ($selected_author)?'author-selected':'' ?> author-item-<?php echo $author['id'] ?>">
              <?php echo a_button($author->getName() ? $author->getName() : $author, url_for(aUrl::addParams($filterUrl, array('author' => ($sf_params->get('author') === $author['username']) ? '' : $author['username']))), array_merge(array('a-link'),$selected_author)) ?>
          </li>
        <?php endforeach ?>
       </ul>
  </div>
<?php endif ?>

<?php end_slot('a_blog_sidebar_authors') ?>

<?php // Add sections in the sidebar for custom filter criteria ?>
<?php if (isset($params['extraFilterCriteria'])): ?>
  <?php foreach ($params['extraFilterCriteria'] as $efc): ?>
    <?php slot('a_blog_sidebar_' . $efc['arrayKey']) ?>
      <?php $items = array() ?>
      <?php foreach ($info[$efc['arrayKey']] as $row): ?>
        <?php if ($sf_params->get($efc['urlParameter']) == $row[$efc['urlColumn']]): ?>
          <?php // Take it out ?>
          <?php $row['filterUrl'] = aUrl::addParams($filterUrl, array($efc['urlParameter'] => '')) ?>
        <?php else: ?>
          <?php $row['filterUrl'] = aUrl::addParams($filterUrl, array($efc['urlParameter'] => $row[$efc['urlColumn']])) ?>
        <?php endif ?>
        <?php $items[] = $row ?>
      <?php endforeach ?>
      <?php if (isset($efc['sidebarComponent'])): ?>
        <?php include_component($efc['sidebarComponent'][0], $efc['sidebarComponent'][1], array('items' => $items)) ?>
      <?php else: ?>
        <?php include_partial($efc['sidebarPartial'], array('items' => $items)) ?>
      <?php endif ?>
    <?php end_slot() ?>
  <?php endforeach ?>
<?php endif ?>

<?php slot('a_blog_sidebar_feeds') ?>
<?php if(!isset($noFeed)): ?>
	<ul class="a-ui a-controls stacked">
        <?php $full = $url . '?feed=rss' ?>
        <?php // Everything not date-related. A date-restricted RSS feed is a bit of a contradiction ?>
        <?php $filtered = aUrl::addParams($filterUrl, array('feed' => 'rss', 'year' => '', 'month' => '', 'day' => '')) ?>
        <?php if ($full === $filtered): ?>
            <li><?php echo a_button(a_('RSS Feed'), url_for($full), array('a-link','icon','a-rss-feed', 'no-bg','color')) ?></li>
        <?php else: ?>
            <li><?php echo a_button(a_('Full Feed'), url_for($full), array('a-link','icon','a-rss-feed','no-bg', 'color')) ?></li>
            <li><?php echo a_button(a_('Filtered Feed'), url_for($filtered), array('a-link','icon','a-rss-feed','no-bg', 'color')) ?></li>
        <?php endif ?>
	</ul>
<?php endif ?>
<?php end_slot('a_blog_sidebar_feeds') ?>

<?php a_js_call('aBlog.sidebarEnhancements(?)', array()) ?>
<?php a_js_call('apostrophe.selfLabel(?)', array('selector' => '#a-search-blog-field', 'title' => $searchLabel, 'focus' => false )) ?>

<?php include_partial('aBlog/sidebarLayout', array('options' => $layoutOptions)) ?>
