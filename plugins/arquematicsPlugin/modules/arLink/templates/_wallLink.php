<?php $link = isset($link) ? $sf_data->getRaw('link') : false; ?>
<?php $preview = isset($preview) ? $sf_data->getRaw('preview') : false; ?>

<div data-url="<?php echo $link->getUrl() ?>"
     data-description="<?php echo $link->getDescription() ?>"
     data-thumb="<?php echo $link->getThumb() ?>"
     data-title="<?php echo $link->getTitle() ?>"
     data-provider="<?php echo $link->getProvider() ?>"   
     data-oembed="<?php echo $link->getOembed() ?>"
     data-link-id="<?php echo $link->getId() ?>"
     data-content=""
     data-dec-content=""
     data-oembedtype="<?php echo $link->getOembedtype() ?>"
     data-content-enc="<?php echo $link->EncContent->getContent(); ?>"
     data-preview="<?php echo ($preview)?'true':'false' ?>"
     id="link-<?php echo $link->getId() ?>" class="content-data wall-link-item">
   

</div>