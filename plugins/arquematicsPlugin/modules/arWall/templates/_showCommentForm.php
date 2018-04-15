<div class="commentupdate" style='display:none' id='commentbox<?php echo $message->getId();?>'>
    <div class="stcommentimg">
        <?php include_partial('arProfile/imageSmall', 
                    array('image' => $profileImage)) ?>
    </div> 
    <div class="stcommenttext" > 


<form id="message_comment_form<?php echo $message->getId() ?>" method="post" action="<?php echo url_for('wall_comment_send', $message) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <textarea style='width:95%' name="comment" class="comment" maxlength="200"  id="ctextarea<?php echo $message->getId();?>" ></textarea>
        <button data-loading-text="<?php echo __("send...",array(),'wall') ?>" id="<?php echo $message->getId();?>"  class="comment_button btn btn-primary" style="clear:both;margin-top:5px"><?php echo __("Comment",array(),'wall') ?></button> 
 </form>

    </div>
</div>