<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

    <?php echo form_tag_for($form, '@a_tag_admin', array('id'=>'a-admin-form', 'class'=>$sf_request->getParameter('class', ''))) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <div class="row">
        <div class="has-feedback form-group <?php echo $form['name']->hasError()?'has-error':''?>">
            
            <label for="tag_name"><?php echo a_('Name'); ?></label>
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

    <?php include_partial('aTagAdmin/form_actions', array('tag' => $tag, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
  
  <?php include_js_call('aTagAdmin/jsForm') ?>
