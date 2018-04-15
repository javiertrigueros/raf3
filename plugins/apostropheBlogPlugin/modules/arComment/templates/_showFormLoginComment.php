<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>

<?php use_helper('arBlog') ?>

<div id="respond">
    <h3 id="reply-title">
        <?php echo __('Leave a Comment', array(), 'blog') ?>
        <small>
            <a style="display:none;" href="" id="cancel-comment-reply-link" rel="nofollow"><?php echo __('Cancel reply', array(),'blog') ?></a>
        </small>
    </h3>

    <form autocomplete="off" id="form-comment" method="post" action="<?php echo url_for('ar_comment_create') ?>">
        <?php echo $form->renderHiddenFields() ?>
        <p class="logged-in-as">
            <?php echo __('Logged in as %user%', array('%user%' => link_to($aUserProfile->username,'@a_blog_author?' . http_build_query(array('author' => $aUserProfile->username)))), 'blog') ?>.
            <?php echo link_to(__('Want to go out?', null, 'blog'), sfConfig::get('app_a_actions_logout', 'sf_guard_signout'), array()) ?>
        </p>
        <p class="comment-form-comment">
            <label for="comment form-group">
                <?php echo __('Comment', array(), 'blog') ?>
                <span class="required">*</span>
                <span id="error-comment" class="error-info"></span>
            </label>
            <?php echo $form['comment']->render() ?>
        </p>
        <p class="form-allowed-tags">
            <?php echo __('You can use these HTML tags and attributes:', array(),'blog') ?>
            <code><?php echo  getNoFilterHTMLtags() ?></code>
        </p>
        <button id="cmd-comment-submit" class="btn btn-primary" data-loading-text="<?php echo __("send...",array(),'wall') ?>"><?php echo __('Submit',array(),'blog') ?></button>
    </form>
</div>