<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $enabledTools = isset($enabledTools) ? $sf_data->getRaw('enabledTools') : false; ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.tab.js"); ?>

<?php include_js_call('arWall/jsTabs', array('aUserProfile' => $aUserProfile, 'aUserProfileFilter' => $aUserProfileFilter)) ?>

<ul id="profile-tabs" class="nav nav-tabs tab-control-buttons control-update">
    <?php foreach ($enabledTools as $tool): ?>
    
    <li id="<?php echo $tool['name']; ?>" class="tab-button <?php if ($tool['is_active']){ echo 'active'; };?>">
	<a href="#" data-toggle="tab">
           <span class="<?php echo $tool['icon'] ?>"></span>
           <?php echo __($tool['name'],array(),'wall'); ?>
        </a>
    </li>
    <?php endforeach ?>
</ul>