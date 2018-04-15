<li class="sf_admin_batch_actions_choice">
  <select id="batch-action" name="batch_action" class="batch-action-select">
    <option value=""><?php echo __('Choose an action', array(), 'blog') ?></option>
    <option value="batchApproved"><?php echo __('Approved', array(), 'blog') ?></option>
    <option value="batchPending"><?php echo __('Pending', array(), 'blog') ?></option>
    <option value="batchDelete"><?php echo __('Delete', array(), 'blog') ?></option>
  </select>
  <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
    <input id="<?php echo $form->getCSRFFieldName() ?>" type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
  <?php endif; ?>
  
</li>
<?php include_js_call('arCommentAdmin/jsBachActions') ?>