<?php
  // Compatible with sf_escaping_strategy: true
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  // Pager URL defaults to current page. This makes it MUCH
  // easier to avoid losing all the other filter parameters
  $pagerUrl = isset($pagerUrl) ? $sf_data->getRaw('pagerUrl') : aUrl::addParams($sf_request->getUri(), array('page' => ''));
  // Our pager crashes the browser with 3,000+ pages as is common on YouTube
	
?>
<?php use_helper('a') ?>


<a href="<?php echo url_for(aUrl::addParams($pagerUrl, array('page' => (($pager->getPage() - 1) > 0) ? $pager->getPage() - 1 : 1))) ?>" class="alignleft"><?php echo __('Previous Page', null, 'apostrophe') ?></a>
<a href="<?php echo url_for(aUrl::addParams($pagerUrl, array('page' => (($pager->getPage() + 1) < $pager->getLastPage()) ? $pager->getPage() + 1 : $pager->getLastPage()))) ?>" class="alignright"><?php echo __('Next Page', null, 'apostrophe') ?></a>
  	

