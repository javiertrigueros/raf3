<?php use_helper('a') ?>

<?php $page = aTools::getCurrentPage() ?>
<?php $pageEdit = ($page && $page->userHasPrivilege('edit')) || empty($page) ?>
<?php $cmsAdmin = $sf_user->hasCredential('cms_admin') ?>
<?php //$pageSettings = $page && (!$page->admin) && ($cmsAdmin || $pageEdit) ?> 
<?php //$maxPageLevels = (sfConfig::get('app_a_max_page_levels'))? sfConfig::get('app_a_max_page_levels') : 0; ?><?php // Your Site Tree can only get so deep ?>
<?php //$maxChildPages = (sfConfig::get('app_a_max_children_per_page'))? sfConfig::get('app_a_max_children_per_page') : 0; ?><?php // Your Site Tree can only get so wide ?>

<?php $root = isset($root) ? $sf_data->getRaw('root') : false?>
<?php $aUserProfile =  $sf_user->getProfile(); ?>

<?php // Remove the Add Page Button if we have reached our max depth, max peers, or if it is an engine page, ?>
<?php // or we don't have the privs in the first place ?>

<?php //$addPage = $page && (!(($maxPageLevels && ($page->getLevel() == $maxPageLevels)) || ($maxChildPages && (count($page->getChildren()) == $maxChildPages)) || strlen($page->getEngine()))) && $page->userHasPrivilege('manage') ?>

<?php $countNotModeratedComments = aTools::countNotModerated() ?>

<?php //$aboutPage = aPageTable::retrieveBySlug('/about') ?>

<?php if ($sf_user->isAuthenticated() ): ?> 
  
                                             
<div id="wpadminbar" class="nojq nojs admin-bar" role="navigation">

    <div class="quicklinks" id="wp-toolbar" role="navigation"  tabindex="0">
        <ul id="wp-admin-bar-root-default" class="ab-top-menu">
		<li id="wp-admin-bar-wp-logo" class="menupop">
                    <a class="ab-item"  aria-haspopup="true" href="<?php echo $root->getUrl() ?>" title="<?php echo __('About', null, 'arquematics') ?>">
                        <span class="ab-icon"></span>
                    </a>
                    <div class="ab-sub-wrapper">
                        <ul id="wp-admin-bar-wp-logo-default" class="ab-submenu">
                            <li id="wp-admin-bar-about" class="cms-admin-about">
                                <a class="ab-item cms-admin-about"  href="#">
                                    <?php echo __('About', null, 'arquematics') ?>
                                </a>
                            </li>
                        </ul>
                       
                    </div>
                </li>
		<li id="wp-admin-bar-site-name" class="menupop">
                    
                    <a class="ab-item" aria-haspopup="true" href="<?php echo url_for('@wall') ?>"><?php echo sfConfig::get('app_a_title_simple') ?></a>
                    <?php /*
                    <div class="ab-sub-wrapper">
                        <ul id="wp-admin-bar-site-name-default" class="ab-submenu">
                            <li id="wp-admin-bar-dashboard"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/">Escritorio</a></li>
                        </ul>
                        <ul id="wp-admin-bar-appearance" class="ab-submenu">
                            <li id="wp-admin-bar-themes"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/themes.php">Temas</a></li>
                            <li id="wp-admin-bar-customize" class="hide-if-no-customize"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/customize.php?url=http%3A%2F%2Fwww.arquematics.com%2F">Personalizar</a></li>
                            <li id="wp-admin-bar-widgets"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/widgets.php">Widgets</a></li>
                            <li id="wp-admin-bar-menus"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/nav-menus.php">Menús</a></li>
                            <li id="wp-admin-bar-background"><a class="ab-item"  href="http://www.arquematics.com/wp-admin/themes.php?page=custom-background">Fondo</a></li>
                        </ul>
                    </div>
                     * 
                     */ ?>
                </li>
                <?php if ($countNotModeratedComments > 0): ?>
                    <li id="wp-admin-bar-comments">
                    <?php if ($countNotModeratedComments == 0): ?>
                        <a title="<?php echo __('One comment is awaiting moderation', null, 'blog') ?>" href="<?php echo url_for('@ar_comment_admin') ?>" class="ab-item">
                            <span class="ab-icon"></span>
                            <span class="ab-label awaiting-mod pending-count count-1" id="ab-awaiting-mod"><?php echo $countNotModeratedComments ?></span>
                        </a>
                    <?php else: ?>
                        <a title="<?php echo __('%comments% comments are awaiting moderation', array('%comments%' => $countNotModeratedComments), 'blog') ?>" href="<?php echo url_for('@ar_comment_admin') ?>" class="ab-item">
                            <span class="ab-icon"></span>
                            <span class="ab-label awaiting-mod pending-count count-1" id="ab-awaiting-mod"><?php echo $countNotModeratedComments ?></span>
                        </a>
                    <?php endif; ?>
                    </li>
                <?php endif; ?>
                    
                <?php if ($cmsAdmin): ?>
                    
		<li id="wp-admin-bar-new-content" class="admin-bar-new-content menupop">
                    <a class="ab-item"  aria-haspopup="true" href="#">
                        <span class="ab-icon"></span>
                        <span class="ab-label"><?php echo __('New',null,'apostrophe') ?></span>
                    </a>
                    <div class="ab-sub-wrapper">
                        <ul id="wp-admin-bar-new-content-default" class="ab-submenu">
                            
                            <li id="wp-admin-bar-new-user">
                                <a class="ab-item" href="<?php echo url_for('aUserAdmin/index') ?>" id="a-create-user-button">
                                    <?php echo __('Users', null, 'apostrophe') ?>
                                </a>
                            </li>
                            
                            <li id="admin-bar-new-page">
                                <a href="<?php echo url_for('ar_page_admin') ?>" class="ab-item" id="cmd-create-page-button">
                                    <?php echo __("Page", null, 'apostrophe') ?>
                                </a>
                            </li>
                            
                            
                            <li id="wp-admin-bar-new-media">
                                <a class="ab-item"  href="<?php echo url_for('aMedia/index') ?>">
                                    <?php echo __('Media', null, 'apostrophe') ?>
                                </a>
                            </li>
                            
                           
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                <?php /*	
                <li>
                    <a href="/#page-settings" onclick="return false;" class="a-btn icon alt no-bg a-page-settings" id="a-page-settings-button"><span class="icon"></span><?php echo a_('Page Settings') ?></a>
                    <div id="a-page-settings" class="a-page-settings-menu dropshadow"></div>
                </li>	*/ ?>			
                
                
        </ul>
        <ul id="wp-admin-bar-top-secondary" class="ab-top-secondary ab-top-menu">
           
           
            <li id="wp-admin-bar-my-account" class="menupop with-avatar">
                <a class="ab-item"  aria-haspopup="true" href="<?php echo url_for('@user_profile?username='.$sf_user->getUsername())  ?>">
                    <?php echo __('Hello, %username%',array('%username%' => $sf_user->getUsername()),'profile') ?>
                    <?php include_partial('arProfile/imageMini', 
                                        array(
                                            'image' => $aUserProfile->getProfileImage(),
                                            'class' => 'avatar avatar-16 photo'
                                        )); ?>
                </a>
                <div class="ab-sub-wrapper">
                    <ul id="wp-admin-bar-user-actions" class="ab-submenu">
                        <li id="wp-admin-bar-user-info">
                            <a class="ab-item" tabindex="-1" href="<?php echo url_for('@user_profile?username='.$sf_user->getUsername())  ?>">
                                <?php /*include_partial('arProfile/imageSmall', 
                                        array(
                                            'image' => $aUserProfile->getProfileImage(),
                                            'class' => 'avatar avatar-64 photo'
                                        )); */?>
                                <span class='display-name'>
                                    <?php echo $aUserProfile->getFirstLast() ?>
                                </span>
                            </a>
                        </li>
                        <?php if ($cmsAdmin): ?>
                            <li id="cmd-admin-cms">
                                <a class="ab-item" id="admin-cms" href="#">
                                    <?php echo __('Admin CMS', null, 'wall'); ?>
                                </a>
                            </li>
                            <li id="cmd-edit-page">
                                <a class="ab-item" id="edit-page" href="#">
                                    <?php echo __('Edit page', null, 'apostrophe'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li id="wp-admin-bar-edit-profile"><a class="ab-item"  href="<?php echo url_for('@user_profile?username='.$sf_user->getUsername())  ?>"><?php echo __('Edit my profile', null, 'profile') ?></a></li>
                        <li class="a-login-logout">
                            <a class="ab-item" id="logout-cms" href="<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>">
                                <?php echo __('Log Out', null, 'apostrophe') ?>
                            </a>
                        </li>
                    </ul>
               </div>
            </li>
        </ul>
  </div>
</div>

<?php if ($cmsAdmin): ?>
    <?php include_partial('arMenuAdmin/sidrAdminMenu') ?>
    <?php include_js_call('ar/jsAdminPage'); ?>
<?php endif; ?>

<?php include_js_call('ar/jsAbout'); ?>


<?php endif ?>

<?php // Bring in various editing utilities both for potential editors ?>
<?php // (people whose credentials suggest they might be an editor for ?>
<?php // some kind of content that might conceivably be inline here) ?>
<?php // and for actual editors (people who have privileges on this ?>
<?php // specific page, one way or another). Checking for the latter allows ?>
<?php // fancy overrides of privileges at the project level without ?>
<?php // overrides of isPotentialEditor ?>

<?php // All real editors need the history browser, while real managers need ?>
<?php // access to the overlay and page settings features ?>

<?php if (aTools::isPotentialEditor() || $pageEdit): ?>
	<?php include_partial('ar/historyBrowser') ?>
<?php endif ?>

<?php /*if (aTools::isPotentialEditor() || $pageSettings): ?>
	<div class="a-page-overlay"></div>
	<?php if ($page): ?>
		<?php a_js_call('apostrophe.enablePageSettingsButtons(?)', array('aPageSettingsURL' => a_url('a', 'settings') . '?' . http_build_query(array('id' => $page->id)), 'aPageSettingsCreateURL' => a_url('a', 'settings') . '?' . http_build_query(array('new' => 1, 'parent' => $page->slug)))) ?>
	<?php endif ?>	
<?php endif */?>
