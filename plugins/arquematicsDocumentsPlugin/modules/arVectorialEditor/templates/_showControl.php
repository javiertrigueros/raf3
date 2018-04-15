<?php $diagrams = isset($diagrams) ? $sf_data->getRaw('diagrams') : array(); ?>
<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>
<?php $documentsTypeEnabled = isset($documentsTypeEnabled) ? $sf_data->getRaw('documentsTypeEnabled') : array(); ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/arDocumentControl.css"); ?>



<?php use_stylesheet("/arquematicsPlugin/js/vendor/PhotoSwipe/dist/photoswipe.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/js/vendor/PhotoSwipe/dist/default-skin/default-skin.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/PhotoSwipe/dist/photoswipe.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/PhotoSwipe/dist/photoswipe-ui-default.js"); ?>


<?php slot('user-gallery')?>

<div class="modal-load modal-load-fix modal-load-opacity">
  <div class="spinner">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
  </div>
</div>

<div id="modal-full-screen" class="modal fade modal-fullscreen force-fullscreen"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="ar-container-photo-swipe">
          <div class="item-photo-swip cssload-piano">
            <div class="cssload-rect1"></div>
            <div class="cssload-rect2"></div>
            <div class="cssload-rect3"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <span title="<?php echo __('Close (Esc)',array(),'documents'); ?>" class="ar-button fa fa-times pswp__button--close"></span>
                
                <a title="<?php echo __('Download',array(),'documents'); ?>" id="cmd-download-image" download="" target="_blank" class="ar-button" href="">
                   <span  class="fa fa-download"></span> 
                </a>
                
                <a title="<?php echo __('Edit',array(),'documents'); ?>" id="cmd-edit-image" class="ar-button" href="">
                   <span  class="fa fa-pencil"></span> 
                </a>
               
                <button class="pswp__button pswp__button--fs" title="<?php echo __('Toggle fullscreen',array(),'documents'); ?>"></button>

                <button class="pswp__button pswp__button--zoom" title="<?php echo __('Zoom in/out',array(),'documents'); ?>"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="<?php echo __('Previous (arrow left)',array(),'documents'); ?>"></button>

            <button class="pswp__button pswp__button--arrow--right" title="<?php echo __('Next (arrow right)',array(),'documents'); ?>"></button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
<?php end_slot() ?>

<?php slot('docs-svg-enabled')?>
      <?php foreach ($documentsTypeEnabled as $documentType): ?>
            <li class="ar-icon-modal-wall">
                <?php if ($documentType['name'] == 'note'): ?>
                <a class="ar-icon-modal-inner" href="<?php echo url_for('@laverna_doc').'#/note/add' ?>" >
                 <span class="<?php echo $documentType['class'] ?>"></span>
                 <span class="document-type-text"><?php echo __($documentType['name'], null, 'documents') ?></span>
                </a>
                <?php else: ?>
                 <a class="ar-icon-modal-inner" href="<?php echo url_for('@diagram_create?name='.$documentType['name']) ?>" >
                 <span class="<?php echo $documentType['class'] ?>"></span>
                 <span class="document-type-text"><?php echo __($documentType['name'], null, 'documents') ?></span>
                </a>
                <?php endif; ?>
           </li>    
       <?php endforeach; ?>
<?php end_slot() ?>