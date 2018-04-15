<script type="text/javascript">

    $(document).ready(function()
    {
        arquematics.wallBlog = new arquematics.blogitem({
                            elem:                   '#aBlog',
                            form:                   '#a-blog-new-form',
                            input_control_title:    '#a_blog_new_post_title',
                            input_control_message:  '#a_blog_new_post_message',
                            input_control_select:   '#a_blog_new_post_groups',
                            update_button:          '#cmd-update-button-aBlog'}
        );
           
        arquematics.tab.subscribeTab(arquematics.wallBlog);
    });
    
</script>