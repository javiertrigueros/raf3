<?php $enabledTools = isset($enabledTools) ? $sf_data->getRaw('enabledTools') : array(); ?>
<?php $hasEnabledTools = isset($hasEnabledTools) ? $sf_data->getRaw('hasEnabledTools') : false; ?>
<?php $activeTabModule = isset($activeTabModule) ? $sf_data->getRaw('activeTabModule') : null; ?>

<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; ?>
<?php $form  = isset($form) ? $sf_data->getRaw('form') : null; ?>

<div id="updateboxarea" class="control-update">
    <?php foreach ($enabledTools as $tab): ?>
        <?php if ($tab['name'] == 'arWall'): ?>
        <div id="control-<?php echo $tab['name']; ?>" class="messages tab-control <?php if ($tab['is_active']){ echo 'tab-control-active'; } else { echo 'hide'; };?>">
            <?php include_partial('arProfile/imageNormal', 
                            array('image' => $aUserProfile->getProfileImage(),
                                  'class' => 'messages-avatar')) ?> 
            <div class="messages-body">
                <?php include_partial('arWall/form', array('form' => $form, 'aUserProfileFilter' => $aUserProfileFilter,'aUserProfile' => $aUserProfile)); ?>
                <div id="buttons-<?php echo $tab['name']; ?>" class="<?php echo (!$tab['is_active'])?'hide':'' ?> tab-buttons">
                    <div id="buttons-wall-container" class="tab-buttons-inner">
                        <button id="cmd-update-button-<?php echo $tab['name']; ?>" data-loading-text="<?php echo __("send...",array(),'wall') ?>"  class="btn btn-large btn-primary update_button col-xs-4 col-sm-4 col-md-4 col-lg-2">
                            <?php echo __("Share",array(),'wall') ?>
                        </button> 
    
                        <?php include_component('arGroup','showListSelect', array('aUserProfile' => $aUserProfile,
                                                                                  'tab' => $tab)); ?>
                
                        <?php include_component('arWall','showButtonTools'); ?>
                    </div>
                </div>
            </div> <!-- / .messages-body -->
        </div><!-- / tab-control -->
        <?php elseif ($tab['name'] == 'aBlog'): ?>
            <div id="control-<?php echo $tab['name']; ?>" class="messages tab-control <?php if ($tab['is_active']){ echo 'tab-control-active'; } else { echo 'hide'; };?>">
                 <?php include_partial('arProfile/imageNormal', 
                            array('image' => $aUserProfile->getProfileImage(),
                                  'class' => 'messages-avatar')) ?> 
                <div class="messages-body">
                    <?php include_partial('arBlog/formBlog', array('form' => $formBlog)); ?>
                    <div id="buttons-<?php echo $tab['name']; ?>" class="<?php echo (!$tab['is_active'])?'hide':'' ?> tab-buttons">
                        <div class="tab-buttons-inner">
                    
                            <button id="cmd-update-button-<?php echo $tab['name']; ?>" data-loading-text="<?php echo __("send...",array(),'wall') ?>"  class="btn btn-large btn-primary update_button col-xs-4 col-sm-4 col-md-4 col-lg-2">
                                <?php echo __("Share",array(),'wall') ?>
                            </button> 
    
                            <div id="control-list-select-buttons-<?php echo $tab['name']; ?>" class="control-list-select col-xs-6 col-sm-6 col-md-6 col-lg-6">
           
                            </div>
        
                            <?php include_component('arGroup','showListSelect', array('aUserProfile' => $aUserProfile,'tab' => $tab)); ?>
                        </div>
                    </div>
                </div> <!-- / .messages-body -->
            </div><!-- / tab-control -->
        <?php elseif ($tab['name'] == 'aEvent'): ?>
            <div id="control-<?php echo $tab['name']; ?>" class="messages tab-control <?php if ($tab['is_active']){ echo 'tab-control-active'; } else { echo 'hide'; };?>">
                <?php include_partial('arProfile/imageNormal', 
                            array('image' => $aUserProfile->getProfileImage(),
                                  'class' => 'messages-avatar')) ?> 
                <div class="messages-body">
                    <?php include_partial('arBlog/formEvents', array('form' => $formEvent)); ?>
                    
                    <div id="buttons-<?php echo $tab['name']; ?>" class="<?php echo (!$tab['is_active'])?'hide':'' ?> tab-buttons">
                        <div class="tab-buttons-inner">
                    
                            <button id="cmd-update-button-<?php echo $tab['name']; ?>" data-loading-text="<?php echo __("send...",array(),'wall') ?>"  class="btn btn-large btn-primary update_button col-xs-4 col-sm-4 col-md-4 col-lg-2">
                                <?php echo __("Share",array(),'wall') ?>
                            </button> 
    
                            <div id="control-list-select-buttons-<?php echo $tab['name']; ?>" class="control-list-select col-xs-6 col-sm-6 col-md-6 col-lg-6">
           
                            </div>
        
                            <?php include_component('arGroup','showListSelect', array('aUserProfile' => $aUserProfile,'tab' => $tab)); ?>
                        </div>
                    </div>
                </div><!-- / .messages-body -->
            </div><!-- / tab-control -->
            
            
        <?php endif; ?> 
    <?php endforeach; ?>
</div>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.tab.js"); ?>
<?php include_js_call('arWall/jsTabs', array('aUserProfile' => $aUserProfile, 'aUserProfileFilter' => $aUserProfileFilter)) ?>