<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $sections = isset($sections) ? $sf_data->getRaw('sections') : array(); ?>
<?php $lang = isset($lang) ? $sf_data->getRaw('lang') : 'es'; ?>

<?php if (sfConfig::get('app_arquematics_encrypt')): ?>
                                  
    <?php include_partial('arWall/encryptjs'); ?>

    <?php include_js_call('arWall/jsEncrypt', array('lang' => $lang,
    												'sections' => $sections,
                                                    'aUserProfile' => $aUserProfile)) ?>
 <?php endif; ?>