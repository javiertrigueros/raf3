<?php $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es'; ?>
<?php $data = isset($data) ? $sf_data->getRaw('data') : null; ?>
<?php $isAdmin = isset($isAdmin) ? $sf_data->getRaw('isAdmin') : false; ?>

<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsTelegramPlugin/js/vendor/angular/angular-csp.css"); ?>

<?php use_stylesheet("/arquematicsTelegramPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsTelegramPlugin/js/vendor/jquery.nanoscroller/nanoscroller.css"); ?>
<?php use_stylesheet("/arquematicsMenuPlugin/css/jquery.sidr.dark.css"); ?>
<?php use_stylesheet("/arquematicsTelegramPlugin/css/app.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/components/moment/moment.js") ?>
<?php use_javascript("/arquematicsPlugin/js/components/moment/lang/".$culture.".js") ?>
    
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/console-polyfill/console-polyfill.js"); ?>
        
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/jquery.nanoscroller/nanoscroller.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/jquery.emojiarea/jquery.emojiarea.js"); ?> 

<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/angular.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/angular-route.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/angular-animate.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/angular-sanitize.js"); ?>

<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/components/angular-gettext/angular-gettext.js"); ?>
                
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/components/angular-moment/angular-moment.js"); ?>
        
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/angular/components/angular-sidr/angular-sidr.js") ?>
    
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/ui-bootstrap/ui-bootstrap-custom-tpls-0.10.0.js"); ?>

<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/jsbn/jsbn_combined.js"); ?>
  
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/cryptoJS/crypto.js"); ?>
  
<?php use_javascript("/arquematicsTelegramPlugin/js/vendor/zlib/gunzip.min.js"); ?>
 
<?php use_javascript("/arquematicsTelegramPlugin/js/lib/config.js"); ?>
        
<?php use_javascript("/arquematicsTelegramPlugin/js/mtproto.js"); ?>

<?php use_javascript("/arquematicsTelegramPlugin/js/util.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/app.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/services.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/controllers.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/filters.js"); ?>
<?php use_javascript("/arquematicsTelegramPlugin/js/directives.js"); ?>

<?php if ($isAdmin): ?>
    <div id="admin-cms-content" class="hide" style="display: none">
        <?php include_partial('arMenuAdmin/sidrAdminMenuContent') ?>
    </div>
<?php endif; ?>

<?php //arreglo para que los modales salgan centrados ?>
<?php include_js_call('ar/jsFixModal') ?>
    
<?php include_js_call('arTelegram/jsGlobalConfig',array('data' => $data)) ?>