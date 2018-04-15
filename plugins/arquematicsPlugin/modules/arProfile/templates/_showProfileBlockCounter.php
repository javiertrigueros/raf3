<?php use_helper('I18N','a','ar') ?>
<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $aRouteUserProfile = isset($aRouteUserProfile)? $sf_data->getRaw('aRouteUserProfile') : null; ?>

<?php $arUserMessagesCount = isset($arUserMessagesCount)? $sf_data->getRaw('arUserMessagesCount') : 0; ?>
<?php $arMutualFriendsCount = isset($arMutualFriendsCount)? $sf_data->getRaw('arMutualFriendsCount') : 0; ?>

<?php $disableMessage = isset($disableMessage)? $sf_data->getRaw('disableMessage') : false; ?>
<?php $disableFriends = isset($disableFriends)? $sf_data->getRaw('disableFriends') : false; ?>

<div id="block-counter" class="panel panel-transparent" data-message_count="<?php echo $arUserMessagesCount  ?>" data-friends_count="<?php echo $arMutualFriendsCount ?>">
    <div class="panel-heading">
	<span class="panel-title small-title"><?php echo $aRouteUserProfile->getFirstLast() ?></span>
    </div>
    
    <div class="list-group">
        <?php if (!$disableMessage && $arUserMessagesCount && ($arUserMessagesCount > 0)): ?>
                <a id="link-count-message" class="list-group-item" data-url="<?php echo url_for('@wall?pag=1&userid='.$aRouteUserProfile->getId()) ?>" href="<?php echo url_for('@wall?pag=1&userid='.$aRouteUserProfile->getId()) ?>">
                    <span id="count-message" class="block-counter-integer"><?php echo $arUserMessagesCount ?></span>
                    <span><?php echo __('Message', null, 'arquematics') ?></span>
                </a>
        <?php else: ?>
                <a id="link-count-message" class="list-group-item" data-url="<?php echo url_for('@wall?pag=1&userid='.$aRouteUserProfile->getId()) ?>" href="#">
                    <span id="count-message" class="block-counter-integer"><?php echo $arUserMessagesCount ?></span>
                    <span><?php echo __('Message', null, 'arquematics') ?></span>
                </a>
        <?php endif; ?>
        <?php if (!$disableFriends && $arMutualFriendsCount && ($arMutualFriendsCount > 0)): ?>
                <a href="<?php echo url_for('user_profile_mutual_view',$aRouteUserProfile) ?>" class="list-group-item">
                    <span id="count-mutual-friends" class="block-counter-integer"><?php echo $arMutualFriendsCount; ?></span>
                    <span><?php echo __('Subscribers', null, 'arquematics') ?></span>
                </a>
             <?php else: ?>
                 <a href="#" class="list-group-item">
                    <span id="count-mutual-friends" class="block-counter-integer"><?php echo $arMutualFriendsCount; ?></span>
                    <span><?php echo __('Subscribers', null, 'arquematics') ?></span>
                </a>
       <?php endif; ?>
    </div>
</div>