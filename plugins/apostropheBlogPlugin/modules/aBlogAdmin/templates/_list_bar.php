<?php
  // Compatible with sf_escaping_strategy: true
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $filters = isset($filters) ? $sf_data->getRaw('filters') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $appliedFilters = $filters->getAppliedFilters();
?>

<div class="a-admin-title-sentence">

<h4> 	
        <?php $fields = $configuration->getFormFilterFields($filters) ?>

	<?php if ($appliedFilters): ?>
		<?php echo a_('You are viewing posts') ?> 
	<?php else: ?>
		<?php echo a_('You are viewing all posts') ?>
	<?php endif ?>	

	<?php $n=1; foreach($appliedFilters as $name => $values): ?>
            <?php $field = $fields[$name] ?>
            <?php echo __($field->getConfig('label', $name),null,'apostrophe') ?>
            <?php foreach($values as $value): ?>
                <?php echo link_to($value, "@a_blog_admin_removeFilter?name=$name&value=$value", array('class' => 'selected')) ?><?php if ($n < count($appliedFilters)): ?>,<?php endif ?>
            <?php endforeach ?>
	<?php endforeach ?>
</h4>

</div>

<?php a_js_call("$('a.selected').prepend('<span class=\"close\"></span>')") ?>