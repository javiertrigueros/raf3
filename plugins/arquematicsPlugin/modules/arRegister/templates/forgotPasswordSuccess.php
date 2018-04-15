<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

<?php $errors = $form->getErrors(); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/bootstrap-2.0.2.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>

<?php if (sfConfig::get('app_arquematics_encrypt')): ?>
    <?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

    <?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
    
    <?php include_partial('arWall/encryptjs'); ?>

    <?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
<?php endif; ?>

                         
           <div class="modal fade" id="conditions-terms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="cmd-private-key-cancel-extra" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4><?php echo __('Terms of service', array(), 'arquematics'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <?php include_partial('arRegister/conditions_terms_'.$culture); ?>
                    </div>
                  <div class="modal-footer">
                      <input id="cmd-private-key-send" type="submit" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>"  class="cmd-close-conditions-terms accept-extra btn btn-primary" value="<?php echo __('Accept', array(), 'blog') ?>" />
                  </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
                        

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
			<form id="forgot_password-form" autocomplete="off" action="<?php echo url_for('@ar_forgot'); ?>" method="POST">
	
                                <?php echo $form->renderHiddenFields() ?>
                                <div class="signin-text">
					<span>
                                            <?php echo __('Password reset', array(), 'arquematics'); ?>
                                        </span>
				</div> <!-- / .signin-text -->

				<div class="form-group w-icon <?php if (isset($errors['email_address'])): ?> has-error<?php endif; ?>">
                                        <?php echo $form['email_address']->render(array('class' => 'form-control input-lg', 'autocomplete' => 'off', 'placeholder' => __('Username or email', array(), 'arquematics') )); ?>
                                        <p class="err-email_address help-block <?php if (!isset($errors['email_address'])): ?> hide<?php endif; ?>">
                                         <?php echo isset($errors['email_address'])?$errors['email_address']:''; ?>
                                        </p>
                                        <span class="fa fa-envelope-o signin-form-icon"></span>
				</div> 
                                
                                <div class="form-actions">
                                    <input type="submit" class="cmd-password-reset signin-btn bg-primary" value="<?php echo __('SEND PASSWORD RESET LINK', array(), 'arquematics'); ?>">
                                </div> <!-- / .form-actions -->
			</form>
			<!-- / Form --> 
		</div>
		<!-- Right side -->
	</div>
              
<?php slot('body_class','theme-default page-signin'); ?>