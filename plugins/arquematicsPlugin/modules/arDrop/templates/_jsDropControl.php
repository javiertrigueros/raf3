<?php $imageSizes = $sf_data->getRaw('imageSizes'); ?>
<?php $hasSessionFiles = $sf_data->getRaw('hasSessionFiles'); ?>
<?php $culture = $sf_data->getRaw('culture'); ?>
<?php $extensionsAllowed = $sf_data->getRaw('extensionsAllowed'); ?>

<script id="nofiles-template" type="text/x-jquery-tmpl">
<div class="dz-default dz-message needsclick default-message-nofiles">
    <div class="wall-default-drop-inner">
        <div class="dz-image dropzone-main-icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 215 150">
                        <g>
                            <path 
                                style="fill:#555555;stroke:none"
                                d="M 67.816835,45.990126 C 56.208749,43.91287 44.892421,44.036009 33.795239,48.392802 26.836827,51.124725 20.068936,55.606009 15.220858,61.145091 -2.4053669,81.283467 -2.6252377,113.35129 18.061827,131.92888 c 7.463675,6.70288 16.595238,10.83698 26.520747,12.73149 6.324615,1.20747 12.742167,1.34854 19.085286,0.1586 6.46826,-1.21226 12.609575,-3.07009 18.255504,-6.42791 7.283938,-4.33097 12.856012,-9.69408 17.370931,-16.68468 5.344705,-8.27459 7.516275,-17.44661 7.522925,-27.098445 3.27852,3.149 6.9404,8.049035 11.61712,9.215075 6.94621,1.7319 10.80891,-5.125603 8.41578,-10.809106 C 98.455845,65.823054 115.10625,81.886623 98.519266,65.915457 72.123062,92.32423 98.455845,66.228766 72.22961,92.216898 c -4.626623,4.584615 0.635457,13.451192 8.03416,11.606112 4.676726,-1.16604 8.338611,-6.066075 11.617132,-9.215075 -0.612389,7.449685 -2.701813,14.565415 -7.293069,20.722345 -3.746525,5.02516 -9.156789,9.47489 -15.111408,11.88347 -5.729818,2.31771 -11.198333,3.49968 -17.425695,3.22869 C 33.271887,129.62471 16.428791,111.86725 17.285803,93.81092 17.90342,80.797289 26.921798,69.061746 38.77401,63.333131 42.955346,61.312145 47.333926,59.776061 52.05073,59.57075 62.160371,59.130561 68.709612,63.5441 77.774386,66.712469 78.463115,58.609633 81.704298,50.351223 86.367743,43.599086 104.48051,17.374163 141.25287,7.8734502 169.05184,26.08751 c 5.99278,3.926327 11.32421,8.709442 15.63666,14.323523 4.27344,5.562834 7.49139,11.63735 9.35262,18.331302 8.66389,31.153657 -12.34569,63.015055 -44.90436,70.412925 -6.95202,1.57969 -14.52972,1.97182 -21.57467,0.67667 -3.96642,-0.72847 -9.25222,-3.46461 -13.24353,-2.94337 -3.52083,0.46067 -6.62693,1.6399 -7.77423,7.2608 -0.39702,3.75552 5.05582,7.46084 7.74103,8.25944 9.96336,2.96171 21.17554,4.28794 31.53222,2.73775 29.02291,-4.34533 53.54335,-22.19204 62.33005,-49.7416 C 219.46106,59.930761 201.35742,21.488345 165.73266,6.8350217 136.0011,-5.3942536 98.748285,3.3978224 78.6075,27.658822 74.04711,33.151917 69.780296,39.150079 67.816835,45.990126 z" />
                        </g>
                 </svg>  
        </div>
        <div class="default-message">
          <?php echo __("Click here or drop files to upload",array(),'wall') ?>
        </div>
    </div>  
</div>
</script>  
<!-- The template to display files available -->
<script id="preview-file-drop-template" type="text/x-jquery-tmpl">
     <div class="dropzone-file dz-preview dz-file-preview">
      <span data-url='' class="icon-remove-file cmd-remove-file fa fa-times-circle"></span>
      
      <div class="dz-image"><img data-dz-thumbnail /></div>

        <div class="dz-details">
          <div class="dz-size"><span data-dz-size></span></div>
          <div class="dz-filename"><span data-dz-name></span></div>
        </div>
        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>

        <div class="dz-success-mark">

          <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
            <title>Check</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
              <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
            </g>
          </svg>
        </div>
        <div class="dz-error-mark">

          <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
            <title>error</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                    <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                </g>
            </g>
          </svg>
        </div>

    </div>
</script>

<script type="text/javascript">

  Dropzone.autoDiscover = false;

  $(document).ready(function()
   {
       arquematics.dropzone.init({
           extensions: <?php echo $extensionsAllowed ; ?>,
           culture: '<?php echo $culture ; ?>',
           hasSessionFiles: <?php echo $hasSessionFiles?'true':'false' ; ?>,
           BYTES_PER_CHUNK: <?php echo sfConfig::get('app_arquematics_plugin_bytes_per_chunk'); ?>,
           image_sizes: <?php echo $imageSizes; ?>
       });
        
       arquematics.wall.subscribeTool(arquematics.dropzone);
  });
     
</script>