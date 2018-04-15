<?php $hasMedia = isset($hasMedia) ? $sf_data->getRaw('hasMedia') : false; ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php $execJsScript = isset($execJsScript) ? $sf_data->getRaw('execJsScript') : false; ?>
<?php if ($hasMedia): ?>
    <a class="a-blog-item-media" href="<?php echo url_for('a_blog_post',$aBlogItem) ?>">
        <?php include_component('arMedia','showPicture',array('execJsScript' => $execJsScript ,'mediaItem' => $aBlogItem->getImage())); ?> 
    </a>


    <?php /* if ($sf_request->isXmlHttpRequest()): ?>
        <?php use_helper('a','ar') ?>

        <?php a_include_stylesheets() ?>
        <?php a_include_javascripts() ?>
        <?php a_include_js_calls() ?>
        <?php echo ar_get_js_calls() ?>
    <?php endif; */ ?>

<?php endif; ?>
