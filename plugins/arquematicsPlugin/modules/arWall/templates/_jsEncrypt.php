<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $mainNodeId = isset($mainNodeId) ? $sf_data->getRaw('mainNodeId') : 'body'; ?>
<?php $secundaryNode = isset($secundaryNode) ? $sf_data->getRaw('secundaryNode') : ''; ?>
<?php $updateboxarea = isset($updateboxarea) ? $sf_data->getRaw('updateboxarea') : ''; ?>
<?php $sections = isset($sections) ? $sf_data->getRaw('sections') : array(); ?>
<?php $lang = isset($lang) ? $sf_data->getRaw('lang') : 'es'; ?>

<script type="text/javascript" >

jQuery(document).ready(function($) 
 {
   try {

      arquematics.lang = '<?php echo $lang ?>';

      arquematics.initEncrypt(
                '<?php echo $aUserProfile->getId() ?>',
                '<?php echo $aUserProfile->getStoreKey () ?>');
        
      arquematics.crypt.setPublicEncKeys(<?php echo json_encode(
                    $aUserProfile->getEncryptKeys(),
                    JSON_HEX_QUOT); ?>); 
                                
      <?php foreach ($sections as $section): ?>
         arquematics.decryptNodeText($('<?php echo $section ?>'));  
      <?php endforeach; ?>
   }
   catch(Err) {
      if ($('#modal-full-screen').length > 0)
      {
        $('#modal-full-screen').modal('show');      
      }
      else
      {
        $('body').addClass('loading');
      }
      window.location = '<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>';
   }
                              
   $('body').bind('changeScrollContent', function (e, $node)
   {
       try {
           arquematics.decryptNodeText($node, true);
       } catch(Err) {
          if ($('#modal-full-screen').length > 0)
          {
            $('#modal-full-screen').modal('show');      
          }
          else
          {
            $('body').addClass('loading');
          }
          window.location = '<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>';
       }
   });
   
    $('body').bind('changeControlContent', function (e, $node)
    {
       try {
           arquematics.decryptNodeText($node, true);
       } catch(Err) {
          if ($('#modal-full-screen').length > 0)
          {
            $('#modal-full-screen').modal('show');      
          }
          else
          {
            $('body').addClass('loading');
          }
          window.location = '<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>';
       }
    });
  });
</script>