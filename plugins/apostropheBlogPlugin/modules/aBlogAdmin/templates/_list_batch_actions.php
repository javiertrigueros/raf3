
<?php $listActions = Array ( 
                'batchDelete' => Array ( 
                        'label' => 'Delete',
                        'params' => Array ( ),
                        'class_suffix' => 'delete' ), 
                'batchPublish' => Array ( 
                        'label' => 'Publish', 
                        'params' => Array ( ), 
                        'class_suffix' => 'publish' ), 
                'batchUnpublish' => Array ( 
                        'label' => 'Unpublish', 
                        'params' => Array ( ), 
                        'class_suffix' => 'unpublish' ) 
        ); ?>

<li class="a-admin-batch-actions-choice">
  <select id="batch-action" name="batch_action">
    <option value=""><?php echo __('Choose an action', array(), 'apostrophe') ?></option>
<?php foreach ($listActions as $action => $params): ?>
    <option value="<?php echo $action ?>"><?php echo __($params['label'], array(), 'apostrophe') ?></option>
<?php endforeach; ?>
  </select>
<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
  <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
<?php endif; ?>
</li>

<?php include_js_call('aBlogAdmin/jsBachActionsBlog') ?>