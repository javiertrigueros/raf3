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


	<!-- Container -->
	<div id="login" class="signin-container">

		<!-- Left side -->
		<div class="signin-info">
			<a href="index.html" class="logo">
				<img src="/arquematicsPlugin/assets/images/pixel-admin/main-navbar-logo.png" alt="" style="margin-top: -5px;">&nbsp;
                                <?php echo sfConfig::get('app_a_title_simple') ?>
			</a> <!-- / .logo -->
			<div class="slogan">
                                <?php echo sfConfig::get('app_a_title_slogan'.'_'.$culture); ?>
			</div> <!-- / .slogan -->
			<ul>
				<li><i class="fa fa-sitemap signin-icon"></i> Flexible modular structure</li>
				<li><i class="fa fa-file-text-o signin-icon"></i> LESS &amp; SCSS source files</li>
				<li><i class="fa fa-outdent signin-icon"></i> RTL direction support</li>
				<li><i class="fa fa-heart signin-icon"></i> Crafted with love</li>
			</ul> <!-- / Info list -->
		</div>
		<!-- / Left side -->

		<!-- Right side -->
		<div class="signin-form">

			<!-- Form -->
			<form id="login-form" action="<?php echo url_for('@sf_guard_signin') ?>">
	
        <?php if (sfConfig::get('app_arquematics_encrypt')): ?>
        <div class="modal fade modal-fullscreen force-fullscreen" id="private-key-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      
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
                                        <?php echo $form['username']->render(array('autocomplete' => 'off', 'value' => $aUserProfile->getUsername(), 'class' => 'form-control input-lg', 'placeholder' => __('Username or email', array(), 'arquematics') )); ?>
					<span class="fa fa-user signin-form-icon"></span>
				</div> <!-- / Username -->

				<div class="form-group w-icon">
                                        <?php echo $form['password']->render(array('autocomplete' => 'off','class' => 'form-control input-lg', 'placeholder' => __('Password', array(), 'arquematics'))); ?>
					<span class="fa fa-lock signin-form-icon"></span>
				</div> <!-- / Password -->

				<div class="form-actions">
					<input id="cmd-send-login" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>" type="submit" value="<?php echo __('Signin', null, 'sf_guard') ?>" class="btn btn-primary signin-btn bg-primary">
				</div> <!-- / .form-actions -->
			</form>
			<!-- / Form -->

			<!-- "Sign In with" block -->
			<div class="signin-with">
				<!-- Facebook -->
                                <?php echo link_to(__('Sign In with %net%', array('%net%' => '<span>Facebook</span>'), 'arquematics'), '@facebook_connect', array('class' => 'signin-with-btn', 'style' => 'background:#4f6faa;background:rgba(79, 111, 170, .8);')) ?>
			</div>
			<!-- / "Sign In with" block -->

			<!-- Password reset form -->
			<div class="password-reset-form" id="password-reset-form">
				<div class="header">
					<div class="signin-text">
						<span>Password reset</span>
						<div class="close">&times;</div>
					</div> <!-- / .signin-text -->
				</div> <!-- / .header -->
				
				<!-- Form -->
				<form action="index.html" id="password-reset-form_id">
					<div class="form-group w-icon">
						<input type="text" name="password_reset_email" id="p_email_id" class="form-control input-lg" placeholder="Enter your email">
						<span class="fa fa-envelope signin-form-icon"></span>
					</div> <!-- / Email -->

					<div class="form-actions">
						<input type="submit" value="SEND PASSWORD RESET LINK" class="signin-btn bg-primary">
					</div> <!-- / .form-actions -->
				</form>
				<!-- / Form -->
			</div>
			<!-- / Password reset form -->
		</div>
		<!-- Right side -->
	</div>