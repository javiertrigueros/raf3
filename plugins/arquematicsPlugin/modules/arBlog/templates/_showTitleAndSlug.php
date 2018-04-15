<?php $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : null; ?>

<div id="ui-title" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h3 id="blog-title" class="profile-head ui-control-edit control-text ui-control-text">
        <span class="glyphicon glyphicon-pencil"></span>
        <span class="status-text"><?php echo $a_blog_post->getTitle() ?></span>
        <?php if ($a_blog_post['status'] == 'draft'): ?>
            <span id="a-blog-item-status" class="a-blog-item-status">&ndash; <?php echo __('Draft',array(),'blog') ?></span>
        <?php endif ?>
    </h3>
    <div id="container-title-form" class="alert alert-warning alert-dismissable ui-control-text-form hide input-group">
        <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
        <form id="form-title" action="<?php echo url_for('ar_blog_update_title', $a_blog_post) ?>" method="post" class="controls-row ui-control-form">
        <?php echo $arBlogItemTitle->renderHiddenFields() ?>
        <?php echo $arBlogItemTitle['title']->render(array('class' => 'ui-control-text-input form-control')) ?>
        <?php if ($a_blog_post['status'] == 'draft'): ?>
            <span id="a-blog-item-status-input" class="a-blog-item-status control-item-status">&ndash; <?php echo __('Draft',array(),'blog') ?></span>
        <?php endif ?>
            <p class="controls-buttom">
                <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>&nbsp;
                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
            </p>
        </form>
    </div>
</div>

<div id="ui-slug" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div id="blog-slug" class="control-text ui-control-edit ui-control-text">
        <span class="glyphicon glyphicon-pencil"></span>
        <span><?php echo aTools::urlForPage($a_blog_post->findBestEngine()->getSlug()).'/' ?><?php echo date('Y/m/d/', strtotime($a_blog_post->getPublishedAt())) ?></span>
        <span class="status-text"><?php echo a_entities($a_blog_post->slug) ?></span>
    </div>
    <div id="container-slug-form" class="alert alert-warning alert-dismissable ui-control-text-form hide control-group">
        <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
        <form id="form-slug" action="<?php echo url_for('ar_blog_update_slug', $a_blog_post) ?>" method="post" class="controls-row ui-control-form">
        <?php echo $arBlogItemSlug->renderHiddenFields() ?>
        <span class="span5 control-item-url-base"><?php echo aTools::urlForPage($a_blog_post->findBestEngine()->getSlug()).'/' ?><?php echo date('Y/m/d/', strtotime($a_blog_post->getPublishedAt())) ?></span>
        <?php echo $arBlogItemSlug['slug']->render(array('class' => 'ui-control-text-input form-control')) ?>
       
            <p class="controls-buttom">
                <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>&nbsp;
                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
            </p>
        </form>
    </div>
</div>

<div id="ui-excerpt" class="ui-control col-xs-12 col-sm-12 col-md-12 col-lg-12">
    
    <p id="blog-excerpt" class="control-text ui-control-edit ui-control-text blog-excerpt">
        <span class="glyphicon glyphicon-pencil"></span>
        <?php if (sfConfig::get('app_arquematics_encrypt')): ?>
            <span class="status-text content-text" data-encrypt-text="<?php echo $a_blog_post->getEncryptExcerpt($authUser) ?>"></span>
        <?php else: ?>
            <span class="status-text content-text"><?php echo $a_blog_post->getExcerpt() ?></span>
        <?php endif; ?>
    </p>
    <div id="container-excerpt-form" class="alert alert-warning alert-dismissable ui-control-text-form hide control-group">
        <button type="button" class="close cancel" data-dismiss="alert" aria-hidden="true">&times;</button>
        <form id="form-excerpt" action="<?php echo url_for('ar_blog_update_excerpt', $a_blog_post) ?>" method="post" class="controls-row ui-control-form">
            <?php echo $arBlogItemExcerpt->renderHiddenFields() ?>
            <?php if (sfConfig::get('app_arquematics_encrypt')): ?>
                 <?php echo $arBlogItemExcerpt['excerpt']->render(array('class' => 'ui-control-text-input form-control control-crypt','data-encrypt-text' => $a_blog_post->getEncryptExcerpt($authUser))) ?>
            <?php else: ?>
                <?php echo $arBlogItemExcerpt['excerpt']->render() ?>
            <?php endif; ?>
            <p class="controls-buttom">
                <a data-loading-text="<?php echo __("send...",array(),'wall') ?>" class="btn btn-primary send" href="#"><?php echo __('Save',null,'profile'); ?></a>&nbsp;
                <a class="btn btn-default cancel" href="#"><?php echo __('cancel',null,'profile'); ?></a>
            </p>
        </form>
    </div>
</div>

<?php //include_partial('arBlog/meta', array('a_blog_post' => $a_blog_post)) ?>
<?php include_partial('arBlog/tags', array('aBlogItem' => $a_blog_post)) ?>
<?php include_partial('arBlog/addThis', array('aBlogItem' => $a_blog_post)) ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/blogitem/arquematics.fieldeditor.js"); ?>


<?php include_js_call('arBlog/jsShowTitleAndSlug') ?>

