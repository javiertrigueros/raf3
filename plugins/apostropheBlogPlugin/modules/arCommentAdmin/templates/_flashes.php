<?php if ($sf_user->hasFlash('notice') || $sf_user->hasFlash('error') ): ?>
<li class="flash-message-head">
    <?php if ($sf_user->hasFlash('notice')): ?>
        <span class="alert alert-success"><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></span>
    <?php endif; ?>

    <?php if ($sf_user->hasFlash('error')): ?>
        <span class="alert alert-danger"><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></span>
    <?php endif; ?>
</li>
<?php endif; ?>