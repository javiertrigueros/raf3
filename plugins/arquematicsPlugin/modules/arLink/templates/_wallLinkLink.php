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
     data-content-enc="<?php echo $link->EncContent->getContent(); ?>"
     id="link-<?php echo $link->getId() ?>" class="content-data wall-link-item">
   
   <?php if ($preview): ?>
        <span data-link-id="<?php echo $link->getId(); ?>" id="remove-link-<?php echo $link->getId(); ?>" class="icon-remove-link cmd-remove-link fa fa-times-circle"></span>
   <?php endif; ?>

   <?php if ($link->getHasThumb()): ?>
   <div class="wall-link-static">
        <div class="wall-link-image col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a class="link-url" href="" target="_blank">
              <img class="link-image link-photo-image"  src=""  />
            </a>
        </div>

        <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <p>
                <a class="link-url" href="" target="_blank">
                  <span class="link-title"></span> - <span class="link-provider"></span>
                </a>
            </p>
            <p class="link-description"></p>
        </div>
   </div>
   <?php else: ?>
    <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <p>
                <a class="link-url" href='' target='_blank'>
                  <span class="link-title"></span> - <span class="link-provider"></span>
                </a>
            </p>
            <p class="link-description"></p>
    </div>
   <?php endif ?>
</div>