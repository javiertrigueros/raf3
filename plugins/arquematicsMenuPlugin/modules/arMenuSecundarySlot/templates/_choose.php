
<?php $id = isset($id) ? $sf_data->getRaw('id') : false; ?>
<?php $class = isset($class) ? $sf_data->getRaw('class') : ''; ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : false; ?>

<?php $formMenu = isset($formMenu) ? $sf_data->getRaw('formMenu') : false; ?>

<?php if ($id): ?>
<form id="form-menu-edit-<?php echo $id ?>"  action="<?php echo url_for('@menu_create') ?>" method="POST" enctype="multipart/form-data" style="display:none">
    <?php echo $formMenu->renderHiddenFields() ?>
</form>

<li class="hide-ui" id="cmd-edit-menu-<?php echo $id ?>"><a href="#" class="<?php echo $class . ' a-inject-actual-url a-js-choose-button' ?>"><span class="icon"></span><?php echo a_('Edit') ?></a></li>

<?php a_js_call('apostrophe.arAsSubmit(?)', array('id' => "#$id", 'classname' => 'a-options-open', 'overlay' => false)) ?>	
<?php endif; ?>
