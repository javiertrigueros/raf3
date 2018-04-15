<script type="text/javascript">
    $(document).ready(function()
    {
         $('.main-content').pageEditor({
                    form:                   '#update-nav-menu',
                    input_control_title:    '#a_blog_new_post_title',
                    input_control_message:  '#a_blog_new_post_message',
                    input_control_select:   '#a_blog_new_post_groups',
                    url_reload: '<?php echo url_for('@ar_page_admin'); ?>'
                });
     
    });
</script>
