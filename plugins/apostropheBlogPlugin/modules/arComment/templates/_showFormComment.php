<?php $isAuth = isset($isAuth) ? $sf_data->getRaw('isAuth') : false; ?>
<?php $form = isset($form) ? $sf_data->getRaw('form') : null; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>


<?php if ($isAuth): ?>                       
    <?php include_partial('arComment/showFormLoginComment', array('aUserProfile' => $aUserProfile, 'form' => $form)); ?>
<?php else: ?>
    <?php include_partial('arComment/showFormLogoutComment', array('form' => $form)); ?>
<?php endif; ?>

