<?php
  // Compatible with sf_escaping_strategy: true
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : null;
  $current = isset($current) ? $sf_data->getRaw('current') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $search = isset($search) ? $sf_data->getRaw('search') : null;
  $searchForm = isset($searchForm) ? $sf_data->getRaw('searchForm') : null;
  $selectedCategory = isset($selectedCategory) ? $sf_data->getRaw('selectedCategory') : null;
  $selectedTag = isset($selectedTag) ? $sf_data->getRaw('selectedTag') : null;
  $selected = array('icon','a-selected','alt','icon-right'); // Class names for selected filters
  $txtSearch = trim(htmlspecialchars($sf_params->get('search')));
  $hasSearch = strlen($txtSearch) > 0;
?>
<li>
    <form id="form-search" class="navbar-form pull-left" action="<?php echo url_for(aUrl::addParams($current, array("search" => false))) ?>" method="post">
            <span class="form-search navbar-icon cmd-search">
                <i class="fa fa-search"></i>
            </span> 
            <input id="a-search-media-field" name="search" type="text" autocomplete="off" class="form-control" value="" placeholder="<?php echo (!$hasSearch)?a_('Search Media'):$txtSearch ?>">
    </form>
</li>

<?php a_js_call("$(?).on('click', function (e){ e.preventDefault(); $('#form-search').submit() });", '.cmd-search') ?>