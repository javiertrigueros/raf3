<?php use_helper('I18N','a','ar') ?>
<?php $pageMaxResults = sfConfig::get('app_arquematics_plugin_wall_wall_mutual_friends_view', 7); ?> 

<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $aRouteUserProfile = isset($aRouteUserProfile)? $sf_data->getRaw('aRouteUserProfile') : null; ?>
<?php $arRouteProfileImage = isset($arRouteProfileImage)? $sf_data->getRaw('arRouteProfileImage') : null; ?> 

<?php $arMutualFriends = isset($arMutualFriends)? $sf_data->getRaw('arMutualFriends') : false; ?> 
<?php $arMutualFriendsCount = isset($arMutualFriendsCount)? $sf_data->getRaw('arMutualFriendsCount') : 0; ?> 

<?php $showScript = isset($showScript)? $sf_data->getRaw('showScript') : true; ?>

<?php if ($showScript): ?>
    <?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.profile.js"); ?>
    <?php include_js_call('arProfile/jsProfileWall') ?>
<?php endif; ?>

<div id="profile-wall" class="rBlock" data-count_messages="<?php echo $aRouteUserProfile->countUserMessages() ?>" data-url_mutual_list="<?php echo url_for('user_profile_mutual',$aRouteUserProfile) ?>"  >
    <div class="profile-block">
       <div id="profileImage" class="panel profile-photo">
        <a href="<?php echo url_for('user_profile',$aRouteUserProfile) ?>">
            <?php include_partial('arProfile/imageWallProfile', 
                    array('image' => $arRouteProfileImage,
                      'class' => false)) ?>
        </a> 
       </div> 
    </div>
    
     <?php include_component('arProfile','showProfileBlockCounter',
                                        array('disableMessage' => false,
                                              'disableFriends' => false,
                                              'aUserProfile' => $aUserProfile,
                                              'aRouteUserProfile' => $aRouteUserProfile)); ?>
    
    
</div>

<div id="profile-wall-mutual" class="panel panel-transparent rBlock<?php echo ($arMutualFriends && (count($arMutualFriends) > 0))?'':' hide' ?>">
  <div class="panel-heading">
    <span class="panel-title small-title">
      <?php echo __('Subscribers', null, 'arquematics') ?>
    </span>
  </div>
  <div class="panel-body small-image-list">
    <?php if ($arMutualFriends && (count($arMutualFriends) > 0)): ?>
       <?php include_partial('arProfile/listMutualFriends', array(
                    'arMutualFriends' => $arMutualFriends,
                    'aUserProfile' => $aUserProfile)); ?>
       <?php if ($arMutualFriendsCount > $pageMaxResults): ?>
            <div id ="cmd-list-mutuals" class="mutuals-icon-plus list-mutuals-container">
                <span class="icon-plus-container">
                  <i class="fa fa-plus-square"></i>
                </span> 
                <span class="hide icon-plus-loader-container wall-loader-icon">

                <span>
            </div> 
       <?php endif; ?>
    <?php endif; ?>
  </div>
</div>