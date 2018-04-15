<?php $lavernaDocs = isset($lavernaDocs) ? $sf_data->getRaw('lavernaDocs') : array(); ?>
<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>

<?php use_stylesheet("/arquematicsDocumentsPlugin/css/arDocumentControl.css"); ?>


<?php use_javascript("/arquematicsDocumentsPlugin/js/arquematics/arquematics.svgdoc.js"); ?>

<?php include_js_call('arVectorialEditor/jsControl',  array(
    'hasContent' => $hasContent,
    'showTool' => $showTool)) ?>

<?php slot('docs-laverna')?>
    <li class="ar-icon-modal-wall">
        <a class="ar-icon-modal-inner" href="<?php echo url_for('@laverna_doc').'#/notes/share' ?>" >
            <span class="ar-icon-document ar-icon-big"></span>
            <span class="document-type-text"><?php echo __('Laverna Doc', null, 'documents') ?></span>
        </a>
    </li>    
<?php end_slot() ?>

<div id="document-control" class="<?php echo (!$hasContent)?'hide':'' ?>">
  <div id="document-preview-container" class="document-preview-container control-border">
    <?php if ($hasContent): ?>
        <?php foreach ($lavernaDocs as $lavernaDoc): ?>
            <?php if ($lavernaDoc->isNoteType()): ?>
                <?php include_partial('arLaverna/notePreview',array('document' => $lavernaDoc)); ?> 
            <?php else: ?>
                <?php include_partial('arVectorialEditor/vectorialDocPreview',array( 'document' => $lavernaDoc)); ?>
            <?php endif; ?>
         <?php endforeach; ?>
    <?php endif; ?>
  </div>
 </div>  
