<script type="text/javascript" >
 
  $(document).ready(function() {
         
    $('#admin-cms').sidr({
         closeExtra: '.cms-close',
         hideExtra: '#main-menu-bg',
         side: 'right',         // Accepts 'left' or 'right'
         name: 'sidr-menu-admin-cms',
         body: '#main-navbar, body, .admin-bar, #sfWebDebug, #header, #main-navbar',
         renaming: false,
         source: '#admin-cms-content'
    });
    
  });
</script>
