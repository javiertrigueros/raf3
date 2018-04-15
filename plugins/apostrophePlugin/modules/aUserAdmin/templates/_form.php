<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

  <?php echo form_tag_for($form, '@a_user_admin', array('role' => 'form','id'=>'a-admin-form')) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
    <div class="row">
        <div class="has-feedback form-group <?php echo $form['first_name']->hasError()?'has-error':''?>">
            
            <?php echo $form['first_name']->renderLabel() ?>
            <?php echo $form['first_name']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['first_name']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['first_name']->renderError() ?>
            </span>
        </div>
        
        <div class="has-feedback form-group <?php echo $form['last_name']->hasError()?'has-error':''?>">
            <?php echo $form['last_name']->renderLabel() ?>
            <?php echo $form['last_name']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['last_name']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['last_name']->renderError() ?>
            </span>
        </div>
        
        <div class="has-feedback form-group <?php echo $form['email_address']->hasError()?'has-error':''?>">
            <?php echo $form['email_address']->renderLabel() ?>
            <?php echo $form['email_address']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['email_address']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['email_address']->renderError() ?>
            </span>
        </div>
        
        <div class="has-feedback form-group <?php echo $form['username']->hasError()?'has-error':''?>">
            <?php echo $form['username']->renderLabel() ?>
            <?php echo $form['username']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['username']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['username']->renderError() ?>
            </span>
        </div>
        
        <div class="has-feedback form-group <?php echo ($form['password']->hasError())?'has-error':''?>">
            <?php echo $form['password']->renderLabel() ?>
            <?php echo $form['password']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['password']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            
         </div>
         <div class="has-feedback form-group <?php echo ($form['password_again']->hasError())?'has-error':''?>">
            <?php echo $form['password_again']->renderLabel() ?>
            <?php echo $form['password_again']->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
            <?php if ($form['password_again']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
           
        </div>
        
        <div class="has-feedback form-group <?php echo ($form['is_active']->hasError())?'has-error':''?>">
            <?php echo $form['is_active']->renderLabel() ?><br />
            <?php echo $form['is_active']->render(array('autocomplete' => 'off', 'class' => '')) ?>
            <?php if ($form['is_active']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
            <span class="help-inline">
                <?php echo $form['is_active']->renderError() ?>
            </span>
        </div>
        
        <div class="has-feedback form-group <?php echo ($form['groups_list']->hasError())?'has-error':''?>">
            <?php echo $form['groups_list']->renderLabel() ?>
            <?php echo $form['groups_list']->render(array('autocomplete' => 'off', 'class' => '')) ?>
            <?php if ($form['groups_list']->hasError()): ?>
                 <i class="fa fa-times-circle form-control-feedback"></i>
            <?php else: ?>
                 <i class="fa fa-check icon-ok form-control-feedback"></i> 
            <?php endif; ?>
        </div>
            
    </div>
    <?php include_partial('aUserAdmin/form_actions', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
  
  <?php include_js_call('aUserAdmin/jsForm') ?>
