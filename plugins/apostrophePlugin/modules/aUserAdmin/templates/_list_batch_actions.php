<li class="a-admin-batch-actions-choice">
  <select id="batch-action" name="batch_action">
    <option value=""><?php echo __('Choose an action', array(), 'apostrophe') ?></option>
    <option value="batchActive"><?php echo __('Active users', array(), 'apostrophe') ?></option>
    <option value="batchDisable"><?php echo __('Disable users', array(), 'apostrophe') ?></option>
    <option value="batchDelete"><?php echo __('Delete', array(), 'apostrophe') ?></option>
  </select>
  <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
  <?php endif; ?>
</li>

<?php include_js_call('aUserAdmin/jsBachActions') ?>
