<?php $sessionLinks = isset($sessionLinks) ? $sf_data->getRaw('sessionLinks') : array(); ?>
<script type="text/javascript">
     $(document).ready(function()
     {
         
        arquematics.link.init({
            sessionLinks: <?php echo json_encode($sessionLinks,JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>,
            embedlyAPI:             '<?php echo sfConfig::get('app_arquematics_plugin_embedlyAPI') ?>',
            cancel_url:             '<?php echo url_for('@wall_link_cancel?id=') ?>',
            send_url:               '<?php echo url_for('@wall_link_send') ?>',
            has_content:            <?php echo ($hasContent)?'true':'false' ?>,
            show_tool:              <?php echo ($showTool)?'true':'false' ?>
        });
        
        arquematics.wall.subscribeTool(arquematics.link);
     });
</script>

<!-- The template to display links available -->
<script id="template-link" type="text/x-jquery-tmpl">
    <div data-url="${url}"
     data-provider="${provider}"   
     data-oembed_html="${oembed}"
     data-link-id="${id}"
     id="link-${id}" class="wall-link-item">
   {{if preview}}
    <span data-link-id="${id}" id="remove-link-${id}" class="icon-remove-link cmd-remove-link fa fa-times-circle"></span>
   {{/if}}

   {{if has_thumb}}
   <div class="wall-link-static">
        <div class="wall-link-image-container wall-link-image col-xs-12 col-sm-12 col-md-3 col-lg-3">
           
        </div>

        <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <p>
                <a href='${url}' target='_blank'>${title} - ${provider}</a>
            </p>
            <p>${description}</p>
        </div>
   </div>
   {{else}}
    <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <p>
                <a href='${url}' target='_blank'>${title} - ${provider}</a>
            </p>
            <p>${description}</p>
    </div>
   {{/if}}
</div>
</script>

<!-- The template to display videos available -->
<script id="template-video" type="text/x-jquery-tmpl">

  {{if preview}}
   <span data-link-id="${id}" id="remove-link-${id}" class="icon-remove-link cmd-remove-link fa fa-times-circle"></span>
  {{/if}}
        
   <div class="wall-link-dinamic hide col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
   </div>
    
   <div class="wall-link-video-static">
        <div class="wall-link-image-container cmd-wall-link-image wall-link-divider wall-link-video-image col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <span class="icon-play-link cmd-play-link fa fa-play-circle-o"></span>
        </div>

        <div class="wall-link-divider wall-link-text col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <p>
                <a class="link-url" href='${url}' target='_blank'>
                  <span class="link-title">${title}</span> - <span class="link-provider">${provider}</span>
                </a>
            </p>
            <p class="link-description">
              ${description}
            </p>
        </div>
   </div>
</script>