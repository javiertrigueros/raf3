<?php
/**
 * E-mail template used when said hello and send private key
 * the first time
 *
 * @param sfGuardUser           $user            the sfGuardUser object
 * @param sfGuardForgotPassword $forgot_password the sfGuardForgotPassword object
 */
 $privateKey = isset($privateKey) ? $sf_data->getRaw('privateKey') : '';
 $user = isset($user) ? $sf_data->getRaw('user') : null; 
?>
<?php use_helper('I18N') ?>
<?php echo __('Hello %first_name%', array('%first_name%' => $user->getFirstName()), 'arquematics'); ?>,



<?php echo __('This email has been sent because it has been logged for the first time at %web_title%.', array( '%web_title%' => sfConfig::get('app_a_title_simple')), 'arquematics'); ?>



<?php echo __('The following key is automatically generated:', null, 'arquematics'); ?> 



<?php echo $privateKey; ?>


<?php echo __('If you want to access from another browser or device you will require this key. Keep it, it is an encryption system and is required to access your information.', null, 'arquematics'); ?>