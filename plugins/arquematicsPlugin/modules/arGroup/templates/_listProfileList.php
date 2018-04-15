<div>
    <div id="content-groups" class="groups container" style="margin:0 auto;width: 100%; overflow-y: auto;  text-align: center;">
        <?php foreach ($currentUser->getAdminListNoMain() as $list): ?>
            <?php include_partial('arGroup/profileList', array('list' => $list)); ?>
        <?php endforeach; ?>
        <?php if ($currentUser->countAdminList() < (int)sfConfig::get('app_arquematics_plugin_max_list_items', 6)): ?>
        <div class="group group-active" id="group0" data-id="0" data-items="0" >
            <button class="btn btn-success group-button" data-mouseover="<?php echo __("Create list",array(),'profile') ?>"><?php echo __("Drop to create list",array(),'profile') ?></button>
        </div>
        <?php else: ?>
        <div class="hide group" id="group0" data-id="0" data-items="0" >
            <button class="btn btn-success group-button" data-mouseover="<?php echo __("Create list",array(),'profile') ?>"><?php echo __("Drop to create list",array(),'profile') ?></button>
        </div>
        <?php endif ?>
        <div class="group-black" id="blaclk1"></div>
        <div class="group-black" id="blaclk2"></div>
        <div class="group-black" id="blaclk3"></div>
    </div>
    <h1  class="group-separator">
        <small>
            <span class="glyphicon glyphicon-arrow-up"></span>
            <?php echo __('Drag people to your lists.',array(),'profile') ?>
            <span class="glyphicon glyphicon-arrow-up"></span>
        </small>
    </h1>
</div>
