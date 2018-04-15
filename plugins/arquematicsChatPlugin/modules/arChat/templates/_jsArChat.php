<?php use_helper('I18N','a','ar') ?>

<?php if ($arHasToken):  ?>

<script type="text/javascript">

 //traducciones
        var You = 0;
        var Video_conference = 1;
       
        var translation=[];
  
        translation[You] = "<?php echo __('You', array(), 'archat')?>";
        translation[Video_conference] = "<?php echo __('Video conference', array(), 'archat')?>";
         
         
        // Jappix main configuration
        var SERVICE_NAME = '<?php echo sfConfig::get('app_archat_client_service_name') ?>';
        var SERVICE_DESC = '<?php echo sfConfig::get('app_archat_client_service_desc') ?>';
        var JAPPIX_RESOURCE = '<?php echo sfConfig::get('app_archat_client_jappix_resource') ?>';
        var LOCK_HOST = '<?php echo sfConfig::get('app_archat_client_lock_host') ?>';
        var ANONYMOUS = '<?php echo sfConfig::get('app_archat_client_anonymous') ?>';
        var REGISTRATION = '<?php echo sfConfig::get('app_archat_client_registration') ?>';
        var BOSH_PROXY = '<?php echo sfConfig::get('app_archat_client_bosh_proxy') ?>';
        var MANAGER_LINK = '<?php echo sfConfig::get('app_archat_client_manager_link') ?>';
        var GROUPCHATS_JOIN = '<?php echo sfConfig::get('app_archat_client_groupchats_join') ?>';
        var ENCRYPTION = '<?php echo sfConfig::get('app_archat_client_encryption') ?>';
        var HTTPS_STORAGE = '<?php echo sfConfig::get('app_archat_client_https_storage') ?>';
        var HTTPS_FORCE = '<?php echo sfConfig::get('app_archat_client_https_force') ?>';
        var COMPRESSION = '<?php echo sfConfig::get('app_archat_client_compression') ?>';
        var MULTI_FILES = '<?php echo sfConfig::get('app_archat_client_multi_files') ?>';
        var DEVELOPER = '<?php echo sfConfig::get('app_archat_client_developer') ?>';

        // Anonymous mode
        var ANONYMOUS_ROOM = <?php echo sfConfig::get('app_archat_client_anonymous_room') ?>;
        var ANONYMOUS_NICK = <?php echo sfConfig::get('app_archat_client_anonymous_nick') ?>;   
        // Jappix hosts configuration
        var HOST_MAIN = '<?php echo sfConfig::get('app_archat_server_host_main') ?>';
        var HOST_MUC = '<?php echo sfConfig::get('app_archat_server_host_muc') ?>';
        var HOST_PUBSUB = '<?php echo sfConfig::get('app_archat_server_host_pubsub') ?>';
        var HOST_VJUD = '<?php echo sfConfig::get('app_archat_server_host_vhud') ?>';
        var HOST_ANONYMOUS = '<?php echo sfConfig::get('app_archat_server_host_anonymous') ?>';
        var HOST_BOSH = '<?php echo sfConfig::get('app_archat_server_host_bosh') ?>';
        var HOST_BOSH_MAIN = '<?php echo sfConfig::get('app_archat_server_host_bosh_main') ?>';
        var HOST_BOSH_MINI = '<?php echo sfConfig::get('app_archat_server_host_bosh_mini') ?>';
        var HOST_STATIC = '<?php echo sfConfig::get('app_archat_server_host_static') ?>';
        var HOST_UPLOAD = '<?php echo sfConfig::get('app_archat_server_host_upload') ?>';

        // Jappix Mini vars
        var MINI_DISCONNECT	= false;
        var MINI_AUTOCONNECT	= true;
        var MINI_SHOWPANE	= false;
        var MINI_INITIALIZED	= false;
        var MINI_ANONYMOUS	= false;
        var MINI_ANIMATE	= false;
        var MINI_NICKNAME	= null;
        var MINI_TITLE		= null;
        var MINI_DOMAIN		= null;
        var MINI_USER		= null;
        var MINI_PASSWORD	= null;
        var MINI_RECONNECT	= 0;
        var MINI_GROUPCHATS	= [];
        var MINI_PASSWORDS	= [];
        var MINI_RESOURCE	= JAPPIX_RESOURCE + ' Mini';
        
        
                // Define groupchats here
                
                MINI_GROUPCHATS = [];
                MINI_PASSWORDS = [];
                
                // Add an animation
                MINI_ANIMATE = true;
                
                // Define the user nickname
                MINI_NICKNAME = "<?php echo $sf_user->getGuardUser()->getUsername() ?>";
            
                // Override the default session resource
                MINI_RESOURCE = "MyOwnResource";

        //var $j = jQuery.noConflict();
        
        jQuery(document).ready(function(){  
                // Connect the user (autoconnect, show_pane, domain, username, password)
                // Notice: put true/false to autoconnect and show_pane
                // Notice: exclude "user" and "password" if using anonymous login
                //launchMini(true, false, "<?php echo sfConfig::get('app_archat_server_host_main') ?>");
                //launchMini(true, false, "localhost");
                launchMini(true, true, "<?php echo sfConfig::get('app_archat_server_host_main') ?>","<?php echo $sf_user->getGuardUser()->getUsername() ?>","<?php echo $arToken ?>");
        });
        
</script>

<?php else: ?>


<script type="text/javascript">

        //traducciones
        var You = 0;
        var Video_conference = 1;
       
        var translation=[];
  
        translation[You] = "<?php echo __('You', array(), 'archat')?>";
        translation[Video_conference] = "<?php echo __('Video conference', array(), 'archat')?>";
         
         
        // Jappix main configuration
        var SERVICE_NAME = '<?php echo sfConfig::get('app_archat_client_service_name') ?>';
        var SERVICE_DESC = '<?php echo sfConfig::get('app_archat_client_service_desc') ?>';
        var JAPPIX_RESOURCE = '<?php echo sfConfig::get('app_archat_client_jappix_resource') ?>';
        var LOCK_HOST = '<?php echo sfConfig::get('app_archat_client_lock_host') ?>';
        var ANONYMOUS = '<?php echo sfConfig::get('app_archat_client_anonymous') ?>';
        var REGISTRATION = '<?php echo sfConfig::get('app_archat_client_registration') ?>';
        var BOSH_PROXY = '<?php echo sfConfig::get('app_archat_client_bosh_proxy') ?>';
        var MANAGER_LINK = '<?php echo sfConfig::get('app_archat_client_manager_link') ?>';
        var GROUPCHATS_JOIN = '<?php echo sfConfig::get('app_archat_client_groupchats_join') ?>';
        var ENCRYPTION = '<?php echo sfConfig::get('app_archat_client_encryption') ?>';
        var HTTPS_STORAGE = '<?php echo sfConfig::get('app_archat_client_https_storage') ?>';
        var HTTPS_FORCE = '<?php echo sfConfig::get('app_archat_client_https_force') ?>';
        var COMPRESSION = '<?php echo sfConfig::get('app_archat_client_compression') ?>';
        var MULTI_FILES = '<?php echo sfConfig::get('app_archat_client_multi_files') ?>';
        var DEVELOPER = '<?php echo sfConfig::get('app_archat_client_developer') ?>';

        // Anonymous mode
        var ANONYMOUS_ROOM = <?php echo sfConfig::get('app_archat_client_anonymous_room') ?>;
        var ANONYMOUS_NICK = <?php echo sfConfig::get('app_archat_client_anonymous_nick') ?>;   
        // Jappix hosts configuration
        var HOST_MAIN = '<?php echo sfConfig::get('app_archat_server_host_main') ?>';
        var HOST_MUC = '<?php echo sfConfig::get('app_archat_server_host_muc') ?>';
        var HOST_PUBSUB = '<?php echo sfConfig::get('app_archat_server_host_pubsub') ?>';
        var HOST_VJUD = '<?php echo sfConfig::get('app_archat_server_host_vhud') ?>';
        var HOST_ANONYMOUS = '<?php echo sfConfig::get('app_archat_server_host_anonymous') ?>';
        var HOST_BOSH = '<?php echo sfConfig::get('app_archat_server_host_bosh') ?>';
        var HOST_BOSH_MAIN = '<?php echo sfConfig::get('app_archat_server_host_bosh_main') ?>';
        var HOST_BOSH_MINI = '<?php echo sfConfig::get('app_archat_server_host_bosh_mini') ?>';
        var HOST_STATIC = '<?php echo sfConfig::get('app_archat_server_host_static') ?>';
        var HOST_UPLOAD = '<?php echo sfConfig::get('app_archat_server_host_upload') ?>';

        // Jappix Mini vars
        var MINI_DISCONNECT	= false;
        var MINI_AUTOCONNECT	= true;
        var MINI_SHOWPANE	= false;
        var MINI_INITIALIZED	= false;
        var MINI_ANONYMOUS	= false;
        var MINI_ANIMATE	= false;
        var MINI_NICKNAME	= null;
        var MINI_TITLE		= null;
        var MINI_DOMAIN		= null;
        var MINI_USER		= null;
        var MINI_PASSWORD	= null;
        var MINI_RECONNECT	= 0;
        var MINI_GROUPCHATS	= [];
        var MINI_PASSWORDS	= [];
        var MINI_RESOURCE	= JAPPIX_RESOURCE + ' Mini';
        
        
                // Define groupchats here
                
                MINI_GROUPCHATS = [];
                MINI_PASSWORDS = [];
                
                // Add an animation
                MINI_ANIMATE = true;
                
                // Define the user nickname
                MINI_NICKNAME = "ANONIMOUS";
            
                // Override the default session resource
                MINI_RESOURCE = "MyOwnResource";

                //var $j = jQuery.noConflict();
                
        jQuery(document).ready(function() 
        { 
            

            jQuery("#a-signin-form").bind("aActAsSubmitCallbackAfter", function(e){
               launchMini(true, true, 
                    "<?php echo sfConfig::get('app_archat_server_host_main') ?>",
                    jQuery('#a-signin-form #signin_username').val(),
                    jQuery('#a-signin-form #signin_password').val());
               return false;
            });
            
                
                
        });
        
</script>

<?php endif; ?>