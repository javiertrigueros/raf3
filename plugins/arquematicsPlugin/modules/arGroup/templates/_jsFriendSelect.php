<script type="text/javascript">
    $(document).ready(function(){
                    $("#ff-request-list").friendselector(
                        {
                         buttonText: function(options) {
				if (options.length === 0) {
					return '<?php echo __('Add to lists',array(),'arquematics') ?> <b class="caret"></b>';
				}
                                else if (options.length === 1)
                                {
                                    return options.length + ' <?php echo __('List selected',array(),'arquematics') ?> <b class="caret"></b>';
                                }
				else if (options.length > 1) {
					return options.length + ' <?php echo __('Lists selected',array(),'arquematics') ?> <b class="caret"></b>';
				}
				else {
					var selected = '<?php echo __('Add to lists',array(),'arquematics') ?> ';
					options.each(function() {
                                                selected += $(this).text();
					});
					return selected.substr(0, selected.length) + ' <b class="caret"></b>';
				}
                            }
                        }
                    );
    });
</script>