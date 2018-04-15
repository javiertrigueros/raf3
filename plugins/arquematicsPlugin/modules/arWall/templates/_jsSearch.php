<script id="user-search-template" type="text/x-jquery-tmpl">
<div class="user-autocomplete" data-id="${id}" data-url="${wall}">
    <span class="user-avatar-link">
        <img class="user-avatar-image" alt="" src="${image}" />
        <span class="user-name">
            ${first_last} 
        </span>
    </span>
</div>
</script>
<script type="text/javascript">
$(document).ready(function(){
     $("#form-search>").search();
});	
</script>