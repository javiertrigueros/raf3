<?php if ($field->isPartial()): ?>
  <?php include_partial('aUserAdmin/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('aUserAdmin', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
      <?php echo $name ?>
      <?php echo $form[$name]->renderLabel($label) ?>
      <?php //echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
      <?php echo $form[$name]->render(array('autocomplete' => 'off', 'class' => 'form-control')) ?>
      
      <div class="a-form-error">
    	<?php echo $form[$name]->renderError() ?>
      </div>

      <?php if ($help || $help = $form[$name]->renderHelp()): ?>
       <div class="a-help">
            <?php echo __($help, array(), 'apostrophe') ?>
       </div>
      <?php endif; ?>

<?php endif; ?>
