<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>

<?php use_javascript("/arquematicsMenuPlugin/js/jquery.sidr.js"); ?>

<div id="admin-cms-content" class="hide" style="display: none">
    <?php include_partial('arMenuAdmin/sidrAdminMenuContent') ?>
</div>

<?php include_js_call('arMenuAdmin/jsSidr'); ?>