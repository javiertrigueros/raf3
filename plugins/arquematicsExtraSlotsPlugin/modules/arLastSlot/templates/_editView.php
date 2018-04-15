<?php use_helper('a') ?>
<?php use_javascript("/apostropheBlogPlugin/js/aBlog.js"); ?>
<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
?>
<?php echo $form->renderHiddenFields() ?>
<div class="a-blog-edit-wrapper clearfix">

         <div class="a-form-row">
	  <div class="a-form-row">
                <div class="a-form-field">
                     <h3><?php echo a_('Header Text') ?></h3>
                    <?php echo $form['title_head']->render() ?>
                </div>
	  </div>
	</div>
	<div class="a-form-row">
	  <div class="a-form-row">
               
	  	<div class="a-form-field">
                    <label for="<?php echo $form['count']->renderId() ?>" class="a-form-field-label"><?php echo a_('Number of Posts') ?></label>
                    <?php echo $form['count']->render() ?>
                    <div class="a-help"><?php echo $form['count']->renderHelp() ?></div>
	  	</div>
	  	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
	  </div>
	</div>
        
</div>


