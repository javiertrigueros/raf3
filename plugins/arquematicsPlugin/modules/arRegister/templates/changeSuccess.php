<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php $errors = $form->getErrors(); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

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
                                <?php echo sfConfig::get('app_a_title_simple') ?>
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
			<form id="change-password-form" autocomplete="off" action="<?php echo url_for('@ar_change?unique_key='.$forgotPassword->unique_key); ?>" method="POST">
	
                                <?php echo $form->renderHiddenFields() ?>
                                <div class="signin-text">
					<span>
                                            <?php echo __('Password reset', array(), 'arquematics'); ?>
                                        </span>
				</div> <!-- / .signin-text -->

				<div class="form-group w-icon <?php if (isset($errors['password'])): ?> has-error<?php endif; ?>">
                                        <?php echo $form['password']->render(array('class' => 'form-control input-lg', 'autocomplete' => 'off', 'placeholder' => __('Password', array(), 'arquematics') )); ?>
                                        <p class="err-email_address help-block <?php if (!isset($errors['password'])): ?> hide<?php endif; ?>">
                                         <?php echo isset($errors['password'])?$errors['password']:''; ?>
                                        </p>
                                        <span class="fa fa-envelope-o signin-form-icon"></span>
				</div>
                                
                                <div class="form-group w-icon <?php if (isset($errors['password_again'])): ?> has-error<?php endif; ?>">
                                        <?php echo $form['password_again']->render(array('class' => 'form-control input-lg', 'autocomplete' => 'off', 'placeholder' => __('Repeat Password', array(), 'arquematics') )); ?>
                                        <p class="err-email_address help-block <?php if (!isset($errors['password'])): ?> hide<?php endif; ?>">
                                         <?php echo isset($errors['password_again'])?$errors['password_again']:''; ?>
                                        </p>
                                        <span class="fa fa-envelope-o signin-form-icon"></span>
				</div> 
                                
                                
                                <div class="form-actions">
                                    <input type="submit" class="cmd-change-password signin-btn bg-primary" value="<?php echo __('Change current password', array(), 'arquematics'); ?>">
                                </div> <!-- / .form-actions -->
			</form>
			<!-- / Form --> 
		</div>
		<!-- Right side -->
	</div>
              
<?php slot('body_class','theme-default page-signin'); ?>