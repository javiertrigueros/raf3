<?php if (sfConfig::get('app_a_i18n_switch')): ?>
    <form method="post" action="<?php echo a_url('a', 'language') ?>" class="a-language-form">
      <?php $form = new aLanguageForm(null, array('languages' => sfConfig::get('app_a_i18n_languages'))) ?>
			<div class="a-form-row a-hidden">
      	<?php echo $form->renderHiddenFields() ?>
			</div>
			<div class="a-form-row">
      	<?php echo $form['language']->render() ?>
			</div>
    </form>
<?php endif ?>

<?php a_js_call('$(".a-language-form select").change(function() { this.form.submit(); })') ?>