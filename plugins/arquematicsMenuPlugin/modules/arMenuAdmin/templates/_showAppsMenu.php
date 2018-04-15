<?php $isCMSAdmin = isset($isCMSAdmin) ? $sf_data->getRaw('isCMSAdmin') : false; ?> 
<?php $documentsTypeEnabled = isset($documentsTypeEnabled) ? $sf_data->getRaw('documentsTypeEnabled') : array(); ?> 
<?php $aUserProfile = $sf_user->getGuardUser()->getProfile() ?>
<div id="main-menu" role="navigation">
    <div id="main-menu-inner">
			<div class="menu-content top" id="menu-content-demo">
				<div>
					<div class="text-bg">
                        <span class="text-slim"><?php echo __('Welcome', null, 'arquematics') ?>,</span> 
                        <span class="text-semibold"><?php echo $aUserProfile->getFirstLast() ?></span>
                    </div>
                                    
                    <?php include_partial('arProfile/imageWallProfile', 
                                                        array(
                                                            'image' => $aUserProfile->getProfileImage(),
                                                            'class' => 'app-menu'
                                                        )); ?> 

					<div class="btn-group">
						<a title="<?php echo __('My Profile', null, 'arquematics') ?>" href="<?php echo url_for('@user_profile?username='.$aUserProfile->getUsername())  ?>" class="btn btn-xs btn-primary btn-outline dark">
                                                    <i class="fa fa-user"></i>
                                                </a>
						<a title="<?php echo __('Config', null, 'arquematics') ?>" href="<?php echo url_for('@user_profile_configure') ?>" class="btn btn-xs btn-primary btn-outline dark">
                                                    <i class="fa fa-cog"></i>
                                                </a>
						<a title="<?php echo __('Log Out', null, 'arquematics') ?>" href="<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>" class="btn btn-xs btn-danger btn-outline dark">
                                                    <i class="fa fa-power-off"></i>
                                                </a>
					</div>
					<a href="#" class="close menu-content-close">&times;</a>
				</div>
			</div>
			<ul class="navigation">
                                 <?php foreach ($documentsTypeEnabled as $documentType): ?>
                                    <li>
                                        <a title="<?php echo __($documentType['name'], null, 'documents') ?>" href="<?php echo url_for('@laverna_doc').'#/'.$documentType['name'] ?>" >
                                            <i class="<?php echo $documentType['classInverse'] ?>"></i>
                                            <span class="mm-text"><?php echo __($documentType['name'], null, 'documents') ?></span>
                                        </a>
                                    </li>    
                                <?php endforeach; ?>
			</ul> <!-- / .navigation -->
			<div class="menu-content-close menu-content">
				<a href="#" class="btn btn-primary btn-block btn-outline dark">
                                    <?php echo __('Close', null, 'arquematics') ?>
                                </a>
			</div>
		</div> <!-- / #main-menu-inner -->
</div> 
