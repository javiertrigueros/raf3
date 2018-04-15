<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $arProfileImage = isset($arProfileImage) ? $sf_data->getRaw('arProfileImage') : null; ?>
<?php $userUrl = isset($userUrl) ? $sf_data->getRaw('userUrl') : ''; ?>
<!-- The template to display form comment -->
<script id="comment-form-template" type="text/x-jquery-tmpl">
<div class="message-form-comments">
    <div class="comment-wall-icon">
        <a  href="<?php echo $userUrl ?>" >
            <?php include_partial('arProfile/imageSmall', 
                    array('class' => 'comment-avatar','image' => $arProfileImage)) ?>
        </a>
    </div>
    <div class="comment-wall-text">
        <form class="form-message-comment" id="message-comment-form-${id}" method="post" action="<?php echo url_for('@wall_comment_send?id=') ?>${id}">
            <?php echo $form->renderHiddenFields() ?>
            <?php echo $form['comment']->render(array('placeholder' => __("Write a comment...",array(),'wall'), 'rows' => '1', 'autocomplete'=>'off','class' => 'form-control')) ?>
            <button data-loading-text="<?php echo __("send...",array(),'wall') ?>"   class="cmd-comment-button btn btn-primary"><?php echo __("Comment",array(),'wall') ?></button> 
        </form>
    </div>
    <div style="clear:both"></div>
</div>
</script>