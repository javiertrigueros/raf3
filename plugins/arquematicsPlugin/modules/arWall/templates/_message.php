<?php use_helper('Date',
        'ar',
        'JavascriptBase',
        'I18N',
        'Wall') ?>

<?php $display = isset($display) ? $sf_data->getRaw('display') : true; ?>
<?php $enabledModules = sfConfig::get('sf_enabled_modules'); ?>
<?php $message = isset($message) ? $sf_data->getRaw('message') : null; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : null; ?>
<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>

<?php $canDelete = $message->canDelete(); ?>
<?php $classMessage = (count($message->Comments) > 0)?'message-open':'message-close' ?>

<?php if (in_array('arBlog', $enabledModules) && (count($message->getBlogItems()) > 0)): ?>

    <div class="messages <?php echo (!$display)?'hide':'' ?>">
        <a class="message-user-iconwall" href="<?php echo url_for('user_profile', $message->getUser()) ?>" >
            <?php include_partial('arProfile/imageNormal', 
                    array('image' => $message->getImageUserProfile(),
                          'class' => 'messages-avatar')) ?> 
        </a>
        <div data-message_id="<?php echo $message->getId(); ?>" id="message-<?php echo $message->getId(); ?>" class="message messages-body <?php echo $classMessage ?>">
            <div class="messages-text">
                <?php include_partial("arWall/messageHeader",  array('aUserProfileFilter' => $aUserProfileFilter,'authUser' => $authUser, 'canDelete' => $canDelete,'message' => $message)) ?>
                <?php include_partial("arBlog/listBlogItems",   array('authUser' => $authUser,'message' => $message)) ?>
            </div>
            <?php include_partial("arWall/messageControls",  array('message' => $message)) ?>
        </div>
        <?php include_partial("arWall/listComments",  array(
            'authUser' => $authUser,
            'canDelete' => $canDelete,
            'message' => $message)) ?>
    </div>

<?php else: ?>
 
    <div class="messages <?php echo (!$display)?'hide':'' ?>">
        <a class="message-user-iconwall" href="<?php echo url_for('user_profile', $message->getUser()) ?>" >
            <?php include_partial('arProfile/imageNormal', 
                    array('image' => $message->getImageUserProfile(),
                          'class' => 'messages-avatar')) ?> 
        </a>
        <div data-message_id="<?php echo $message->getId(); ?>" id="message-<?php echo $message->getId(); ?>" class="message messages-body <?php echo $classMessage ?>">
            <div class="messages-text">
            
            <?php include_partial("arWall/messageHeader",  array('aUserProfileFilter' => $aUserProfileFilter,'authUser' => $authUser, 'canDelete' => $canDelete,'message' => $message)) ?>
        
            <?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
                <div data-encrypt-text="<?php echo $message->EncContent->getContent(); ?>" class="message-user-content content-text">
                </div>
            <?php else: ?>
                <div class="message-user-content content-text">
                    <?php echo nl2br2($message->getMessage(), true); ?>    
                </div>
            <?php endif; ?>
        
            <?php if (in_array('arMap', $enabledModules)): ?>
                <?php include_partial("arMap/listLocations",   array('message' => $message)) ?>
            <?php endif; ?>
        
            <?php if (in_array('arLink', $enabledModules)): ?>
                <?php include_partial("arLink/listLink",  array('message' => $message)) ?>
            <?php endif; ?>

            <?php if (in_array('arDrop', $enabledModules)): ?>
                <?php include_component('arDrop','showList', array('enabledModules' => $enabledModules,'authUser'  => $authUser, 'message' => $message)); ?>
            <?php endif; ?>
        
        
            </div>
            <?php include_partial("arWall/messageControls",  array('message' => $message)) ?>
        </div>
        <?php include_partial("arWall/listComments",  array(
            'authUser' => $authUser,
            'canDelete' => $canDelete,
            'message' => $message)) ?>
    </div>


<?php endif; ?>