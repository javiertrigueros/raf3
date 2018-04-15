<?php $arDiagram = isset($arDiagram) ? $sf_data->getRaw('arDiagram') : false ?>
<?php $documentType = isset($documentType) ? $sf_data->getRaw('documentType') : array() ?>

<script type="text/javascript">
    
 var mindmaps = mindmaps || {};
    mindmaps.DEBUG = false;
    <?php // mindmaps.DIAGRAM_TYPE es un string ?>
    mindmaps.DIAGRAM_TYPE='<?php echo $documentType['name']; ?>';
    //mindmaps.WAIT_ICON='<?php echo sfConfig::get('app_arquematics_waint_icon'); ?>';
    
    <?php if ($arDiagram): ?>
           mindmaps.autoload = true;
           mindmaps.DATA='<?php echo $arDiagram->getContent(); ?>';
           <?php if (sfConfig::get('app_arquematics_encrypt', false)): ?>
                mindmaps.PASS = '<?php echo $arDiagram->EncContent->getContent() ?>';
           <?php else: ?>
                mindmaps.PASS = false;
           <?php endif ?>
    <?php else: ?>
           mindmaps.autoload = false;
           mindmaps.DATA= false;
           mindmaps.PASS = false;
    <?php endif ?>
        
      $(window).load(function()
     {
         removeEventLayerXY();

        // take car of old browsers
        createECMA5Shims();
        createHTML5Shims();

        //setupConsole();
        trackErrors();

        /*
        if (!mindmaps.DEBUG) {
            addUnloadHook();
        }*/
        
        // create a new app controller and go
        var appController = new mindmaps.ApplicationController();
        appController.go();
        //cierra los paneles al inicio
        $('.ui-dialog-titlebar-close').click();
     });
        
        
</script>

<script id="template-float-panel" type="text/x-jquery-tmpl">
<div class="ui-widget ui-dialog ui-corner-all ui-widget-content float-panel no-select">
  <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix">
    <span class="ui-dialog-title">${title}</span>
    <a class="ui-dialog-titlebar-close ui-corner-all" href="#" role="button">
      <span class="ui-icon"></span>
    </a>
  </div>
  <div class="ui-dialog-content ui-widget-content">
  </div>
</div>
</script>

<script id="template-notification" type="text/x-jquery-tmpl">
<div class="notification">
  {{if closeButton}}
  <a href="#" class="close-button">x</a>
  {{/if}}
  {{if title}}
  <h1 class="title">{{html title}}</h1>
  {{/if}}
  <div class="content">{{html content}}</div>
</div>
</script>

<script id="template-open-table-item" type="text/x-jquery-tmpl">
<tr>
  <td><a class="title" href="#">${title}</a></td>
  <td>${$item.format(dates.modified)}</td>
  <td><a class="delete" href="#">delete</a></td>
</tr>
</script>


<script id="template-navigator" type="text/x-jquery-tmpl">
<div id="navigator">
  <div class="active">
    <div id="navi-content">
      <div id="navi-canvas-wrapper">
        <canvas id="navi-canvas"></canvas>
        <div id="navi-canvas-overlay"></div>
      </div>
      <div id="navi-controls">
        <span id="navi-zoom-level"></span>
        <div class="button-zoom" id="button-navi-zoom-out"></div>
        <div id="navi-slider"></div>
        <div class="button-zoom" id="button-navi-zoom-in"></div>
      </div>
    </div>
  </div>
  <div class="inactive">
  </div>
</div>
</script>


<script id="template-inspector" type="text/x-jquery-tmpl">
<div id="inspector">
  <div id="inspector-content">
    <table id="inspector-table">
      <tr>
        <td><?php echo __('Font size',array(),'diagram-editor') ?>:&nbsp;</td>
        <td><div
            class="buttonset buttons-very-small buttons-less-padding">
            <button id="inspector-button-font-size-decrease">A-</button>
            <button id="inspector-button-font-size-increase">A+</button>
          </div></td>
      </tr>
      <tr>
        <td><?php echo __('Font style',array(),'diagram-editor') ?>:</td>
        <td><div
            class="font-styles buttonset buttons-very-small buttons-less-padding">
            <input type="checkbox" id="inspector-checkbox-font-bold" /> 
            <label
            for="inspector-checkbox-font-bold" id="inspector-label-font-bold">B</label>
              
            <input type="checkbox" id="inspector-checkbox-font-italic" /> 
            <label
            for="inspector-checkbox-font-italic" id="inspector-label-font-italic">I</label> 
            
            <input
            type="checkbox" id="inspector-checkbox-font-underline" /> 
            <label
            for="inspector-checkbox-font-underline" id="inspector-label-font-underline">U</label> 
            
            <input
            type="checkbox" id="inspector-checkbox-font-linethrough" />
             <label
            for="inspector-checkbox-font-linethrough" id="inspector-label-font-linethrough">S</label>
          </div>
        </td>
      </tr>
      <tr>
        <td><?php echo __('Font color',array(),'diagram-editor') ?>:</td>
        <td><input type="hidden" id="inspector-font-color-picker"
          class="colorpicker" /></td>
      </tr>
      <tr>
        <td><?php echo __('Branch color',array(),'diagram-editor') ?>:</td>
        <td><input type="hidden" id="inspector-branch-color-picker"
          class="colorpicker" />
          <button id="inspector-button-branch-color-children" title="" class="right buttons-small buttons-less-padding"><?php echo __('Inherit',array(),'diagram-editor') ?></button>
        </td>
      </tr>
    </table>
  </div>
</div>
</script>

<script id="template-export-map" type="text/x-jquery-tmpl">
<div id="export-map-dialog" title="Export mind map">
  <h2 class='image-description'>To download the map right-click the
    image and select "Save Image As"</h2>
  <div id="export-preview"></div>
</div>
</script>