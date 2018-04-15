
<script type="text/javascript">

$(document).ready(function()
 {
     
    $('.ar-filter-search').click(function(e){
        e.preventDefault();
        if ($('#a-admin-filters-container').hasClass('hide'))
        {
           $('#a-admin-filters-container')
                    .removeClass('hide')
                    .show();
        }
        else
        {
          $('#a-admin-filters-container').toggle();      
        }

        $('#sf_guard_user_filters_username').focus();
    });
    
    $('.cmd-cancel, .im_dialogs_search_clear').click(function(e){
        e.preventDefault();
        
        var $node = $(e.currentTarget);
        
        var f = document.createElement('form');
        f.style.display = 'none';
        this.parentNode.appendChild(f);
        f.method = 'post';
        f.action = $node.data('url');
        var m = document.createElement('input');
        m.setAttribute('type', 'hidden');
        m.setAttribute('name', '_csrf_token');
        m.setAttribute('value', $('#sf_guard_user_filters__csrf_token').val());
        f.appendChild(m);
        
        f.submit();
        
        return false;
    });
    
 });
</script>