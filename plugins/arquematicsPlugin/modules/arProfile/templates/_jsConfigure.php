<script type="text/javascript">
 $(document).ready(function()
 {
    $('#ui-profile-name').fieldeditor({textSelector: ".user-first-last-text", hideTextControl: false});
    
    $('#ui-profile-pass').fieldpass({
        passLenght: <?php echo sfConfig::get('app_arquematics_pass_chars', 8) ?>,
        txtErrorMatch:  '<?php echo __('Passwords must match!', null, 'arquematics') ?>',   
        txtStrong: '<?php echo __('Strong password', null, 'arquematics') ?>',
        txtMedium: '<?php echo __('Midrange password', null, 'arquematics') ?>',
        
        txtMediumLowercase: '<?php echo __('Midrange password no more lowercase letters', null, 'arquematics') ?>',
        txtMediumCapital:   '<?php echo __('Midrange password no capital letters', null, 'arquematics') ?>',
        txtMediumNumbers:   '<?php echo __('Midrange password no more numbers', null, 'arquematics') ?>',
        txtMediumSpecial:   '<?php echo __('Midrange password no more special characters', null, 'arquematics') ?>',
        
        txtWeak: '<?php echo __('Weak password', null, 'arquematics') ?>',
        txtHasError: '<?php echo __('Password must be at least %passLong% characters.', array('%passLong%' => sfConfig::get('app_arquematics_pass_chars', 8)), 'arquematics') ?>',
        hideTextControl: false});
 });
</script>