<div id="link-preview-container" class="preview-container control-border col-xs-12 col-sm-12 col-md-12 col-lg-12">
 
 <?php if ($hasContent) : ?>
    <?php foreach($listLinks as $link): ?>
            <?php include_partial("arLink/wallLink", array(
                                        'preview' => true,
                                        'link' => $link)) ?>
    <?php endforeach; ?>
 <?php endif; ?>
</div>