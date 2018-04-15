<div class="header">
    <a href="/" class="logo">
        <img src="/arquematicsPlugin/assets/images/pixel-admin/main-navbar-logo-login.png" alt="" style="margin-top: -5px;">&nbsp;
        <strong><?php echo sfConfig::get('app_a_title_simple') ?></strong>
    </a> <!-- / .logo -->
</div> <!-- / .header -->

<div class="error-code">404</div>

<div class="error-text">
		<span class="oops">OOPS!</span><br>
		<span class="hr"></span>
		<br>
                <?php echo __("SOMETHING WENT WRONG, OR THAT PAGE DOESN'T EXIST... YET", null, 'arquematics'); ?>
</div> <!-- / .error-text -->

<?php slot('body_class','page-404'); ?>