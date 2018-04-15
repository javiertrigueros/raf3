<script type="text/javascript" >
        
$(document).ready(function()
{
    $('#ui-title').fieldeditor({hideTextControl: false});
    $('#ui-slug').fieldeditor({regex: '^([A-Za-z0-9]+\-?)+$'}); 
    
    $('#ui-excerpt').fieldeditor({input_control_crypt_names: ['a_blog_new_post[excerpt]']}); 
});

</script>