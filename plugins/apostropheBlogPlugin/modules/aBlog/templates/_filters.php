<?php $type = $sf_data->getRaw('type') ?>
<?php $typePlural = $sf_data->getRaw('typePlural') ?>
<?php $url = $sf_data->getRaw('url') ?>
<?php $params = $sf_data->getRaw('params') ?>
<?php $count = $sf_data->getRaw('count') ?>

<?php // We need this so that tags don't get double-escaped etc. ?>
<?php $sf_params = $sf_data->getRaw('sf_params') ?>
 <?php $filters = array() ?>
  <?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author'))) ?>
  
  <?php foreach ($params['extraFilterCriteria'] as $efc): ?>
    <?php $filterUrl = aUrl::addParams($filterUrl, array($efc['urlParameter'] => $sf_params->get($efc['urlParameter']))) ?>
  <?php endforeach ?>

   
      <?php $date = aDate::dataToHuman(
            $sf_params->get('year'),
            $sf_params->get('month'),
            $sf_params->get('day')); ?>
   
	<?php if ($sf_params->get('year') > 0): ?>
	  <?php $filters[] = a_('for %date%', array('%date%' => a_remove_filter_button($date, $filterUrl, array('year', 'month', 'day')))) ?>
	<?php endif ?>

	<?php if (strlen($sf_params->get('q'))): ?>
	  <?php $filters[] = a_('matching the search %search%', array('%search%' => a_remove_filter_button($sf_params->get('q'), $filterUrl, 'q'))) ?>
	<?php endif ?>

	<?php /*if (strlen($sf_params->get('cat'))): ?>
	  <?php $category = Doctrine::getTable('aCategory')->findOneBySlug($sf_params->get('cat')) ?>
	  <?php if ($category): ?>
	    <?php $filters[] = a_(sfConfig::get('app_aBlog_category_filter_label', 'in the category %category%'), array('%category%' => a_remove_filter_button($category->name, $filterUrl, 'cat'))) ?>
	  <?php endif ?>
	<?php endif*/ ?>

	<?php if (strlen($sf_params->get('tag'))): ?>
	  <?php $filters[] = a_('with the tag %tag%', array('%tag%' => a_remove_filter_button($sf_params->get('tag'), $filterUrl, 'tag'))) ?>
	<?php endif ?>

        <?php if (strlen($sf_params->get('author'))): ?>
        <?php // Pulling the author object here is a little redundant but we don't want to change the signature of ?>
        <?php // the partials too much in order to pass in information ?>
        <?php $author = Doctrine::getTable('sfGuardUser')->findOneByUsername($sf_params->get('author')) ?>
        <?php if ($author): ?>
                <?php $filters[] = a_('by the author %author%', array('%author%' => a_remove_filter_button($author->getName() ? $author->getName() : $author, $filterUrl, 'author'))) ?>
                <?php endif ?>
        <?php endif ?>

  <?php if (isset($params['extraFilterCriteria'])): ?>
    <?php foreach ($params['extraFilterCriteria'] as $efc): ?>
      <?php $p = $efc['urlParameter'] ?>
      <?php if (strlen($sf_params->get($p))): ?>
        <?php $v = $sf_params->get($p) ?>
        <?php $filters[] = a_($efc['labelTemplate'], array('%value%' => a_remove_filter_button($efc['labelValueCallback'] ? call_user_func($efc['labelValueCallback'], $v) : $v, $filterUrl, $p))) ?>
      <?php endif ?>
    <?php endforeach ?>
  <?php endif ?>

<?php if (count($filters)): ?>

<div class="a-ui a-blog-browser">

  <?php include_partial('aBlog/datePrevAndNext', array('params' => $params, 'url' => $url)) ?>

	
            <h3 class="a-blog-filters browser">
		<?php slot('a-blog-has-filters', true) ?>
		<?php // Pluralize $type ?>
		<?php $type = ($count != 1) ? $typePlural : $type  ?>
		<?php echo a_('You are viewing %count% %type% %filters%', array('%count%' => $count, '%type%' => a_($type), '%filters%' => implode(' ', $filters))) ?>
            </h3>
        <?php /*elseif (!$count): ?>
            <h3 class="a-blog-filters browser">
		<?php echo a_('There are no %type%', array('%type%' => $typePlural)) ?>
            </h3>
	<?php endif */?>
</div>

<?php endif ?>


