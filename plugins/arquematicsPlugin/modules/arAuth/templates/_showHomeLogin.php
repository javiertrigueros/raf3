<?php $showContent = isset($showContent) ? $sf_data->getRaw('showContent') : false; ?>
<?php if ($showContent): ?>
    <?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
    <?php $userBackForm = isset($userBackForm) ? $sf_data->getRaw('userBackForm') : null; ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/vendor/jsencrypt/bin/jsencrypt.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
<?php include_partial('arWall/encryptjs'); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.login.js"); ?>

<?php include_js_call('arAuth/jsLogin', array('showModalFullScreen' => false)); ?>

<?php slot('extra-modals'); ?>
<div class="modal-load modal-load-fix">
     <div class="ar-container-photo-swipe">
           <div class="item-photo-swip cssload-piano">
                                <div class="cssload-rect1"></div>
                                <div class="cssload-rect2"></div>
                                <div class="cssload-rect3"></div>
          </div>
     </div>
</div>

<?php if (sfConfig::get('app_arquematics_encrypt')): ?>
           <div id="private-key-modal" class="modal fade fullscreen"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                        <button id="cmd-private-key-cancel-extra" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4><?php echo __('Private Key', array(),'profile'); ?></h4>
                    </div>
                    <div class="modal-body ar-modal-login">
                        <?php echo $form['private_key']->render(array('class' => 'form-control ar-control-private-key', 'style' => 'height: 200px !important;')); ?>
                    </div>
                    <div class="modal-footer">
                        <button id="cmd-private-key-cancel" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', array(), 'blog') ?></button>
                        <input id="cmd-private-key-send" type="submit" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>"  class="cmd-private-key-send-extra btn btn-primary" value="<?php echo __('Accept', array(), 'blog') ?>" />
                    </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
 <?php endif; ?>
       
<?php end_slot() ?>

<div class="dropdown ar-login">
  <a class="ar-login-home" id="dLabel" data-target="#" href="<?php echo url_for('@sf_guard_signin') ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    <?php echo __('Log in', null, 'arquematics'); ?>
    <span class="caret"></span>
  </a>
    
  <div class="ar-signin-dropdown dropdown-menu dropdown-form" role="dialog" aria-hidden="false" aria-labelledby="dLabel">
    <div tabindex="0" class="js-first-tabstop"></div>
    <div class="dropdown-caret right"> <span class="caret-outer"></span> <span class="caret-inner"></span> </div>
    <div class="signin-dialog-body">
        <div><?php echo __('Have an account?', null,'arquematics'); ?> </div>
        <form id="login-form" method="post" action="<?php echo url_for('@sf_guard_signin') ?>">
           <?php echo $form->renderHiddenFields() ?>
           
           <div class="form-group">
            <?php echo $form['username']->render(array('class' => 'form-control', 'placeholder' => __('Username or email', array(), 'arquematics') )); ?>
           </div> <!-- / Username -->
           
           <div class="form-group">
            <?php echo $form['password']->render(array('class' => 'form-control', 'placeholder' => __('Password', array(), 'arquematics'))); ?>
           </div> <!-- / Password -->

           <div class="form-actions">
                <input id="cmd-send-login" data-loading-text="<?php echo __('Sending', array(),'profile'); ?>" type="submit" value="<?php echo __('Signin', null, 'sf_guard') ?>" class="btn btn-primary signin-btn bg-primary">
		
           </div> 
        </form>
        <a href="<?php echo url_for('@ar_forgot'); ?>" class="forgot-link forgot-password" id="forgot-password-link"><?php echo __('Forgot your password?', array(), 'arquematics'); ?></a>
        
        <?php include_partial('arAuth/user_form', array('form' => $userBackForm)) ?>
        
        <hr class="home-login-hr">
        <?php if (sfConfig::get('app_facebook_enable')): ?>
            <?php echo link_to(__('Sign In with %net%', array('%net%' => '<span>Facebook</span>'), 'arquematics'), '@facebook_connect', array('class' => 'signin-with-btn btn', 'style' => 'background:#4f6faa;background:rgba(79, 111, 170, .8);')); ?>
        <?php endif; ?>
        <div class="signup-form-header">
            <?php echo __('New to %net%?', array('%net%' =>  sfConfig::get('app_a_title_simple')), 'arquematics'); ?>
        </div>
        <a class="btn btn-success" role="button" href="<?php echo url_for('@ar_register'); ?>">
            <?php echo __('Sign up', null, 'arquematics'); ?>
        </a>
    </div>
  </div>
</div>

<?php endif; ?>
