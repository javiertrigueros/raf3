<?php $file = isset($file) ? $sf_data->getRaw('file') : false ?>
<?php $isEncrypt = sfConfig::get('app_arquematics_encrypt', false); ?>
<div class="dropzone-file content-data dz-preview dz-processing dz-image-preview dz-success dz-complete"
     data-src="<?php echo $file->getMiniImageSrc(); ?>"
     data-content=""
     data-content-enc="<?php echo $file->EncContent->getContent(); ?>"
     data-document-type="<?php echo $file->getType() ?>"
     data-name="<?php echo $file->getName() ?>"
     data-id="<?php echo $file->getId() ?>"
     data-load-url="<?php echo url_for('@drop_file_view?slug='.$file->getSlug()) ?>">
     <span class="icon-remove-file cmd-remove-file fa fa-times-circle" 
            id="remove-file-<?php echo $file->getId() ?>" 
            data-url="<?php echo url_for('@drop_file_view?slug='.$file->getSlug()) ?>"
            data-id="<?php echo $file->getId() ?>">
          
     </span>

      <div class="dz-image">
          <img class="dropzone-file-image" data-dz-thumbnail="" alt="" src="" />
      </div>

      <div class="dz-details">
        <div class="dz-size">
            <span data-dz-size="">
                <strong><?php echo $file->getHumanFileSize(); ?></strong> 
            </span>
        </div>
        <div class="dz-filename">
            <span data-dz-name="" class="dropzone-file-name"></span>
        </div>
      </div>
    
      <div class="dz-progress">
          <span data-dz-uploadprogress="" class="dz-upload" style="width: 100%;"></span>
      </div>
    
      <div class="dz-error-message">
          <span data-dz-errormessage=""></span>
      </div>

      <div class="dz-success-mark">

        <svg xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 54 54" height="54px" width="54px">
          <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
          <title>Check</title>
          <desc>Created with Sketch.</desc>
          <defs/>
          <g sketch:type="MSPage" fill-rule="evenodd" fill="none" stroke-width="1" stroke="none" id="Page-1">
              <path sketch:type="MSShapeGroup" fill="#FFFFFF" fill-opacity="0.816519475" stroke="#747474" stroke-opacity="0.198794158" id="Oval-2" d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"/>
          </g>
        </svg>
      
      </div>
      <div class="dz-error-mark">

        <svg xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 54 54" height="54px" width="54px">
            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
            <title>error</title>
            <desc>Created with Sketch.</desc>
            <defs/>
            <g sketch:type="MSPage" fill-rule="evenodd" fill="none" stroke-width="1" stroke="none" id="Page-1">
                <g fill-opacity="0.816519475" fill="#FFFFFF" stroke-opacity="0.198794158" stroke="#747474" sketch:type="MSLayerGroup" id="Check-+-Oval-2">
                    <path sketch:type="MSShapeGroup" id="Oval-2" d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"/>
                </g>
            </g>
        </svg>

      </div>
</div>
