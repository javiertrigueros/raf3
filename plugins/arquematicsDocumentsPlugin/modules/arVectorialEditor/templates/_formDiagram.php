<?php if ($arDiagram): ?>
    <form class="form-image title-vectorial" id="form-diagram" name="form-diagram" action="<?php echo url_for('@diagram_update?guid='.$arDiagram->getGuid().'&name='.$documentType['name'])?>" enctype="multipart/form-data" method="post">
        <?php echo $form->renderHiddenFields() ?>
        <div class="input-group diagram-title-group">
           <?php echo $form['title']->render(array('placeholder' => __('Title', null, 'diagram-editor'), 'autocomplete'=>'off','class' => 'form-control')) ?>  
        </div>
    </form>
<?php else: ?>
    <form class="form-image title-vectorial" id="form-diagram" name="form-diagram" action="<?php echo url_for('@diagram_create?name='.$documentType['name'])?>" enctype="multipart/form-data" method="post">
        <?php echo $form->renderHiddenFields() ?>
        <div class="input-group diagram-title-group">
            <?php echo $form['title']->render(array('placeholder' => __('Title', null, 'diagram-editor'),'autocomplete'=>'off','class' => 'form-control')) ?>    
        </div>
    </form>
<?php endif; ?>