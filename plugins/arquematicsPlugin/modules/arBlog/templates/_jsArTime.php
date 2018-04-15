<script type="text/javascript">
$(document).ready(function()
{
	$("#<?php echo $nameId ?>").timepicker({
                    autoclose: true,
                    pickerPosition: "bottom-right",
                    language: "<?php echo $culture ?>"});
                
});
</script>