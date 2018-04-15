<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

  <?php echo form_tag_for($form, '@a_group_admin', array('id'=>'a-admin-form', 'class'=>$sf_request->getParameter('class', ''))) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <div class="row">
        <div class="has-feedback form-group <?php echo $form['name']->hasError()?'has-error':''?>">
            <?php echo $form['name']->renderLabel() ?>
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
        
        <div class="has-feedback form-group <?php echo $form['description']->hasError()?'has-error':''?>">
            <?php echo $form['description']->renderLabel() ?>
            <?php echo $form['description']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['description']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['description']->renderError() ?>
            </span>
        </div>
        
        
        <div class="has-feedback form-group <?php echo $form['permissions_list']->hasError()?'has-error':''?>">
            <?php echo $form['permissions_list']->renderLabel() ?>
            <?php echo $form['permissions_list']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['permissions_list']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i>
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['permissions_list']->renderError() ?>
            </span>
        </div>
    </div>
    <?php /*foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php //print_r($fields) ?>
      <?php include_partial('aGroupAdmin/form_fieldset', array('sf_guard_group' => $sf_guard_group, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach; */?>

    <?php include_partial('aGroupAdmin/form_actions', array('sf_guard_group' => $sf_guard_group, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
  
  <?php include_js_call('aGroupAdmin/jsForm') ?>
