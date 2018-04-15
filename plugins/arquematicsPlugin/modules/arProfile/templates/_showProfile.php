<?php use_helper('I18N','a','ar') ?>
<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $aRouteUserProfile = isset($aRouteUserProfile)? $sf_data->getRaw('aRouteUserProfile') : null; ?>
<?php $arRouteProfileImage = isset($arRouteProfileImage)? $sf_data->getRaw('arRouteProfileImage') : null; ?> 

<?php $downloadPrivateKey = isset($downloadPrivateKey)? $sf_data->getRaw('downloadPrivateKey') : false; ?>
<?php $formImage = isset($formImage)? $sf_data->getRaw('formImage') : null; ?> 
<?php $formFirstLast = isset($formFirstLast)? $sf_data->getRaw('formFirstLast') : null; ?> 
<?php $formDescription = isset($formDescription)? $sf_data->getRaw('formDescription') : null; ?> 

<?php $canEdit = isset($canEdit) ? $sf_data->getRaw('canEdit') : false; ?> 


<?php include_partial('arProfile/showProfileContent', 
                            array('canEdit' => $canEdit,
                                  'formFirstLast' => $formFirstLast,
                                  'formDescription' => $formDescription,
                                  'downloadPrivateKey' => $downloadPrivateKey,
                                  'aUserProfile' => $aUserProfile,
                                  'aRouteUserProfile' => $aRouteUserProfile)); ?> 