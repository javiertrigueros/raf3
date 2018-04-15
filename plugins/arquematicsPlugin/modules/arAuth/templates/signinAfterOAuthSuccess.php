<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es' ?>

 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/bootstrap.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/pixel-admin.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/widgets.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/pages.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/rtl.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/themes.css"); ?>
 <?php use_stylesheet("/arquematicsPlugin/css/arquematics/arCommon.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/bootstrap-2.0.2.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js"); ?>

<?php if (sfConfig::get('app_arquematics_encrypt')): ?>

    <?php use_javascript("/arquematicsPlugin/js/vendor/jsencrypt/bin/jsencrypt.js"); ?>
   
    <?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

    <?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>
    
    <?php include_partial('arWall/encryptjs'); ?>

    <?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>
   
    <?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.login.js"); ?>

    <?php include_js_call('arAuth/jsLoginOAuth') ?>

<?php endif; ?>

<?php include_partial('arAuth/signin_form_OAuth', array('form' => $form, 'aUserProfile' => $aUserProfile, 'culture' => $culture)) ?>
<?php include_partial('arAuth/user_form', array('form' => $userBackForm)) ?>

<?php slot('body_class','theme-default page-signin'); ?>
