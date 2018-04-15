<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>

<?php use_helper('arBlog') ?>

<div id="respond">
    <h3 id="reply-title">
        <?php echo __('Leave a Comment', array(), 'blog') ?>
        <small>
            <a style="display:none;" href="#respond" id="cancel-comment-reply-link" rel="nofollow"><?php echo __('Cancel reply', null, 'blog') ?></a>
        </small>
    </h3>
    
    <form autocomplete="off" id="form-comment" method="post" action="<?php echo url_for('ar_comment_create') ?>">
        <?php echo $form->renderHiddenFields() ?>
        <p class="comment-notes form-group">
            <?php echo __('Your email address will not be published.', null, 'blog') ?>
            <span class="required">
                <?php echo __('Required fields are marked *', null,'blog') ?>
            </span>
        </p>
        
        <p class="comment-form-author form-group">
            <label for="author">
                <?php echo __('Name', null, 'blog') ?>
                <span class="required">*</span>
                <span id="error-comment_author" class="error-info"></span>
            </label>
            
            <?php echo $form['comment_author']->render() ?>
        </p>
                    
        <p class="comment-form-email form-group">
            <label for="email">
                <?php echo __('e-mail', null, 'blog') ?>
                 <span class="required">*</span>
                 <span id="error-comment_author_email" class="error-info"></span>
            </label>
            
            <?php echo $form['comment_author_email']->render() ?>
        </p>
                    
        <p class="comment-form-url form-group">
            <label for="url">
                <?php echo __('Web', null, 'blog') ?>
                <span id="error-comment_author_url" class="error-info"></span>
            </label>
            
            <?php echo $form['comment_author_url']->render() ?>
        </p>
                    
        <p class="comment-form-comment form-group">
            <label for="comment">
                <?php echo __('Comentario', null, 'blog') ?>
                <span class="required">*</span>
                <span id="error-comment" class="error-info"></span>
            </label>
            
            <?php echo $form['comment']->render() ?>
        </p>
                    
        <p class="form-allowed-tags">
            <?php echo __('You can use these HTML tags and attributes:', null, 'blog') ?> 
            <code>
               <?php echo  getNoFilterHTMLtags() ?>
            </code>
        </p>
       
       <button id="cmd-comment-submit" class="btn btn-primary" data-loading-text="<?php echo __("send...",array(),'wall') ?>"><?php echo __('Submit',array(),'blog') ?></button>
    </form>
</div><!-- #respond -->