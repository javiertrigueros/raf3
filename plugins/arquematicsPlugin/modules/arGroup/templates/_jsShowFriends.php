<script type="text/javascript">
    $('#friends-tabs-menu').friends();
    
    $('#friends-tabs-menu').friendscreen(
        {
            counter:          2,
            autocomplete_url: '<?php echo url_for('@search_friends_byname_auto'); ?>'
        }
    );
</script>
