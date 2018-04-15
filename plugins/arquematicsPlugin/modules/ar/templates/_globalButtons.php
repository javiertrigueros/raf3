<?php // P'unks: please do not add any complicated conditionals and calls here, this partial ?>
<?php // should receive clean and simple boolean parameters from the globalTools partial. ?>
<?php // That keeps this simple to override. -Tom ?>

<?php // The usual buttons: blog, events, media, categories, tags, users, reorganize... ?>

<?php // The normal way: output all global buttons the user has access to, ?>
<?php // in the order specified by app_a_global_button_order if any. Note that if ?>
<?php // you do specify an order you must specify all of the buttons ?>

<?php include_partial('ar/globalProjectUsers') ?>
<?php $buttons = aTools::getGlobalButtons() ?>
<?php foreach ($buttons as $button): ?>
	<li class="a-global-toolbar-<?php echo aTools::slugify($button->getLabel()) ?>">
            <?php echo link_to('<i class="'.aTools::getIconTranslate($button->getLabel()).'"></i> '.__($button->getLabel(), null, 'apostrophe'), $button->getLink(), array('class' => 'ab-item')) ?>
	</li>
<?php endforeach ?>
<?php include_partial('ar/globalProjectButtons') ?>