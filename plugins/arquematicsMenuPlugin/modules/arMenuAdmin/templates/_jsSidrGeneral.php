<script type="text/javascript" >
   $(document).ready(function() {
         
    $('#documents-cmd').sidr({
                closeExtra: '.cms-close',
                hideExtra: '#main-menu-bg',
                side: 'right',         // Accepts 'left' or 'right'
                name: 'sidr-menu-documents',
                body: '#main-navbar, body, .admin-bar, #header, #sfWebDebug',
                renaming: false,
                source: '#admin-cms-documents'
             });
    
  });
</script>
