<?php $link = isset($link) ? $sf_data->getRaw('link') : false; ?>
<?php $preview = isset($preview) ? $sf_data->getRaw('preview') : false; ?>
<?php $oembedData = $link->getOembed() ?>
<div data-url="<?php echo $link->getUrl() ?>"
     data-provider="<?php echo $oembedData['provider'] ?>"   
     data-oembed_html="<?php echo $oembedData['oembed_html'] ?>"
     data-link-id="<?php echo $link->getId() ?>"
     id="link-<?php echo $link->getId() ?>" class="wall-link-item">
   
   <?php if ($preview): ?>
        <span data-link-id="<?php echo $link->getId(); ?>" id="remove-link-<?php echo $link->getId(); ?>" class="icon-remove-link cmd-remove-link fa fa-times-circle"></span>
   <?php endif; ?>
   <div class="wall-link-dinamic hide col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
   </div>
    
   <div class="wall-link-video-static">
        <div class="cmd-wall-link-image wall-link-divider wall-link-video-image col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <span class="icon-play-link cmd-play-link fa fa-play-circle-o"></span>
            <?php include_partial('arLink/imageExternalSmall', 
                array('arWallLink' => $link,
                    'class' => 'video-image ')) ?>
        </div>

        <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <p>
                <a href='<?php echo $link->getUrl() ?>' target='_blank'><?php echo $oembedData['title'] ?> - <?php echo $oembedData['provider'] ?></a>
            </p>
            <p><?php echo $oembedData['description'] ?></p>
        </div>
   </div>
    
</div>