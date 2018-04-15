<?php use_helper('I18N') ?>
      
        <div id="waiting-modal" class="modal fade modal-fullscreen force-fullscreen"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"></div>
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

	<!-- Page background -->
	<div id="page-signin-bg">
		<!-- Background overlay -->
		<div class="overlay"></div>
		<!-- Replace this with your bg image -->
		<img src="/arquematicsPlugin/assets/images/pixel-admin/signin-bg-1.jpg" alt="">
	</div>
	<!-- / Page background -->

	<!-- Container -->
	<div id="login" class="signin-container">

		<!-- Left side -->
		<div class="signin-info">
			<a href="/" class="logo">
				<img src="/arquematicsPlugin/assets/images/pixel-admin/main-navbar-logo-login.png" alt="" style="margin-top: -5px;">&nbsp;
                                <?php echo sfConfig::get('app_a_title_simple'); ?>
			</a> <!-- / .logo -->
			<div class="slogan">
                                <?php echo sfConfig::get('app_a_title_slogan'.'_'.$culture); ?>
			</div> <!-- / .slogan -->
			
                        <?php include_partial('arAuth/listInfoLogin'); ?>
		</div>
		<!-- / Left side -->

		<!-- Right side -->
		<div class="signin-form">

			<!-- Form -->
			<form id="login-form" action="<?php echo url_for('@sf_guard_signin'); ?>">
	
        <?php if (sfConfig::get('app_arquematics_encrypt')): ?>
        <div class="modal fade modal-fullscreen force-fullscreen" id="private-key-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="cmd-private-key-cancel-extra" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body ar-modal-login">
                        <div class="ar-modal-login-content panel form-horizontal">
                            <div class="panel-heading">
				<span class="panel-title"><?php echo __('Private Key', array(),'profile'); ?></span>
                            </div>
                            <div class="panel-body">
                                <?php echo $form['private_key']->render(array('class' => 'form-control ar-control-private-key')) ?>
                            </div>
                            <div class="panel-footer text-right">
				<button id="cmd-private-key-cancel" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', array(), 'blog') ?></button>
                                <input id="cmd-private-key-send" type="submit" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>"  class="btn btn-primary" value="<?php echo __('Accept', array(), 'blog') ?>" />
                            </div>
                        </div>
                    </div>
                  <div class="modal-footer"></div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php endif; ?>
      
                                <?php echo $form->renderHiddenFields() ?>
                                <div class="signin-text">
					<span><?php echo __('Sign In to your account', array(), 'arquematics'); ?></span>
				</div> <!-- / .signin-text -->

				<div class="form-group w-icon">
                                        <?php echo $form['username']->render(array('class' => 'form-control input-lg', 'placeholder' => __('Username or email', array(), 'arquematics') )); ?>
					<span class="fa fa-user signin-form-icon"></span>
				</div> <!-- / Username -->

				<div class="form-group w-icon">
                                        <?php echo $form['password']->render(array('class' => 'form-control input-lg', 'placeholder' => __('Password', array(), 'arquematics'))); ?>
					<span class="fa fa-lock signin-form-icon"></span>
				</div> <!-- / Password -->

				<div class="form-actions">
					<input id="cmd-send-login" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>" type="submit" value="<?php echo __('Signin', null, 'sf_guard') ?>" class="btn btn-primary signin-btn bg-primary">
					<a href="#" class="cmd-forgot-password forgot-password" id="forgot-password-link"><?php echo __('Forgot your password?', array(), 'arquematics'); ?></a>
				</div> <!-- / .form-actions -->
			</form>
			<!-- / Form -->
                        
                        <div id="password-reset-form" class="password-reset-form" style="display: none;">
				<div class="header">
					<div class="signin-text">
						<span><?php echo __('Password reset', array(), 'arquematics') ?></span>
						<div class="close cmd-close-password-reset">×</div>
					</div> <!-- / .signin-text -->
				</div> <!-- / .header -->
				
				<!-- Form -->
                                <form id="password-reset-forgot-form" action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post">
			
                                        <?php echo $formRegister->renderHiddenFields() ?>
                                    
                                        <div class="form-group w-icon">
						<?php echo $formRegister['email_address']->render(array('autocomplete' => 'off','class' => 'form-control input-lg', 'placeholder' => __('Username or email', array(), 'arquematics') )); ?>
						<span class="fa fa-envelope signin-form-icon"></span>
					</div> <!-- / Email -->

					<div class="form-actions">
						<input type="submit" class="cmd-password-reset signin-btn bg-primary" value="<?php echo __('SEND PASSWORD RESET LINK', array(), 'arquematics'); ?>">
					</div> <!-- / .form-actions -->
				</form>
				<!-- / Form -->
			</div>
                        <?php if (sfConfig::get('app_facebook_enable')): ?>
			<!-- "Sign In with" block -->
			<div class="signin-with">
				<!-- Facebook -->
                                <?php echo link_to(__('Sign In with %net%', array('%net%' => '<span>Facebook</span>'), 'arquematics'), '@facebook_connect', array('class' => 'signin-with-btn', 'style' => 'background:#4f6faa;background:rgba(79, 111, 170, .8);')) ?>
			</div>
			<!-- / "Sign In with" block -->
                        <?php endif; ?>

		</div>
		<!-- Right side -->
	</div>
	<!-- / Container -->

	<div class="not-a-member">
            <?php echo __('Not a member?', null, 'arquematics') ?>
           
            <?php echo link_to(__('Sign up now', null, 'arquematics'), 'ar_register', array(), array('class' => 'cmd-register')) ?>
	</div>