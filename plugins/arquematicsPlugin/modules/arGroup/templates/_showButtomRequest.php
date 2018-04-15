 <?php if ($sf_user->isAuthenticated()): ?>
<?php use_helper('I18N','a','ar') ?>

<?php $form = isset($form) ? $sf_data->getRaw('form') : false; ?>
<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $countList = $aUserProfile->countFriendRequest() ?>
<?php $profileList = $aUserProfile->getFriendRequest() ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.friendselector.js"); ?> 

    <li id="cmd-friend-request" class="nav-icon-btn nav-icon-btn-success">
        <a href="#" title="<?php echo __('Requests', null, 'wall') ?>">
            <?php if ($countList > 0): ?>
               <span id="requests-counter" class="label badge"><?php echo $countList ?></span>
            <?php endif; ?>
            <i class="nav-icon fa fa-users"></i>
            <span class="small-screen-text"><?php echo __('Requests', null, 'wall') ?></span>
        </a>
    </li>
                
    <?php slot('nav-modal-primary'); ?>
         <div id="modal-friend-request" class="modal fade">
            <div class="modal-dialog modal-dialog-center">
                <div class="modal-content">
                    <div class="modal-header">
                       <button type="button" id="cmd-cancel-friend-secondary" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
                       <h4 class="modal-title">
                           <?php echo __("Applications for subscription:",array(),'arquematics') ?>
                       </h4>
                    </div>
                    <div id="modal-friend-request-content" class="modal-body">
                        <?php if ($countList > 0): ?>
                        <div id="ff-request-list" class="users-request messages-list widget-messages-alt">
                        <?php foreach ($profileList as $profile): ?>
                                <?php include_partial('arGroup/profileSmallAcceptRequest',array('profile' => $profile)) ?> 
                        <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div id="modal-friend-request-footer" class="modal-footer">
                         <button  type="submit" id="cmd-friend-request-cancel" class="btn close-modal btn-info"><?php echo __("Accept",array(),'profile') ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    
        <div id="modal-accept" class="modal fade">
            <div class="modal-dialog modal-vertical-centered">
                <div class="modal-content">
                    <div class="modal-header">
                       <button type="button" id="cmd-cancel-friend-secondary" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
                       <h4 class="modal-title">
                           <?php echo __("Accept request:",array(),'wall') ?>
                       </h4>
                    </div>
                    <div id="modal-accept-content" class="modal-body">
              
                    </div>
                    <div id="modal-accept-footer" class="modal-footer">
                         <button  type="submit" id="cmd-cancel-friend" class="btn close-modal btn-info"><?php echo __("cancel",array(),'profile') ?></button>
                         <button  type="submit" data-loading-text="<?php echo __("send...",array(),'wall') ?>" id="cmd-add-friend" class="btn btn-success"><?php echo __("Accept",array(),'profile') ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    
    <form id="add_friend" action="<?php echo url_for('@add_friend_to_list') ?>" method="POST" enctype="multipart/form-data">
            <?php echo $form['profile_list_id']->render() ?>
            <?php echo $form->renderHiddenFields() ?>
    </form>
   <?php end_slot(); ?>

    <?php include_js_call('arGroup/jsFriendSelect') ?>
<?php endif ?>