<?php

  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;

 
?>

<?php use_helper('a','ar') ?>

<div class="a-form-row a-hidden">
	<?php echo $form->renderHiddenFields() ?>
</div>

<div class="a-form-row title">
    <?php echo __('Menu name',null,'adminMenu') ?>
    <div class="a-form-field">
	<?php echo $form['title']->render() ?>
    </div>
    <?php echo $form['title']->renderError() ?>
</div>

<div class="a-form-row title">
    <?php echo __('Show menu name',null,'adminMenu') ?>
    <div class="a-form-field">
	<?php echo $form['showTitle']->render() ?>
    </div>
    <?php echo $form['showTitle']->renderError() ?>
</div>

<?php a_js_call('apostrophe.slotEnhancements(?)', array('slot' => '#a-slot-'.$pageid.'-'.$name.'-'.$permid, 'editClass' => 'a-options')) ?>
