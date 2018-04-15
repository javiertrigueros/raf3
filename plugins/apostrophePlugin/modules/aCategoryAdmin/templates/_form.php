<?php
  // Compatible with sf_escaping_strategy: true
  $a_category = isset($a_category) ? $sf_data->getRaw('a_category') : null;
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $helper = isset($helper) ? $sf_data->getRaw('helper') : null;
?>
<?php use_helper('I18N','a') ?>
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

  <?php echo form_tag_for($form, '@a_category_admin', array('id'=>'a-admin-form')) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <div class="row">
        <div class="has-feedback form-group <?php echo $form['name']->hasError()?'has-error':''?>">
            
            <label for="category_name"><?php echo a_('Name'); ?></label>
            <?php echo $form['name']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['name']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['name']->renderError() ?>
            </span>
        </div>
    </div>

    <?php /* foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php include_partial('aCategoryAdmin/form_fieldset', array('a_category' => $a_category, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach;*/ ?>

    <?php include_partial('aCategoryAdmin/form_actions', array('a_category' => $a_category, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>

</form>

<?php include_js_call('aCategoryAdmin/jsForm') ?>

<?php //a_js_call('aMultipleSelect(?, ?)', '.a-admin-form-field-users_list', array('choose-one' => a_('Choose Users'))) ?>
<?php //a_js_call('aMultipleSelect(?, ?)', '.a-admin-form-field-groups_list', array('choose-one' => a_('Choose Groups'))) ?>
