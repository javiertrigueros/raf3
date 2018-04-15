<?php $page = aTools::getCurrentPage() ?>
<?php $pageEdit = ($page && $page->userHasPrivilege('edit')) || empty($page) ?>
<?php $cmsAdmin = $sf_user->hasCredential('cms_admin') ?>
	
<?php //$buttons = aTools::getGlobalButtons() ?>

<?php $buttons = aTools::getGlobalButtonsByName(); ?>
<?php if ($cmsAdmin || count($buttons) || $pageEdit): ?>
    <div class="nav-collapse">
        <ul class="nav">
            <?php foreach ($buttons as $button): ?>
            <li class="navi">
		<?php echo link_to('<span class="icon"></span>'.__($button->getLabel(), null, 'apostrophe'), $button->getLink(), array('class' => 'a-btn icon alt no-bg ' . $button->getCssClass())) ?>
            </li>
            <?php endforeach ?>
           
        </ul>
    </div><!--/.nav-collapse -->
<?php endif ?>