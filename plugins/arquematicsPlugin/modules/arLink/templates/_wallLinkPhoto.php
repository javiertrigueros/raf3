<?php $link = isset($link) ? $sf_data->getRaw('link') : false; ?>
<?php $preview = isset($preview) ? $sf_data->getRaw('preview') : false; ?>
<?php $oembedData = $link->getOembed() ?>

<div data-url="<?php echo $link->getUrl() ?>"
     data-provider="<?php echo $oembedData['provider'] ?>"   
     data-oembed_html="<?php echo $oembedData['oembed_html'] ?>"
     data-link-id="<?php echo $link->getId() ?>"
     id="link-<?php echo $link->getId() ?>" class="wall-link-item">
   
  
</div>