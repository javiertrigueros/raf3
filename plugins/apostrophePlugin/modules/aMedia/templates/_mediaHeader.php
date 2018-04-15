<?php use_helper('a') ?>
<?php $page = aTools::getCurrentPage() ?>
<?php if ($page->admin): ?>

            <?php if (aMediaTools::userHasUploadPrivilege() && ($uploadAllowed || $embedAllowed)): ?>
                <?php // This is important because sometimes you are selecting specific media types ?>
                <?php $typeLabel = aMediaTools::getBestTypeLabel() ?>
                <li>
                    <a href="<?php echo a_url('aMedia', 'resume', array('add' => 1)) ?>" id="a-media-add-button" class="a-btn icon big a-add">
                        <span class="icon"></span><?php echo a_('Add  ' . $typeLabel) ?>
                    </a>
                </li>
            <?php endif ?>

	<?php a_js_call('apostrophe.clickOnce(?)', '#a-save-media-selection,.a-media-select-video,.a-select-cancel') ?>

	<?php if (aMediaTools::isSelecting()): ?>
		<?php a_js_call('apostrophe.mediaClearSelectingOnNavAway(?)', a_url('aMedia', 'clearSelecting')) ?>	
	<?php endif ?>

<?php endif ?>
