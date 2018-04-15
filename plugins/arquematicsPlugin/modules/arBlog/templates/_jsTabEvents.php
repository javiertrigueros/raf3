<script type="text/javascript">

    $(document).ready(function()
    {
        arquematics.wallEvent = new arquematics.blogitem({
                    elem:                   '#aEvent',
                    form:                   '#a-event-new-form',
                    input_control_title:    '#a_new_event_title',
                    input_control_message:  '#a_new_event_message',
                    input_control_select:   '#a_new_event_groups',
                    update_button:          '#cmd-update-button-aEvent'
                });

        arquematics.tab.subscribeTab(arquematics.wallEvent);
    });
    
</script>